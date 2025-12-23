@echo off
REM Run PHPUnit tests for GlobeGo Project

echo ========================================
echo GlobeGo Project - Running Tests
echo ========================================
echo.

REM Check if XAMPP PHP exists
if not exist "C:\xampp\php\php.exe" (
    echo ERROR: XAMPP PHP not found at C:\xampp\php\php.exe
    echo Please update the path in this script if XAMPP is installed elsewhere.
    pause
    exit /b 1
)

REM Set PHP path
set PHP_PATH=C:\xampp\php\php.exe

REM Check if vendor directory exists
if not exist "vendor\bin\phpunit" (
    echo ERROR: Dependencies not installed!
    echo Please run install-dependencies.bat first
    pause
    exit /b 1
)

echo Running PHPUnit tests...
echo.

%PHP_PATH% vendor\bin\phpunit %*

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Tests completed successfully!
    echo ========================================
) else (
    echo.
    echo ========================================
    echo Some tests failed. Check output above.
    echo ========================================
)

echo.
pause

