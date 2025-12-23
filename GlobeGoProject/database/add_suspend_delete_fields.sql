-- Add suspend_until field for temporary suspensions
ALTER TABLE users 
ADD COLUMN suspend_until DATETIME NULL AFTER status;

-- Add index for suspend_until
CREATE INDEX idx_suspend_until ON users(suspend_until);

