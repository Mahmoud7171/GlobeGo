<?php
// Fines view
// Expects: $fines (array), $total_pending (float), $total_paid (float)
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><i class="fas fa-exclamation-triangle me-2"></i>My Fines</h1>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">Total Pending</h5>
                    <h3 class="mb-0"><?php echo formatPrice($total_pending ?? 0); ?></h3>
                    <small class="text-muted">Unpaid cancellation fees</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">Total Paid</h5>
                    <h3 class="mb-0"><?php echo formatPrice($total_paid ?? 0); ?></h3>
                    <small class="text-muted">Fees already paid</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Fines Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Fines (<?php echo count($fines ?? []); ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($fines)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h5>No Fines</h5>
                    <p class="mb-0">You don't have any cancellation fines at this time.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking Reference</th>
                                <th>Tour</th>
                                <th>Date</th>
                                <th>Original Price</th>
                                <th>Fine Amount</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fines as $fine_item): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($fine_item['booking_reference']); ?></strong></td>
                                    <td>
                                        <?php echo htmlspecialchars($fine_item['tour_title'] ?? 'N/A'); ?>
                                        <?php if (!empty($fine_item['attraction_name'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($fine_item['attraction_name']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($fine_item['tour_date']): ?>
                                            <?php echo date('M j, Y', strtotime($fine_item['tour_date'])); ?>
                                            <?php if ($fine_item['tour_time']): ?>
                                                <br><small><?php echo date('g:i A', strtotime($fine_item['tour_time'])); ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo formatPrice($fine_item['original_price']); ?></td>
                                    <td><strong class="text-danger"><?php echo formatPrice($fine_item['amount']); ?></strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo $fine_item['status'] === 'paid' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($fine_item['status']); ?>
                                        </span>
                                        <?php if ($fine_item['status'] === 'paid' && $fine_item['payment_method']): ?>
                                            <br><small class="text-muted"><?php echo ucfirst($fine_item['payment_method']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($fine_item['created_at'])); ?></td>
                                    <td>
                                        <?php if ($fine_item['status'] === 'pending'): ?>
                                            <button type="button" class="btn btn-sm btn-primary pay-fine-btn" 
                                                    data-fine-id="<?php echo $fine_item['id']; ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#payFineModal<?php echo $fine_item['id']; ?>">
                                                <i class="fas fa-credit-card me-1"></i>Pay Now
                                            </button>
                                        <?php else: ?>
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> Paid
                                            </span>
                                            <?php if ($fine_item['paid_at']): ?>
                                                <br><small class="text-muted"><?php echo date('M j, Y', strtotime($fine_item['paid_at'])); ?></small>
                                            <?php endif; ?>
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

<!-- Payment Modals (placed outside table to avoid positioning issues) -->
<?php if (!empty($fines)): ?>
    <?php foreach ($fines as $fine_item): ?>
        <?php if ($fine_item['status'] === 'pending'): ?>
            <div class="modal fade" id="payFineModal<?php echo $fine_item['id']; ?>" tabindex="-1" aria-labelledby="payFineModalLabel<?php echo $fine_item['id']; ?>" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="payFineModalLabel<?php echo $fine_item['id']; ?>">Pay Cancellation Fine</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="process-fine-payment.php" id="payFineForm<?php echo $fine_item['id']; ?>">
                            <div class="modal-body">
                                <input type="hidden" name="fine_id" value="<?php echo $fine_item['id']; ?>">
                                
                                <div class="alert alert-info">
                                    <strong>Fine Details:</strong><br>
                                    Booking: <?php echo htmlspecialchars($fine_item['booking_reference']); ?><br>
                                    Tour: <?php echo htmlspecialchars($fine_item['tour_title'] ?? 'N/A'); ?><br>
                                    <strong>Amount Due: <?php echo formatPrice($fine_item['amount']); ?></strong>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input payment-method-radio" type="radio" name="payment_method" 
                                                   id="visa<?php echo $fine_item['id']; ?>" value="visa" required data-fine-id="<?php echo $fine_item['id']; ?>">
                                            <label class="form-check-label" for="visa<?php echo $fine_item['id']; ?>">
                                                <i class="fab fa-cc-visa me-1"></i>Visa
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input payment-method-radio" type="radio" name="payment_method" 
                                                   id="paypal<?php echo $fine_item['id']; ?>" value="paypal" required data-fine-id="<?php echo $fine_item['id']; ?>">
                                            <label class="form-check-label" for="paypal<?php echo $fine_item['id']; ?>">
                                                <i class="fab fa-paypal me-1"></i>PayPal
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Visa Payment Fields -->
                                <div class="visa-payment-fields" id="visaFields<?php echo $fine_item['id']; ?>" style="display: none;">
                                    <div class="mb-3">
                                        <label for="card_number<?php echo $fine_item['id']; ?>" class="form-label">Card Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control card-number-input" id="card_number<?php echo $fine_item['id']; ?>" 
                                               name="card_number" maxlength="19" 
                                               placeholder="0000 0000 0000 0000"
                                               pattern="[0-9\s]{13,19}">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="exp_date<?php echo $fine_item['id']; ?>" class="form-label">Expiration Date <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control exp-date-input" id="exp_date<?php echo $fine_item['id']; ?>" 
                                                   name="exp_date" maxlength="5" placeholder="MM/YY"
                                                   pattern="\d{2}/\d{2}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cvv<?php echo $fine_item['id']; ?>" class="form-label">CVV <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cvv<?php echo $fine_item['id']; ?>" 
                                                   name="cvv" maxlength="4" placeholder="123"
                                                   pattern="\d{3,4}">
                                        </div>
                                    </div>
                                </div>

                                <!-- PayPal Payment Fields -->
                                <div class="paypal-payment-fields" id="paypalFields<?php echo $fine_item['id']; ?>" style="display: none;">
                                    <div class="mb-3">
                                        <label for="paypal_email<?php echo $fine_item['id']; ?>" class="form-label">PayPal Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="paypal_email<?php echo $fine_item['id']; ?>" 
                                               name="paypal_email" placeholder="your-email@example.com"
                                               required>
                                        <small class="form-text text-muted">Enter the email address associated with your PayPal account.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Pay <?php echo formatPrice($fine_item['amount']); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<style>
    /* Modal Styles */
    .modal {
        z-index: 1055 !important;
    }
    
    .modal-backdrop {
        z-index: 1050 !important;
        background-color: rgba(0, 0, 0, 0.5) !important;
    }
    
    .modal-dialog {
        z-index: 1056 !important;
    }
    
    .modal-content {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .modal-title,
    .modal label,
    .modal .form-label {
        color: #212529 !important;
        font-weight: 500;
    }
    
    body.dark .modal-content {
        background-color: #2d3338 !important;
        color: #e3e6ea !important;
    }
    
    body.dark .modal-title,
    body.dark .modal label,
    body.dark .modal .form-label {
        color: #e3e6ea !important;
    }
    
    body.dark .modal-header {
        border-bottom-color: #495057 !important;
    }
    
    body.dark .modal-footer {
        border-top-color: #495057 !important;
    }
    
    body.dark .form-control {
        background-color: #3a4148 !important;
        border-color: #495057 !important;
        color: #e3e6ea !important;
    }
    
    body.dark .form-control:focus {
        background-color: #3a4148 !important;
        border-color: #0d6efd !important;
        color: #e3e6ea !important;
    }
    
    body.dark .form-control::placeholder {
        color: #adb5bd !important;
    }
    
    body.dark .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle payment method selection
    document.querySelectorAll('.payment-method-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const fineId = this.getAttribute('data-fine-id');
            const visaFields = document.getElementById('visaFields' + fineId);
            const paypalFields = document.getElementById('paypalFields' + fineId);
            const cardNumber = document.getElementById('card_number' + fineId);
            const expDate = document.getElementById('exp_date' + fineId);
            const cvv = document.getElementById('cvv' + fineId);
            
            if (this.value === 'visa') {
                if (visaFields) visaFields.style.display = 'block';
                if (paypalFields) paypalFields.style.display = 'none';
                if (cardNumber) cardNumber.required = true;
                if (expDate) expDate.required = true;
                if (cvv) cvv.required = true;
                // Remove required from PayPal email
                const paypalEmail = document.getElementById('paypal_email' + fineId);
                if (paypalEmail) paypalEmail.required = false;
            } else if (this.value === 'paypal') {
                if (visaFields) visaFields.style.display = 'none';
                if (paypalFields) paypalFields.style.display = 'block';
                if (cardNumber) cardNumber.required = false;
                if (expDate) expDate.required = false;
                if (cvv) cvv.required = false;
                // Add required to PayPal email
                const paypalEmail = document.getElementById('paypal_email' + fineId);
                if (paypalEmail) paypalEmail.required = true;
            }
        });
    });
    
    // Card number formatting
    document.querySelectorAll('.card-number-input').forEach(function(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    });
    
    // Expiration date formatting
    document.querySelectorAll('.exp-date-input').forEach(function(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    });
    
    // Ensure modal closes properly when clicking backdrop
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                const bsModal = bootstrap.Modal.getInstance(this);
                if (bsModal) {
                    bsModal.hide();
                }
            }
        });
    });
    
    // Reset form when modal is hidden
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
                // Reset payment fields visibility and required attributes
                const fineId = this.id.replace('payFineModal', '');
                const visaFields = document.getElementById('visaFields' + fineId);
                const paypalFields = document.getElementById('paypalFields' + fineId);
                const cardNumber = document.getElementById('card_number' + fineId);
                const expDate = document.getElementById('exp_date' + fineId);
                const cvv = document.getElementById('cvv' + fineId);
                const paypalEmail = document.getElementById('paypal_email' + fineId);
                
                if (visaFields) visaFields.style.display = 'none';
                if (paypalFields) paypalFields.style.display = 'none';
                if (cardNumber) cardNumber.required = false;
                if (expDate) expDate.required = false;
                if (cvv) cvv.required = false;
                if (paypalEmail) paypalEmail.required = false;
            }
        });
    });
});
</script>


