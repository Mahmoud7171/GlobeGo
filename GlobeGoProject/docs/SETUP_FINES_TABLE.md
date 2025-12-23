# Setup Instructions for Fines Table

The fines table needs to be created in your database before using the fines feature.

## Option 1: Run Setup Script (Recommended)

Visit this URL in your browser:
```
http://localhost/GlobeGoProject/create-fines-table.php
```

This will automatically create the fines table for you.

## Option 2: Run SQL in phpMyAdmin

1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
2. Select your `globego_db` database
3. Click on the "SQL" tab
4. Copy and paste this SQL:

```sql
CREATE TABLE IF NOT EXISTS fines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tourist_id INT NOT NULL,
    booking_id INT NOT NULL,
    booking_reference VARCHAR(20) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    original_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid') DEFAULT 'pending',
    payment_method VARCHAR(50) NULL,
    payment_reference VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    paid_at TIMESTAMP NULL,
    FOREIGN KEY (tourist_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    INDEX idx_tourist_id (tourist_id),
    INDEX idx_booking_id (booking_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

5. Click "Go" to execute

After running either option, you can access the Fines page from your dashboard!


