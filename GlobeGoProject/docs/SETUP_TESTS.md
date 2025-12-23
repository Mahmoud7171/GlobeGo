# How to Install Dependencies and Run Tests

## Quick Start (Easiest Method for XAMPP Users)

Since you're using XAMPP, I've created batch files to make this easy:

1. **Double-click `install-dependencies.bat`** - This will automatically:
   - Download Composer (if needed)
   - Install PHPUnit and all dependencies

2. **Double-click `run-tests.bat`** - This will run all tests

That's it! The batch files handle everything automatically.

---

## Manual Installation (Alternative Method)

### Step 1: Install Composer

Composer is required to install PHPUnit and other dependencies. Choose one method:

### Option A: Install Composer Globally (Recommended)

1. Download Composer from: https://getcomposer.org/download/
2. Run the Windows installer: `Composer-Setup.exe`
3. Follow the installation wizard
4. Restart your terminal/PowerShell after installation

### Option B: Use Composer.phar (No Installation Required)

1. Download `composer.phar` from: https://getcomposer.org/download/
2. Save it in your project root directory (`C:\xampp\htdocs\GlobeGoProject\`)
3. Use it with: `php composer.phar` instead of `composer`

### Option C: Quick Download Script

Run this in PowerShell (as Administrator):
```powershell
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

This will create `composer.phar` in your project directory.

## Step 2: Install Dependencies

Once Composer is available, run:

**For XAMPP users (PHP not in PATH):**
```bash
C:\xampp\php\php.exe composer.phar install
```

**If Composer is installed globally:**
```bash
composer install
```

**If using composer.phar (with PHP in PATH):**
```bash
php composer.phar install
```

This will:
- Create a `vendor/` directory
- Install PHPUnit and all dependencies
- Generate autoload files

## Step 3: Run the Tests

After installing dependencies, run the tests:

**For XAMPP users (PHP not in PATH):**
```bash
C:\xampp\php\php.exe vendor\bin\phpunit
```

**If PHP is in PATH:**
```bash
vendor/bin/phpunit
```

**Or:**
```bash
php vendor/bin/phpunit
```

**Run a specific test file:**
```bash
vendor/bin/phpunit tests/TourTest.php
```

**Run tests with verbose output:**
```bash
vendor/bin/phpunit --verbose
```

**Run tests and see coverage:**
```bash
vendor/bin/phpunit --coverage-html coverage/
```

## Troubleshooting

### If you get "composer command not found":
- Make sure Composer is installed and in your PATH
- Or use `php composer.phar` instead of `composer`

### If you get "PHPUnit not found":
- Make sure you ran `composer install` first
- Check that `vendor/` directory exists

### If tests fail with "Class not found":
- Make sure `vendor/autoload.php` exists
- Try running `composer dump-autoload`

## Quick Test Command

After setup, you can verify everything works:
```bash
vendor/bin/phpunit --version
```

This should display PHPUnit version (e.g., "PHPUnit 9.5.x").

