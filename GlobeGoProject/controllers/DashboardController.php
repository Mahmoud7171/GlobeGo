<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Tour.php';
require_once __DIR__ . '/../classes/Booking.php';
require_once __DIR__ . '/../classes/Attraction.php';

class DashboardController extends BaseController
{
    public function index(): void
    {
        // Require login (same check as original)
        if (!isLoggedIn()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $userModel = new User($this->db);
        $userModel->getUserById($_SESSION['user_id']);

        // Display messages via session in the view (we'll read/unset there)
        $error_message = $_SESSION['error_message'] ?? null;
        $success_message = $_SESSION['success_message'] ?? null;
        unset($_SESSION['error_message'], $_SESSION['success_message']);

        // Get user-specific data based on role (exactly as original)
        $user_bookings = [];
        $upcoming_bookings = [];
        $user_tours = [];
        $all_users = [];
        $all_bookings = [];
        $booking_stats = [];
        
        // Initialize pagination variables
        $current_page = 1;
        $total_pages = 1;
        $total_items = 0;
        $items_per_page = 5;

        if (isTourist()) {
            $booking = new Booking($this->db);
            $user_bookings = $booking->getBookingsByTourist($_SESSION['user_id']);
            // Show all bookings (all statuses - confirmed, pending, etc.)
            $all_upcoming_bookings = $user_bookings;
            // Sort by date (upcoming first, then by status)
            usort($all_upcoming_bookings, function($a, $b) {
                $dateA = strtotime($a['tour_date'] . ' ' . $a['tour_time']);
                $dateB = strtotime($b['tour_date'] . ' ' . $b['tour_time']);
                // First sort by date
                if ($dateA != $dateB) {
                    return $dateA - $dateB;
                }
                // Then by status priority (confirmed > pending > cancelled)
                $statusPriority = ['confirmed' => 1, 'pending' => 2, 'cancelled' => 3];
                $priorityA = $statusPriority[$a['status']] ?? 4;
                $priorityB = $statusPriority[$b['status']] ?? 4;
                return $priorityA - $priorityB;
            });
            
            // Pagination settings
            $items_per_page = 5;
            $total_items = count($all_upcoming_bookings);
            $total_pages = $total_items > 0 ? ceil($total_items / $items_per_page) : 1;
            $current_page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $total_pages)) : 1;
            
            // Calculate offset
            $offset = ($current_page - 1) * $items_per_page;
            
            // Get paginated bookings
            $upcoming_bookings = array_slice($all_upcoming_bookings, $offset, $items_per_page);
        } elseif (isGuide()) {
            $tour = new Tour($this->db);
            $user_tours = $tour->getToursByGuide($_SESSION['user_id']);
            $booking = new Booking($this->db);
            $user_bookings = $booking->getBookingsByGuide($_SESSION['user_id']);
            $booking_stats = $booking->getBookingStats($_SESSION['user_id']);
        } elseif (isAdmin()) {
            $user_obj = new User($this->db);
            $all_users = $user_obj->getAllUsers();
            $booking = new Booking($this->db);
            $all_bookings = $booking->getAllBookings();
            $booking_stats = $booking->getBookingStats();
        }

        $this->render('dashboard/index', [
            'user' => $userModel,
            'error_message' => $error_message,
            'success_message' => $success_message,
            'user_bookings' => $user_bookings,
            'upcoming_bookings' => $upcoming_bookings,
            'user_tours' => $user_tours,
            'all_users' => $all_users,
            'all_bookings' => $all_bookings,
            'booking_stats' => $booking_stats,
            'current_page' => $current_page ?? 1,
            'total_pages' => $total_pages ?? 1,
            'total_items' => $total_items ?? 0,
            'items_per_page' => $items_per_page ?? 5,
        ], 'Dashboard');
    }
}


