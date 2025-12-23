-- Add guide-specific fields to users table
ALTER TABLE users 
ADD COLUMN national_id VARCHAR(50) NULL AFTER phone,
ADD COLUMN date_of_birth DATE NULL AFTER national_id,
ADD COLUMN address TEXT NULL AFTER date_of_birth,
ADD COLUMN criminal_records BOOLEAN DEFAULT FALSE AFTER address,
ADD COLUMN application_status ENUM('pending', 'under_review', 'approved', 'rejected') DEFAULT 'pending' AFTER criminal_records,
ADD COLUMN application_notes TEXT NULL AFTER application_status;

-- Add index for application status
CREATE INDEX idx_application_status ON users(application_status);

