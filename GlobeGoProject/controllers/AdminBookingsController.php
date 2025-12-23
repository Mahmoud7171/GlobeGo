<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/Booking.php';

class AdminBookingsController extends BaseController
{
    public function index(): void
    {
        // Check if user is logged in and is admin (same as original admin/bookings.php)
        if (!isLoggedIn() || !isAdmin()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $booking = new Booking($this->db);
        
        // Handle status updates from admin (Accept / Reject)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
            $booking_id = (int) $_POST['booking_id'];
            $new_status = $_POST['status'];

            // Only allow valid statuses to be set from the dropdown
            $allowed_statuses = ['pending', 'confirmed', 'cancelled'];
            if (in_array($new_status, $allowed_statuses, true)) {
                $booking->updateBookingStatus($booking_id, $new_status);
            }

            // Build redirect URL with filters and page
            $redirect_params = [];
            $status_filter = $_GET['status'] ?? 'all';
            if ($status_filter !== 'all') {
                $redirect_params['status'] = $status_filter;
            }
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($current_page > 1) {
                $redirect_params['page'] = $current_page;
            }
            
            $redirect_url = SITE_URL . '/admin/bookings.php';
            if (!empty($redirect_params)) {
                $redirect_url .= '?' . http_build_query($redirect_params);
            }
            
            redirect($redirect_url);
        }
        $all_bookings = $booking->getAllBookings();

        $status_filter = $_GET['status'] ?? 'all';
        $filtered_bookings = $all_bookings;

        if ($status_filter !== 'all') {
            $filtered_bookings = array_filter($all_bookings, function($b) use ($status_filter) {
                return $b['status'] === $status_filter;
            });
        }

        // Reset array keys after filtering
        $filtered_bookings = array_values($filtered_bookings);

        // Pagination settings
        $items_per_page = 5;
        $total_items = count($filtered_bookings);
        $total_pages = $total_items > 0 ? ceil($total_items / $items_per_page) : 1;
        $current_page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $total_pages)) : 1;
        
        // Calculate offset
        $offset = ($current_page - 1) * $items_per_page;
        
        // Get paginated bookings
        $paginated_bookings = array_slice($filtered_bookings, $offset, $items_per_page);

        $this->render('admin/bookings', [
            'all_bookings' => $all_bookings,
            'filtered_bookings' => $paginated_bookings,
            'status_filter' => $status_filter,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'total_items' => $total_items,
            'items_per_page' => $items_per_page,
        ], 'Manage Bookings');
    }
}
