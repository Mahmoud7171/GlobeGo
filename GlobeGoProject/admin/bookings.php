<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Booking.php';

// Initialize database connection
$database = new Database();

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL . '/auth/login.php');
}

$page_title = "Manage Bookings";

$booking = new Booking($database->getConnection());

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

// Filter bookings
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

// Get current page from URL - ensure it's always valid
$page_from_url = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($page_from_url, $total_pages));

// Ensure all are integers and current_page is NEVER 0
$items_per_page = (int)$items_per_page;
$total_items = (int)$total_items;
$total_pages = (int)$total_pages;
$current_page = max(1, (int)$current_page); // Force minimum of 1

// Calculate offset
$offset = ((int)$current_page - 1) * (int)$items_per_page;

// Get paginated bookings
$paginated_bookings = array_slice($filtered_bookings, $offset, $items_per_page);

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Manage Bookings</h1>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="bookings.php" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card bg-transparent border-0">
        <div class="card-header border-0 bg-transparent">
            <h5>All Bookings (<?php echo $total_items; ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Tour</th>
                            <th>Tourist</th>
                            <th>Guide</th>
                            <th>Date</th>
                            <th>Participants</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($paginated_bookings)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted mb-0">No bookings found.</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($paginated_bookings as $booking_item): ?>
                        <tr>
                            <td><?php echo $booking_item['booking_reference']; ?></td>
                            <td><?php echo $booking_item['tour_title']; ?></td>
                            <td><?php echo $booking_item['tourist_first_name'] . ' ' . $booking_item['tourist_last_name']; ?></td>
                            <td><?php echo $booking_item['guide_first_name'] . ' ' . $booking_item['guide_last_name']; ?></td>
                            <td><?php echo formatDate($booking_item['tour_date']); ?></td>
                            <td><?php echo $booking_item['num_participants']; ?></td>
                            <td><span class="text-price"><?php echo formatPrice($booking_item['total_price']); ?></span></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo match($booking_item['status']) {
                                        'confirmed' => 'success',
                                        'pending' => 'warning',
                                        'cancelled' => 'danger',
                                        'completed' => 'info',
                                        default => 'secondary'
                                    };
                                ?>">
                                    <?php 
                                        // Friendlier labels for admins
                                        echo match($booking_item['status']) {
                                            'confirmed' => 'Accepted',
                                            'cancelled' => 'Rejected',
                                            default => ucfirst($booking_item['status'])
                                        };
                                    ?>
                                </span>
                            </td>
                            <td>
                                <?php if (in_array($booking_item['status'], ['pending', 'confirmed', 'cancelled'])): ?>
                                    <form method="POST" action="bookings.php" class="d-flex gap-2 align-items-center">
                                        <input type="hidden" name="booking_id" value="<?php echo (int) $booking_item['id']; ?>">
                                        <input type="hidden" name="status_filter" value="<?php echo htmlspecialchars($status_filter); ?>">
                                        <input type="hidden" name="page" value="<?php echo (int)$current_page; ?>">
                                        <select name="status" class="status-update-select" onchange="this.form.submit()">
                                            <option value="pending" <?php echo $booking_item['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo $booking_item['status'] === 'confirmed' ? 'selected' : ''; ?>>Accepted</option>
                                            <option value="cancelled" <?php echo $booking_item['status'] === 'cancelled' ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted small">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-wrapper mt-4" data-total-pages="<?php echo $total_pages; ?>" data-current-page="<?php echo $current_page; ?>">
                <div class="d-flex flex-column align-items-center gap-3">
                    <!-- Page Info - Centered -->
                    <div class="pagination-info text-center">
                        <small class="text-muted">
                            <?php 
                                // Get page directly from URL to ensure it updates correctly
                                $display_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $display_page = max(1, min($display_page, $total_pages)); // Ensure it's within valid range
                                $per_page = (int)$items_per_page;
                                $total = (int)$total_items;
                                
                                // Calculate start and end based on actual current page
                                if ($total > 0 && $display_page >= 1) {
                                    $start = (($display_page - 1) * $per_page) + 1;
                                    $end = min($display_page * $per_page, $total);
                                } else {
                                    $start = 0;
                                    $end = 0;
                                }
                            ?>
                            Showing <strong><?php echo $start; ?></strong> 
                            to <strong><?php echo $end; ?></strong> 
                            of <strong><?php echo $total; ?></strong> bookings
                        </small>
                    </div>
                    
                    <!-- Pagination Buttons - Centered -->
                    <?php if ($total_pages > 1): ?>
                    <nav>
                        <ul class="pagination-custom mb-0">
                        <!-- Previous Button -->
                        <?php 
                            // Get page directly from URL to avoid variable scope issues
                            $page_from_get = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $current_page_for_prev = max(1, $page_from_get);
                            
                            // DEBUG: Log values
                            error_log("PREVIOUS BUTTON DEBUG: GET[page]=" . (isset($_GET['page']) ? $_GET['page'] : 'not set') . ", current_page_for_prev=" . $current_page_for_prev . ", \$current_page=" . (isset($current_page) ? $current_page : 'not set'));
                        ?>
                        <!-- DEBUG: current_page_for_prev=<?php echo $current_page_for_prev; ?>, should show button: <?php echo ($current_page_for_prev > 1) ? 'YES' : 'NO'; ?> -->
                        <?php if ($current_page_for_prev > 1): 
                            $prev_params = [];
                            if ($status_filter !== 'all') {
                                $prev_params['status'] = $status_filter;
                            }
                            $prev_page = $current_page_for_prev - 1;
                            // Ensure prev_page is at least 1
                            $prev_page = max(1, $prev_page);
                            $prev_params['page'] = $prev_page;
                            $prev_url = '?' . http_build_query($prev_params);
                            
                            // DEBUG: Log URL
                            error_log("PREVIOUS BUTTON DEBUG: prev_url=" . $prev_url . ", full_href=bookings.php" . $prev_url);
                        ?>
                        <li class="pagination-item" id="prev-btn-li" style="pointer-events: auto !important;">
                            <a class="pagination-link pagination-nav" id="prev-btn-link" href="bookings.php<?php echo htmlspecialchars($prev_url); ?>" data-debug-url="bookings.php<?php echo htmlspecialchars($prev_url); ?>" style="pointer-events: auto !important; cursor: pointer !important; text-decoration: none !important; display: inline-block !important;">
                                <i class="fas fa-chevron-left me-1"></i> Previous
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="pagination-item disabled" id="prev-btn-li-disabled">
                            <span class="pagination-link pagination-nav disabled">
                                <i class="fas fa-chevron-left me-1"></i> Previous
                            </span>
                        </li>
                        <?php endif; ?>
                        
                        <!-- Page Numbers -->
                        <?php
                        // Calculate which pages to show (show 2 pages before and after current)
                        $current_page_int = (int)$current_page;
                        $total_pages_int = (int)$total_pages;
                        $start_page = max(1, $current_page_int - 2);
                        $end_page = min($total_pages_int, $current_page_int + 2);
                        
                        // Show first page if not in range
                        if ($start_page > 1): 
                            $first_params = [];
                            if ($status_filter !== 'all') {
                                $first_params['status'] = $status_filter;
                            }
                            $first_url = !empty($first_params) ? '?' . http_build_query($first_params) : '';
                        ?>
                            <li class="pagination-item">
                                <a class="pagination-link" href="bookings.php<?php echo htmlspecialchars($first_url); ?>">1</a>
                            </li>
                            <?php if ($start_page > 2): ?>
                                <li class="pagination-item">
                                    <span class="pagination-link pagination-ellipsis" data-expand-start="2" data-expand-end="<?php echo $start_page - 1; ?>" title="Click to show pages 2-<?php echo $start_page - 1; ?>">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php 
                        // Display page numbers in the calculated range
                        for ($i = $start_page; $i <= $end_page; $i++): 
                            $page_params = [];
                            if ($status_filter !== 'all') {
                                $page_params['status'] = $status_filter;
                            }
                            if ($i > 1) {
                                $page_params['page'] = $i;
                            }
                            $page_url = !empty($page_params) ? '?' . http_build_query($page_params) : '';
                        ?>
                            <li class="pagination-item <?php echo $i === $current_page_int ? 'active' : ''; ?>">
                                <a class="pagination-link <?php echo $i === $current_page_int ? 'active' : ''; ?>" href="bookings.php<?php echo htmlspecialchars($page_url); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php 
                        // Show last page if not in range
                        if ($end_page < $total_pages_int): 
                            if ($end_page < $total_pages_int - 1):
                        ?>
                            <li class="pagination-item">
                                <span class="pagination-link pagination-ellipsis" data-expand-start="<?php echo $end_page + 1; ?>" data-expand-end="<?php echo $total_pages_int - 1; ?>" title="Click to show pages <?php echo $end_page + 1; ?>-<?php echo $total_pages_int - 1; ?>">...</span>
                            </li>
                        <?php 
                            endif;
                            $last_params = [];
                            if ($status_filter !== 'all') {
                                $last_params['status'] = $status_filter;
                            }
                            $last_params['page'] = $total_pages_int;
                            $last_url = '?' . http_build_query($last_params);
                        ?>
                            <li class="pagination-item">
                                <a class="pagination-link" href="bookings.php<?php echo htmlspecialchars($last_url); ?>">
                                    <?php echo $total_pages_int; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <!-- Next Button -->
                        <?php 
                            // Get page directly from URL to avoid variable scope issues
                            $page_from_get_next = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $current_page_for_next = max(1, $page_from_get_next);
                            $total_pages_int = (int)$total_pages;
                            $is_last_page = $current_page_for_next >= $total_pages_int;
                        ?>
                        <?php if (!$is_last_page): 
                            $next_params = [];
                            if ($status_filter !== 'all') {
                                $next_params['status'] = $status_filter;
                            }
                            $next_page = $current_page_for_next + 1;
                            $next_params['page'] = $next_page;
                            $next_url = '?' . http_build_query($next_params);
                        ?>
                        <li class="pagination-item">
                            <a class="pagination-link pagination-nav" href="bookings.php<?php echo htmlspecialchars($next_url); ?>">
                                Next <i class="fas fa-chevron-right ms-1"></i>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="pagination-item disabled">
                            <span class="pagination-link pagination-nav disabled">
                                Next <i class="fas fa-chevron-right ms-1"></i>
                            </span>
                        </li>
                        <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Pagination Styles - Matching GlobeGo Design */
.pagination-wrapper {
    padding: 1.5rem 0;
    display: flex;
    justify-content: center;
}

.pagination-info {
    font-size: 0.95rem;
    text-align: center;
    width: 100%;
}

.pagination-info strong {
    color: var(--primary-color);
    font-weight: 600;
}

.pagination-custom {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination-item {
    margin: 0;
}

.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0.5rem 1rem;
    background: #fff;
    border: 2px solid #e9ecef;
    border-radius: 25px;
    color: #495057;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.pagination-link:hover:not(.disabled):not(.active) {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-color: #007bff;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.pagination-link.active {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-color: #007bff;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

/* Only disable actual disabled elements - be very specific */
.pagination-item.disabled .pagination-link.disabled,
.pagination-item.disabled span.pagination-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none !important;
}

/* CRITICAL: Ensure ALL links in non-disabled items are clickable - override everything */
.pagination-item:not(.disabled) a.pagination-link {
    pointer-events: auto !important;
    cursor: pointer !important;
    text-decoration: none !important;
    display: inline-block !important;
}

/* Force Previous and Next buttons to be clickable when not disabled */
#prev-btn-link,
.pagination-item:not(.disabled) .pagination-nav {
    pointer-events: auto !important;
    cursor: pointer !important;
}

/* Make sure disabled state only applies to actual disabled items */
.pagination-item.disabled span.pagination-link:not(.pagination-ellipsis) {
    pointer-events: none !important;
    cursor: not-allowed !important;
}

/* Ensure ellipsis is always clickable - HIGHEST PRIORITY */
.pagination-item .pagination-ellipsis,
.pagination-item.disabled .pagination-ellipsis,
.pagination-item.disabled span.pagination-ellipsis,
.pagination-item.disabled span.pagination-link.pagination-ellipsis,
.pagination-item.disabled .pagination-link.pagination-ellipsis {
    pointer-events: auto !important;
    cursor: pointer !important;
    opacity: 1 !important;
}

/* Remove disabled class from parent when it contains ellipsis */
.pagination-item:has(.pagination-ellipsis),
.pagination-item.disabled:has(.pagination-ellipsis) {
    pointer-events: auto !important;
}

/* Override all disabled rules for ellipsis specifically */
.pagination-item.disabled .pagination-link.disabled.pagination-ellipsis,
.pagination-item.disabled span.pagination-link.disabled.pagination-ellipsis {
    pointer-events: auto !important;
    cursor: pointer !important;
    opacity: 1 !important;
}

.pagination-nav {
    padding: 0.5rem 1.25rem;
    font-weight: 600;
}

.pagination-ellipsis {
    border: 2px solid #e9ecef !important;
    background: #fff !important;
    cursor: pointer !important;
    pointer-events: auto !important;
    color: #495057 !important;
    min-width: 40px !important;
    height: 40px !important;
    padding: 0.5rem 0.75rem !important;
    border-radius: 25px !important;
    transition: all 0.3s ease !important;
}

.pagination-ellipsis:hover {
    background: rgba(0, 123, 255, 0.1) !important;
    border-color: #007bff !important;
    color: #007bff !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2) !important;
}

/* Dark Mode Support */
body.dark .pagination-link {
    background: #1b1e22;
    border-color: #3b3f45;
    color: #e3e6ea;
}

body.dark .pagination-link:hover:not(.disabled):not(.active) {
    background: linear-gradient(45deg, #0056b3, #004085);
    border-color: #5dade2;
    color: #fff;
}

body.dark .pagination-link.active {
    background: linear-gradient(45deg, #5dade2, #007bff);
    border-color: #5dade2;
    color: #fff;
}

body.dark .pagination-info strong {
    color: #5dade2;
}

body.dark .pagination-ellipsis {
    color: #9ca3af;
    background: transparent;
}

@media (max-width: 576px) {
    .pagination-wrapper {
        flex-direction: column;
        align-items: center;
    }
    
    .pagination-info {
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .pagination-custom {
        justify-content: center;
    }
    
    .pagination-link {
        min-width: 36px;
        height: 36px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    
    .pagination-nav {
        padding: 0.4rem 1rem;
    }
}

/* Modern Circular Status Update Dropdown - White Background */
.status-update-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: #ffffff;
    color: #212529;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    padding: 0.5rem 2.5rem 0.5rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 120px;
    text-align: left;
    position: relative;
    outline: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236c757d' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 12px;
}

.status-update-select:hover {
    border-color: #007bff;
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    transform: translateY(-1px);
}

.status-update-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
}

.status-update-select:active {
    transform: translateY(0);
}

/* Dark mode support */
body.dark .status-update-select {
    background: #1b1e22;
    color: #e3e6ea;
    border-color: #3b3f45;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239ca3af' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 12px;
}

body.dark .status-update-select:hover {
    border-color: #5dade2;
    box-shadow: 0 4px 8px rgba(93, 173, 226, 0.2);
}

body.dark .status-update-select:focus {
    border-color: #5dade2;
    box-shadow: 0 0 0 3px rgba(93, 173, 226, 0.25);
}

/* Responsive */
@media (max-width: 768px) {
    .status-update-select {
        min-width: 100px;
        padding: 0.4rem 2rem 0.4rem 1rem;
        font-size: 0.8rem;
    }
}
</style>

<script>
// DEBUG: Pagination button debugging
console.log('=== PAGINATION DEBUG START ===');
console.log('Current page from PHP (raw):', <?php echo json_encode($current_page); ?>);
console.log('Current page from PHP (int):', <?php echo (int)$current_page; ?>);
console.log('Current page from PHP (max 1):', <?php echo max(1, (int)$current_page); ?>);
console.log('Total pages:', <?php echo (int)$total_pages; ?>);
console.log('GET page param:', <?php echo isset($_GET["page"]) ? json_encode($_GET["page"]) : '"not set"'; ?>);

// Check if Previous button exists
const prevBtn = document.getElementById('prev-btn-link');
const prevBtnDisabled = document.getElementById('prev-btn-li-disabled');
const prevBtnLi = document.getElementById('prev-btn-li');

console.log('Previous button link element:', prevBtn);
console.log('Previous button disabled element:', prevBtnDisabled);
console.log('Previous button LI element:', prevBtnLi);

if (prevBtn) {
    console.log('Previous button HREF:', prevBtn.href);
    console.log('Previous button data-debug-url:', prevBtn.getAttribute('data-debug-url'));
    console.log('Previous button computed styles:', window.getComputedStyle(prevBtn));
    console.log('Previous button pointer-events:', window.getComputedStyle(prevBtn).pointerEvents);
    console.log('Previous button cursor:', window.getComputedStyle(prevBtn).cursor);
    
    // Add click event listener
    prevBtn.addEventListener('click', function(e) {
        console.log('PREVIOUS BUTTON CLICKED!');
        console.log('Event:', e);
        console.log('Target href:', this.href);
        console.log('Will navigate to:', this.href);
    });
    
    // Check parent LI
    if (prevBtnLi) {
        console.log('Parent LI classes:', prevBtnLi.className);
        console.log('Parent LI has disabled class:', prevBtnLi.classList.contains('disabled'));
    }
} else {
    console.log('Previous button link NOT FOUND - might be disabled');
    if (prevBtnDisabled) {
        console.log('Previous button is DISABLED (on page 1)');
    }
}

// Check Next button for comparison
const nextBtn = document.querySelector('.pagination-item:not(.disabled) .pagination-nav[href*="page"]');
if (nextBtn) {
    console.log('Next button found for comparison:', nextBtn);
    console.log('Next button HREF:', nextBtn.href);
    console.log('Next button pointer-events:', window.getComputedStyle(nextBtn).pointerEvents);
}

// Check all pagination links
const allPaginationLinks = document.querySelectorAll('.pagination-link[href]');
console.log('All pagination links found:', allPaginationLinks.length);
allPaginationLinks.forEach((link, index) => {
    console.log(`Link ${index}:`, {
        href: link.href,
        text: link.textContent.trim(),
        pointerEvents: window.getComputedStyle(link).pointerEvents,
        cursor: window.getComputedStyle(link).cursor,
        parentClasses: link.parentElement.className
    });
});

// Check for ellipsis elements
const ellipsisElements = document.querySelectorAll('.pagination-ellipsis');
console.log('=== ELLIPSIS DEBUG ===');
console.log('Ellipsis elements found:', ellipsisElements.length);
ellipsisElements.forEach((ellipsis, index) => {
    const computedStyle = window.getComputedStyle(ellipsis);
    console.log(`Ellipsis ${index}:`, {
        element: ellipsis,
        text: ellipsis.textContent.trim(),
        tagName: ellipsis.tagName,
        classes: ellipsis.className,
        dataExpandStart: ellipsis.getAttribute('data-expand-start'),
        dataExpandEnd: ellipsis.getAttribute('data-expand-end'),
        pointerEvents: computedStyle.pointerEvents,
        cursor: computedStyle.cursor,
        display: computedStyle.display,
        visibility: computedStyle.visibility,
        opacity: computedStyle.opacity,
        parentClasses: ellipsis.parentElement.className,
        parentDisabled: ellipsis.parentElement.classList.contains('disabled'),
        inlineStyles: ellipsis.style.cssText
    });
    
    // Check if click listener is attached
    const hasClickHandler = ellipsis.onclick !== null || ellipsis.getAttribute('onclick') !== null;
    console.log(`Ellipsis ${index} has click handler:`, hasClickHandler);
});

// Check for any CSS that might be blocking
const styleSheets = Array.from(document.styleSheets);
console.log('Checking for blocking CSS rules...');
styleSheets.forEach((sheet, sheetIndex) => {
    try {
        const rules = Array.from(sheet.cssRules || []);
        rules.forEach((rule, ruleIndex) => {
            if (rule.selectorText && (
                rule.selectorText.includes('pagination') && 
                rule.style.pointerEvents === 'none'
            )) {
                console.log(`Blocking CSS found in sheet ${sheetIndex}, rule ${ruleIndex}:`, rule.selectorText, rule.style.cssText);
            }
        });
    } catch (e) {
        // Cross-origin stylesheet, skip
    }
});

console.log('=== PAGINATION DEBUG END ===');

// Force initialize ellipsis after page load
console.log('=== FORCING ELLIPSIS INIT ===');
if (typeof initPaginationEllipsis === 'function') {
    console.log('initPaginationEllipsis function exists, calling it...');
    initPaginationEllipsis();
} else {
    console.error('initPaginationEllipsis function NOT FOUND!');
    console.log('Available functions:', Object.keys(window).filter(k => k.includes('Pagination') || k.includes('ellipsis')));
}

// Also try direct event listener
setTimeout(() => {
    console.log('=== SETTING UP DIRECT ELLIPSIS LISTENERS ===');
    const ellipsisElements = document.querySelectorAll('.pagination-ellipsis');
    console.log('Found', ellipsisElements.length, 'ellipsis elements for direct listener');
    ellipsisElements.forEach((ellipsis, index) => {
        // Force styles
        ellipsis.style.setProperty('cursor', 'pointer', 'important');
        ellipsis.style.setProperty('pointer-events', 'auto', 'important');
        ellipsis.style.setProperty('opacity', '1', 'important');
        ellipsis.style.border = '2px solid #e9ecef';
        ellipsis.style.background = '#fff';
        ellipsis.style.color = '#495057';
        ellipsis.style.borderRadius = '25px';
        ellipsis.style.padding = '0.5rem 0.75rem';
        ellipsis.style.minWidth = '40px';
        ellipsis.style.height = '40px';
        
        // Remove disabled from parent
        const parent = ellipsis.closest('.pagination-item, .page-item');
        if (parent && parent.classList.contains('disabled')) {
            console.log('Removing disabled from parent:', parent);
            parent.classList.remove('disabled');
            parent.style.setProperty('pointer-events', 'auto', 'important');
        }
        
        // Add click listener with inline expand function
        ellipsis.addEventListener('click', function(e) {
            console.log('ELLIPSIS CLICKED!', this, e);
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            // Try to use global function first
            if (typeof expandEllipsis === 'function') {
                expandEllipsis(this);
                return;
            }
            
            // Fallback: implement expandEllipsis inline
            console.log('Using inline expandEllipsis function');
            const ellipsisElement = this;
            const paginationWrapper = ellipsisElement.closest('.pagination-wrapper');
            const paginationList = ellipsisElement.closest('.pagination-custom') || ellipsisElement.closest('.pagination');
            
            if (!paginationList || !paginationWrapper) {
                console.error('Missing pagination elements');
                return;
            }
            
            const totalPages = parseInt(paginationWrapper.getAttribute('data-total-pages')) || 0;
            const currentPage = parseInt(paginationWrapper.getAttribute('data-current-page')) || 1;
            const expandStart = parseInt(ellipsisElement.getAttribute('data-expand-start')) || 0;
            const expandEnd = parseInt(ellipsisElement.getAttribute('data-expand-end')) || 0;
            
            console.log('Expand params:', { totalPages, currentPage, expandStart, expandEnd });
            
            if (totalPages === 0 || expandStart === 0 || expandEnd === 0 || expandStart > expandEnd) {
                console.error('Invalid expand parameters');
                return;
            }
            
            // Get base URL from existing link
            const existingLink = paginationList.querySelector('a.pagination-link:not(.pagination-nav)') || paginationList.querySelector('a.page-link:not(.disabled)');
            if (!existingLink || !existingLink.href) {
                console.error('No existing link found');
                return;
            }
            
            try {
                const url = new URL(existingLink.href, window.location.origin);
                const basePath = url.pathname;
                const searchParams = new URLSearchParams(url.search);
                searchParams.delete('page');
                
                const filterParams = {};
                for (const [key, value] of searchParams.entries()) {
                    filterParams[key] = value;
                }
                
                console.log('Creating page links from', expandStart, 'to', expandEnd);
                
                const fragment = document.createDocumentFragment();
                const ellipsisParent = ellipsisElement.parentElement;
                const isBootstrapPagination = paginationList.classList.contains('pagination');
                
                for (let i = expandStart; i <= expandEnd; i++) {
                    const li = document.createElement('li');
                    li.className = (isBootstrapPagination ? 'page-item' : 'pagination-item') + (i === currentPage ? ' active' : '');
                    
                    const a = document.createElement('a');
                    a.className = (isBootstrapPagination ? 'page-link' : 'pagination-link') + (i === currentPage ? ' active' : '');
                    
                    const pageParams = { ...filterParams };
                    if (i > 1) {
                        pageParams.page = i;
                    }
                    const queryString = Object.keys(pageParams).length > 0 ? '?' + new URLSearchParams(pageParams).toString() : '';
                    a.href = basePath + queryString;
                    a.textContent = i;
                    
                    li.appendChild(a);
                    fragment.appendChild(li);
                }
                
                console.log('Replacing ellipsis with', expandEnd - expandStart + 1, 'page links');
                ellipsisParent.replaceWith(...Array.from(fragment.children));
                console.log('Ellipsis expanded successfully!');
            } catch (error) {
                console.error('Error expanding ellipsis:', error);
            }
        }, true);
        
        console.log(`Direct listener added to ellipsis ${index}`);
    });
}, 1000);
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>


