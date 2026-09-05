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
ssh -p 65002 u664715641@46.202.186.86 "cd domains/buyle.id/public_html && git checkout . && git pull origin main && php artisan storage:link && php artisan migrate --force && php artisan favicon:refresh && php artisan optimize:clear"

echo.
echo ==============================================
echo Deploy Berhasil!
echo ==============================================
pause

