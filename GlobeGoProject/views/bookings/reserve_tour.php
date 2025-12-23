<?php
// View for reserving a tour with payment options
// Expects: $tour_id, $tour_details, $tour_schedules
$errors = $_SESSION['reservation_errors'] ?? [];
unset($_SESSION['reservation_errors']);
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Reserve: <?php echo htmlspecialchars($tour_details['title']); ?></h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h5>Please fix the following errors:</h5>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($tour_schedules)): ?>
                        <div class="alert alert-info">
                            <h5>No Available Dates</h5>
                            <p>This tour doesn't have any available dates at the moment.</p>
                            <a href="tours.php" class="btn btn-primary">Browse Other Tours</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="process-reservation.php" id="reservationForm">
                            <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>">
                            <?php if (isset($tour_details['has_discount']) && $tour_details['has_discount']): ?>
                                <input type="hidden" name="discounted_price" value="<?php echo $tour_details['discounted_price']; ?>">
                                <input type="hidden" name="original_price" value="<?php echo $tour_details['original_price']; ?>">
                                <input type="hidden" name="discount_percent" value="<?php echo $tour_details['discount_percent']; ?>">
                            <?php endif; ?>
                            
                            <!-- Hidden fields for multi-ticket discount offer -->
                            <input type="hidden" id="multi_ticket_discount_price" name="multi_ticket_discount_price" value="">
                            <input type="hidden" id="multi_ticket_original_price" name="multi_ticket_original_price" value="">
                            <input type="hidden" id="multi_ticket_discount_percent" name="multi_ticket_discount_percent" value="">
                            <input type="hidden" id="multi_ticket_offer_claimed" name="multi_ticket_offer_claimed" value="0">
                            
                            <div class="row">
                                <!-- Left Column: Tour Details -->
                                <div class="col-md-5">
                                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Tour Details</h5>
                                    <div class="mb-3">
                                        <?php if ($tour_details['image_url']): ?>
                                            <img src="<?php echo htmlspecialchars($tour_details['image_url']); ?>" 
                                                 class="img-fluid rounded mb-3" 
                                                 alt="<?php echo htmlspecialchars($tour_details['title']); ?>"
                                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                    <?php if (isset($tour_details['has_discount']) && $tour_details['has_discount']): ?>
                                        <p><strong><i class="fas fa-dollar-sign me-2"></i>Price:</strong> 
                                            <span class="text-decoration-line-through text-muted"><?php echo formatPrice($tour_details['original_price']); ?></span>
                                            <span class="text-price fw-bold ms-2"><?php echo formatPrice($tour_details['discounted_price']); ?></span>
                                            <span class="badge bg-danger ms-2"><?php echo $tour_details['discount_percent']; ?>% OFF</span>
                                        </p>
                                        <input type="hidden" name="discounted_price" id="discounted_price" value="<?php echo $tour_details['discounted_price']; ?>">
                                        <input type="hidden" name="original_price" value="<?php echo $tour_details['original_price']; ?>">
                                        <input type="hidden" name="discount_percent" value="<?php echo $tour_details['discount_percent']; ?>">
                                    <?php else: ?>
                                        <p><strong><i class="fas fa-dollar-sign me-2"></i>Price:</strong> <span class="text-price"><?php echo formatPrice($tour_details['price']); ?></span> per person</p>
                                    <?php endif; ?>
                                    <p><strong><i class="fas fa-clock me-2"></i>Duration:</strong> <?php echo $tour_details['duration_hours']; ?> hours</p>
                                    <p><strong><i class="fas fa-users me-2"></i>Max Participants:</strong> <?php echo $tour_details['max_participants']; ?></p>
                                    <p><strong><i class="fas fa-map-marker-alt me-2"></i>Location:</strong> <?php echo htmlspecialchars($tour_details['location'] ?? 'N/A'); ?></p>
                                    <p><strong><i class="fas fa-walking me-2"></i>Meeting Point:</strong> <?php echo htmlspecialchars($tour_details['meeting_point']); ?></p>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><strong>Total Price:</strong></span>
                                        <div id="total_price_container" class="text-end">
                                            <span id="total_price_original" class="text-muted text-decoration-line-through me-2" style="display: none; font-size: 1.2rem;">$0.00</span>
                                            <span id="total_price" class="text-success fw-bold fs-4">$0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Booking Form -->
                                <div class="col-md-7">
                                    <h5 class="mb-3"><i class="fas fa-calendar me-2"></i>Reservation Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="tour_schedule_id" class="form-label">Select Date & Time <span class="text-danger">*</span></label>
                                        <select class="form-select" id="tour_schedule_id" name="tour_schedule_id" required>
                                            <option value="">Choose a date and time</option>
                                            <?php foreach ($tour_schedules as $schedule): ?>
                                            <option value="<?php echo $schedule['id']; ?>" 
                                                    data-available="<?php echo $schedule['available_spots']; ?>"
                                                    data-price="<?php echo $tour_details['price']; ?>">
                                                <?php echo formatDate($schedule['tour_date']); ?> at <?php echo date('H:i', strtotime($schedule['tour_time'])); ?>
                                                (<?php echo $schedule['available_spots']; ?> spots left)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="num_participants" class="form-label">Number of Participants <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="num_participants" name="num_participants" 
                                               min="1" max="<?php echo $tour_details['max_participants']; ?>" 
                                               value="1" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="booking_notes" class="form-label">Special Requirements (Optional)</label>
                                        <textarea class="form-control" id="booking_notes" name="booking_notes" rows="3" 
                                                  placeholder="Any special requests or requirements..."></textarea>
                                    </div>

                                    <hr class="my-4">

                                    <!-- Payment Method Selection -->
                                    <h5 class="mb-3"><i class="fas fa-credit-card me-2"></i>Payment Method <span class="text-danger">*</span></h5>
                                    
                                    <div class="mb-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_visa" value="visa" required>
                                            <label class="form-check-label" for="payment_visa">
                                                <i class="fab fa-cc-visa me-2" style="font-size: 1.5rem; color: #1A1F71;"></i>
                                                <strong>Visa</strong>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_paypal" value="paypal" required>
                                            <label class="form-check-label" for="payment_paypal">
                                                <i class="fab fa-paypal me-2" style="font-size: 1.5rem; color: #0070BA;"></i>
                                                <strong>PayPal</strong>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" required>
                                            <label class="form-check-label" for="payment_cash">
                                                <i class="fas fa-money-bill-wave me-2" style="font-size: 1.5rem; color: #28a745;"></i>
                                                <strong>Cash (Pay on arrival)</strong>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Visa Payment Details -->
                                    <div id="visa_payment_details" class="payment-details" style="display: none;">
                                        <div class="card bg-light p-3 mb-3">
                                            <h6 class="mb-3"><i class="fab fa-cc-visa me-2"></i>Visa Card Information</h6>
                                            
                                            <div class="mb-3">
                                                <label for="card_number" class="form-label">Card Number (16 digits) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="card_number" name="card_number" 
                                                       placeholder="1234 5678 9012 3456" maxlength="19"
                                                       pattern="[0-9\s]{13,19}">
                                                <small class="form-text text-muted">Enter 16-digit card number</small>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="exp_date" class="form-label">Expiration Date (MM/YY) <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="exp_date" name="exp_date" 
                                                           placeholder="MM/YY" maxlength="5"
                                                           pattern="\d{2}\/\d{2}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="cvv" class="form-label">CVV <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="cvv" name="cvv" 
                                                           placeholder="123" maxlength="4"
                                                           pattern="\d{3,4}">
                                                    <small class="form-text text-muted">3 or 4 digits on the back</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PayPal Payment Details -->
                                    <div id="paypal_payment_details" class="payment-details" style="display: none;">
                                        <div class="card bg-light p-3 mb-3">
                                            <h6 class="mb-3"><i class="fab fa-paypal me-2"></i>PayPal Account Information</h6>
                                            
                                            <div class="mb-3">
                                                <label for="paypal_email" class="form-label">PayPal Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="paypal_email" name="paypal_email" 
                                                       placeholder="your-email@example.com"
                                                       required>
                                                <small class="form-text text-muted">Enter the email address associated with your PayPal account.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cash Payment Details -->
                                    <div id="cash_payment_details" class="payment-details alert alert-warning" style="display: none;">
                                        <i class="fas fa-money-bill-wave me-2"></i>
                                        <strong>Cash Payment:</strong> Please bring exact cash on the day of the tour. Your reservation will be confirmed after payment.
                                    </div>

                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-warning btn-lg">
                                            <i class="fas fa-calendar-check me-2"></i>Reserve Now
                                        </button>
                                        <a href="tours.php" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Multi-Ticket Discount Offer Modal -->
<?php if (!isset($tour_details['is_in_offers']) || !$tour_details['is_in_offers']): ?>
<div class="modal fade" id="multiTicketOfferModal" tabindex="-1" aria-labelledby="multiTicketOfferModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content offer-modal-content">
            <div class="modal-header offer-modal-header">
                <h5 class="modal-title text-white" id="multiTicketOfferModalLabel">
                    <i class="fas fa-gift me-2"></i>Special Offer Available!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body offer-modal-body text-white">
                <div class="text-center mb-4">
                    <div class="offer-icon">🎉</div>
                    <h4 class="mb-3 offer-headline">Book Multiple Tickets & Save!</h4>
                    <p class="lead mb-4 offer-description">
                        You're booking <strong id="offer_participant_count">3</strong> tickets! Claim a <strong id="offer_discount_percent_display">10</strong>% discount on your booking.
                    </p>
                </div>
                <div class="card offer-price-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Original Price:</span>
                            <span id="offer_original_total" class="text-decoration-line-through offer-original-price">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><strong>Discount (<span id="offer_discount_percent_label">10</span>%):</strong></span>
                            <span id="offer_discount_amount" class="offer-discount-amount">-$0.00</span>
                        </div>
                        <hr class="offer-divider">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="offer-total-label">New Total:</span>
                            <span id="offer_discounted_total" class="offer-total-amount">$0.00</span>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-lg offer-claim-btn" id="claimOfferBtn">
                        <i class="fas fa-check-circle me-2"></i>Claim Offer
                    </button>
                    <button type="button" class="btn offer-dismiss-btn" data-bs-dismiss="modal">
                        No Thanks, Continue Without Discount
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Offer Modal Styling - Matching Website Theme */
.offer-modal-content {
    border-radius: 20px !important;
    overflow: hidden;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    border: 1px solid rgba(93, 173, 226, 0.3) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5) !important;
    transition: all 0.3s ease !important;
}

.offer-modal-header {
    border-bottom: 2px solid rgba(93, 173, 226, 0.3) !important;
    background: transparent !important;
    padding: 1.5rem 2rem !important;
}

.offer-modal-header .modal-title {
    font-weight: 700 !important;
    font-size: 1.5rem !important;
    color: #ffffff !important;
}

.offer-modal-header .modal-title i {
    color: #5dade2 !important;
}

.offer-modal-body {
    background: transparent !important;
    padding: 2rem !important;
}

.offer-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    filter: drop-shadow(0 4px 8px rgba(93, 173, 226, 0.3));
}

.offer-headline {
    font-weight: 700 !important;
    color: #ffffff !important;
    font-size: 1.75rem;
}

.offer-description {
    font-size: 1.1rem;
    opacity: 0.95;
    color: #e3e6ea !important;
}

.offer-description strong {
    color: #5dade2 !important;
}

.offer-price-card {
    background: rgba(43, 48, 64, 0.8) !important;
    border: 1px solid rgba(93, 173, 226, 0.2) !important;
    border-radius: 15px !important;
    backdrop-filter: blur(10px);
}

.offer-price-card .card-body {
    padding: 1.5rem;
    color: #e3e6ea;
}

.offer-original-price {
    opacity: 0.8;
    color: #9ca3af !important;
}

.offer-discount-amount {
    color: #5dade2 !important;
    font-weight: 700 !important;
    transition: color 0.3s ease !important;
}

.offer-divider {
    border-color: rgba(93, 173, 226, 0.3) !important;
    margin: 1rem 0;
    opacity: 0.5;
}

.offer-total-label {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff !important;
}

.offer-total-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #5dade2 !important;
    transition: color 0.3s ease !important;
}

.offer-claim-btn {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 25px !important;
    font-weight: 600 !important;
    padding: 0.75rem !important;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4) !important;
    transition: all 0.3s ease !important;
}

.offer-claim-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
    color: #fff !important;
}

.offer-claim-btn:active {
    transform: translateY(0);
}

.offer-dismiss-btn {
    background: transparent !important;
    color: #ffffff !important;
    border: 2px solid rgba(93, 173, 226, 0.5) !important;
    border-radius: 25px !important;
    font-weight: 600 !important;
    padding: 0.75rem !important;
    transition: all 0.3s ease !important;
}

.offer-dismiss-btn:hover {
    background: rgba(93, 173, 226, 0.2) !important;
    border-color: #5dade2 !important;
    color: #ffffff !important;
    transform: translateY(-2px);
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.8;
}

.btn-close-white:hover {
    opacity: 1;
}
</style>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tourScheduleSelect = document.getElementById('tour_schedule_id');
    const numParticipantsInput = document.getElementById('num_participants');
    const totalPriceSpan = document.getElementById('total_price');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const visaDetails = document.getElementById('visa_payment_details');
    const paypalDetails = document.getElementById('paypal_payment_details');
    const cashDetails = document.getElementById('cash_payment_details');
    const cardNumberInput = document.getElementById('card_number');
    const expDateInput = document.getElementById('exp_date');
    const cvvInput = document.getElementById('cvv');
    
    // Multi-ticket offer elements
    const multiTicketOfferModal = document.getElementById('multiTicketOfferModal');
    const multiTicketDiscountPrice = document.getElementById('multi_ticket_discount_price');
    const multiTicketOriginalPrice = document.getElementById('multi_ticket_original_price');
    const multiTicketDiscountPercent = document.getElementById('multi_ticket_discount_percent');
    const multiTicketOfferClaimed = document.getElementById('multi_ticket_offer_claimed');
    const isInOffers = <?php echo (isset($tour_details['is_in_offers']) && $tour_details['is_in_offers']) ? 'true' : 'false'; ?>;
    let offerModal = null;
    if (multiTicketOfferModal) {
        offerModal = new bootstrap.Modal(multiTicketOfferModal);
    }

    // Update total price calculation
    function updateTotalPrice() {
        const selectedOption = tourScheduleSelect.options[tourScheduleSelect.selectedIndex];
        const totalPriceOriginalSpan = document.getElementById('total_price_original');
        
        if (selectedOption.value) {
            const basePrice = parseFloat(selectedOption.dataset.price);
            const participants = parseInt(numParticipantsInput.value) || 0;
            const originalTotal = basePrice * participants;
            
            // Check if there's a discounted price (from offers or multi-ticket offer)
            const discountedPriceInput = document.getElementById('discounted_price');
            const multiTicketDiscount = multiTicketOfferClaimed && multiTicketOfferClaimed.value === '1' ? multiTicketDiscountPrice.value : null;
            
            let price;
            let hasDiscount = false;
            
            if (discountedPriceInput && discountedPriceInput.value) {
                // Use discounted price from offers page
                price = parseFloat(discountedPriceInput.value);
                hasDiscount = price < basePrice;
            } else if (multiTicketDiscount && parseFloat(multiTicketDiscount) > 0) {
                // Use multi-ticket discount price
                price = parseFloat(multiTicketDiscount);
                hasDiscount = price < basePrice;
            } else {
                // Use regular price
                price = basePrice;
                hasDiscount = false;
            }
            
            const total = price * participants;
            
            // Show original price crossed out if there's a discount
            if (hasDiscount && originalTotal > total) {
                totalPriceOriginalSpan.textContent = '$' + originalTotal.toFixed(2);
                totalPriceOriginalSpan.style.display = 'inline';
                totalPriceSpan.textContent = '$' + total.toFixed(2);
            } else {
                totalPriceOriginalSpan.style.display = 'none';
                totalPriceSpan.textContent = '$' + total.toFixed(2);
            }
        } else {
            totalPriceOriginalSpan.style.display = 'none';
            totalPriceSpan.textContent = '$0.00';
        }
    }
    
    // Check and show multi-ticket offer modal
    function checkMultiTicketOffer() {
        // Only show offer if tour is NOT in offers section
        if (isInOffers || !multiTicketOfferModal) {
            return;
        }
        
        const participants = parseInt(numParticipantsInput.value) || 0;
        const selectedOption = tourScheduleSelect.options[tourScheduleSelect.selectedIndex];
        
        if (!selectedOption.value) {
            // No schedule selected, remove offer if it was claimed
            if (multiTicketOfferClaimed.value === '1') {
                removeMultiTicketOffer();
            }
            return;
        }
        
        // Check if there's already a discount from offers page
        const discountedPriceInput = document.getElementById('discounted_price');
        if (discountedPriceInput && discountedPriceInput.value) {
            // Already has discount from offers, don't show multi-ticket offer
            if (multiTicketOfferClaimed.value === '1') {
                removeMultiTicketOffer();
            }
            return;
        }
        
        const basePrice = parseFloat(selectedOption.dataset.price);
        
        if (participants >= 2) {
            // Show offer modal if not already claimed
            if (multiTicketOfferClaimed.value !== '1') {
                showMultiTicketOfferModal(basePrice, participants);
            }
        } else {
            // Remove offer if participant count goes back to 1 or less
            if (multiTicketOfferClaimed.value === '1') {
                removeMultiTicketOffer();
            }
            // Close modal if open
            if (offerModal && offerModal._isShown) {
                offerModal.hide();
            }
        }
    }
    
    // Calculate discount percent based on ticket count
    function getDiscountPercent(participants) {
        if (participants >= 4) {
            return 15; // 4+ tickets: 15%
        } else if (participants === 3) {
            return 10; // 3 tickets: 10%
        } else if (participants === 2) {
            return 5;  // 2 tickets: 5%
        }
        return 0;
    }
    
    // Get color scheme based on discount level
    function getDiscountColorScheme(discountPercent) {
        if (discountPercent === 15) {
            return {
                gradient: 'linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%)',
                accent: '#20c997',
                border: 'rgba(32, 201, 151, 0.3)',
                name: 'green'
            };
        } else if (discountPercent === 10) {
            return {
                gradient: 'linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%)',
                accent: '#5dade2',
                border: 'rgba(93, 173, 226, 0.3)',
                name: 'blue'
            };
        } else if (discountPercent === 5) {
            return {
                gradient: 'linear-gradient(135deg, #ffc107 0%, #ff9800 50%, #f57c00 100%)',
                accent: '#ffc107',
                border: 'rgba(255, 193, 7, 0.3)',
                name: 'yellow'
            };
        }
        return {
            gradient: 'linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)',
            accent: '#5dade2',
            border: 'rgba(93, 173, 226, 0.3)',
            name: 'default'
        };
    }
    
    // Show multi-ticket offer modal
    function showMultiTicketOfferModal(basePrice, participants) {
        if (!offerModal) return;
        
        const discountPercent = getDiscountPercent(participants);
        const originalTotal = basePrice * participants;
        const discountAmount = originalTotal * (discountPercent / 100);
        const discountedTotal = originalTotal - discountAmount;
        const discountedPricePerTicket = discountedTotal / participants;
        const colorScheme = getDiscountColorScheme(discountPercent);
        
        // Update modal content
        document.getElementById('offer_participant_count').textContent = participants;
        document.getElementById('offer_discount_percent_display').textContent = discountPercent;
        const discountPercentLabel = document.getElementById('offer_discount_percent_label');
        if (discountPercentLabel) {
            discountPercentLabel.textContent = discountPercent;
        }
        document.getElementById('offer_original_total').textContent = '$' + originalTotal.toFixed(2);
        document.getElementById('offer_discount_amount').textContent = '-$' + discountAmount.toFixed(2);
        document.getElementById('offer_discounted_total').textContent = '$' + discountedTotal.toFixed(2);
        
        // Update modal colors based on discount level
        const modalContent = document.querySelector('.offer-modal-content');
        const modalHeader = document.querySelector('.offer-modal-header');
        const claimBtn = document.getElementById('claimOfferBtn');
        const dismissBtn = document.querySelector('.offer-dismiss-btn');
        
        if (modalContent) {
            modalContent.style.background = colorScheme.gradient + ' !important';
            modalContent.style.borderColor = colorScheme.border + ' !important';
        }
        if (modalHeader) {
            modalHeader.style.borderBottomColor = colorScheme.border + ' !important';
        }
        if (claimBtn) {
            claimBtn.style.background = colorScheme.gradient + ' !important';
            claimBtn.style.boxShadow = `0 4px 15px ${colorScheme.accent}40 !important`;
        }
        if (dismissBtn) {
            dismissBtn.style.borderColor = colorScheme.border + ' !important';
        }
        
        // Update icon color
        const icon = document.querySelector('.offer-modal-header .modal-title i');
        if (icon) {
            icon.style.color = colorScheme.accent + ' !important';
        }
        
        // Update discount amount and total colors
        const discountAmountEl = document.getElementById('offer_discount_amount');
        const totalAmountEl = document.getElementById('offer_discounted_total');
        if (discountAmountEl) {
            discountAmountEl.style.color = colorScheme.accent;
        }
        if (totalAmountEl) {
            totalAmountEl.style.color = colorScheme.accent;
        }
        
        // Update claim button hover effect
        if (claimBtn) {
            claimBtn.addEventListener('mouseenter', function() {
                this.style.filter = 'brightness(1.1)';
                this.style.boxShadow = `0 6px 20px ${colorScheme.accent}60`;
            });
            claimBtn.addEventListener('mouseleave', function() {
                this.style.filter = 'brightness(1)';
                this.style.boxShadow = `0 4px 15px ${colorScheme.accent}40`;
            });
        }
        
        // Show modal
        offerModal.show();
    }
    
    // Remove multi-ticket offer
    function removeMultiTicketOffer() {
        multiTicketDiscountPrice.value = '';
        multiTicketOriginalPrice.value = '';
        multiTicketDiscountPercent.value = '';
        multiTicketOfferClaimed.value = '0';
        updateTotalPrice();
    }
    
    // Claim offer button handler
    if (document.getElementById('claimOfferBtn')) {
        document.getElementById('claimOfferBtn').addEventListener('click', function() {
            const selectedOption = tourScheduleSelect.options[tourScheduleSelect.selectedIndex];
            if (!selectedOption.value) {
                alert('Please select a date and time first.');
                return;
            }
            
            const basePrice = parseFloat(selectedOption.dataset.price);
            const participants = parseInt(numParticipantsInput.value) || 0;
            const discountPercent = getDiscountPercent(participants);
            const originalTotal = basePrice * participants;
            const discountAmount = originalTotal * (discountPercent / 100);
            const discountedTotal = originalTotal - discountAmount;
            const discountedPricePerTicket = discountedTotal / participants;
            
            // Set hidden fields
            multiTicketDiscountPrice.value = discountedPricePerTicket.toFixed(2);
            multiTicketOriginalPrice.value = basePrice.toFixed(2);
            multiTicketDiscountPercent.value = discountPercent;
            multiTicketOfferClaimed.value = '1';
            
            // Close modal
            if (offerModal) {
                offerModal.hide();
            }
            
            // Update total price
            updateTotalPrice();
        });
    }
    
    // Handle modal close - remove offer if user dismisses
    if (multiTicketOfferModal) {
        multiTicketOfferModal.addEventListener('hidden.bs.modal', function() {
            // Only remove if offer wasn't claimed
            if (multiTicketOfferClaimed.value !== '1') {
                const participants = parseInt(numParticipantsInput.value) || 0;
                if (participants <= 2) {
                    removeMultiTicketOffer();
                }
            }
        });
    }

    // Update max participants based on availability
    function updateMaxParticipants() {
        const selectedOption = tourScheduleSelect.options[tourScheduleSelect.selectedIndex];
        if (selectedOption.value) {
            const availableSpots = parseInt(selectedOption.dataset.available);
            const maxParticipants = Math.min(availableSpots, <?php echo $tour_details['max_participants']; ?>);
            numParticipantsInput.max = maxParticipants;
            
            if (parseInt(numParticipantsInput.value) > maxParticipants) {
                numParticipantsInput.value = maxParticipants;
            }
        }
    }

    // Show/hide payment details based on selected method
    const paypalEmailInput = document.getElementById('paypal_email');
    
    function togglePaymentDetails() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedMethod) {
            visaDetails.style.display = 'none';
            paypalDetails.style.display = 'none';
            cashDetails.style.display = 'none';
            return;
        }

        visaDetails.style.display = (selectedMethod.value === 'visa') ? 'block' : 'none';
        paypalDetails.style.display = (selectedMethod.value === 'paypal') ? 'block' : 'none';
        cashDetails.style.display = (selectedMethod.value === 'cash') ? 'block' : 'none';

        // Set required attributes
        const cardFields = [cardNumberInput, expDateInput, cvvInput];
        if (selectedMethod.value === 'visa') {
            cardFields.forEach(field => field.setAttribute('required', 'required'));
            if (paypalEmailInput) paypalEmailInput.removeAttribute('required');
        } else if (selectedMethod.value === 'paypal') {
            cardFields.forEach(field => field.removeAttribute('required'));
            if (paypalEmailInput) paypalEmailInput.setAttribute('required', 'required');
        } else {
            cardFields.forEach(field => field.removeAttribute('required'));
            if (paypalEmailInput) paypalEmailInput.removeAttribute('required');
        }
    }

    // Format card number with spaces (XXXX XXXX XXXX XXXX)
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        if (formattedValue.length <= 19) {
            e.target.value = formattedValue;
        }
    });

    // Format expiration date (MM/YY)
    expDateInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // Only allow digits for CVV
    cvvInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Event listeners
    tourScheduleSelect.addEventListener('change', function() {
        updateMaxParticipants();
        updateTotalPrice();
        checkMultiTicketOffer();
    });

    numParticipantsInput.addEventListener('input', function() {
        updateTotalPrice();
        checkMultiTicketOffer();
    });

    paymentMethods.forEach(method => {
        method.addEventListener('change', togglePaymentDetails);
    });

    // Form validation
    const form = document.getElementById('reservationForm');
    form.addEventListener('submit', function(e) {
        const selectedSchedule = tourScheduleSelect.value;
        const participants = parseInt(numParticipantsInput.value);
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

        if (!selectedSchedule) {
            e.preventDefault();
            alert('Please select a date and time.');
            return false;
        }

        if (!selectedPayment) {
            e.preventDefault();
            alert('Please select a payment method.');
            return false;
        }

        const selectedOption = tourScheduleSelect.options[tourScheduleSelect.selectedIndex];
        const availableSpots = parseInt(selectedOption.dataset.available);
        
        if (participants > availableSpots) {
            e.preventDefault();
            alert(`Only ${availableSpots} spots available for this time slot.`);
            return false;
        }

        // Validate Visa card if selected
        if (selectedPayment.value === 'visa') {
            const cardNumber = cardNumberInput.value.replace(/\s+/g, '');
            const expDate = expDateInput.value;
            const cvv = cvvInput.value;

            // Validate card number (16 digits)
            if (!/^\d{16}$/.test(cardNumber)) {
                e.preventDefault();
                alert('Please enter a valid 16-digit Visa card number.');
                cardNumberInput.focus();
                return false;
            }

            // Validate expiration date format (MM/YY)
            if (!/^\d{2}\/\d{2}$/.test(expDate)) {
                e.preventDefault();
                alert('Please enter a valid expiration date (MM/YY).');
                expDateInput.focus();
                return false;
            }
            
            // Validate CVV (3 or 4 digits)
            if (!/^\d{3,4}$/.test(cvv)) {
                e.preventDefault();
                alert('Please enter a valid CVV (3 or 4 digits).');
                cvvInput.focus();
                return false;
            }

            // Check if expiration date is in the past
            const [month, year] = expDate.split('/');
            const expDateObj = new Date(2000 + parseInt(year), parseInt(month) - 1);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (expDateObj < today) {
                e.preventDefault();
                alert('Card expiration date cannot be in the past.');
                expDateInput.focus();
                return false;
            }
        }

        // Validate PayPal email if selected
        if (selectedPayment.value === 'paypal') {
            const paypalEmail = paypalEmailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!paypalEmail || !emailRegex.test(paypalEmail)) {
                e.preventDefault();
                alert('Please enter a valid PayPal email address.');
                paypalEmailInput.focus();
                return false;
            }
        }
    });
});
</script>




