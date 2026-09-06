@echo off
echo ==============================================
echo 1. Menyiapkan ^& Commit Perubahan Lokal...
echo ==============================================
git add .
git commit -m "Deploy: update terbaru"

echo.
echo ==============================================
echo 2. Push update ke Github...
echo ==============================================
git push origin main

echo.
echo ==============================================
echo 3. Pull ke Hosting, Jalankan Migration ^& Clear Cache...
echo ==============================================
ssh -p 65002 u664715641@46.202.186.86 "cd domains/buyle.id/public_html && rm -rf public_html/storage public/storage && ln -s /home/u664715641/domains/buyle.id/public_html/storage/app/public /home/u664715641/domains/buyle.id/public_html/public_html/storage && ln -s /home/u664715641/domains/buyle.id/public_html/storage/app/public /home/u664715641/domains/buyle.id/public_html/public/storage && git checkout . && git pull origin main && php artisan migrate --force && php artisan favicon:refresh && php artisan optimize:clear"

echo.
echo ==============================================
echo Deploy Berhasil!
echo ==============================================
pause
