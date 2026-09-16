/**
 * Buyle.id — Lynk.id Scraper Microservice
 * Puppeteer-Extra + Stealth Plugin for Cloudflare Turnstile Bypass
 * KHUSUS: lynk.id saja (Shopee/Tokopedia/TikTok sudah handled di Laravel)
 * Deploy to Render.com (Free Plan)
 */

const express = require('express');
const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');

puppeteer.use(StealthPlugin());

const app  = express();
const PORT = process.env.PORT || 3000;

// Whitelist: HANYA lynk.id — marketplace lain sudah handled di Laravel
const ALLOWED_DOMAINS = [
    'lynk.id',
];

function isAllowedUrl(urlStr) {
    try {
        const host = new URL(urlStr).hostname.toLowerCase().replace('www.', '');
        return ALLOWED_DOMAINS.some(d => host === d || host.endsWith('.' + d));
    } catch {
        return false;
    }
}

// Health check
app.get('/', (req, res) => {
    res.json({
        status : 'ok',
        service: 'Buyle.id Lynk.id Scraper v2.0 (lynk.id only)',
        ts     : new Date().toISOString(),
    });
});

// ── Main scrape endpoint ──────────────────────────────────────────────────────
app.get('/api/scrape', async (req, res) => {
    const targetUrl = (req.query.url || '').trim();

    if (!targetUrl) {
        return res.status(400).json({ success: false, error: 'Parameter url wajib diisi' });
    }

    if (!isAllowedUrl(targetUrl)) {
        return res.status(403).json({ success: false, error: 'Microservice ini khusus untuk lynk.id saja.' });
    }

    let browser = null;

    try {
        const launchOptions = {
            headless : 'new',
            args     : [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--no-first-run',
                '--no-zygote',
                '--single-process',
                '--disable-gpu',
                '--window-size=1280,800',
            ],
        };

        if (process.env.PUPPETEER_EXECUTABLE_PATH) {
            launchOptions.executablePath = process.env.PUPPETEER_EXECUTABLE_PATH;
        }

        browser = await puppeteer.launch(launchOptions);

        const page = await browser.newPage();

        // Realistic browser fingerprint
        await page.setUserAgent(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' +
            'AppleWebKit/537.36 (KHTML, like Gecko) ' +
            'Chrome/120.0.0.0 Safari/537.36'
        );
        await page.setViewport({ width: 1280, height: 800 });
        await page.setExtraHTTPHeaders({
            'Accept-Language': 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
            'Accept'         : 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        });

        // Navigate & wait for content to settle
        await page.goto(targetUrl, {
            waitUntil: 'networkidle2',
            timeout  : 30000,
        });

        // Extra wait for JS-rendered content (React / Vue)
        await new Promise(r => setTimeout(r, 3000));

        // ── Extraction ────────────────────────────────────────────────────────
        const data = await page.evaluate(() => {
            const getMeta = (prop) => {
                const selectors = [
                    `meta[property="${prop}"]`,
                    `meta[name="${prop}"]`,
                ];
                for (const s of selectors) {
                    const el = document.querySelector(s);
                    if (el && el.getAttribute('content')) {
                        return el.getAttribute('content').trim();
                    }
                }
                return '';
            };

            // ── Title ────────────────────────────────────────────────────────
            let title = '';
            const titleProdEl = document.querySelector('[id="title_product"], .title_product, h1.product-title');
            if (titleProdEl) title = titleProdEl.textContent.trim();
            if (!title) title = getMeta('og:title');
            if (!title) title = document.title || '';
            title = title.replace(/^LYNK\s*\|\s*/i, '');
            title = title.replace(/\s*[-|]\s*(Lynk\.id|Shopee|Tokopedia|TikTok).*$/i, '');
            title = title.trim();

            // ── Description ─────────────────────────────────────────────────
            let description = '';
            const richContent = document.querySelector('.rich-content, [class*="rich-content"]');
            if (richContent) description = richContent.innerText.trim();
            if (!description) description = getMeta('og:description') || getMeta('description') || '';

            // ── Images (max 6) ───────────────────────────────────────────────
            const images = [];
            const addImg = (src) => {
                if (!src) return;
                src = src.trim().split('?')[0];
                if (
                    src.startsWith('http') &&
                    !images.includes(src) &&
                    images.length < 6 &&
                    !src.includes('avatar') &&
                    !src.includes('icon') &&
                    !src.includes('logo') &&
                    !src.includes('banner')
                ) {
                    images.push(src);
                }
            };

            addImg(getMeta('og:image'));
            addImg(getMeta('og:image:secure_url'));
            addImg(getMeta('twitter:image'));

            document.querySelectorAll('img').forEach(img => {
                const src = img.src || img.dataset.src || img.dataset.lazySrc || '';
                addImg(src);
            });

            // ── Price ─────────────────────────────────────────────────────────
            let price = 0;
            let originalPrice = 0;

            // Lynk.id JS variable pattern: var p = _g('750000.0')
            const scripts = Array.from(document.querySelectorAll('script')).map(s => s.textContent);
            for (const script of scripts) {
                const pMatch = script.match(/var\s+p\s*=\s*_g\(['"]([0-9.]+)['"]\)/i);
                if (pMatch) price = parseFloat(pMatch[1]);
                const spMatch = script.match(/var\s+sPrice\s*=\s*_g\(['"]([0-9.]+)['"]\)/i);
                if (spMatch) originalPrice = parseFloat(spMatch[1]);
                if (price > 0) break;
            }

            // Fallback: scan page text for Rp amounts
            if (!price) {
                const pageText = document.body.innerText || '';
                const rpMatches = pageText.match(/Rp\s*([\d.,]+)/gi) || [];
                const parsed = rpMatches
                    .map(p => parseInt(p.replace(/[^0-9]/g, ''), 10))
                    .filter(p => p > 999);
                if (parsed.length > 0) {
                    price = Math.min(...parsed);
                    if (parsed.length > 1) originalPrice = Math.max(...parsed);
                }
            }

            return {
                title,
                description,
                images,
                price,
                original_price: originalPrice > price ? originalPrice : 0
            };
        });

        await browser.close();
        return res.json({ success: true, data });

    } catch (err) {
        if (browser) { try { await browser.close(); } catch (_) {} }
        console.error('[scrape error]', err.message);
        return res.status(500).json({ success: false, error: err.message });
    }
});

app.listen(PORT, () => {
    console.log(`Buyle Scraper Service ready on port ${PORT}`);
});
