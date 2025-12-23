# How to Run Automated Unit Tests - Demo Guide

## Quick Demo Steps

### Option 1: Using the Batch File (Easiest for Demo)
1. **Double-click `run-tests.bat`** in Windows Explorer
   - OR right-click and select "Run"
   - The window will stay open so you can show the results

### Option 2: Using Command Line (More Professional)
1. Open **Command Prompt** or **PowerShell**
2. Navigate to project folder:
   ```bash
   cd C:\xampp\htdocs\GlobeGoProject
   ```
3. Run tests:
   ```bash
   C:\xampp\php\php.exe vendor\bin\phpunit
   ```

### Option 3: Using Command Line with Detailed Output
```bash
C:\xampp\php\php.exe vendor\bin\phpunit --testdox
```
This shows a nice formatted output with checkmarks (✔) for each test.

## What to Show During Demo

### 1. Show the Test Files Structure
- Open the `tests/` folder
- Show the 5 test files:
  - `TourTest.php`
  - `BookingTest.php`
  - `UserTest.php`
  - `AttractionTest.php`
  - `FineTest.php`

### 2. Show Test Configuration
- Open `phpunit.xml` - shows test configuration
- Open `composer.json` - shows PHPUnit dependency

### 3. Run the Tests
Execute one of the commands above and show:
- **25 tests** running automatically
- **75 assertions** being checked
- **All tests passing** (OK status)
- **Execution time** (shows it's fast)

### 4. Explain What's Being Tested
- **TourTest**: Tests tour creation, filtering, retrieval
- **BookingTest**: Tests booking creation, availability checking
- **UserTest**: Tests user registration, login, authentication
- **AttractionTest**: Tests attraction management
- **FineTest**: Tests fine creation and payment

## Expected Output

When you run the tests, you should see:

```
PHPUnit 9.6.31 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.0.30
Configuration: C:\xampp\htdocs\GlobeGoProject\phpunit.xml

Attraction
 ✔ Create attraction with valid data
 ✔ Get attractions with location filter
 ✔ Get popular attractions
 ✔ Get attraction by id
 ✔ Get categories

Booking
 ✔ Create booking with valid data
 ✔ Get booking by id
 ✔ Check availability with enough spots
 ✔ Check availability with insufficient spots
 ✔ Update booking status

Fine
 ✔ Create fine with valid data
 ✔ Get fines by tourist
 ✔ Get fine by id
 ✔ Mark fine as paid
 ✔ Fine exists for booking

Tour
 ✔ Create tour with valid data
 ✔ Get tours with location filter
 ✔ Get featured tours
 ✔ Get tour by id
 ✔ Get tour by id not found

User
 ✔ Register user with valid data
 ✔ Login with correct credentials
 ✔ Login with incorrect password
 ✔ Email exists
 ✔ Get user by id

Time: 00:00.255, Memory: 6.00 MB

OK (25 tests, 75 assertions)
```

## Key Points to Mention

1. **Automated**: Tests run automatically with one command
2. **Comprehensive**: 25 tests covering all main classes
3. **Fast**: Runs in less than 1 second
4. **Isolated**: Uses mocks, doesn't affect real database
5. **Professional**: Uses industry-standard PHPUnit framework
6. **Maintainable**: Easy to add more tests as code grows

## Troubleshooting

If tests don't run:
1. Make sure dependencies are installed: `install-dependencies.bat`
2. Check that `vendor/` folder exists
3. Verify PHP path in batch files matches your XAMPP installation

