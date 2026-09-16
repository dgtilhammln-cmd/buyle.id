# Buyle.id Scraper Microservice

Node.js + Puppeteer-Extra Stealth microservice untuk bypass Cloudflare Turnstile pada Lynk.id dan marketplace lainnya. Deploy ke **Render.com** (GRATIS, laptop boleh mati).

---

## 🚀 Cara Deploy ke Render.com (5 Menit)

### Langkah 1 — Buat GitHub repo baru khusus microservice

```bash
# Di folder buyle-scraper-service ini, jalankan:
git init
git add .
git commit -m "initial: buyle scraper microservice"
git branch -M main
git remote add origin https://github.com/USERNAME/buyle-scraper-service.git
git push -u origin main
```

### Langkah 2 — Deploy ke Render.com

1. Buka **dashboard.render.com** → klik **+ New** → pilih **Web Service**
2. Pilih **"Build and deploy from a Git repository"**
3. Connect ke repo `buyle-scraper-service` yang baru dibuat
4. Isi settings:
   - **Name**: `buyle-scraper-service`
   - **Region**: Singapore (paling dekat Indonesia)
   - **Branch**: `main`
   - **Runtime**: **Docker** (Render akan pakai Dockerfile otomatis)
   - **Plan**: **Free**
5. Klik **Create Web Service**

> Build pertama ±5-10 menit karena install Chrome. Tunggu status **Live**.

### Langkah 3 — Catat URL Service

Setelah deploy, Render memberikan URL seperti:
```
https://buyle-scraper-service.onrender.com
```

### Langkah 4 — Set ENV di Hostinger buyle.id

Tambahkan ke `.env` di server Hostinger:
```env
SCRAPER_SERVICE_URL=https://buyle-scraper-service.onrender.com/api/scrape
```

Lalu clear cache Laravel:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🧪 Test Manual

```bash
# Health check
curl https://buyle-scraper-service.onrender.com/

# Test scrape Lynk.id
curl "https://buyle-scraper-service.onrender.com/api/scrape?url=https://lynk.id/mindiw/PZbVe7P"
```

---

## 📖 API Response Format

```json
{
  "success": true,
  "data": {
    "title": "Nama Produk",
    "description": "Deskripsi produk...",
    "price": 150000,
    "original_price": 200000,
    "images": [
      "https://cdn.lynkid.my.id/products/xxx.jpg",
      "..."
    ]
  }
}
```

---

## ⚠️ Catatan

- **Free tier Render.com**: Service akan *sleep* setelah 15 menit tidak ada request. Request pertama setelah sleep akan butuh ~30 detik untuk "bangun". Itu normal.
- **Whitelist domain**: Hanya `lynk.id`, `shopee.co.id`, `tokopedia.com`, `tiktok.com` yang bisa di-scrape (keamanan).
- **Upgrade** ke Render Starter ($7/bln) jika ingin service selalu aktif tanpa sleep.
