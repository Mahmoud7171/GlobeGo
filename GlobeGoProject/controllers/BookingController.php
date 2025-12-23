<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/Booking.php';
require_once __DIR__ . '/../classes/Tour.php';
// Strategy Pattern includes
require_once __DIR__ . '/../patterns/strategy/PaymentContext.php';
require_once __DIR__ . '/../patterns/strategy/CreditCardPaymentStrategy.php';
require_once __DIR__ . '/../patterns/strategy/PayPalPaymentStrategy.php';
require_once __DIR__ . '/../patterns/strategy/BankTransferPaymentStrategy.php';

class BookingController extends BaseController
{
    public function showBookForm(): void
    {
        // Check if user is logged in and is a tourist
        if (!isLoggedIn() || !isTourist()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $tour_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if (!$tour_id) {
            redirect(SITE_URL . '/tours.php');
        }

        $tour = new Tour($this->db);
        $tour_details = $tour->getTourById($tour_id);

        if (!$tour_details) {
            redirect(SITE_URL . '/tours.php');
        }

        $tour_schedules = $tour->getTourSchedules($tour_id);

        $this->render('bookings/book_tour', [
            'tour_id' => $tour_id,
            'tour_details' => $tour_details,
            'tour_schedules' => $tour_schedules,
        ], 'Book Tour');
    }

    public function showReserveForm(): void
    {
        // Check if user is logged in and is a tourist
        if (!isLoggedIn() || !isTourist()) {
            redirect(SITE_URL . '/auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        }

        $tour_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if (!$tour_id) {
            redirect(SITE_URL . '/tours.php');
        }

        $tour = new Tour($this->db);
        $tour_details = $tour->getTourById($tour_id);

        if (!$tour_details) {
            redirect(SITE_URL . '/tours.php');
        }

        // Check if discount is applied from offers page
        $discount_percent = isset($_GET['discount']) ? (float) $_GET['discount'] : 0;
        $discounted_price = isset($_GET['discounted_price']) ? (float) $_GET['discounted_price'] : null;
        $original_price = isset($_GET['original_price']) ? (float) $_GET['original_price'] : null;
        
        // Apply discount if coming from offers
        if ($discount_percent > 0 && $discounted_price !== null && $original_price !== null) {
            $tour_details['original_price'] = $original_price;
            $tour_details['discounted_price'] = $discounted_price;
            $tour_details['discount_percent'] = $discount_percent;
            $tour_details['has_discount'] = true;
        } else {
            $tour_details['has_discount'] = false;
        }

        // Check if tour is in offers section (Egypt, India/Taj Mahal, Japan/Shibuya)
        $tour_details['is_in_offers'] = $this->isTourInOffers($tour_details);

        $tour_schedules = $tour->getTourSchedules($tour_id);

        $this->render('bookings/reserve_tour', [
            'tour_id' => $tour_id,
            'tour_details' => $tour_details,
            'tour_schedules' => $tour_schedules,
        ], 'Reserve Tour');
    }

    public function processBooking(): void
    {
        // Check if user is logged in and is a tourist
        if (!isLoggedIn() || !isTourist()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking = new Booking($this->db);
            $tour = new Tour($this->db);

            // Sanitize input
            $tour_id = (int) ($_POST['tour_id'] ?? 0);
            $tour_schedule_id = (int) ($_POST['tour_schedule_id'] ?? 0);
            $num_participants = (int) ($_POST['num_participants'] ?? 0);
            $booking_notes = sanitize($_POST['booking_notes'] ?? '');
            $payment_method = sanitize($_POST['payment_method'] ?? 'credit_card'); // Strategy Pattern - default to credit_card for backward compatibility

            // Validate input
            $errors = [];

            if (!$tour_id || !$tour_schedule_id || !$num_participants) {
                $errors[] = "All fields are required.";
            }

            if ($num_participants < 1 || $num_participants > 20) {
                $errors[] = "Number of participants must be between 1 and 20.";
            }

            // Check tour exists and get details
            $tour_details = $tour->getTourById($tour_id);
            if (!$tour_details) {
                $errors[] = "Tour not found.";
            }

            // Check availability
            if (empty($errors) && !$booking->checkAvailability($tour_schedule_id, $num_participants)) {
                $errors[] = "Not enough spots available for this tour.";
            }

            if (empty($errors)) {
                // Calculate total price - check for multi-ticket discount first, then offers discount
                $base_price = $tour_details['price'];
                
                // Check for multi-ticket discount (only if not in offers)
                $multi_ticket_offer_claimed = isset($_POST['multi_ticket_offer_claimed']) && $_POST['multi_ticket_offer_claimed'] === '1';
                if ($multi_ticket_offer_claimed && !$this->isTourInOffers($tour_details)) {
                    $multi_ticket_discount_price = isset($_POST['multi_ticket_discount_price']) ? (float) $_POST['multi_ticket_discount_price'] : null;
                    if ($multi_ticket_discount_price && $multi_ticket_discount_price > 0) {
                        $base_price = $multi_ticket_discount_price;
                    }
                }
                
                // Check for offers discount (from offers page)
                if (isset($_POST['discounted_price']) && !empty($_POST['discounted_price'])) {
                    $base_price = (float) $_POST['discounted_price'];
                }
                
                $total_price = $base_price * $num_participants;

                // Process payment using Strategy Pattern
                $paymentContext = new PaymentContext();
                $paymentData = [];
                $paymentResult = null;
                
                // Set strategy based on payment method (Strategy Pattern)
                switch ($payment_method) {
                    case 'credit_card':
                        $paymentContext->setStrategy(new CreditCardPaymentStrategy());
                        $paymentData = [
                            'card_number' => $_POST['card_number'] ?? '',
                            'cvv' => $_POST['cvv'] ?? ''
                        ];
                        break;
                    case 'paypal':
                        $paymentContext->setStrategy(new PayPalPaymentStrategy());
                        $paymentData = [
                            'paypal_email' => $_POST['paypal_email'] ?? ''
                        ];
                        break;
                    case 'bank_transfer':
                        $paymentContext->setStrategy(new BankTransferPaymentStrategy());
                        $paymentData = [
                            'account_number' => $_POST['account_number'] ?? ''
                        ];
                        break;
                    default:
                        // Default to credit card for backward compatibility
                        $paymentContext->setStrategy(new CreditCardPaymentStrategy());
                        $paymentData = [];
                }
                
                // Execute payment using Strategy Pattern
                try {
                    $paymentResult = $paymentContext->executePayment($total_price, $paymentData);
                    
                    if (!$paymentResult['success']) {
                        throw new Exception($paymentResult['message'] ?? 'Payment processing failed');
                    }
                } catch (Exception $e) {
                    $errors[] = "Payment processing error: " . $e->getMessage();
                }

                if (empty($errors) && $paymentResult !== null) {
                    // Create booking
                    $booking->tourist_id = $_SESSION['user_id'];
                    $booking->tour_schedule_id = $tour_schedule_id;
                    $booking->num_participants = $num_participants;
                    $booking->total_price = $total_price;
                    $booking->booking_notes = $booking_notes;
                    $booking->payment_method = $paymentContext->getPaymentMethod();
                    $booking->payment_reference = $paymentResult['reference'] ?? null;

                    try {
                        // Start transaction
                        $this->db->beginTransaction();

                        if ($booking->create()) {
                            // Update payment status
                            $booking->updatePaymentStatus(
                                $booking->id, 
                                'paid', 
                                $booking->payment_method, 
                                $booking->payment_reference
                            );
                            
                            // Update available spots
                            $booking->updateAvailableSpotsAfterBooking($tour_schedule_id, $num_participants);

                            // Commit transaction
                            $this->db->commit();

                            // Redirect to booking confirmation
                            redirect(SITE_URL . '/booking-confirmation.php?id=' . $booking->id);
                        } else {
                            throw new Exception("Failed to create booking.");
                        }
                    } catch (Exception $e) {
                        // Rollback transaction only if it was started
                        if ($this->db->inTransaction()) {
                            $this->db->rollBack();
                        }
                        $errors[] = "Booking failed. Please try again.";
                    }
                }
            }

            // If there are errors, redirect back with error message
            if (!empty($errors)) {
                $_SESSION['booking_errors'] = $errors;
                redirect(SITE_URL . '/tour-details.php?id=' . $tour_id);
            }
        } else {
            redirect(SITE_URL . '/tours.php');
        }
    }

    public function processReservation(): void
    {
        // Check if user is logged in and is a tourist
        if (!isLoggedIn() || !isTourist()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking = new Booking($this->db);
            $tour = new Tour($this->db);

            // Sanitize input
            $tour_id = (int) ($_POST['tour_id'] ?? 0);
            $tour_schedule_id = (int) ($_POST['tour_schedule_id'] ?? 0);
            $num_participants = (int) ($_POST['num_participants'] ?? 0);
            $booking_notes = sanitize($_POST['booking_notes'] ?? '');
            $payment_method = sanitize($_POST['payment_method'] ?? '');

            // Validate input
            $errors = [];

            if (!$tour_id || !$tour_schedule_id || !$num_participants) {
                $errors[] = "All fields are required.";
            }

            if ($num_participants < 1 || $num_participants > 20) {
                $errors[] = "Number of participants must be between 1 and 20.";
            }

            if (empty($payment_method) || !in_array($payment_method, ['visa', 'paypal', 'cash'])) {
                $errors[] = "Please select a valid payment method.";
            }

            // Validate Visa card if selected
            if ($payment_method === 'visa') {
                $card_number = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
                $cvv = $_POST['cvv'] ?? '';
                $exp_date = $_POST['exp_date'] ?? '';

                if (empty($card_number) || !preg_match('/^\d{16}$/', $card_number)) {
                    $errors[] = "Please enter a valid 16-digit Visa card number.";
                }

                if (empty($cvv) || !preg_match('/^\d{3,4}$/', $cvv)) {
                    $errors[] = "Please enter a valid CVV (3 or 4 digits).";
                }

                if (empty($exp_date) || !preg_match('/^\d{2}\/\d{2}$/', $exp_date)) {
                    $errors[] = "Please enter a valid expiration date (MM/YY).";
                } else {
                    // Validate expiration date is not in the past
                    list($month, $year) = explode('/', $exp_date);
                    $exp_timestamp = mktime(0, 0, 0, (int)$month, 1, 2000 + (int)$year);
                    $current_timestamp = mktime(0, 0, 0, date('n'), 1, 2000 + (int)date('y'));
                    if ($exp_timestamp < $current_timestamp) {
                        $errors[] = "Card expiration date cannot be in the past.";
                    }
                }
            }

            // Validate PayPal email if selected
            if ($payment_method === 'paypal') {
                $paypal_email = sanitize($_POST['paypal_email'] ?? '');
                if (empty($paypal_email) || !filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Please enter a valid PayPal email address.";
                }
            }

            // Check tour exists and get details
            $tour_details = $tour->getTourById($tour_id);
            if (!$tour_details) {
                $errors[] = "Tour not found.";
            }

            // Check availability
            if (empty($errors) && !$booking->checkAvailability($tour_schedule_id, $num_participants)) {
                $errors[] = "Not enough spots available for this tour.";
            }

            if (empty($errors)) {
                // Calculate total price - check for multi-ticket discount first, then offers discount
                $base_price = $tour_details['price'];
                
                // Check for multi-ticket discount (only if not in offers)
                $multi_ticket_offer_claimed = isset($_POST['multi_ticket_offer_claimed']) && $_POST['multi_ticket_offer_claimed'] === '1';
                if ($multi_ticket_offer_claimed && !$this->isTourInOffers($tour_details)) {
                    $multi_ticket_discount_price = isset($_POST['multi_ticket_discount_price']) ? (float) $_POST['multi_ticket_discount_price'] : null;
                    if ($multi_ticket_discount_price && $multi_ticket_discount_price > 0) {
                        $base_price = $multi_ticket_discount_price;
                    }
                }
                
                // Check for offers discount (from offers page)
                if (isset($_POST['discounted_price']) && !empty($_POST['discounted_price'])) {
                    $base_price = (float) $_POST['discounted_price'];
                }
                
                $total_price = $base_price * $num_participants;

                // Set booking properties
                $booking->tourist_id = $_SESSION['user_id'];
                $booking->tour_schedule_id = $tour_schedule_id;
                $booking->num_participants = $num_participants;
                $booking->total_price = $total_price;
                $booking->booking_notes = $booking_notes;
                $booking->payment_method = $payment_method;
                $booking->payment_status = ($payment_method === 'cash') ? 'pending' : 'paid';
                $booking->status = ($payment_method === 'cash') ? 'pending' : 'confirmed';

                // Generate payment reference for Visa/PayPal
                if ($payment_method === 'visa') {
                    $card_number = preg_replace('/\s+/', '', $_POST['card_number'] ?? '');
                    $booking->payment_reference = 'VISA-XXXX' . substr($card_number, -4);
                } elseif ($payment_method === 'paypal') {
                    $paypal_email = sanitize($_POST['paypal_email'] ?? '');
                    $booking->payment_reference = 'PAYPAL-' . $paypal_email . '-' . uniqid();
                } else {
                    $booking->payment_reference = 'CASH-PENDING';
                }

                // Create booking with transaction
                try {
                    // Start transaction
                    $this->db->beginTransaction();

                    if ($booking->create()) {
                        // Update available spots
                        $booking->updateAvailableSpotsAfterBooking($tour_schedule_id, $num_participants);

                        // Commit transaction
                        $this->db->commit();

                        // Redirect to booking confirmation
                        redirect(SITE_URL . '/booking-confirmation.php?id=' . $booking->id);
                    } else {
                        throw new Exception("Failed to create reservation.");
                    }
                } catch (Exception $e) {
                    // Rollback transaction if it was started
                    if ($this->db->inTransaction()) {
                        $this->db->rollBack();
                    }
                    $errors[] = "Reservation failed. Please try again.";
                }
            }

            // If there are errors, redirect back with error message
            if (!empty($errors)) {
                $_SESSION['reservation_errors'] = $errors;
                redirect(SITE_URL . '/reserve-tour.php?id=' . $tour_id);
            }
        } else {
            redirect(SITE_URL . '/tours.php');
        }
    }

    public function showBookingDetails(): void
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $booking_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if (!$booking_id) {
            redirect(SITE_URL . '/dashboard.php');
        }

        $booking = new Booking($this->db);
        $booking_details = $booking->getBookingById($booking_id);

        if (!$booking_details) {
            $_SESSION['error_message'] = "Booking not found.";
            redirect(SITE_URL . '/dashboard.php');
        }

        // Check if user has permission to view this booking
        $user_can_view = false;
        if (isAdmin()) {
            $user_can_view = true;
        } elseif (isTourist() && $booking_details['tourist_id'] == $_SESSION['user_id']) {
            $user_can_view = true;
        } elseif (isGuide() && $booking_details['guide_id'] == $_SESSION['user_id']) {
            $user_can_view = true;
        }

        if (!$user_can_view) {
            $_SESSION['error_message'] = "You don't have permission to view this booking.";
            redirect(SITE_URL . '/dashboard.php');
        }

        $this->render('bookings/details', [
            'booking_details' => $booking_details,
        ], 'Booking Details');
    }

    public function showConfirmation(): void
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $booking_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if (!$booking_id) {
            redirect(SITE_URL . '/dashboard.php');
        }

        $booking = new Booking($this->db);
        $booking_details = $booking->getBookingById($booking_id);

        if (!$booking_details || $booking_details['tourist_id'] != $_SESSION['user_id']) {
            redirect(SITE_URL . '/dashboard.php');
        }

        $this->render('bookings/confirmation', [
            'booking_details' => $booking_details,
        ], 'Booking Confirmation');
    }

    public function showCancelForm(): void
    {
        // Check if user is logged in and is a tourist
        if (!isLoggedIn() || !isTourist()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $booking_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if (!$booking_id) {
            redirect(SITE_URL . '/dashboard.php');
        }

        $booking = new Booking($this->db);

        // Get booking details to verify ownership
        $booking_details = $booking->getBookingById($booking_id);

        if (!$booking_details || $booking_details['tourist_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = "Booking not found or you don't have permission to cancel it.";
            redirect(SITE_URL . '/dashboard.php');
        }

        // Check if booking can be cancelled
        if (!in_array($booking_details['status'], ['pending', 'confirmed'])) {
            $_SESSION['error_message'] = "This booking cannot be cancelled.";
            redirect(SITE_URL . '/dashboard.php');
        }

        // Handle cancellation POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['confirm_cancel']) || isset($_POST['booking_id']))) {
            // Get booking_id from POST if not in GET
            $cancel_booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : $booking_id;
            
            if (!$cancel_booking_id) {
                $_SESSION['error_message'] = "No booking ID provided.";
                redirect(SITE_URL . '/dashboard.php');
            }
            
            // Verify booking ownership and status one more time before cancellation
            $verify_query = "SELECT id, tourist_id, status FROM bookings WHERE id = :id";
            $verify_stmt = $this->db->prepare($verify_query);
            $verify_stmt->bindParam(":id", $cancel_booking_id, PDO::PARAM_INT);
            $verify_stmt->execute();
            $verify_booking = $verify_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$verify_booking) {
                $_SESSION['error_message'] = "Booking not found in database.";
                redirect(SITE_URL . '/dashboard.php');
            }

            if ($verify_booking['tourist_id'] != $_SESSION['user_id']) {
                $_SESSION['error_message'] = "You don't have permission to cancel this booking.";
                redirect(SITE_URL . '/dashboard.php');
            }

            if (!in_array($verify_booking['status'], ['pending', 'confirmed'])) {
                $_SESSION['error_message'] = "This booking cannot be cancelled. Current status: " . $verify_booking['status'];
                redirect(SITE_URL . '/dashboard.php');
            }

            // Call cancelBooking - it handles its own transaction
            $result = $booking->cancelBooking($cancel_booking_id, $_SESSION['user_id']);
            
            if ($result) {
                $_SESSION['success_message'] = "Your booking has been cancelled successfully.";
                redirect(SITE_URL . '/dashboard.php');
            } else {
                // Get more details about why it failed
                $debug_query = "SELECT id, status, tourist_id, tour_schedule_id FROM bookings WHERE id = :id";
                $debug_stmt = $this->db->prepare($debug_query);
                $debug_stmt->bindParam(":id", $cancel_booking_id, PDO::PARAM_INT);
                $debug_stmt->execute();
                $debug_booking = $debug_stmt->fetch(PDO::FETCH_ASSOC);
                
                $error_msg = "Failed to cancel booking. ";
                if ($debug_booking) {
                    if ($debug_booking['status'] === 'cancelled') {
                        $error_msg = "This booking is already cancelled.";
                    } else if (!in_array($debug_booking['status'], ['pending', 'confirmed'])) {
                        $error_msg = "This booking cannot be cancelled. Current status: " . htmlspecialchars($debug_booking['status']);
                    } else if ($debug_booking['tourist_id'] != $_SESSION['user_id']) {
                        $error_msg = "You don't have permission to cancel this booking.";
                    } else {
                        // Check if tour_schedule exists
                        $schedule_check = $this->db->prepare("SELECT id FROM tour_schedules WHERE id = :id");
                        $schedule_check->bindParam(":id", $debug_booking['tour_schedule_id'], PDO::PARAM_INT);
                        $schedule_check->execute();
                        if (!$schedule_check->fetch()) {
                            $error_msg = "Cannot cancel: Tour schedule not found. Please contact support.";
                        } else {
                            $error_msg .= "Please check PHP error logs for details or contact support.";
                        }
                    }
                } else {
                    $error_msg .= "Booking not found.";
                }
                
                $_SESSION['error_message'] = $error_msg;
                redirect(SITE_URL . '/dashboard.php');
            }
        }

        // GET: show confirmation form
        $this->render('bookings/cancel', [
            'booking_details' => $booking_details,
        ], 'Cancel Booking');
    }

    /**
     * Check if a tour is in the offers section
     * Tours in offers: Egypt, India/Taj Mahal, Japan/Shibuya
     */
    private function isTourInOffers($tour_details): bool
    {
        $title = strtolower($tour_details['title'] ?? '');
        $location = strtolower($tour_details['location'] ?? '');

        // Check for Egypt
        if (stripos($title, 'egyptian') !== false || stripos($title, 'egypt') !== false ||
            stripos($location, 'egypt') !== false || stripos($location, 'cairo') !== false) {
            return true;
        }

        // Check for India/Taj Mahal
        if (stripos($title, 'taj mahal') !== false || stripos($title, 'india') !== false ||
            stripos($location, 'india') !== false || stripos($location, 'agra') !== false) {
            return true;
        }

        // Check for Japan/Shibuya
        if (stripos($title, 'shibuya') !== false || stripos($title, 'japan') !== false || stripos($title, 'tokyo') !== false ||
            stripos($location, 'japan') !== false || stripos($location, 'tokyo') !== false || stripos($location, 'shibuya') !== false) {
            return true;
        }

        return false;
    }
}


