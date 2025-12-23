-- Add password reset fields to users table
-- Run this in phpMyAdmin to enable password reset functionality
-- If a column already exists, you'll get an error - just ignore it and continue

USE globego_db;

-- Add reset_token column
ALTER TABLE users 
ADD COLUMN reset_token VARCHAR(64) NULL AFTER suspend_until;

-- Add reset_token_expires column
ALTER TABLE users 
ADD COLUMN reset_token_expires DATETIME NULL AFTER reset_token;

-- Add index for reset_token for faster lookups
CREATE INDEX idx_reset_token ON users(reset_token);

-- Add index for reset_token_expires to help with cleanup queries
CREATE INDEX idx_reset_token_expires ON users(reset_token_expires);


