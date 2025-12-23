<?php
// Dashboard view.
// Expects:
// - $user (User instance with current user data)
// - $error_message, $success_message
// - $user_bookings, $upcoming_bookings, $user_tours
// - $all_users, $all_bookings, $booking_stats
?>

<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($success_message)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $success_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><?php echo Language::t('dashboard.welcome_back', ['name' => $user->first_name]); ?></h1>
        </div>
    </div>

    <?php if (isTourist()): ?>
        <!-- Tourist Dashboard -->
        <div class="row">
            <div class="col-md-8">
                <div class="card bg-transparent border-0">
                    <div class="card-header border-0 bg-transparent">
                        <h5><?php echo Language::t('dashboard.your_booked_tours'); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($upcoming_bookings)): ?>
                            <p class="text-muted"><?php echo Language::t('dashboard.no_booked_tours'); ?> <a href="tours.php"><?php echo Language::t('dashboard.explore_tours'); ?></a> <?php echo Language::t('dashboard.start_adventure'); ?></p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo Language::t('dashboard.tour'); ?></th>
                                            <th><?php echo Language::t('dashboard.date_time'); ?></th>
                                            <th><?php echo Language::t('dashboard.participants'); ?></th>
                                            <th><?php echo Language::t('dashboard.status'); ?></th>
                                            <th><?php echo Language::t('dashboard.actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($upcoming_bookings as $booking_item): 
                                            $booking_datetime = strtotime($booking_item['tour_date'] . ' ' . $booking_item['tour_time']);
                                            $is_past = $booking_datetime < time();
                                        ?>
                                        <tr class="<?php echo $is_past ? 'opacity-75' : ''; ?>">
                                            <td>
                                                <strong><?php echo htmlspecialchars($booking_item['tour_title']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($booking_item['attraction_name'] ?? 'N/A'); ?></small>
                                            </td>
                                            <td>
                                                <?php echo formatDate($booking_item['tour_date']); ?><br>
                                                <small><?php echo date('H:i', strtotime($booking_item['tour_time'])); ?></small>
                                                <?php if ($is_past): ?>
                                                    <br><small class="text-muted">(<?php echo Language::t('dashboard.past'); ?>)</small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $booking_item['num_participants']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo match($booking_item['status']) {
                                                        'confirmed' => $is_past ? 'secondary' : 'success',
                                                        'pending' => 'warning',
                                                        'cancelled' => 'danger',
                                                        default => 'info'
                                                    };
                                                ?>">
                                                    <?php echo Language::t('dashboard.' . $booking_item['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="booking-details.php?id=<?php echo $booking_item['id']; ?>" class="btn btn-sm btn-outline-primary"><?php echo Language::t('dashboard.view'); ?></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if (isset($total_pages) && $total_pages > 1): ?>
                            <div class="pagination-wrapper mt-4">
                                <div class="d-flex flex-column align-items-center gap-3">
                                    <!-- Page Info - Centered -->
                                    <div class="pagination-info text-center">
                                        <small class="text-muted">
                                            <?php 
                                                $display_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                                $display_page = max(1, min($display_page, $total_pages));
                                                $per_page = (int)$items_per_page;
                                                $total = (int)$total_items;
                                                
                                                if ($total > 0 && $display_page >= 1) {
                                                    $start = (($display_page - 1) * $per_page) + 1;
                                                    $end = min($display_page * $per_page, $total);
                                                } else {
                                                    $start = 0;
                                                    $end = 0;
                                                }
                                            ?>
                                            <?php echo Language::t('dashboard.showing'); ?> <strong><?php echo $start; ?></strong> 
                                            <?php echo Language::t('dashboard.to'); ?> <strong><?php echo $end; ?></strong> 
                                            <?php echo Language::t('dashboard.of'); ?> <strong><?php echo $total; ?></strong> <?php echo Language::t('dashboard.bookings'); ?>
                                        </small>
                                    </div>
                                    
                                    <!-- Pagination Buttons - Centered -->
                                    <nav>
                                        <ul class="pagination-custom mb-0">
                                            <!-- Previous Button -->
                                            <?php 
                                                $page_from_get = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                                $current_page_for_prev = max(1, $page_from_get);
                                            ?>
                                            <?php if ($current_page_for_prev > 1): 
                                                $prev_page = max(1, $current_page_for_prev - 1);
                                                $prev_url = $prev_page > 1 ? '?page=' . $prev_page : '';
                                            ?>
                                            <li class="pagination-item">
                                                <a class="pagination-link pagination-nav" href="dashboard.php<?php echo htmlspecialchars($prev_url); ?>">
                                                    <i class="fas fa-chevron-left me-1"></i> <?php echo Language::t('dashboard.previous'); ?>
                                                </a>
                                            </li>
                                            <?php else: ?>
                                            <li class="pagination-item disabled">
                                                <span class="pagination-link pagination-nav disabled">
                                                    <i class="fas fa-chevron-left me-1"></i> <?php echo Language::t('dashboard.previous'); ?>
                                                </span>
                                            </li>
                                            <?php endif; ?>
                                            
                                            <!-- Page Numbers -->
                                            <?php
                                                $current_page_int = (int)$current_page;
                                                $total_pages_int = (int)$total_pages;
                                                $start_page = max(1, $current_page_int - 2);
                                                $end_page = min($total_pages_int, $current_page_int + 2);
                                                
                                                // Show first page if not in range
                                                if ($start_page > 1): 
                                            ?>
                                                <li class="pagination-item">
                                                    <a class="pagination-link" href="dashboard.php">1</a>
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
                                                $page_url = $i > 1 ? '?page=' . $i : '';
                                            ?>
                                                <li class="pagination-item <?php echo $i === $current_page_int ? 'active' : ''; ?>">
                                                    <a class="pagination-link <?php echo $i === $current_page_int ? 'active' : ''; ?>" href="dashboard.php<?php echo htmlspecialchars($page_url); ?>">
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
                                                $last_url = '?page=' . $total_pages_int;
                                            ?>
                                                <li class="pagination-item">
                                                    <a class="pagination-link" href="dashboard.php<?php echo htmlspecialchars($last_url); ?>">
                                                        <?php echo $total_pages_int; ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <!-- Next Button -->
                                            <?php 
                                                $page_from_get_next = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                                $current_page_for_next = max(1, $page_from_get_next);
                                                $is_last_page = $current_page_for_next >= $total_pages_int;
                                            ?>
                                            <?php if (!$is_last_page): 
                                                $next_page = $current_page_for_next + 1;
                                                $next_url = '?page=' . $next_page;
                                            ?>
                                            <li class="pagination-item">
                                                <a class="pagination-link pagination-nav" href="dashboard.php<?php echo htmlspecialchars($next_url); ?>">
                                                    <?php echo Language::t('dashboard.next'); ?> <i class="fas fa-chevron-right ms-1"></i>
                                                </a>
                                            </li>
                                            <?php else: ?>
                                            <li class="pagination-item disabled">
                                                <span class="pagination-link pagination-nav disabled">
                                                    <?php echo Language::t('dashboard.next'); ?> <i class="fas fa-chevron-right ms-1"></i>
                                                </span>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo Language::t('dashboard.quick_actions'); ?></h5>
                    </div>
                    <div class="card-body">
                        <a href="tours.php" class="btn btn-primary btn-block mb-2"><?php echo Language::t('dashboard.browse_tours'); ?></a>
                        <a href="tourist/itinerary.php" class="btn btn-outline-primary btn-block mb-2"><?php echo Language::t('dashboard.view_itinerary'); ?></a>
                        <a href="fines.php" class="btn btn-outline-warning btn-block mb-2">
                            <i class="fas fa-exclamation-triangle me-1"></i><?php echo Language::t('dashboard.fines'); ?>
                        </a>
                        <a href="profile.php" class="btn btn-outline-secondary btn-block"><?php echo Language::t('dashboard.update_profile'); ?></a>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif (isGuide()): ?>
        <!-- Guide Dashboard -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><?php echo Language::t('dashboard.recent_bookings'); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($user_bookings)): ?>
                            <p class="text-muted"><?php echo Language::t('dashboard.no_bookings'); ?> <a href="guide/create-tour.php"><?php echo Language::t('dashboard.create_first_tour'); ?></a> <?php echo Language::t('dashboard.get_started'); ?></p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo Language::t('dashboard.tour'); ?></th>
                                            <th><?php echo Language::t('dashboard.tourist'); ?></th>
                                            <th><?php echo Language::t('dashboard.date_time'); ?></th>
                                            <th><?php echo Language::t('dashboard.participants'); ?></th>
                                            <th><?php echo Language::t('dashboard.status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($user_bookings, 0, 5) as $booking_item): ?>
                                        <tr>
                                            <td><?php echo $booking_item['tour_title']; ?></td>
                                            <td><?php echo $booking_item['tourist_first_name'] . ' ' . $booking_item['tourist_last_name']; ?></td>
                                            <td>
                                                <?php echo formatDate($booking_item['tour_date']); ?><br>
                                                <small><?php echo date('H:i', strtotime($booking_item['tour_time'])); ?></small>
                                            </td>
                                            <td><?php echo $booking_item['num_participants']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $booking_item['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                                    <?php echo Language::t('dashboard.' . $booking_item['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="text-primary"><?php echo $booking_stats['total_bookings'] ?? 0; ?></h3>
                                <p class="mb-0"><?php echo Language::t('dashboard.total_bookings'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="text-success"><?php echo formatPrice($booking_stats['total_revenue'] ?? 0); ?></h3>
                                <p class="mb-0"><?php echo Language::t('dashboard.total_revenue'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><?php echo Language::t('dashboard.quick_actions'); ?></h5>
                            </div>
                            <div class="card-body">
                                <a href="guide/create-tour.php" class="btn btn-primary btn-block mb-2"><?php echo Language::t('dashboard.create_new_tour'); ?></a>
                                <a href="guide/my-tours.php" class="btn btn-outline-primary btn-block mb-2"><?php echo Language::t('dashboard.manage_tours'); ?></a>
                                <a href="guide/bookings.php" class="btn btn-outline-secondary btn-block"><?php echo Language::t('dashboard.view_all_bookings'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif (isAdmin()): ?>
        <!-- Admin Dashboard -->
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?php echo count($all_users); ?></h3>
                        <p class="mb-0"><?php echo Language::t('dashboard.total_users'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success"><?php echo $booking_stats['total_bookings'] ?? 0; ?></h3>
                        <p class="mb-0"><?php echo Language::t('dashboard.total_bookings'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info"><?php echo formatPrice($booking_stats['total_revenue'] ?? 0); ?></h3>
                        <p class="mb-0"><?php echo Language::t('dashboard.total_revenue'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning"><?php echo count(array_filter($all_users, function($u) { return $u['status'] === 'pending'; })); ?></h3>
                        <p class="mb-0"><?php echo Language::t('dashboard.pending_users'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo Language::t('dashboard.recent_users'); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th><?php echo Language::t('dashboard.name'); ?></th>
                                        <th><?php echo Language::t('dashboard.email'); ?></th>
                                        <th><?php echo Language::t('dashboard.role'); ?></th>
                                        <th><?php echo Language::t('dashboard.status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($all_users, 0, 5) as $user_item): ?>
                                    <tr>
                                        <td><?php echo $user_item['first_name'] . ' ' . $user_item['last_name']; ?></td>
                                        <td><?php echo $user_item['email']; ?></td>
                                        <td><?php echo ucfirst($user_item['role']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $user_item['status'] === 'active' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($user_item['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* Pagination Styles - Same as Admin Bookings */
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

.pagination-item.disabled .pagination-link.disabled,
.pagination-item.disabled span.pagination-link.disabled:not(.pagination-ellipsis) {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none !important;
}

/* Ensure ellipsis is always clickable */
.pagination-item:has(.pagination-ellipsis),
.pagination-item.disabled:has(.pagination-ellipsis) {
    pointer-events: auto !important;
}

.pagination-item .pagination-ellipsis {
    pointer-events: auto !important;
    cursor: pointer !important;
}

.pagination-item:not(.disabled) a.pagination-link {
    pointer-events: auto !important;
    cursor: pointer !important;
    text-decoration: none !important;
    display: inline-block !important;
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
</style>

<style>
    /* Smooth Corners for Dashboard - Statistics Cards */
    .card {
        border-radius: 20px !important;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .card-body {
        border-radius: 20px !important;
        padding: 1.5rem;
    }
    
    .card-header {
        border-radius: 20px 20px 0 0 !important;
        padding: 1.25rem 1.5rem;
    }
    
    /* Statistics Cards - Smooth Corners */
    .card.text-center {
        border-radius: 20px !important;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .card.text-center:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }
    
    .card.text-center .card-body {
        border-radius: 20px !important;
    }
    
    /* Table Container - Smooth Corners */
    .table-responsive {
        border-radius: 15px !important;
        overflow: hidden;
    }
    
    .table {
        border-radius: 15px !important;
        overflow: hidden;
        margin-bottom: 0;
    }
    
    .table thead th {
        border-top-left-radius: 15px !important;
        border-top-right-radius: 15px !important;
    }
    
    .table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 15px !important;
    }
    
    .table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 15px !important;
    }
    
    /* Badges - Smooth Rounded Corners */
    .badge {
        border-radius: 20px !important;
        padding: 0.5rem 0.75rem;
        font-weight: 600;
    }
    
    /* Alerts - Smooth Corners */
    .alert {
        border-radius: 20px !important;
        overflow: hidden;
        border: none;
    }
    
    /* Ensure smooth rendering */
    .card,
    .table-responsive,
    .table {
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        transform: translateZ(0);
    }
</style>

