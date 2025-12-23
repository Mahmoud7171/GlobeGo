<?php
// Booking details view (for booking-details.php)
// Expects: $booking_details
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 bg-transparent text-white">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Booking Details</h4>
                        <?php
                            $status_color = match($booking_details['status']) {
                                'confirmed' => 'success',
                                'pending' => 'warning',
                                'cancelled' => 'danger',
                                'completed' => 'info',
                                default => 'secondary'
                            };
                            $status_label = match($booking_details['status']) {
                                'confirmed' => 'Accepted',
                                'cancelled' => 'Rejected',
                                default => ucfirst($booking_details['status'])
                            };
                        ?>
                        <span class="badge bg-<?php echo $status_color; ?> fs-6">
                            <?php echo $status_label; ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Booking Information -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Booking Information</h5>
                            <table class="table table-borderless text-white">
                                <tr>
                                    <td><strong>Booking Reference:</strong></td>
                                    <td><?php echo $booking_details['booking_reference']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Booking Date:</strong></td>
                                    <td><?php echo formatDate($booking_details['created_at']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo $status_color; ?>">
                                            <?php echo $status_label; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo match($booking_details['payment_status']) {
                                                'paid' => 'success',
                                                'pending' => 'warning',
                                                'refunded' => 'info',
                                                default => 'secondary'
                                            };
                                        ?>">
                                            <?php echo ucfirst($booking_details['payment_status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Tour Information -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Tour Information</h5>
                            <table class="table table-borderless text-white">
                                <tr>
                                    <td><strong>Tour:</strong></td>
                                    <td><?php echo $booking_details['tour_title']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td><?php echo formatDate($booking_details['tour_date']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Time:</strong></td>
                                    <td><?php echo date('H:i', strtotime($booking_details['tour_time'])); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Duration:</strong></td>
                                    <td><?php echo $booking_details['duration_hours']; ?> hours</td>
                                </tr>
                                <tr>
                                    <td><strong>Meeting Point:</strong></td>
                                    <td><?php echo $booking_details['meeting_point']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <!-- Tourist Information -->
                        <div class="col-md-4">
                            <h5 class="text-primary mb-3">Tourist Information</h5>
                            <div class="text-center">
                                <p class="mb-1 fw-bold"><?php echo $booking_details['tourist_first_name'] . ' ' . $booking_details['tourist_last_name']; ?></p>
                                <p class="mb-0"><?php echo $booking_details['tourist_email']; ?></p>
                            </div>
                        </div>

                        <!-- Guide Information -->
                        <div class="col-md-4">
                            <h5 class="text-primary mb-3">Guide Information</h5>
                            <div class="text-center">
                                <img src="<?php echo $booking_details['guide_profile_image'] ?: SITE_URL . '/images/TourGuide1.png'; ?>" 
                                     class="rounded-circle mb-2" width="80" height="80" alt="Guide Photo">
                                <p class="mb-1"><strong><?php echo $booking_details['guide_first_name'] . ' ' . $booking_details['guide_last_name']; ?></strong></p>
                                <p class="text-muted mb-0"><?php echo $booking_details['guide_email']; ?></p>
                                <?php if (isset($booking_details['guide_verified']) && $booking_details['guide_verified']): ?>
                                    <p class="text-success mb-0"><i class="fas fa-check-circle"></i> Verified Guide</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Booking Summary -->
                        <div class="col-md-4">
                            <h5 class="text-primary mb-3">Booking Summary</h5>
                            <table class="table table-borderless text-white">
                                <tr>
                                    <td><strong>Participants:</strong></td>
                                    <td><?php echo $booking_details['num_participants']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Price per Person:</strong></td>
                                    <td class="text-price"><?php echo formatPrice($booking_details['price']); ?></td>
                                </tr>
                                <tr class="border-top">
                                    <td><strong>Total Price:</strong></td>
                                    <td class="text-price fw-bold"><?php echo formatPrice($booking_details['total_price']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if ($booking_details['booking_notes']): ?>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">Special Requirements</h5>
                            <div class="alert alert-info">
                                <?php echo nl2br(htmlspecialchars($booking_details['booking_notes'])); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <hr>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="dashboard.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                        
                        <?php if (isTourist() && $booking_details['tourist_id'] == $_SESSION['user_id']): ?>
                            <?php if (in_array($booking_details['status'], ['pending', 'confirmed'])): ?>
                                <a href="cancel-booking.php?id=<?php echo $booking_details['id']; ?>" 
                                   class="btn btn-outline-danger"
                                   onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    <i class="fas fa-times"></i> Cancel Booking
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (isGuide() && $booking_details['guide_id'] == $_SESSION['user_id']): ?>
                            <?php if ($booking_details['status'] === 'pending'): ?>
                                <form method="POST" action="guide/bookings.php" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking_details['id']; ?>">
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Confirm Booking
                                    </button>
                                </form>
                                <form method="POST" action="guide/bookings.php" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking_details['id']; ?>">
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to cancel this booking?')">
                                        <i class="fas fa-times"></i> Cancel Booking
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


