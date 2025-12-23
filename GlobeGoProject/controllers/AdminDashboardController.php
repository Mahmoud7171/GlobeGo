<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Tour.php';
require_once __DIR__ . '/../classes/Booking.php';
require_once __DIR__ . '/../classes/Attraction.php';

class AdminDashboardController extends BaseController
{
    public function index(): void
    {
        // Check if user is logged in and is admin (same as original admin/dashboard.php)
        if (!isLoggedIn() || !isAdmin()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $user = new User($this->db);
        $tour = new Tour($this->db);
        $booking = new Booking($this->db);
        $attraction = new Attraction($this->db);

        $all_users = $user->getAllUsers();
        $all_bookings = $booking->getAllBookings();
        $booking_stats = $booking->getBookingStats();
        
        // Get pending guides only (status = 'pending' OR application_status = 'pending' AND role = 'guide')
        $pending_users = array_filter($all_users, function($u) { 
            return $u['role'] === 'guide' && 
                   ($u['status'] === 'pending' || 
                   (isset($u['application_status']) && $u['application_status'] === 'pending')); 
        });

        $this->render('admin/dashboard', [
            'all_users' => $all_users,
            'all_bookings' => $all_bookings,
            'booking_stats' => $booking_stats,
            'pending_users' => $pending_users,
        ], 'Admin Dashboard');
    }
}
