<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/Fine.php';

class FinesController extends BaseController
{
    public function index(): void
    {
        // Check if user is logged in and is a tourist
        if (!isLoggedIn() || !isTourist()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        // Check if fines table exists, if not create it
        try {
            $checkTable = $this->db->query("SHOW TABLES LIKE 'fines'");
            if ($checkTable->rowCount() == 0) {
                // Table doesn't exist, create it directly
                $createTableSQL = "CREATE TABLE IF NOT EXISTS fines (
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
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                
                $this->db->exec($createTableSQL);
            }
        } catch (PDOException $e) {
            // If table creation fails, redirect to setup page
            error_log("Fines table creation error: " . $e->getMessage());
            $_SESSION['error_message'] = "The fines table needs to be created. Please visit <a href='" . SITE_URL . "/create-fines-table.php'>create-fines-table.php</a> to set it up.";
            redirect(SITE_URL . '/dashboard.php');
            return;
        }

        // Now try to get fines, with error handling in case table still doesn't exist
        try {
            $fine = new Fine($this->db);
            $fines = $fine->getFinesByTourist($_SESSION['user_id']);
        } catch (PDOException $e) {
            // If table still doesn't exist or other DB error
            error_log("Error fetching fines: " . $e->getMessage());
            $_SESSION['error_message'] = "Database error. Please visit <a href='" . SITE_URL . "/create-fines-table.php'>create-fines-table.php</a> to set up the fines table.";
            redirect(SITE_URL . '/dashboard.php');
            return;
        }

        // Calculate totals
        $total_pending = 0;
        $total_paid = 0;
        foreach ($fines as $fine_item) {
            if ($fine_item['status'] === 'pending') {
                $total_pending += $fine_item['amount'];
            } else {
                $total_paid += $fine_item['amount'];
            }
        }

        $this->render('fines/index', [
            'fines' => $fines,
            'total_pending' => $total_pending,
            'total_paid' => $total_paid,
        ], 'Fines');
    }

    public function payFine(): void
    {
        // Check if user is logged in and is a tourist
        if (!isLoggedIn() || !isTourist()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fine_id = isset($_POST['fine_id']) ? (int)$_POST['fine_id'] : 0;
            $payment_method = sanitize($_POST['payment_method'] ?? '');

            if (!$fine_id || !in_array($payment_method, ['visa', 'paypal'])) {
                $_SESSION['error_message'] = "Invalid payment method or fine ID.";
                redirect(SITE_URL . '/fines.php');
            }

            $fine = new Fine($this->db);
            $fine_details = $fine->getFineById($fine_id);

            // Verify fine belongs to user and is pending
            if (!$fine_details || $fine_details['tourist_id'] != $_SESSION['user_id'] || $fine_details['status'] !== 'pending') {
                $_SESSION['error_message'] = "Fine not found or already paid.";
                redirect(SITE_URL . '/fines.php');
            }

            // Validate payment method specific fields
            $errors = [];
            $payment_reference = '';

            if ($payment_method === 'visa') {
                $card_number = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
                $exp_date = sanitize($_POST['exp_date'] ?? '');
                $cvv = sanitize($_POST['cvv'] ?? '');

                if (empty($card_number) || !preg_match('/^\d{16}$/', $card_number)) {
                    $errors[] = "Valid 16-digit card number is required.";
                }
                if (empty($exp_date) || !preg_match('/^\d{2}\/\d{2}$/', $exp_date)) {
                    $errors[] = "Valid expiration date (MM/YY) is required.";
                }
                if (empty($cvv) || !preg_match('/^\d{3,4}$/', $cvv)) {
                    $errors[] = "Valid CVV is required.";
                }

                if (empty($errors)) {
                    // Validate expiration date is not in the past
                    list($month, $year) = explode('/', $exp_date);
                    $exp_timestamp = mktime(0, 0, 0, (int)$month, 1, 2000 + (int)$year);
                    $current_timestamp = mktime(0, 0, 0, date('n'), 1, 2000 + (int)date('y'));
                    if ($exp_timestamp < $current_timestamp) {
                        $errors[] = "Card expiration date cannot be in the past.";
                    } else {
                        $payment_reference = 'VISA-XXXX' . substr($card_number, -4);
                    }
                }
            } elseif ($payment_method === 'paypal') {
                $paypal_email = sanitize($_POST['paypal_email'] ?? '');
                if (empty($paypal_email) || !filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Valid PayPal email address is required.";
                } else {
                    $payment_reference = 'PAYPAL-' . $paypal_email . '-' . uniqid();
                }
            }

            if (empty($errors)) {
                try {
                    $this->db->beginTransaction();

                    if ($fine->markAsPaid($fine_id, $payment_method, $payment_reference)) {
                        $this->db->commit();
                        $_SESSION['success_message'] = "Fine paid successfully!";
                        redirect(SITE_URL . '/fines.php');
                    } else {
                        $this->db->rollBack();
                        $_SESSION['error_message'] = "Failed to process payment. Please try again.";
                    }
                } catch (Exception $e) {
                    $this->db->rollBack();
                    error_log("Fine payment error: " . $e->getMessage());
                    $_SESSION['error_message'] = "An error occurred while processing your payment.";
                }
            } else {
                $_SESSION['error_message'] = implode(' ', $errors);
            }
        }

        redirect(SITE_URL . '/fines.php');
    }
}


