<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/Booking.php';

class GuideBookingController extends BaseController
{
    private function requireGuide(): void
    {
        if (!isLoggedIn() || !isGuide()) {
            redirect(SITE_URL . '/auth/login.php');
        }
    }

    public function index(): void
    {
        $this->requireGuide();

        $booking = new Booking($this->db);

        // Handle booking status update (same as original)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
            $booking_id = (int) $_POST['booking_id'];
            $status = sanitize($_POST['status']);
            
            if (in_array($status, ['confirmed', 'cancelled'], true)) {
                $booking->updateBookingStatus($booking_id, $status);
            }
        }

        $user_bookings = $booking->getBookingsByGuide($_SESSION['user_id']);
        $booking_stats = $booking->getBookingStats($_SESSION['user_id']);

        // Filter bookings by status
        $status_filter = $_GET['status'] ?? 'all';
        $filtered_bookings = $user_bookings;

        if ($status_filter !== 'all') {
            $filtered_bookings = array_filter($user_bookings, function ($b) use ($status_filter) {
                return $b['status'] === $status_filter;
            });
        }

        $this->render('guide/bookings', [
            'booking_stats' => $booking_stats,
            'user_bookings' => $user_bookings,
            'filtered_bookings' => $filtered_bookings,
            'status_filter' => $status_filter,
        ], 'My Bookings');
    }
}


