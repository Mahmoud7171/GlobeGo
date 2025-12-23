-- Create tours for Tower Bridge and Sagrada Familia with proper images

-- First, update attraction images
UPDATE attractions SET image_url = 'images/TowerBridge.jpg' WHERE name = 'Tower Bridge';
UPDATE attractions SET image_url = 'images/SargadaFamilia.jpg' WHERE name = 'Sagrada Familia';

-- Create Tower Bridge tour (assuming guide_id = 1 exists)
INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, max_participants, meeting_point, category, image_url, status) 
SELECT 1, id, 'London Tower Bridge Experience', 
       'Discover the iconic Tower Bridge with a guided tour including the high-level walkways and Victorian engine rooms. Learn about the bridge''s history and enjoy stunning views of the Thames.',
       55.00, 2, 20, 'Tower Bridge Exhibition Entrance', 'Historical Tour', 'images/TowerBridge.jpg', 'active'
FROM attractions WHERE name = 'Tower Bridge';

-- Create Sagrada Familia tour
INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, max_participants, meeting_point, category, image_url, status) 
SELECT 1, id, 'Sagrada Familia & Gaudí''s Barcelona',
       'Explore Antoni Gaudí''s masterpiece, the Sagrada Familia, and discover the architectural genius behind this unfinished basilica. Includes skip-the-line access and expert commentary.',
       75.00, 3, 15, 'Sagrada Familia Main Entrance', 'Cultural Tour', 'images/SargadaFamilia.jpg', 'active'
FROM attractions WHERE name = 'Sagrada Familia';

-- Create tour schedules for the new tours
-- Tower Bridge schedules
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status)
SELECT t.id, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', t.max_participants, 'available'
FROM tours t 
JOIN attractions a ON t.attraction_id = a.id 
WHERE a.name = 'Tower Bridge' AND t.title = 'London Tower Bridge Experience';

INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status)
SELECT t.id, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', t.max_participants, 'available'
FROM tours t 
JOIN attractions a ON t.attraction_id = a.id 
WHERE a.name = 'Tower Bridge' AND t.title = 'London Tower Bridge Experience';

INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status)
SELECT t.id, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '10:00:00', t.max_participants, 'available'
FROM tours t 
JOIN attractions a ON t.attraction_id = a.id 
WHERE a.name = 'Tower Bridge' AND t.title = 'London Tower Bridge Experience';

-- Sagrada Familia schedules
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status)
SELECT t.id, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', t.max_participants, 'available'
FROM tours t 
JOIN attractions a ON t.attraction_id = a.id 
WHERE a.name = 'Sagrada Familia' AND t.title = 'Sagrada Familia & Gaudí''s Barcelona';

INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status)
SELECT t.id, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', t.max_participants, 'available'
FROM tours t 
JOIN attractions a ON t.attraction_id = a.id 
WHERE a.name = 'Sagrada Familia' AND t.title = 'Sagrada Familia & Gaudí''s Barcelona';

INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status)
SELECT t.id, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '10:00:00', t.max_participants, 'available'
FROM tours t 
JOIN attractions a ON t.attraction_id = a.id 
WHERE a.name = 'Sagrada Familia' AND t.title = 'Sagrada Familia & Gaudí''s Barcelona';

-- Show results
SELECT 'London & Barcelona tours created successfully!' as message;

-- Display created tours
SELECT t.id, t.title, a.name as attraction, a.location, t.image_url
FROM tours t 
JOIN attractions a ON t.attraction_id = a.id 
WHERE a.name IN ('Tower Bridge', 'Sagrada Familia') 
ORDER BY t.id;

