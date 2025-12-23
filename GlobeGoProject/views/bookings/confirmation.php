<?php
// Booking confirmation view (for booking-confirmation.php)
// Expects: $booking_details
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="alert alert-success text-center mb-4">
                <i class="fas fa-check-circle fa-3x mb-3"></i>
                <h2>Booking Confirmed!</h2>
                <p class="lead">Your tour has been successfully booked.</p>
                <p class="mb-0">Booking Reference: <strong><?php echo $booking_details['booking_reference']; ?></strong></p>
            </div>

            <!-- Booking Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4><i class="fas fa-calendar-alt"></i> Booking Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Tour Information</h5>
                            <p><strong>Tour:</strong> <?php echo $booking_details['tour_title']; ?></p>
                            <p><strong>Description:</strong> <?php echo substr($booking_details['tour_description'], 0, 150) . '...'; ?></p>
                            <p><strong>Meeting Point:</strong> <?php echo $booking_details['meeting_point']; ?></p>
                            <?php if ($booking_details['attraction_name']): ?>
                                <p><strong>Attraction:</strong> <?php echo $booking_details['attraction_name']; ?></p>
                                <p><strong>Location:</strong> <?php echo $booking_details['location']; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h5>Booking Information</h5>
                            <p><strong>Date:</strong> <?php echo formatDate($booking_details['tour_date']); ?></p>
                            <p><strong>Time:</strong> <?php echo date('H:i', strtotime($booking_details['tour_time'])); ?></p>
                            <p><strong>Participants:</strong> <?php echo $booking_details['num_participants']; ?></p>
                            <p><strong>Total Price:</strong> <span class="text-price"><?php echo formatPrice($booking_details['total_price']); ?></span></p>
                            <?php
                                $status_label = match($booking_details['status']) {
                                    'confirmed' => 'Accepted',
                                    'cancelled' => 'Rejected',
                                    default => ucfirst($booking_details['status'])
                                };
                            ?>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-warning"><?php echo $status_label; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guide Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4><i class="fas fa-user-tie"></i> Your Guide</h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="<?php echo $booking_details['guide_profile_image'] ?: SITE_URL . '/images/TourGuide1.png'; ?>" 
                                 class="img-fluid rounded-circle" alt="Guide Photo">
                        </div>
                        <div class="col-md-10">
                            <h5><?php echo $booking_details['guide_first_name'] . ' ' . $booking_details['guide_last_name']; ?></h5>
                            <p class="mb-1"><strong>Contact:</strong> <?php echo $booking_details['guide_phone']; ?></p>
                            <p class="mb-0"><strong>Verified Guide:</strong> <i class="fas fa-check-circle text-success"></i></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4><i class="fas fa-info-circle"></i> Important Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Before the Tour:</h6>
                            <ul>
                                <li>Arrive 15 minutes before the scheduled start time</li>
                                <li>Bring comfortable walking shoes</li>
                                <li>Check the weather and dress appropriately</li>
                                <li>Bring a camera to capture memories</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Cancellation Policy:</h6>
                            <ul>
                                <li>Free cancellation up to 24 hours before the tour</li>
                                <li>50% refund for cancellations within 24 hours</li>
                                <li>No refund for no-shows</li>
                                <li>Weather-related cancellations are fully refundable</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mb-4">
                <a href="tourist/itinerary.php" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-calendar-check"></i> View My Itinerary
                </a>
                <a href="tours.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-search"></i> Browse More Tours
                </a>
            </div>

            <!-- Email Confirmation Notice -->
            <div class="alert alert-info text-center">
                <i class="fas fa-envelope"></i>
                <strong>Email Confirmation:</strong> A confirmation email has been sent to <?php echo $_SESSION['user_email']; ?>
                with all the details of your booking.
            </div>
        </div>
    </div>
</div>


