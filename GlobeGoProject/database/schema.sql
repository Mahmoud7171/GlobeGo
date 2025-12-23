-- GlobeGo Database Schema
CREATE DATABASE IF NOT EXISTS globego_db;
USE globego_db;

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
('Eiffel Tower', 'Iconic iron tower and symbol of Paris, offering panoramic city views from its observation decks.', 'Paris, France', 'Landmark', 'images/eiffel-tower.jpg'),
('Colosseum', 'Ancient Roman amphitheater and one of the most famous landmarks in the world.', 'Rome, Italy', 'Historical', 'images/colosseum.jpg'),
('Times Square', 'Major commercial intersection and tourist destination in Manhattan.', 'New York, USA', 'Landmark', 'images/times-square.jpg'),
('Tower Bridge', 'Victorian suspension bridge over the River Thames in London.', 'London, UK', 'Landmark', 'images/tower-bridge.jpg'),
('Sagrada Familia', 'Unfinished basilica designed by Antoni Gaudí in Barcelona.', 'Barcelona, Spain', 'Religious', 'images/sagrada-familia.jpg');

-- Insert sample tours
INSERT INTO tours (guide_id, attraction_id, title, description, price, duration_hours, max_participants, meeting_point, category, image_url) VALUES
(1, 1, 'Paris Evening Walk', 'Experience the magic of Paris at night with a guided walk through the City of Light.', 45.00, 2, 12, 'Trocadéro Metro Station', 'Walking Tour', 'images/paris-evening.jpg'),
(1, 2, 'Ancient Rome Discovery', 'Explore the Colosseum and surrounding ancient Roman ruins with an expert guide.', 65.00, 3, 15, 'Colosseum Main Entrance', 'Historical Tour', 'images/ancient-rome.jpg'),
(1, 3, 'NYC Food Tour', 'Taste the best of New York City through its diverse culinary scene.', 85.00, 4, 10, 'Union Square Park', 'Food Tour', 'images/nyc-food.jpg');

-- Insert sample tour schedules
INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) VALUES
-- Paris Evening Walk schedules
(1, '2024-12-30', '18:00:00', 8, 'available'),
(1, '2024-12-31', '18:00:00', 10, 'available'),
(1, '2025-01-02', '18:00:00', 12, 'available'),
(1, '2025-01-03', '18:00:00', 15, 'available'),
(1, '2025-01-04', '18:00:00', 10, 'available'),
(1, '2025-01-05', '18:00:00', 8, 'available'),
-- Ancient Rome Discovery schedules
(2, '2024-12-30', '10:00:00', 12, 'available'),
(2, '2024-12-31', '10:00:00', 15, 'available'),
(2, '2025-01-02', '10:00:00', 14, 'available'),
(2, '2025-01-03', '14:00:00', 13, 'available'),
(2, '2025-01-04', '10:00:00', 15, 'available'),
(2, '2025-01-05', '14:00:00', 11, 'available'),
-- NYC Food Tour schedules
(3, '2024-12-30', '19:00:00', 8, 'available'),
(3, '2024-12-31', '19:00:00', 10, 'available'),
(3, '2025-01-02', '19:00:00', 9, 'available'),
(3, '2025-01-03', '19:00:00', 8, 'available'),
(3, '2025-01-04', '19:00:00', 7, 'available'),
(3, '2025-01-05', '19:00:00', 10, 'available');
