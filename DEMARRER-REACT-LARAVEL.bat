@echo off
cd /d "%~dp0"
if not exist backend\vendor\autoload.php (
 echo Installez Laravel avec composer install dans backend.
 pause
 exit /b 1
)
if not exist frontend\node_modules (
 echo Installez React avec npm install dans frontend.
 pause
 exit /b 1
)
echo Verifiez que votre serveur SQL est demarre. Consultez README.md.
start "MOROWAY - API Laravel" cmd /k "cd /d "%~dp0backend" && php artisan serve --host=127.0.0.1 --port=8000"
start "MOROWAY - React" cmd /k "cd /d "%~dp0frontend" && npm.cmd run dev"
echo Site : http://127.0.0.1:5173/
echo Admin : http://127.0.0.1:5173/admin
pause
