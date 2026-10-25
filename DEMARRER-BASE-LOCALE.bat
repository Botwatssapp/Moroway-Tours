@echo off
cd /d "%~dp0"
if not exist .local-mysql\mysql (
 echo La base isolee de verification n'est pas presente. Utilisez votre serveur SQL habituel, voir README.md.
 pause
 exit /b 1
)
if not exist C:\xampp\mysql\bin\mysqld.exe (
 echo XAMPP introuvable dans C:\xampp. Adaptez ce lanceur ou utilisez votre serveur SQL habituel.
 pause
 exit /b 1
)
echo Base MariaDB isolee - port 3307 - acces local uniquement.
"C:\xampp\mysql\bin\mysqld.exe" --no-defaults --basedir="C:\xampp\mysql" --datadir="%~dp0.local-mysql" --port=3307 --bind-address=127.0.0.1 --console
pause
