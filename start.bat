@echo off
REM ============================================
REM  HDTT Store - khoi dong server PHP noi bo
REM  Chay file nay roi mo http://localhost:8000
REM ============================================
cd /d "%~dp0"

if not exist "Backend\commons\env.php" (
    echo [!] Thieu Backend\commons\env.php - dang tao tu env.example.php...
    copy "Backend\commons\env.example.php" "Backend\commons\env.php" >nul
)

echo.
echo   HDTT Store dang chay tai: http://localhost:8000
echo   Nhan Ctrl+C de dung.
echo.
php -S localhost:8000 -t .
