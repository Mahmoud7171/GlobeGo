-- Add sample tour schedules for existing tours

-- Paris Evening Walk schedules
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) VALUES
(1, '2024-12-30', '18:00:00', 8, 'available'),
(1, '2024-12-31', '18:00:00', 10, 'available'),
(1, '2025-01-02', '18:00:00', 12, 'available'),
(1, '2025-01-03', '18:00:00', 15, 'available'),
(1, '2025-01-04', '18:00:00', 10, 'available'),
(1, '2025-01-05', '18:00:00', 8, 'available');

-- London Historical Tour schedules
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) VALUES
(2, '2024-12-30', '10:00:00', 15, 'available'),
(2, '2024-12-31', '10:00:00', 20, 'available'),
(2, '2025-01-02', '10:00:00', 18, 'available'),
(2, '2025-01-03', '14:00:00', 16, 'available'),
(2, '2025-01-04', '10:00:00', 20, 'available'),
(2, '2025-01-05', '14:00:00', 12, 'available');

-- Tokyo Food Adventure schedules
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) VALUES
(3, '2024-12-30', '19:00:00', 6, 'available'),
(3, '2024-12-31', '19:00:00', 8, 'available'),
(3, '2025-01-02', '19:00:00', 10, 'available'),
(3, '2025-01-03', '19:00:00', 8, 'available'),
(3, '2025-01-04', '19:00:00', 6, 'available'),
(3, '2025-01-05', '19:00:00', 10, 'available');

-- Rome Ancient Wonders schedules
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) VALUES
(4, '2024-12-30', '09:00:00', 12, 'available'),
(4, '2024-12-31', '09:00:00', 15, 'available'),
(4, '2025-01-02', '09:00:00', 18, 'available'),
(4, '2025-01-03', '13:00:00', 14, 'available'),
(4, '2025-01-04', '09:00:00', 16, 'available'),
(4, '2025-01-05', '13:00:00', 12, 'available');

-- New York City Highlights schedules
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) VALUES
(5, '2024-12-30', '11:00:00', 20, 'available'),
(5, '2024-12-31', '11:00:00', 25, 'available'),
(5, '2025-01-02', '11:00:00', 22, 'available'),
(5, '2025-01-03', '15:00:00', 18, 'available'),
(5, '2025-01-04', '11:00:00', 24, 'available'),
(5, '2025-01-05', '15:00:00', 20, 'available');


