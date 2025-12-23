-- GlobeGo Database Setup Script
-- Run this in phpMyAdmin or MySQL command line

-- Create database
CREATE DATABASE IF NOT EXISTS globego_db;
USE globego_db;

-- Drop tables if they exist (for clean setup)
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS tour_schedules;
DROP TABLE IF EXISTS tours;
DROP TABLE IF EXISTS attractions;
DROP TABLE IF EXISTS users;

-- Users table
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Attractions table
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tours table
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
    FOREIGN KEY (attraction_id) REFERENCES attractions(id) ON DELETE SET NULL
);

-- Tour schedules table
CREATE TABLE tour_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    tour_date DATE NOT NULL,
    tour_time TIME NOT NULL,
    available_spots INT NOT NULL,
    status ENUM('available', 'full', 'cancelled') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tour_datetime (tour_id, tour_date, tour_time)
);

-- Bookings table
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
    FOREIGN KEY (tour_schedule_id) REFERENCES tour_schedules(id) ON DELETE CASCADE
);

-- Reviews table
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
    UNIQUE KEY unique_booking_review (booking_id)
);

-- Insert default admin user
INSERT INTO users (email, password, first_name, last_name, role, verified, status) 
VALUES ('admin@globego.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin', TRUE, 'active');

-- Insert sample attractions
INSERT INTO attractions (name, description, location, category, image_url) VALUES
('Eiffel Tower', 'Iconic iron tower and symbol of Paris, offering panoramic city views from its observation decks.', 'Paris, France', 'Landmark', 'https://picsum.photos/400/300?random=1'),
('Colosseum', 'Ancient Roman amphitheater and one of the most famous landmarks in the world.', 'Rome, Italy', 'Historical', 'https://picsum.photos/400/300?random=2'),
('Times Square', 'Major commercial intersection and tourist destination in Manhattan.', 'New York, USA', 'Landmark', 'https://picsum.photos/400/300?random=3'),
('Tower Bridge', 'Victorian suspension bridge over the River Thames in London.', 'London, UK', 'Landmark', 'https://picsum.photos/400/300?random=4'),
('Sagrada Familia', 'Unfinished basilica designed by Antoni Gaudí in Barcelona.', 'Barcelona, Spain', 'Religious', 'https://picsum.photos/400/300?random=5');

-- Insert sample tours
INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, max_participants, meeting_point, category, image_url) VALUES
(1, 1, 'Paris Evening Walk', 'Experience the magic of Paris at night with a guided walk through the City of Light.', 45.00, 2, 12, 'Trocadéro Metro Station', 'Walking Tour', 'https://picsum.photos/400/300?random=6'),
(1, 2, 'Ancient Rome Discovery', 'Explore the Colosseum and surrounding ancient Roman ruins with an expert guide.', 65.00, 3, 15, 'Colosseum Main Entrance', 'Historical Tour', 'https://picsum.photos/400/300?random=7'),
(1, 3, 'NYC Food Tour', 'Taste the best of New York City through its diverse culinary scene.', 85.00, 4, 10, 'Union Square Park', 'Food Tour', 'https://picsum.photos/400/300?random=8');

-- Insert sample tour schedules
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots) VALUES
(1, DATE_ADD(CURDATE(), INTERVAL 7 DAY), '18:00:00', 12),
(1, DATE_ADD(CURDATE(), INTERVAL 14 DAY), '18:00:00', 12),
(2, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '10:00:00', 15),
(2, DATE_ADD(CURDATE(), INTERVAL 12 DAY), '10:00:00', 15),
(3, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '14:00:00', 10),
(3, DATE_ADD(CURDATE(), INTERVAL 10 DAY), '14:00:00', 10);

-- Show success message
SELECT 'Database setup completed successfully!' as message;

