# Unit Tests for GlobeGo Project

This directory contains automated unit tests for the GlobeGo Tour Booking System.

## Setup

1. Install Composer dependencies:
```bash
composer install
```

This will install PHPUnit and other required packages.

## Running Tests

To run all tests:
```bash
vendor/bin/phpunit
```

To run a specific test file:
```bash
vendor/bin/phpunit tests/TourTest.php
```

To run tests with coverage report:
```bash
vendor/bin/phpunit --coverage-html coverage/
```

## Test Files

1. **TourTest.php** - Tests for Tour class
   - Test tour creation
   - Test getting tours with filters
   - Test getting featured tours
   - Test getting tour by ID

2. **BookingTest.php** - Tests for Booking class
   - Test booking creation
   - Test getting booking by ID
   - Test availability checking
   - Test status updates

3. **UserTest.php** - Tests for User class
   - Test user registration
   - Test user login
   - Test email existence checking
   - Test getting user by ID

4. **AttractionTest.php** - Tests for Attraction class
   - Test attraction creation
   - Test getting attractions with filters
   - Test getting popular attractions
   - Test getting categories

5. **FineTest.php** - Tests for Fine class
   - Test fine creation
   - Test getting fines by tourist
   - Test marking fine as paid
   - Test fine existence checking

## Notes

- All tests use mocking to avoid hitting the actual database
- Tests are isolated and can be run independently
- Each test class extends PHPUnit\Framework\TestCase

