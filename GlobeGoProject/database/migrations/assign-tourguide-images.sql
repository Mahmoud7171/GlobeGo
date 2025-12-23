-- Assign TourGuide images to guides
-- This script updates guide profile images with TourGuide images

-- First, let's see what guides we have
SELECT id, first_name, last_name, profile_image FROM users WHERE role = 'guide' ORDER BY id;

-- Update guide profile images with TourGuide images
-- Guide 1 gets TourGuide1.png
UPDATE users SET profile_image = 'images/TourGuide1.png' WHERE role = 'guide' AND id = (SELECT id FROM users WHERE role = 'guide' ORDER BY id LIMIT 1 OFFSET 0);

-- Guide 2 gets TourGuide2.jpg  
UPDATE users SET profile_image = 'images/TourGuide2.jpg' WHERE role = 'guide' AND id = (SELECT id FROM users WHERE role = 'guide' ORDER BY id LIMIT 1 OFFSET 1);

-- Guide 3 gets TourGuide3.jpg
UPDATE users SET profile_image = 'images/TourGuide3.jpg' WHERE role = 'guide' AND id = (SELECT id FROM users WHERE role = 'guide' ORDER BY id LIMIT 1 OFFSET 2);

-- Guide 4 gets TourGuide4.jpg
UPDATE users SET profile_image = 'images/TourGuide4.jpg' WHERE role = 'guide' AND id = (SELECT id FROM users WHERE role = 'guide' ORDER BY id LIMIT 1 OFFSET 3);

-- Guide 5 gets TourGuide5.png
UPDATE users SET profile_image = 'images/TourGuide5.png' WHERE role = 'guide' AND id = (SELECT id FROM users WHERE role = 'guide' ORDER BY id LIMIT 1 OFFSET 4);

-- Show updated results
SELECT 'TourGuide images assigned successfully!' as message;

-- Display updated guides
SELECT id, first_name, last_name, profile_image FROM users WHERE role = 'guide' ORDER BY id;

-- Display tours with their guides
SELECT t.id, t.title, u.first_name, u.last_name, u.profile_image, a.name as attraction_name
FROM tours t 
LEFT JOIN users u ON t.guide_id = u.id 
LEFT JOIN attractions a ON t.attraction_id = a.id 
WHERE t.status = 'active'
ORDER BY t.id;

