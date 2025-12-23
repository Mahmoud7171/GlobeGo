<?php
// Cancel booking confirmation view (for cancel-booking.php)
// Expects: $booking_details
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Cancel Booking</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle"></i> Are you sure you want to cancel this booking?</h5>
                        <p>This action cannot be undone. If you cancel this booking:</p>
                        <ul>
                            <li>Your spot will be released for other travelers</li>
                            <li>You may be subject to cancellation fees (check the tour's cancellation policy)</li>
                            <li>Any payments made will be processed according to the refund policy</li>
                        </ul>
                    </div>

                    <!-- Booking Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Booking Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Booking Reference:</strong> <?php echo $booking_details['booking_reference']; ?></p>
                                    <p><strong>Tour:</strong> <?php echo $booking_details['tour_title']; ?></p>
                                    <p><strong>Date:</strong> <?php echo formatDate($booking_details['tour_date']); ?></p>
                                    <p><strong>Time:</strong> <?php echo date('H:i', strtotime($booking_details['tour_time'])); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Participants:</strong> <?php echo $booking_details['num_participants']; ?></p>
                                    <p><strong>Total Price:</strong> <span class="text-price"><?php echo formatPrice($booking_details['total_price']); ?></span></p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-<?php 
                                            echo match($booking_details['status']) {
                                                'confirmed' => 'success',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger',
                                                'completed' => 'info',
                                                default => 'secondary'
                                            };
                                        ?>">
                                            <?php echo ucfirst($booking_details['status']); ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <?php if ($booking_details['booking_notes']): ?>
                            <div class="mt-3">
                                <p><strong>Special Requirements:</strong></p>
                                <p class="text-muted"><?php echo $booking_details['booking_notes']; ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Cancellation Form -->
                    <form method="POST" action="cancel-booking.php?id=<?php echo $booking_details['id']; ?>">
                        <input type="hidden" name="booking_id" value="<?php echo $booking_details['id']; ?>">
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Reason for Cancellation (Optional)</label>
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" 
                                      placeholder="Please let us know why you're cancelling this booking..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="confirm_cancel" value="1" class="btn btn-danger">
                                <i class="fas fa-times"></i> Yes, Cancel Booking
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Keep Booking
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation dialog to the cancel button
    const cancelForm = document.querySelector('form');
    const cancelButton = document.querySelector('button[name="confirm_cancel"]');
    
    if (cancelButton && cancelForm) {
        cancelButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you absolutely sure you want to cancel this booking? This action cannot be undone.')) {
                // Submit the form
                cancelForm.submit();
            }
        });
    }
});
</script>


