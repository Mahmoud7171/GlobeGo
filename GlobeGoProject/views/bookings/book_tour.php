<?php
// View for booking a tour from book-tour.php
// Expects: $tour_id, $tour_details, $tour_schedules
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Book: <?php echo $tour_details['title']; ?></h4>
                </div>
                <div class="card-body">
                    <?php if (empty($tour_schedules)): ?>
                        <div class="alert alert-info">
                            <h5>No Available Dates</h5>
                            <p>This tour doesn't have any available dates at the moment.</p>
                            <a href="tours.php" class="btn btn-primary">Browse Other Tours</a>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="process-booking.php">
                            <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Tour Details</h5>
                                    <p><strong>Price:</strong> <span class="text-price"><?php echo formatPrice($tour_details['price']); ?></span> per person</p>
                                    <p><strong>Duration:</strong> <?php echo $tour_details['duration_hours']; ?> hours</p>
                                    <p><strong>Max Participants:</strong> <?php echo $tour_details['max_participants']; ?></p>
                                    <p><strong>Meeting Point:</strong> <?php echo $tour_details['meeting_point']; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Booking Details</h5>
                                    
                                    <div class="mb-3">
                                        <label for="tour_schedule_id" class="form-label">Select Date & Time</label>
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
                                        <label for="num_participants" class="form-label">Number of Participants</label>
                                        <input type="number" class="form-control" id="num_participants" name="num_participants" 
                                               min="1" max="<?php echo $tour_details['max_participants']; ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="booking_notes" class="form-label">Special Requirements (Optional)</label>
                                        <textarea class="form-control" id="booking_notes" name="booking_notes" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span><strong>Total Price:</strong></span>
                                            <span id="total_price" class="text-success fw-bold">$0.00</span>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success btn-lg">Book Now</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tourScheduleSelect = document.getElementById('tour_schedule_id');
    const numParticipantsInput = document.getElementById('num_participants');
    const totalPriceSpan = document.getElementById('total_price');

    function updateTotalPrice() {
        const selectedOption = tourScheduleSelect.options[tourScheduleSelect.selectedIndex];
        if (selectedOption.value) {
            const price = parseFloat(selectedOption.dataset.price);
            const participants = parseInt(numParticipantsInput.value) || 0;
            const total = price * participants;
            totalPriceSpan.textContent = GlobeGo.formatCurrency(total);
        } else {
            totalPriceSpan.textContent = '$0.00';
        }
    }

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

    tourScheduleSelect.addEventListener('change', function() {
        updateMaxParticipants();
        updateTotalPrice();
    });

    numParticipantsInput.addEventListener('input', updateTotalPrice);

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const selectedSchedule = tourScheduleSelect.value;
        const participants = parseInt(numParticipantsInput.value);
        
        if (!selectedSchedule) {
            e.preventDefault();
            GlobeGo.showAlert('Please select a date and time.', 'danger');
            return;
        }
        
        const selectedOption = tourScheduleSelect.options[tourScheduleSelect.selectedIndex];
        const availableSpots = parseInt(selectedOption.dataset.available);
        
        if (participants > availableSpots) {
            e.preventDefault();
            GlobeGo.showAlert(`Only ${availableSpots} spots available for this time slot.`, 'danger');
            return;
        }
    });
});
</script>


