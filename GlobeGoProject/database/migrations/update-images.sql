-- Update tour images to use the local images
-- Paris Evening Walk
UPDATE tours SET image_url = 'images/Paris.png' WHERE title = 'Paris Evening Walk';

-- Ancient Rome Discovery  
UPDATE tours SET image_url = 'images/Rome.png' WHERE title = 'Ancient Rome Discovery';

-- NYC Food Tour
UPDATE tours SET image_url = 'images/NYC.png' WHERE title = 'NYC Food Tour';

-- Update attraction images as well
-- Eiffel Tower
UPDATE attractions SET image_url = 'images/Paris.png' WHERE name = 'Eiffel Tower';

-- Colosseum
UPDATE attractions SET image_url = 'images/Rome.png' WHERE name = 'Colosseum';

-- Times Square
UPDATE attractions SET image_url = 'images/NYC.png' WHERE name = 'Times Square';

-- Show the results
SELECT 'Tour and attraction images updated successfully!' as message;

-- Display updated tours
SELECT id, title, image_url FROM tours WHERE status = 'active' ORDER BY id;

-- Display updated attractions  
SELECT id, name, image_url FROM attractions ORDER BY id;

