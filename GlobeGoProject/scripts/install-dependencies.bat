@echo off
REM Install dependencies for GlobeGo Project Tests
REM This script uses XAMPP's PHP to install Composer dependencies

echo ========================================
echo GlobeGo Project - Install Dependencies
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

echo Step 1: Checking for Composer...
echo.

REM Check if composer.phar exists, if not download it
if not exist "composer.phar" (
    echo Composer not found. Downloading Composer...
    %PHP_PATH% -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    %PHP_PATH% composer-setup.php
    %PHP_PATH% -r "unlink('composer-setup.php');"
    
    if not exist "composer.phar" (
        echo ERROR: Failed to download Composer
        pause
        exit /b 1
    )
    echo Composer downloaded successfully!
    echo.
) else (
    echo Composer.phar found!
    echo.
)

echo Step 2: Installing dependencies...
echo.
%PHP_PATH% composer.phar install

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo SUCCESS! Dependencies installed.
    echo ========================================
    echo.
    echo You can now run tests using:
    echo   vendor\bin\phpunit
    echo.
) else (
    echo.
    echo ERROR: Failed to install dependencies
    echo.
)

pause

