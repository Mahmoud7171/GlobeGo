-- Update Megan Fox's profile image to use a custom image
-- Replace 'images/megan-fox.jpg' with your actual image filename/path

-- First, check current status
SELECT id, first_name, last_name, profile_image 
FROM users 
WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide';

-- Update to use a custom image (change the path to your actual image file)
-- Example: If you saved the image as 'images/megan-fox.jpg', use that path
UPDATE users 
SET profile_image = 'images/megan-fox.jpg'  -- CHANGE THIS to your actual image path
WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide';

-- Verify the change
SELECT id, first_name, last_name, profile_image 
FROM users 
WHERE first_name = 'Megan' AND last_name = 'Fox' AND role = 'guide';



