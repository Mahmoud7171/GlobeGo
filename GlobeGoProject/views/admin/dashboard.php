<?php
// Admin dashboard view extracted from admin/dashboard.php
// Expects: $all_users, $all_bookings, $booking_stats, $pending_users (guides only)
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
            <h1 class="mb-4 dashboard-title"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center dashboard-stat-card">
                <div class="card-body">
                    <i class="fas fa-users fa-2x stat-icon stat-primary mb-2"></i>
                    <h3 class="stat-number stat-primary"><?php echo count($all_users); ?></h3>
                    <p class="mb-0 stat-label">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center dashboard-stat-card">
                <div class="card-body">
                    <i class="fas fa-calendar-check fa-2x stat-icon stat-success mb-2"></i>
                    <h3 class="stat-number stat-success"><?php echo $booking_stats['total_bookings'] ?? 0; ?></h3>
                    <p class="mb-0 stat-label">Total Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center dashboard-stat-card">
                <div class="card-body">
                    <i class="fas fa-dollar-sign fa-2x stat-icon stat-info mb-2"></i>
                    <h3 class="stat-number stat-info"><?php echo formatPrice($booking_stats['total_revenue'] ?? 0); ?></h3>
                    <p class="mb-0 stat-label">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-center dashboard-stat-card">
                <div class="card-body">
                    <i class="fas fa-exclamation-triangle fa-2x stat-icon stat-warning mb-2"></i>
                    <h3 class="stat-number stat-warning"><?php echo count($pending_users); ?></h3>
                    <p class="mb-0 stat-label">Pending Actions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header dashboard-card-header">
                    <h5 class="dashboard-section-title"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body dashboard-card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="users.php" class="btn-quick-action btn-action-primary">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="attractions.php" class="btn-quick-action btn-action-success">
                                <i class="fas fa-map-marker-alt me-2"></i>Manage Attractions
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="bookings.php" class="btn-quick-action btn-action-info">
                                <i class="fas fa-calendar-check me-2"></i>View Bookings
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="reports.php" class="btn-quick-action btn-action-secondary" data-bs-toggle="" title="">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    /* Dashboard Title - White */
    .dashboard-title {
        color: #ffffff !important;
        font-weight: 700;
    }
    
    .dashboard-title i {
        color: #5dade2;
    }
    
    /* Statistics Cards - Dark Style with Smooth Corners */
    .dashboard-stat-card {
        background: #2b3040 !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .dashboard-stat-card .card-body {
        border-radius: 20px !important;
        padding: 1.5rem;
    }
    
    .dashboard-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }
    
    .stat-icon {
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .stat-icon.stat-primary {
        color: #5dade2 !important;
    }
    
    .stat-icon.stat-success {
        color: #28a745 !important;
    }
    
    .stat-icon.stat-info {
        color: #17a2b8 !important;
    }
    
    .stat-icon.stat-warning {
        color: #ffc107 !important;
    }
    
    .stat-number {
        color: #ffffff !important;
        font-weight: 700;
        font-size: 2rem;
        margin: 0.5rem 0;
    }
    
    .stat-label {
        color: #ffffff !important;
        font-weight: 500;
        margin: 0;
    }
    
    /* Dashboard Cards - Dark Style with Smooth Corners */
    .dashboard-card {
        background: #2b3040 !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px !important;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    
    .dashboard-card-header {
        background: #2b3040 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px 20px 0 0 !important;
        padding: 1.25rem 1.5rem;
    }
    
    .dashboard-section-title {
        color: #ffffff !important;
        font-weight: 600;
        margin: 0;
    }
    
    .dashboard-section-title i {
        color: #5dade2;
    }
    
    .dashboard-card-body {
        background: #2b3040 !important;
        color: #e3e6ea;
        border-radius: 0 0 20px 20px !important;
        padding: 1.5rem;
    }
    
    .dashboard-text-muted {
        color: #9ca3af !important;
    }
    
    /* Dashboard Badges - Smooth Rounded Corners */
    .dashboard-badge {
        border-radius: 20px !important;
        padding: 0.5rem 0.75rem;
        font-weight: 600;
    }
    
    /* Quick Action Buttons - Smooth Rounded Corners */
    .btn-quick-action {
        display: block;
        width: 100%;
        padding: 1rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 25px !important;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-action-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
    }
    
    .btn-action-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
        background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
        color: #fff;
        text-decoration: none;
    }
    
    .btn-action-success {
        background: linear-gradient(135deg, #28a745 0%, #218838 50%, #1e7e34 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
    }
    
    .btn-action-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.6);
        background: linear-gradient(135deg, #218838 0%, #1e7e34 50%, #1c7430 100%);
        color: #fff;
        text-decoration: none;
    }
    
    .btn-action-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 50%, #117a8b 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);
    }
    
    .btn-action-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(23, 162, 184, 0.6);
        background: linear-gradient(135deg, #138496 0%, #117a8b 50%, #0f6674 100%);
        color: #fff;
        text-decoration: none;
    }
    
    .btn-action-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 50%, #495057 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
    }
    
    .btn-action-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.6);
        background: linear-gradient(135deg, #5a6268 0%, #495057 50%, #343a40 100%);
        color: #fff;
        text-decoration: none;
    }
    
    /* Action Buttons - Small */
    .btn-action {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 20px !important;
        border: none;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .btn-action-success-sm {
        background: linear-gradient(135deg, #28a745 0%, #218838 50%, #1e7e34 100%);
        color: #fff;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }
    
    .btn-action-success-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.5);
        background: linear-gradient(135deg, #218838 0%, #1e7e34 50%, #1c7430 100%);
        color: #fff;
    }
    
    .btn-action-danger-sm {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 50%, #bd2130 100%);
        color: #fff;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        margin-left: 0.5rem;
    }
    
    .btn-action-danger-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.5);
        background: linear-gradient(135deg, #c82333 0%, #bd2130 50%, #b21f2d 100%);
        color: #fff;
    }
    
    .btn-action-outline {
        background: transparent;
        border: 2px solid #5dade2;
        color: #5dade2;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 25px !important;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .btn-action-outline:hover {
        background: #5dade2;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(93, 173, 226, 0.4);
        text-decoration: none;
    }
    
    .btn-action-outline-sm {
        background: transparent;
        border: 2px solid #5dade2;
        color: #5dade2;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 20px !important;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    .btn-action-outline-sm:hover {
        background: #5dade2;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(93, 173, 226, 0.4);
        text-decoration: none;
    }
    
    /* Dashboard Tables - Dark Style with Smooth Corners */
    .dashboard-table-responsive {
        border-radius: 15px !important;
        overflow: hidden;
    }
    
    .dashboard-table {
        color: #e3e6ea;
        border-radius: 15px !important;
        overflow: hidden;
        background: #2b3040;
    }
    
    .dashboard-table thead th {
        color: #ffffff !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        background: #2b3040;
        padding: 1rem;
    }
    
    .dashboard-table thead th:first-child {
        border-top-left-radius: 15px !important;
    }
    
    .dashboard-table thead th:last-child {
        border-top-right-radius: 15px !important;
    }
    
    .dashboard-table tbody td {
        color: #e3e6ea !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1rem;
    }
    
    .dashboard-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 15px !important;
    }
    
    .dashboard-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 15px !important;
    }
    
    .dashboard-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    
    /* Alert Styling - Smooth Corners */
    .alert {
        border-radius: 20px !important;
        overflow: hidden;
        border: none;
    }
    
    /* Ensure all card elements have smooth corners */
    .card {
        border-radius: 20px !important;
        overflow: hidden;
    }
    
    .card-body {
        border-radius: 20px !important;
    }
    
    /* Fix any potential overflow issues */
    .dashboard-stat-card,
    .dashboard-card {
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        transform: translateZ(0);
    }
    
    /* Remove tooltip/hover effect on Reports button */
    a[href="reports.php"].btn {
        pointer-events: auto !important;
    }
    /* Hide Bootstrap tooltip on Reports button */
    a[href="reports.php"].btn + .tooltip,
    .tooltip[data-popper-placement] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
    }
    /* Prevent tooltip from showing on hover */
    body .tooltip.show {
        display: none !important;
    }
    
    /* ============================================
       LIGHT MODE STYLES - Match Dark Mode Quality
       ============================================ */
    
    /* Dashboard Title - Light Mode */
    body:not(.dark) .dashboard-title {
        color: #212529 !important;
    }
    
    body:not(.dark) .dashboard-title i {
        color: #007bff;
    }
    
    /* Statistics Cards - Light Mode */
    body:not(.dark) .dashboard-stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
        border: 1px solid #e9ecef !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }
    
    body:not(.dark) .dashboard-stat-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }
    
    body:not(.dark) .stat-icon.stat-primary {
        color: #007bff !important;
    }
    
    body:not(.dark) .stat-icon.stat-success {
        color: #28a745 !important;
    }
    
    body:not(.dark) .stat-icon.stat-info {
        color: #17a2b8 !important;
    }
    
    body:not(.dark) .stat-icon.stat-warning {
        color: #ffc107 !important;
    }
    
    body:not(.dark) .stat-number {
        color: #212529 !important;
    }
    
    body:not(.dark) .stat-label {
        color: #6c757d !important;
    }
    
    /* Dashboard Cards - Light Mode */
    body:not(.dark) .dashboard-card {
        background: #ffffff !important;
        border: 1px solid #e9ecef !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }
    
    body:not(.dark) .dashboard-card-header {
        background: #f8f9fa !important;
        border-bottom: 1px solid #e9ecef !important;
    }
    
    body:not(.dark) .dashboard-section-title {
        color: #212529 !important;
    }
    
    body:not(.dark) .dashboard-section-title i {
        color: #007bff;
    }
    
    body:not(.dark) .dashboard-card-body {
        background: #ffffff !important;
        color: #212529 !important;
    }
    
    body:not(.dark) .dashboard-text-muted {
        color: #6c757d !important;
    }
    
    /* Dashboard Tables - Light Mode */
    body:not(.dark) .dashboard-table {
        color: #212529 !important;
        background: #ffffff !important;
    }
    
    body:not(.dark) .dashboard-table thead th {
        color: #212529 !important;
        background: #f8f9fa !important;
        border-bottom: 2px solid #dee2e6 !important;
    }
    
    body:not(.dark) .dashboard-table tbody td {
        color: #212529 !important;
        border-bottom: 1px solid #e9ecef !important;
    }
    
    body:not(.dark) .dashboard-table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    
    body:not(.dark) .dashboard-table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    body:not(.dark) .dashboard-table tbody tr:nth-child(even):hover {
        background-color: #e9ecef !important;
    }
    
    /* Outline buttons - Light Mode */
    body:not(.dark) .btn-action-outline {
        border: 2px solid #007bff;
        color: #007bff;
    }
    
    body:not(.dark) .btn-action-outline:hover {
        background: #007bff;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    
    body:not(.dark) .btn-action-outline-sm {
        border: 2px solid #007bff;
        color: #007bff;
    }
    
    body:not(.dark) .btn-action-outline-sm:hover {
        background: #007bff;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Disable tooltips specifically for Reports button
    const reportsBtn = document.querySelector('a[href="reports.php"]');
    if (reportsBtn) {
        // Remove any existing tooltip instance
        if (reportsBtn._tooltip) {
            reportsBtn._tooltip.dispose();
        }
        // Prevent tooltip initialization
        reportsBtn.removeAttribute('data-bs-toggle');
        reportsBtn.removeAttribute('title');
        reportsBtn.removeAttribute('data-bs-original-title');
    }
});
</script>

    <div class="row">
        <!-- Pending Users -->
        <div class="col-lg-12 mb-4">
            <div class="card dashboard-card">
                <div class="card-header dashboard-card-header d-flex justify-content-between align-items-center">
                    <h5 class="dashboard-section-title"><i class="fas fa-user-clock me-2"></i>Pending Tour Guide Applications</h5>
                    <span class="badge dashboard-badge bg-warning"><?php echo count($pending_users); ?></span>
                </div>
                <div class="card-body dashboard-card-body">
                    <?php if (empty($pending_users)): ?>
                        <p class="dashboard-text-muted">No pending tour guide applications.</p>
                    <?php else: ?>
                        <div class="table-responsive dashboard-table-responsive">
                            <table class="table table-sm dashboard-table">
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
                                            <form method="POST" action="users_mvc.php" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $user_item['id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="redirect" value="dashboard_mvc.php">
                                                <button type="submit" class="btn-action btn-action-success-sm" onclick="return confirm('Are you sure you want to approve this user?');">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="users_mvc.php" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $user_item['id']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <input type="hidden" name="redirect" value="dashboard_mvc.php">
                                                <button type="submit" class="btn-action btn-action-danger-sm" onclick="return confirm('Are you sure you want to reject this user application?');">
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
                                <a href="users_mvc.php?status=pending&role=guide" class="btn-action btn-action-outline-sm">View All</a>
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
            <div class="card dashboard-card">
                <div class="card-header dashboard-card-header">
                    <h5 class="dashboard-section-title"><i class="fas fa-calendar-alt me-2"></i>Recent Bookings</h5>
                </div>
                <div class="card-body dashboard-card-body">
                    <div class="table-responsive dashboard-table-responsive">
                        <table class="table dashboard-table">
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
                                        <span class="badge dashboard-badge bg-<?php 
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
                        <a href="bookings.php" class="btn-action btn-action-outline">View All Bookings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
