<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9">
    <xsl:output method="html" indent="yes" encoding="UTF-8"/>
    
    <xsl:template match="/">
        <html lang="id">
        <head>
            <title>XML Sitemap - Buyle.id</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
            <style>
                *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
                :root {
                    --bg:       #ffffff;
                    --surface:  #f8fafc;
                    --border:   #E2E8F0;
                    --text:     #0F172A;
                    --muted:    #64748B;
                    --accent:   #0EA5E9;
                    --dark:     #0F172A;
                    --font:     'Montserrat', sans-serif;
                }
                body {
                    font-family: var(--font);
                    background: var(--bg);
                    color: var(--text);
                    -webkit-font-smoothing: antialiased;
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                }
                .sm-hero {
                    background: var(--surface);
                    border-bottom: 1px solid var(--border);
                    padding: 5rem 1.5rem 4rem;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                }
                .sm-hero::before {
                    content: ''; position: absolute;
                    top: -120px; right: -80px;
                    width: 400px; height: 400px;
                    background: radial-gradient(circle, rgba(14,165,233,.08) 0%, transparent 70%);
                    border-radius: 50%; pointer-events: none;
                }
                .sm-hero h1 {
                    font-size: clamp(2rem, 5vw, 2.5rem);
                    font-weight: 500; color: var(--text);
                    letter-spacing: -.03em; margin-bottom: 1rem;
                }
                .sm-hero p {
                    font-size: 1rem;
                    color: var(--muted);
                    line-height: 1.7; max-width: 600px; margin: 0 auto;
                }
                .sm-main { max-width: 1000px; margin: 3rem auto 3rem; padding: 0 1.5rem; position: relative; z-index: 10; flex: 1; width: 100%; }
                .sm-table-wrap {
                    background: var(--bg);
                    border: 1px solid var(--border);
                    border-radius: 12px;
                    overflow: hidden;
                }
                .sm-table-header {
                    padding: 1.5rem;
                    border-bottom: 1px solid var(--border);
                    display: flex; justify-content: space-between; align-items: center;
                    background: var(--surface);
                }
                .sm-table-title { font-weight: 600; font-size: 1rem; }
                .sm-table-count { 
                    font-size: .75rem; font-weight: 700; 
                    background: var(--bg); border: 1px solid var(--border); 
                    padding: .25rem .75rem; border-radius: 50px; color: var(--muted);
                }
                table { width: 100%; border-collapse: collapse; text-align: left; }
                th {
                    background: var(--bg);
                    padding: 1rem 1.5rem;
                    font-size: .75rem; font-weight: 700;
                    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
                    border-bottom: 1px solid var(--border);
                }
                td {
                    padding: 1rem 1.5rem;
                    border-bottom: 1px solid var(--border);
                    font-size: .85rem;
                }
                tr:last-child td { border-bottom: none; }
                tr:hover td { background: var(--surface); }
                .url-link { color: var(--accent); text-decoration: none; font-weight: 500; }
                .url-link:hover { text-decoration: underline; }
                .priority-badge {
                    display: inline-block;
                    padding: .25rem .5rem;
                    border-radius: 6px; background: rgba(14,165,233,.1); color: var(--accent);
                    font-weight: 700; font-size: .75rem;
                }
                .sm-footer {
                    text-align: center;
                    padding: 2rem 1.5rem;
                    border-top: 1px solid var(--border);
                    font-size: .8rem;
                    color: var(--muted);
                    background: var(--surface);
                    margin-top: auto;
                }
                .sm-footer a {
                    color: var(--text);
                    text-decoration: none;
                    font-weight: 600;
                }
                .sm-footer a:hover {
                    color: var(--accent);
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <div class="sm-hero">
                <h1>XML Sitemap by buyle.id</h1>
                <p>Ini adalah format XML sitemap yang digunakan oleh Google Search Console dan mesin pencari lainnya untuk melakukan indexing pada seluruh halaman website buyle.id.</p>
            </div>
            <div class="sm-main">
                <div class="sm-table-wrap">
                    <div class="sm-table-header">
                        <div class="sm-table-title">Daftar URL Terindeks</div>
                        <div class="sm-table-count">
                            <xsl:choose>
                                <xsl:when test="sitemap:sitemapindex">
                                    <xsl:value-of select="count(sitemap:sitemapindex/sitemap:sitemap)"/> Sitemaps
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/> URLs
                                </xsl:otherwise>
                            </xsl:choose>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>URL Halaman</th>
                                <xsl:if test="sitemap:urlset">
                                    <th>Prioritas</th>
                                    <th>Frekuensi Index</th>
                                </xsl:if>
                                <th>Terakhir Diubah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- For URL Set -->
                            <xsl:for-each select="sitemap:urlset/sitemap:url">
                                <tr>
                                    <td>
                                        <a class="url-link">
                                            <xsl:attribute name="href">
                                                <xsl:value-of select="sitemap:loc"/>
                                            </xsl:attribute>
                                            <xsl:attribute name="target">_blank</xsl:attribute>
                                            <xsl:value-of select="sitemap:loc"/>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="priority-badge"><xsl:value-of select="sitemap:priority"/></span>
                                    </td>
                                    <td>
                                        <xsl:value-of select="sitemap:changefreq"/>
                                    </td>
                                    <td>
                                        <xsl:value-of select="sitemap:lastmod"/>
                                    </td>
                                </tr>
                            </xsl:for-each>
                            
                            <!-- For Sitemap Index -->
                            <xsl:for-each select="sitemap:sitemapindex/sitemap:sitemap">
                                <tr>
                                    <td>
                                        <a class="url-link">
                                            <xsl:attribute name="href">
                                                <xsl:value-of select="sitemap:loc"/>
                                            </xsl:attribute>
                                            <xsl:attribute name="target">_blank</xsl:attribute>
                                            <xsl:value-of select="sitemap:loc"/>
                                        </a>
                                    </td>
                                    <td>
                                        <xsl:value-of select="sitemap:lastmod"/>
                                    </td>
                                </tr>
                            </xsl:for-each>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="sm-footer">
                SEO by <a href="https://buyle.id" target="_blank" rel="noopener noreferrer">buyle.id</a> &#8212; Digital Creator Marketplace Indonesia
            </div>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
