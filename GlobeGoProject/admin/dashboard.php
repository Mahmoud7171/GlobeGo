<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Tour.php';
require_once __DIR__ . '/../classes/Booking.php';
require_once __DIR__ . '/../classes/Attraction.php';

// Initialize database connection
$database = new Database();

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL . '/auth/login.php');
}

$page_title = "Admin Dashboard";

$user = new User($database->getConnection());
$tour = new Tour($database->getConnection());
$booking = new Booking($database->getConnection());
$attraction = new Attraction($database->getConnection());

// Get statistics
$all_users = $user->getAllUsers();
$all_bookings = $booking->getAllBookings();
$booking_stats = $booking->getBookingStats();
// Get pending guides only (status = 'pending' OR application_status = 'pending' AND role = 'guide')
$pending_users = array_filter($all_users, function($u) { 
    return $u['role'] === 'guide' && 
           ($u['status'] === 'pending' || 
           (isset($u['application_status']) && $u['application_status'] === 'pending')); 
});

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <?php if (isset($_SESSION['admin_message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['admin_message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['admin_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
        unset($_SESSION['admin_message']);
        unset($_SESSION['admin_message_type']);
        ?>
    <?php endif; ?>
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Admin Dashboard</h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h3 class="text-primary"><?php echo count($all_users); ?></h3>
                    <p class="mb-0">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                    <h3 class="text-success"><?php echo $booking_stats['total_bookings'] ?? 0; ?></h3>
                    <p class="mb-0">Total Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-dollar-sign fa-2x text-info mb-2"></i>
                    <h3 class="text-info"><?php echo formatPrice($booking_stats['total_revenue'] ?? 0); ?></h3>
                    <p class="mb-0">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                    <h3 class="text-warning"><?php echo count($pending_users); ?></h3>
                    <p class="mb-0">Pending Actions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="users.php" class="btn btn-primary btn-block w-100">Manage Users</a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo SITE_URL; ?>/admin/attractions.php" class="btn btn-success btn-block w-100">Manage Attractions</a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo SITE_URL; ?>/admin/tours.php" class="btn btn-warning btn-block w-100">Manage Tours</a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="bookings.php" class="btn btn-info btn-block w-100">View Bookings</a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo SITE_URL; ?>/admin/reports.php" class="btn btn-danger btn-block w-100">
                                <i class="fas fa-envelope me-2"></i>Contact Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Tour Guide Applications -->
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Pending Tour Guide Applications</h5>
                    <span class="badge bg-warning"><?php echo count($pending_users); ?></span>
                </div>
                <div class="card-body">
                    <?php if (empty($pending_users)): ?>
                        <p class="text-muted">No pending tour guide applications.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>National ID</th>
                                        <th>Applied Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($pending_users, 0, 5) as $user_item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user_item['first_name'] . ' ' . $user_item['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user_item['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user_item['phone'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($user_item['national_id'] ?? '-'); ?></td>
                                        <td><?php echo formatDate($user_item['created_at']); ?></td>
                                        <td>
                                            <form method="POST" action="<?php echo SITE_URL; ?>/admin/users_mvc.php" style="display: inline-block;">
                                                <input type="hidden" name="user_id" value="<?php echo $user_item['id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="redirect" value="dashboard.php">
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this tour guide?');">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo SITE_URL; ?>/admin/users_mvc.php" style="display: inline-block;">
                                                <input type="hidden" name="user_id" value="<?php echo $user_item['id']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <input type="hidden" name="redirect" value="dashboard.php">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this tour guide application?');">
                                                    <i class="fas fa-times me-1"></i>Reject
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (count($pending_users) > 5): ?>
                            <div class="text-center">
                                <a href="users_mvc.php?status=pending&role=guide" class="btn btn-outline-primary btn-sm">View All</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Bookings</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Booking Ref</th>
                                    <th>Tour</th>
                                    <th>Tourist</th>
                                    <th>Guide</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($all_bookings, 0, 5) as $booking_item): ?>
                                <tr>
                                    <td><?php echo $booking_item['booking_reference']; ?></td>
                                    <td><?php echo $booking_item['tour_title']; ?></td>
                                    <td><?php echo $booking_item['tourist_first_name'] . ' ' . $booking_item['tourist_last_name']; ?></td>
                                    <td><?php echo $booking_item['guide_first_name'] . ' ' . $booking_item['guide_last_name']; ?></td>
                                    <td><?php echo formatDate($booking_item['tour_date']); ?></td>
                                    <td><?php echo formatPrice($booking_item['total_price']); ?></td>
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
                                            <?php echo ucfirst($booking_item['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <a href="bookings.php" class="btn btn-outline-primary">View All Bookings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
