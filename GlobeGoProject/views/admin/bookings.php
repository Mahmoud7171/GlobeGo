<?php
// Admin bookings view extracted from admin/bookings.php
// Expects: $filtered_bookings, $status_filter
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
                                    <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Statuses</option>
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
                        <?php if (empty($filtered_bookings)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted mb-0">No bookings found.</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($filtered_bookings as $booking_item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking_item['booking_reference']); ?></td>
                            <td><?php echo htmlspecialchars($booking_item['tour_title']); ?></td>
                            <td><?php echo htmlspecialchars($booking_item['tourist_first_name'] . ' ' . $booking_item['tourist_last_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking_item['guide_first_name'] . ' ' . $booking_item['guide_last_name']); ?></td>
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
                                    <form method="POST" action="" class="d-inline">
                                        <input type="hidden" name="booking_id" value="<?php echo (int) $booking_item['id']; ?>">
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
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
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
                <!-- Page Info - Bottom Left -->
                <div class="pagination-info">
                    <small class="text-muted">
                        <?php 
                        if ($total_items > 0) {
                            $start_num = ($current_page - 1) * $items_per_page + 1;
                            $end_num = min($current_page * $items_per_page, $total_items);
                            echo "Showing <strong>{$start_num}</strong> to <strong>{$end_num}</strong> of <strong>{$total_items}</strong> bookings";
                        } else {
                            echo "Showing <strong>0</strong> to <strong>0</strong> of <strong>0</strong> bookings";
                        }
                        ?>
                    </small>
                </div>
                
                <?php if ($total_pages > 1): ?>
                <!-- Pagination Buttons - Centered -->
                <div class="pagination-wrapper" data-total-pages="<?php echo $total_pages; ?>" data-current-page="<?php echo $current_page; ?>">
                <nav aria-label="Bookings pagination">
                    <ul class="pagination justify-content-center mb-0">
                        <?php
                        // Build query string for pagination links
                        function buildPaginationUrl($page, $status_filter) {
                            $params = [];
                            if (!empty($status_filter) && $status_filter !== 'all') {
                                $params['status'] = $status_filter;
                            }
                            if ($page > 1) {
                                $params['page'] = $page;
                            }
                            // Use bookings_mvc.php as the base file
                            $base_url = 'bookings_mvc.php';
                            $query_string = !empty($params) ? '?' . http_build_query($params) : '';
                            return $base_url . $query_string;
                        }
                        ?>
                        
                        <!-- Previous Button -->
                        <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                            <?php if ($current_page > 1): ?>
                                <a class="page-link" href="<?php echo htmlspecialchars(buildPaginationUrl($current_page - 1, $status_filter)); ?>">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            <?php else: ?>
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </span>
                            <?php endif; ?>
                        </li>
                        
                        <!-- Page Numbers -->
                        <?php
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        if ($start_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo htmlspecialchars(buildPaginationUrl(1, $status_filter)); ?>">1</a>
                            </li>
                            <?php if ($start_page > 2): ?>
                                <li class="page-item">
                                    <span class="page-link pagination-ellipsis" data-expand-start="2" data-expand-end="<?php echo $start_page - 1; ?>" title="Click to show pages 2-<?php echo $start_page - 1; ?>">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <li class="page-item <?php echo $i === $current_page ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo htmlspecialchars(buildPaginationUrl($i, $status_filter)); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($end_page < $total_pages): ?>
                            <?php if ($end_page < $total_pages - 1): ?>
                                <li class="page-item">
                                    <span class="page-link pagination-ellipsis" data-expand-start="<?php echo $end_page + 1; ?>" data-expand-end="<?php echo $total_pages - 1; ?>" title="Click to show pages <?php echo $end_page + 1; ?>-<?php echo $total_pages - 1; ?>">...</span>
                                </li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo htmlspecialchars(buildPaginationUrl($total_pages, $status_filter)); ?>">
                                    <?php echo $total_pages; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <!-- Next Button -->
                        <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                            <?php if ($current_page < $total_pages): ?>
                                <a class="page-link" href="<?php echo htmlspecialchars(buildPaginationUrl($current_page + 1, $status_filter)); ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="page-link">
                                    Next <i class="fas fa-chevron-right"></i>
                                </span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
