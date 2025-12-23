<?php
// Guide bookings view.
// Expects: $booking_stats, $user_bookings, $filtered_bookings, $status_filter
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">My Bookings</h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary"><?php echo $booking_stats['total_bookings'] ?? 0; ?></h3>
                    <p class="mb-0">Total Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success"><?php echo formatPrice($booking_stats['total_revenue'] ?? 0); ?></h3>
                    <p class="mb-0">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info"><?php echo number_format($booking_stats['avg_participants'] ?? 0, 1); ?></h3>
                    <p class="mb-0">Avg Participants</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning"><?php echo count(array_filter($user_bookings, function($b) { return strtotime($b['tour_date']) >= time() && $b['status'] === 'confirmed'; })); ?></h3>
                    <p class="mb-0">Upcoming Tours</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex gap-3 align-items-center">
                        <span><strong>Filter by Status:</strong></span>
                        <a href="?status=all" class="btn btn-sm <?php echo $status_filter === 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">All</a>
                        <a href="?status=pending" class="btn btn-sm <?php echo $status_filter === 'pending' ? 'btn-primary' : 'btn-outline-primary'; ?>">Pending</a>
                        <a href="?status=confirmed" class="btn btn-sm <?php echo $status_filter === 'confirmed' ? 'btn-primary' : 'btn-outline-primary'; ?>">Confirmed</a>
                        <a href="?status=cancelled" class="btn btn-sm <?php echo $status_filter === 'cancelled' ? 'btn-primary' : 'btn-outline-primary'; ?>">Cancelled</a>
                        <a href="?status=completed" class="btn btn-sm <?php echo $status_filter === 'completed' ? 'btn-primary' : 'btn-outline-primary'; ?>">Completed</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-header">
            <h5>Bookings (<?php echo count($filtered_bookings); ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($filtered_bookings)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h4>No bookings found</h4>
                    <p class="text-muted">You don't have any bookings with the selected filter.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking Ref</th>
                                <th>Tour</th>
                                <th>Tourist</th>
                                <th>Date & Time</th>
                                <th>Participants</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filtered_bookings as $booking_item): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $booking_item['booking_reference']; ?></strong><br>
                                    <small class="text-muted"><?php echo formatDateTime($booking_item['created_at']); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo $booking_item['tour_title']; ?></strong><br>
                                    <small class="text-muted"><?php echo $booking_item['attraction_name']; ?></small>
                                </td>
                                <td>
                                    <?php echo $booking_item['tourist_first_name'] . ' ' . $booking_item['tourist_last_name']; ?><br>
                                    <small class="text-muted"><?php echo $booking_item['tourist_email']; ?></small>
                                </td>
                                <td>
                                    <?php echo formatDate($booking_item['tour_date']); ?><br>
                                    <small><?php echo date('H:i', strtotime($booking_item['tour_time'])); ?></small>
                                </td>
                                <td><?php echo $booking_item['num_participants']; ?></td>
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
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#bookingModal<?php echo $booking_item['id']; ?>">
                                            View
                                        </button>
                                        
                                        <?php if ($booking_item['status'] === 'pending'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking_item['id']; ?>">
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                            </form>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking_item['id']; ?>">
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Booking Details Modal -->
                            <div class="modal fade" id="bookingModal<?php echo $booking_item['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Booking Details - <?php echo $booking_item['booking_reference']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Tour Information</h6>
                                                    <p><strong>Tour:</strong> <?php echo $booking_item['tour_title']; ?></p>
                                                    <p><strong>Date:</strong> <?php echo formatDate($booking_item['tour_date']); ?></p>
                                                    <p><strong>Time:</strong> <?php echo date('H:i', strtotime($booking_item['tour_time'])); ?></p>
                                                    <p><strong>Meeting Point:</strong> <?php echo $booking_item['meeting_point']; ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Tourist Information</h6>
                                                    <p><strong>Name:</strong> <?php echo $booking_item['tourist_first_name'] . ' ' . $booking_item['tourist_last_name']; ?></p>
                                                    <p><strong>Email:</strong> <?php echo $booking_item['tourist_email']; ?></p>
                                                    <p><strong>Participants:</strong> <?php echo $booking_item['num_participants']; ?></p>
                                                    <p><strong>Total Price:</strong> <span class="text-price"><?php echo formatPrice($booking_item['total_price']); ?></span></p>
                                                </div>
                                            </div>
                                            
                                            <?php if ($booking_item['booking_notes']): ?>
                                                <div class="mt-3">
                                                    <h6>Special Requirements</h6>
                                                    <p><?php echo nl2br($booking_item['booking_notes']); ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


