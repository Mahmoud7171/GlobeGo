<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Booking.php';

// Initialize database connection
$database = new Database();

// Check if user is logged in and is a tourist
if (!isLoggedIn() || !isTourist()) {
    redirect(SITE_URL . '/auth/login.php');
}

$page_title = "My Itinerary";
$booking = new Booking($database->getConnection());

$user_bookings = $booking->getBookingsByTourist($_SESSION['user_id']);

// Separate bookings by status
$upcoming_bookings = array_filter($user_bookings, function($b) {
    return strtotime($b['tour_date']) >= time() && in_array($b['status'], ['pending', 'confirmed']);
});

$past_bookings = array_filter($user_bookings, function($b) {
    return strtotime($b['tour_date']) < time() || $b['status'] === 'completed';
});

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">My Itinerary</h1>
        </div>
    </div>

    <!-- Upcoming Tours -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-3">Upcoming Tours</h3>
            <?php if (empty($upcoming_bookings)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
                        <h4>No Upcoming Tours</h4>
                        <p class="text-muted">You don't have any upcoming tours. Start exploring amazing destinations!</p>
                        <a href="../tours.php" class="btn btn-primary">Browse Tours</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($upcoming_bookings as $booking_item): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title"><?php echo $booking_item['tour_title']; ?></h5>
                                    <?php
                                        $upcoming_status_label = $booking_item['status'] === 'confirmed'
                                            ? 'Accepted'
                                            : ucfirst($booking_item['status']);
                                    ?>
                                    <span class="badge bg-<?php echo $booking_item['status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                        <?php echo $upcoming_status_label; ?>
                                    </span>
                                </div>
                                
                                <p class="card-text">
                                    <i class="fas fa-map-marker-alt text-muted"></i> 
                                    <?php echo $booking_item['meeting_point']; ?>
                                </p>
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Date & Time</small>
                                        <strong><?php echo formatDate($booking_item['tour_date']); ?></strong><br>
                                        <small><?php echo date('H:i', strtotime($booking_item['tour_time'])); ?></small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Participants</small>
                                        <strong><?php echo $booking_item['num_participants']; ?> person(s)</strong><br>
                                        <small>Total: <?php echo formatPrice($booking_item['total_price']); ?></small>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="../booking-details.php?id=<?php echo $booking_item['id']; ?>" 
                                       class="btn btn-outline-primary btn-sm">View Details</a>
                                    <?php if ($booking_item['status'] === 'pending'): ?>
                                        <a href="../simple-cancel.php?id=<?php echo $booking_item['id']; ?>" 
                                           class="btn btn-outline-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Past Tours -->
    <div class="row">
        <div class="col-12">
            <h3 class="mb-3">Past Tours</h3>
            <?php if (empty($past_bookings)): ?>
                <div class="card">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-history fa-2x text-muted mb-3"></i>
                        <h5>No Past Tours</h5>
                        <p class="text-muted mb-0">Your completed tours will appear here.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tour</th>
                                <th>Date</th>
                                <th>Participants</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($past_bookings as $booking_item): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $booking_item['tour_title']; ?></strong><br>
                                    <small class="text-muted"><?php echo $booking_item['attraction_name']; ?></small>
                                </td>
                                <td>
                                    <?php echo formatDate($booking_item['tour_date']); ?><br>
                                    <small><?php echo date('H:i', strtotime($booking_item['tour_time'])); ?></small>
                                </td>
                                <td><?php echo $booking_item['num_participants']; ?></td>
                                <td><?php echo formatPrice($booking_item['total_price']); ?></td>
                                <td>
                                    <?php
                                        $past_status_label = match($booking_item['status']) {
                                            'confirmed' => 'Accepted',
                                            'cancelled' => 'Rejected',
                                            default => ucfirst($booking_item['status'])
                                        };
                                    ?>
                                    <span class="badge bg-<?php echo $booking_item['status'] === 'completed' ? 'success' : 'danger'; ?>">
                                        <?php echo $past_status_label; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="../booking-details.php?id=<?php echo $booking_item['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary">View</a>
                                    <?php if ($booking_item['status'] === 'completed'): ?>
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="openReviewModal(<?php echo $booking_item['id']; ?>)">Review</button>
                                    <?php endif; ?>
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

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Write a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <input type="hidden" id="booking_id" name="booking_id">
                    
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select class="form-select" id="rating" name="rating" required>
                            <option value="">Select Rating</option>
                            <option value="5">5 Stars - Excellent</option>
                            <option value="4">4 Stars - Very Good</option>
                            <option value="3">3 Stars - Good</option>
                            <option value="2">2 Stars - Fair</option>
                            <option value="1">1 Star - Poor</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" 
                                  placeholder="Share your experience with other travelers..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitReview()">Submit Review</button>
            </div>
        </div>
    </div>
</div>

<script>
function openReviewModal(bookingId) {
    document.getElementById('booking_id').value = bookingId;
    const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
    modal.show();
}

function submitReview() {
    const form = document.getElementById('reviewForm');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    
    // Simulate review submission
    GlobeGo.showAlert('Review submitted successfully! Thank you for your feedback.', 'success');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
    modal.hide();
    
    // Reset form
    form.reset();
    form.classList.remove('was-validated');
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
