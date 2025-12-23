-- Update Megan Fox's profile image to use TourGuide6.jpg

-- First, check current status
SELECT id, first_name, last_name, profile_image 
FROM users 
WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide';

-- Update to use TourGuide6.jpg
UPDATE users 
SET profile_image = 'images/TourGuide6.jpg'
WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide';

-- Verify the change
SELECT id, first_name, last_name, profile_image 
FROM users 
WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide';

