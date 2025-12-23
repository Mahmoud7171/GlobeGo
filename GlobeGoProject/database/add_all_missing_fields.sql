-- Add all missing fields to users table
-- Run this in phpMyAdmin to fix all column errors
-- If a column already exists, you'll get an error - just ignore it and continue

-- Add guide-specific fields (run these one at a time if you get errors)
ALTER TABLE users 
ADD COLUMN national_id VARCHAR(50) NULL AFTER phone;

ALTER TABLE users 
ADD COLUMN date_of_birth DATE NULL AFTER national_id;

ALTER TABLE users 
ADD COLUMN address TEXT NULL AFTER date_of_birth;

ALTER TABLE users 
ADD COLUMN criminal_records BOOLEAN DEFAULT FALSE AFTER address;

ALTER TABLE users 
ADD COLUMN application_status ENUM('pending', 'under_review', 'approved', 'rejected') DEFAULT 'pending' AFTER criminal_records;

ALTER TABLE users 
ADD COLUMN application_notes TEXT NULL AFTER application_status;

-- Add suspend_until field for temporary suspensions
ALTER TABLE users 
ADD COLUMN suspend_until DATETIME NULL AFTER status;

-- Add indexes (ignore errors if they already exist)
CREATE INDEX idx_application_status ON users(application_status);
CREATE INDEX idx_suspend_until ON users(suspend_until);

