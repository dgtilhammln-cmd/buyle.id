#!/bin/bash
# ══════════════════════════════════════════════════════
# BUYLE.ID — Server Image Optimization Script
# Jalankan di server: bash optimize_images.sh
# Membutuhkan: php, imagemagick atau gd (sudah tersedia)
# ══════════════════════════════════════════════════════

set -e
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

echo "🚀 Buyle.id Image Optimizer"
echo "=============================="
echo ""

# 1. Compress semua gambar di storage dengan artisan command
echo "📦 Step 1: Compress semua gambar di storage..."
php artisan images:optimize \
    --max-width=1280 \
    --max-height=960 \
    --quality=78

echo ""

# 2. Khusus hero_slides — resize ke 1280x640 (landscape banner)
echo "🖼️  Step 2: Compress hero slides (1280x640@76%)..."
php artisan images:optimize \
    --folder=hero_slides \
    --max-width=1280 \
    --max-height=640 \
    --quality=76

echo ""

# 3. Produk images
echo "🛍️  Step 3: Compress product images (800x800@80%)..."
php artisan images:optimize \
    --folder=products \
    --max-width=800 \
    --max-height=800 \
    --quality=80

# 4. Article images
echo "📝 Step 4: Compress article images (1000x600@80%)..."
php artisan images:optimize \
    --folder=articles \
    --max-width=1000 \
    --max-height=600 \
    --quality=80

# 5. Avatars / user uploads
echo "👤 Step 5: Compress avatars (400x400@82%)..."
php artisan images:optimize \
    --folder=avatars \
    --max-width=400 \
    --max-height=400 \
    --quality=82

# 6. Settings images (logo, banners, etc)
echo "⚙️  Step 6: Compress settings images..."
php artisan images:optimize \
    --folder=settings \
    --max-width=1280 \
    --max-height=640 \
    --quality=78

echo ""
echo "✅ Selesai! Membersihkan cache..."
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

echo ""
echo "🎉 Optimasi selesai! Jalankan PageSpeed Insights untuk verifikasi."
echo "   Target: Performance 90+ Mobile"
