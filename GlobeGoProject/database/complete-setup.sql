-- =====================================================
-- GlobeGo Complete Database Setup Script
-- =====================================================
-- This script creates the complete database structure
-- and populates it with sample data for the GlobeGo project
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS globego_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE globego_db;

-- Drop existing tables if they exist (for clean setup)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS tour_schedules;
DROP TABLE IF EXISTS tours;
DROP TABLE IF EXISTS attractions;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- TABLE: users
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    role ENUM('tourist', 'guide', 'admin') NOT NULL DEFAULT 'tourist',
    phone VARCHAR(20),
    profile_image VARCHAR(255),
    bio TEXT,
    languages VARCHAR(255),
    verified BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'suspended', 'pending') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: attractions
-- =====================================================
CREATE TABLE attractions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    category VARCHAR(100),
    image_url VARCHAR(255),
    rating DECIMAL(3,2) DEFAULT 0.00,
    total_reviews INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_location (location),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: tours
-- =====================================================
CREATE TABLE tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guide_id INT NOT NULL,
    attraction_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    duration_hours INT NOT NULL,
    max_participants INT NOT NULL,
    meeting_point VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    image_url VARCHAR(255),
    status ENUM('active', 'inactive', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (guide_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (attraction_id) REFERENCES attractions(id) ON DELETE SET NULL,
    INDEX idx_guide_id (guide_id),
    INDEX idx_attraction_id (attraction_id),
    INDEX idx_status (status),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: tour_schedules
-- =====================================================
CREATE TABLE tour_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    tour_date DATE NOT NULL,
    tour_time TIME NOT NULL,
    available_spots INT NOT NULL,
    status ENUM('available', 'full', 'cancelled') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tour_datetime (tour_id, tour_date, tour_time),
    INDEX idx_tour_id (tour_id),
    INDEX idx_tour_date (tour_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: bookings
-- =====================================================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tourist_id INT NOT NULL,
    tour_schedule_id INT NOT NULL,
    booking_reference VARCHAR(20) UNIQUE NOT NULL,
    num_participants INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50),
    payment_reference VARCHAR(255),
    booking_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tourist_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tour_schedule_id) REFERENCES tour_schedules(id) ON DELETE CASCADE,
    INDEX idx_tourist_id (tourist_id),
    INDEX idx_tour_schedule_id (tour_schedule_id),
    INDEX idx_booking_reference (booking_reference),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: reviews
-- =====================================================
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    tourist_id INT NOT NULL,
    tour_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (tourist_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    UNIQUE KEY unique_booking_review (booking_id),
    INDEX idx_tourist_id (tourist_id),
    INDEX idx_tour_id (tour_id),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SAMPLE DATA INSERTION
-- =====================================================

-- Insert default admin user
-- Password: password (hashed with bcrypt)
INSERT INTO users (email, password, first_name, last_name, role, verified, status) 
VALUES ('admin@globego.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin', TRUE, 'active');

-- Insert sample guide users
-- Password for all guides: password
INSERT INTO users (email, password, first_name, last_name, role, phone, profile_image, bio, languages, verified, status) VALUES
('guide1@globego.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah', 'Johnson', 'guide', '+1-555-0101', 'images/TourGuide1.png', 'Experienced tour guide with 10+ years showing visitors the best of Paris. Fluent in English, French, and Spanish.', 'English, French, Spanish', TRUE, 'active'),
('guide2@globego.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marco', 'Rossi', 'guide', '+39-555-0102', 'images/TourGuide2.jpg', 'Passionate about Roman history and architecture. Specializes in historical tours of ancient Rome.', 'English, Italian, German', TRUE, 'active'),
('guide3@globego.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Emma', 'Williams', 'guide', '+1-555-0103', 'images/TourGuide3.jpg', 'NYC native foodie expert. Knows all the hidden gems and best spots for authentic New York cuisine.', 'English, Spanish', TRUE, 'active'),
('guide4@globego.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'James', 'Anderson', 'guide', '+44-555-0104', 'images/TourGuide4.jpg', 'London historian and architecture enthusiast. Expert in Victorian and modern London landmarks.', 'English, French', TRUE, 'active'),
('guide5@globego.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Isabella', 'Garcia', 'guide', '+34-555-0105', 'images/TourGuide5.png', 'Barcelona native specializing in Gaudí architecture and Catalan culture. Fluent in multiple languages.', 'English, Spanish, Catalan, French', TRUE, 'active');

-- Insert sample attractions (using actual image files from the project)
INSERT INTO attractions (name, description, location, category, image_url, rating, total_reviews) VALUES
('Eiffel Tower', 'Iconic iron tower and symbol of Paris, offering panoramic city views from its observation decks. Built for the 1889 World''s Fair, it stands 324 meters tall and is one of the most recognizable structures in the world.', 'Paris, France', 'Landmark', 'images/Paris.png', 4.8, 1250),
('Colosseum', 'Ancient Roman amphitheater and one of the most famous landmarks in the world. Built in 70-80 AD, it could hold up to 80,000 spectators and hosted gladiatorial contests and public spectacles.', 'Rome, Italy', 'Historical', 'images/Rome.png', 4.9, 2100),
('Times Square', 'Major commercial intersection and tourist destination in Manhattan. Known as "The Crossroads of the World," it features massive digital billboards and is the heart of New York City''s entertainment district.', 'New York, USA', 'Landmark', 'images/NYC.png', 4.6, 980),
('Tower Bridge', 'Victorian suspension bridge over the River Thames in London. Completed in 1894, it features two Gothic-style towers and a bascule bridge that can be raised to allow ships to pass through.', 'London, UK', 'Landmark', 'images/TowerBridge.jpg', 4.7, 1450),
('Sagrada Familia', 'Unfinished basilica designed by Antoni Gaudí in Barcelona. Construction began in 1882 and is still ongoing. This masterpiece of Catalan Modernism is a UNESCO World Heritage Site.', 'Barcelona, Spain', 'Religious', 'images/SargadaFamilia.jpg', 4.9, 1800);

-- Insert sample tours
INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, max_participants, meeting_point, category, image_url, status) VALUES
(2, 1, 'Paris Evening Walk', 'Experience the magic of Paris at night with a guided walk through the City of Light. Visit the Eiffel Tower, stroll along the Seine, and discover hidden gems of the 7th arrondissement. Perfect for first-time visitors and romantic couples.', 45.00, 2, 12, 'Trocadéro Metro Station, Exit 6', 'Walking Tour', 'images/Paris.png', 'active'),
(3, 2, 'Ancient Rome Discovery', 'Explore the Colosseum and surrounding ancient Roman ruins with an expert guide. Learn about gladiators, emperors, and the daily life of ancient Romans. Includes skip-the-line access and detailed historical commentary.', 65.00, 3, 15, 'Colosseum Main Entrance, Via dei Fori Imperiali', 'Historical Tour', 'images/Rome.png', 'active'),
(4, 3, 'NYC Food Tour', 'Taste the best of New York City through its diverse culinary scene. Visit authentic pizzerias, delis, and food markets. Sample bagels, pizza, hot dogs, and international cuisine while learning about NYC''s food culture.', 85.00, 4, 10, 'Union Square Park, North End', 'Food Tour', 'images/NYC.png', 'active'),
(5, 4, 'London Tower Bridge Experience', 'Discover the iconic Tower Bridge with a guided tour including the high-level walkways and Victorian engine rooms. Learn about the bridge''s history, enjoy stunning views of the Thames, and see the glass floor walkway.', 55.00, 2, 20, 'Tower Bridge Exhibition Entrance', 'Historical Tour', 'images/TowerBridge.jpg', 'active'),
(6, 5, 'Sagrada Familia & Gaudí''s Barcelona', 'Explore Antoni Gaudí''s masterpiece, the Sagrada Familia, and discover the architectural genius behind this unfinished basilica. Includes skip-the-line access, expert commentary, and a walk through the Eixample district.', 75.00, 3, 15, 'Sagrada Familia Main Entrance, Carrer de la Marina', 'Cultural Tour', 'images/SargadaFamilia.jpg', 'active');

-- Insert sample tour schedules (for the next 30 days)
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) VALUES
-- Paris Evening Walk schedules
(1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '18:00:00', 12, 'available'),
(1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '18:00:00', 12, 'available'),
(1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '18:00:00', 12, 'available'),
(1, DATE_ADD(CURDATE(), INTERVAL 7 DAY), '18:00:00', 12, 'available'),
(1, DATE_ADD(CURDATE(), INTERVAL 8 DAY), '18:00:00', 12, 'available'),
(1, DATE_ADD(CURDATE(), INTERVAL 14 DAY), '18:00:00', 12, 'available'),
(1, DATE_ADD(CURDATE(), INTERVAL 15 DAY), '18:00:00', 12, 'available'),
(1, DATE_ADD(CURDATE(), INTERVAL 21 DAY), '18:00:00', 12, 'available'),
(1, DATE_ADD(CURDATE(), INTERVAL 22 DAY), '18:00:00', 12, 'available'),
(1, DATE_ADD(CURDATE(), INTERVAL 28 DAY), '18:00:00', 12, 'available'),
-- Ancient Rome Discovery schedules
(2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', 15, 'available'),
(2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '10:00:00', 15, 'available'),
(2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '14:00:00', 15, 'available'),
(2, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '10:00:00', 15, 'available'),
(2, DATE_ADD(CURDATE(), INTERVAL 6 DAY), '14:00:00', 15, 'available'),
(2, DATE_ADD(CURDATE(), INTERVAL 12 DAY), '10:00:00', 15, 'available'),
(2, DATE_ADD(CURDATE(), INTERVAL 13 DAY), '14:00:00', 15, 'available'),
(2, DATE_ADD(CURDATE(), INTERVAL 19 DAY), '10:00:00', 15, 'available'),
(2, DATE_ADD(CURDATE(), INTERVAL 20 DAY), '14:00:00', 15, 'available'),
(2, DATE_ADD(CURDATE(), INTERVAL 26 DAY), '10:00:00', 15, 'available'),
-- NYC Food Tour schedules
(3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '11:00:00', 10, 'available'),
(3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', 10, 'available'),
(3, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '11:00:00', 10, 'available'),
(3, DATE_ADD(CURDATE(), INTERVAL 4 DAY), '14:00:00', 10, 'available'),
(3, DATE_ADD(CURDATE(), INTERVAL 8 DAY), '11:00:00', 10, 'available'),
(3, DATE_ADD(CURDATE(), INTERVAL 9 DAY), '14:00:00', 10, 'available'),
(3, DATE_ADD(CURDATE(), INTERVAL 15 DAY), '11:00:00', 10, 'available'),
(3, DATE_ADD(CURDATE(), INTERVAL 16 DAY), '14:00:00', 10, 'available'),
(3, DATE_ADD(CURDATE(), INTERVAL 22 DAY), '11:00:00', 10, 'available'),
(3, DATE_ADD(CURDATE(), INTERVAL 23 DAY), '14:00:00', 10, 'available'),
-- London Tower Bridge Experience schedules
(4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', 20, 'available'),
(4, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', 20, 'available'),
(4, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '10:00:00', 20, 'available'),
(4, DATE_ADD(CURDATE(), INTERVAL 7 DAY), '14:00:00', 20, 'available'),
(4, DATE_ADD(CURDATE(), INTERVAL 8 DAY), '10:00:00', 20, 'available'),
(4, DATE_ADD(CURDATE(), INTERVAL 14 DAY), '14:00:00', 20, 'available'),
(4, DATE_ADD(CURDATE(), INTERVAL 15 DAY), '10:00:00', 20, 'available'),
(4, DATE_ADD(CURDATE(), INTERVAL 21 DAY), '14:00:00', 20, 'available'),
(4, DATE_ADD(CURDATE(), INTERVAL 22 DAY), '10:00:00', 20, 'available'),
(4, DATE_ADD(CURDATE(), INTERVAL 28 DAY), '14:00:00', 20, 'available'),
-- Sagrada Familia & Gaudí's Barcelona schedules
(5, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:00:00', 15, 'available'),
(5, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', 15, 'available'),
(5, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '10:00:00', 15, 'available'),
(5, DATE_ADD(CURDATE(), INTERVAL 6 DAY), '14:00:00', 15, 'available'),
(5, DATE_ADD(CURDATE(), INTERVAL 7 DAY), '10:00:00', 15, 'available'),
(5, DATE_ADD(CURDATE(), INTERVAL 13 DAY), '14:00:00', 15, 'available'),
(5, DATE_ADD(CURDATE(), INTERVAL 14 DAY), '10:00:00', 15, 'available'),
(5, DATE_ADD(CURDATE(), INTERVAL 20 DAY), '14:00:00', 15, 'available'),
(5, DATE_ADD(CURDATE(), INTERVAL 21 DAY), '10:00:00', 15, 'available'),
(5, DATE_ADD(CURDATE(), INTERVAL 27 DAY), '14:00:00', 15, 'available');

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Show summary
SELECT 'Database setup completed successfully!' as message;
SELECT 'Total Users:' as description, COUNT(*) as count FROM users
UNION ALL
SELECT 'Total Attractions:', COUNT(*) FROM attractions
UNION ALL
SELECT 'Total Tours:', COUNT(*) FROM tours
UNION ALL
SELECT 'Total Tour Schedules:', COUNT(*) FROM tour_schedules;

-- Display created users
SELECT id, email, first_name, last_name, role, verified, status FROM users ORDER BY role, id;

-- Display attractions
SELECT id, name, location, category, image_url FROM attractions ORDER BY id;

-- Display tours with guide information
SELECT t.id, t.title, u.first_name as guide_first_name, u.last_name as guide_last_name, 
       a.name as attraction_name, t.price, t.status
FROM tours t
LEFT JOIN users u ON t.guide_id = u.id
LEFT JOIN attractions a ON t.attraction_id = a.id
ORDER BY t.id;

