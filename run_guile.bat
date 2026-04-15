@echo off
REM Script to update, install dependencies, migrate, and open guile.test on Windows

cd /d %~dp0

echo Pulling latest code...
git pull

echo Running migrations...
php artisan migrate

echo Building assets...
npm run build
pause

echo Opening guile.test in browser...
start http://guile.test

