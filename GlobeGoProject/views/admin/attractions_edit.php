<?php
// Edit attraction view
// Expects: $attraction (array), $related_tours (array), $categories (array)
?>
<div class="login-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="login-card">
                    <div class="edit-header-wrapper">
                        <a href="<?php echo SITE_URL; ?>/admin/attractions.php" class="btn-back">
                            <i class="fas fa-arrow-left me-2"></i>BACK
                        </a>
                        <div class="login-header">
                            <h2 class="login-title"><i class="fas fa-edit me-2"></i>EDIT ATTRACTION</h2>
                            <p class="login-subtitle">Update attraction details below</p>
                        </div>
                    </div>
                    
                    <?php if (isset($_SESSION['admin_message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['admin_message_type']; ?> alert-custom">
                            <?php echo $_SESSION['admin_message']; ?>
                        </div>
                        <?php 
                        unset($_SESSION['admin_message']);
                        unset($_SESSION['admin_message_type']);
                        ?>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo SITE_URL; ?>/admin/attractions.php" class="needs-validation login-form" novalidate>
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="attraction_id" value="<?php echo $attraction['id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="name" class="form-label-custom">
                                        <i class="fas fa-tag me-2"></i>Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control-custom" id="name" name="name" 
                                           value="<?php echo htmlspecialchars($attraction['name']); ?>" 
                                           placeholder="Enter attraction name"
                                           required>
                                    <div class="invalid-feedback">
                                        Name is required.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="location" class="form-label-custom">
                                        <i class="fas fa-map-marker-alt me-2"></i>Location <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control-custom" id="location" name="location" 
                                           value="<?php echo htmlspecialchars($attraction['location']); ?>" 
                                           placeholder="Enter location"
                                           required>
                                    <div class="invalid-feedback">
                                        Location is required.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-custom mb-4">
                            <label for="description" class="form-label-custom">
                                <i class="fas fa-align-left me-2"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control-custom" id="description" name="description" 
                                      rows="4" 
                                      placeholder="Enter attraction description"
                                      required><?php echo htmlspecialchars($attraction['description']); ?></textarea>
                            <div class="invalid-feedback">
                                Description is required.
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="category" class="form-label-custom">
                                        <i class="fas fa-folder me-2"></i>Category
                                    </label>
                                    <input type="text" class="form-control-custom" id="category" name="category" 
                                           value="<?php echo htmlspecialchars($attraction['category'] ?? ''); ?>" 
                                           placeholder="Enter category"
                                           list="categoryList">
                                    <datalist id="categoryList">
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo htmlspecialchars($cat); ?>">
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="image_url" class="form-label-custom">
                                        <i class="fas fa-image me-2"></i>Image URL or Path
                                    </label>
                                    <input type="text" class="form-control-custom" id="image_url" name="image_url" 
                                           value="<?php echo htmlspecialchars($attraction['image_url'] ?? ''); ?>" 
                                           placeholder="images/example.jpg or https://example.com/image.jpg">
                                    <?php if (!empty($attraction['image_url'])): ?>
                                        <?php 
                                        // Handle both relative and absolute image URLs for preview
                                        $preview_url = $attraction['image_url'];
                                        if (!filter_var($preview_url, FILTER_VALIDATE_URL)) {
                                            if (substr($preview_url, 0, 1) === '/') {
                                                $preview_url = SITE_URL . $preview_url;
                                            } else {
                                                $preview_url = SITE_URL . '/' . ltrim($preview_url, '/');
                                            }
                                        }
                                        ?>
                                        <small class="text-muted d-block mt-2">Current image:</small>
                                        <img src="<?php echo htmlspecialchars($preview_url); ?>" 
                                             alt="Current image" 
                                             class="mt-2 img-preview"
                                             onerror="this.style.display='none';">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="latitude" class="form-label-custom">
                                        <i class="fas fa-globe me-2"></i>Latitude
                                    </label>
                                    <input type="number" step="any" class="form-control-custom" id="latitude" name="latitude" 
                                           value="<?php echo htmlspecialchars($attraction['latitude'] ?? ''); ?>" 
                                           placeholder="Enter latitude">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="longitude" class="form-label-custom">
                                        <i class="fas fa-globe me-2"></i>Longitude
                                    </label>
                                    <input type="number" step="any" class="form-control-custom" id="longitude" name="longitude" 
                                           value="<?php echo htmlspecialchars($attraction['longitude'] ?? ''); ?>" 
                                           placeholder="Enter longitude">
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($related_tours)): ?>
                            <div class="form-group-custom mb-4">
                                <h6 class="form-label-custom mb-3"><i class="fas fa-route me-2"></i>Manage Related Tours</h6>
                                <?php foreach ($related_tours as $tour): ?>
                                    <div class="tour-card mb-3">
                                        <div class="tour-header">
                                            <strong><?php echo htmlspecialchars($tour['title']); ?></strong> 
                                            by <?php echo htmlspecialchars($tour['first_name'] . ' ' . $tour['last_name']); ?>
                                            <span class="badge bg-<?php echo $tour['status'] === 'active' ? 'success' : 'secondary'; ?> ms-2">
                                                <?php echo htmlspecialchars($tour['status']); ?>
                                            </span>
                                        </div>
                                        <div class="tour-body">
                                            <!-- Tour Price -->
                                            <div class="mb-3">
                                                <label for="tour_price_<?php echo $tour['id']; ?>" class="form-label-custom">Price per Person</label>
                                                <div class="input-group-custom">
                                                    <span class="input-group-text-custom">$</span>
                                                    <input type="number" step="0.01" min="0" 
                                                           class="form-control-custom" 
                                                           id="tour_price_<?php echo $tour['id']; ?>" 
                                                           name="tour_prices[<?php echo $tour['id']; ?>]" 
                                                           value="<?php echo htmlspecialchars($tour['price'] ?? '0'); ?>">
                                                </div>
                                            </div>
                                            
                                            <!-- Existing Schedules -->
                                            <?php if (!empty($tour['schedules'])): ?>
                                                <div class="mb-3">
                                                    <label class="form-label-custom">Select Date to Update</label>
                                                    <select class="form-control-custom schedule-date-select" 
                                                            id="schedule-select-<?php echo $attraction['id']; ?>-<?php echo $tour['id']; ?>"
                                                            onchange="showScheduleDetails(<?php echo $attraction['id']; ?>, <?php echo $tour['id']; ?>, this.value)">
                                                        <option value="">-- Select a date --</option>
                                                        <?php foreach ($tour['schedules'] as $schedule): ?>
                                                            <option value="<?php echo $schedule['id']; ?>" 
                                                                    data-date="<?php echo htmlspecialchars($schedule['tour_date']); ?>"
                                                                    data-time="<?php echo htmlspecialchars($schedule['tour_time']); ?>"
                                                                    data-spots="<?php echo htmlspecialchars($schedule['available_spots'] ?? '0'); ?>">
                                                                <?php echo date('M j, Y', strtotime($schedule['tour_date'])); ?> at <?php echo date('g:i A', strtotime($schedule['tour_time'])); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    
                                                    <!-- Schedule Details (hidden by default, shown when date is selected) -->
                                                    <div id="schedule-details-<?php echo $attraction['id']; ?>-<?php echo $tour['id']; ?>" class="schedule-details mt-3" style="display: none;">
                                                        <input type="hidden" id="selected-schedule-id-<?php echo $attraction['id']; ?>-<?php echo $tour['id']; ?>" value="">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label-custom">Date</label>
                                                                <input type="date" class="form-control-custom" 
                                                                       id="schedule-date-<?php echo $attraction['id']; ?>-<?php echo $tour['id']; ?>" 
                                                                       readonly>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label-custom">Time</label>
                                                                <input type="time" class="form-control-custom" 
                                                                       id="schedule-time-<?php echo $attraction['id']; ?>-<?php echo $tour['id']; ?>" 
                                                                       readonly>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label-custom">Available Spaces</label>
                                                            <input type="number" min="0" class="form-control-custom" 
                                                                   id="schedule-spots-<?php echo $attraction['id']; ?>-<?php echo $tour['id']; ?>" 
                                                                   name="">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Add New Schedule -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <label class="form-label-custom mb-0">Add New Dates</label>
                                                    <button type="button" class="btn-add-schedule" 
                                                            onclick="addNewSchedule(<?php echo $attraction['id']; ?>, <?php echo $tour['id']; ?>)">
                                                        <i class="fas fa-plus me-1"></i>Add Date
                                                    </button>
                                                </div>
                                                <div id="new-schedules-<?php echo $attraction['id']; ?>-<?php echo $tour['id']; ?>">
                                                    <!-- New schedule rows will be added here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between gap-3 mt-4">
                            <a href="<?php echo SITE_URL; ?>/admin/attractions.php" class="btn-login btn-secondary-custom">
                                <i class="fas fa-times me-2"></i>CANCEL
                            </a>
                            <button type="submit" class="btn-login">
                                <i class="fas fa-save me-2"></i>UPDATE ATTRACTION
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Login Container - Dark Blue Gradient Background */
.login-container {
    min-height: calc(100vh - 200px);
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    padding: 2rem 0;
}

/* Login Card */
.login-card {
    background: rgba(43, 48, 64, 0.95);
    border-radius: 20px;
    padding: 3rem 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

body.dark .login-card {
    background: rgba(43, 48, 64, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Edit Header Wrapper */
.edit-header-wrapper {
    position: relative;
    margin-bottom: 2.5rem;
}

/* Back Button */
.btn-back {
    position: absolute;
    left: 0;
    top: 0;
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 50%, #495057 100%);
    border: none;
    color: #fff;
    padding: 0.75rem 1.5rem;
    font-size: 0.95rem;
    font-weight: 700;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-back:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.6);
    background: linear-gradient(135deg, #5a6268 0%, #495057 50%, #343a40 100%);
    color: #fff;
    text-decoration: none;
}

.btn-back:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(108, 117, 125, 0.4);
}

.btn-back:focus {
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
    outline: none;
}

/* Login Header */
.login-header {
    text-align: center;
    margin-bottom: 0;
}

.login-title {
    font-size: 2rem;
    font-weight: 700;
    color: #e3e6ea;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
}

.login-subtitle {
    color: #9ca3af;
    font-size: 0.95rem;
    margin: 0;
}

/* Form Styles */
.form-group-custom {
    margin-bottom: 1.5rem;
}

.form-label-custom {
    display: block;
    font-weight: 600;
    color: #e3e6ea;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-label-custom i {
    color: #5dade2;
}

.form-control-custom {
    width: 100%;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    border: 2px solid #3b3f45;
    border-radius: 10px;
    background-color: #383e4a;
    color: #e3e6ea;
    transition: all 0.3s ease;
}

.form-control-custom:focus {
    outline: none;
    border-color: #5dade2;
    box-shadow: 0 0 0 0.2rem rgba(93, 173, 226, 0.2);
    background-color: #383e4a;
}

.form-control-custom::placeholder {
    color: #6c757d;
}

.form-control-custom.is-valid {
    border-color: #28a745;
}

.form-control-custom.is-invalid {
    border-color: #dc3545;
}

.form-control-sm-custom {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

/* Input Group Custom */
.input-group-custom {
    display: flex;
    align-items: center;
}

.input-group-text-custom {
    padding: 0.875rem 0.75rem;
    background-color: #383e4a;
    border: 2px solid #3b3f45;
    border-right: none;
    border-radius: 10px 0 0 10px;
    color: #e3e6ea;
    font-weight: 600;
}

.input-group-custom .form-control-custom {
    border-left: none;
    border-radius: 0 10px 10px 0;
}

.input-group-custom .form-control-custom:focus {
    border-left: 2px solid #5dade2;
}

/* Image Preview */
.img-preview {
    max-width: 200px;
    max-height: 150px;
    border-radius: 8px;
    border: 2px solid #3b3f45;
}

/* Tour Card */
.tour-card {
    background: rgba(56, 62, 74, 0.5);
    border: 1px solid #3b3f45;
    border-radius: 12px;
    padding: 1.5rem;
}

.tour-header {
    color: #e3e6ea;
    font-size: 1rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #3b3f45;
}

.tour-body {
    color: #e3e6ea;
}

/* Schedule Date Select Dropdown */
.schedule-date-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23e3e6ea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 12px;
    padding-right: 2.5rem;
}

.schedule-date-select:focus {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235dade2' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
}

/* Schedule Details Container */
.schedule-details {
    background: rgba(56, 62, 74, 0.5);
    border: 1px solid #3b3f45;
    border-radius: 12px;
    padding: 1.5rem;
}

/* Add Schedule Button */
.btn-add-schedule {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
    border: none;
    color: #fff;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-add-schedule:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
}

/* Login Button - Blue/Black Theme */
.btn-login {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
    border: none;
    color: #fff;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 700;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
    text-decoration: none;
    display: inline-block;
    text-align: center;
    flex: 1;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
    background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
    color: #fff;
}

.btn-login:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.4);
}

.btn-login:focus {
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
    outline: none;
}

.btn-secondary-custom {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 50%, #495057 100%);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
}

.btn-secondary-custom:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 50%, #343a40 100%);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.6);
}

/* Alert Custom */
.alert-custom {
    border-radius: 10px;
    border: none;
    margin-bottom: 1.5rem;
}

/* Invalid/Valid Feedback */
.invalid-feedback,
.valid-feedback {
    display: none;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #dc3545;
}

.form-control-custom.is-invalid ~ .invalid-feedback {
    display: block;
}

.was-validated .form-control-custom:invalid ~ .invalid-feedback {
    display: block;
}

/* Light mode overrides for edit attraction page */
body:not(.dark) .login-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%) !important;
}

body:not(.dark) .login-card {
    background: #ffffff !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1) !important;
}

body:not(.dark) .login-title {
    color: #212529 !important;
}

body:not(.dark) .login-subtitle {
    color: #6c757d !important;
}

body:not(.dark) .form-label-custom {
    color: #212529 !important;
}

body:not(.dark) .form-label-custom i {
    color: #007bff !important;
}

body:not(.dark) .form-control-custom {
    background-color: #ffffff !important;
    border: 2px solid #dee2e6 !important;
    color: #212529 !important;
}

body:not(.dark) .form-control-custom:focus {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15) !important;
    background-color: #ffffff !important;
}

body:not(.dark) .form-control-custom::placeholder {
    color: #6c757d !important;
}

body:not(.dark) .input-group-text-custom {
    background-color: #f8f9fa !important;
    border: 2px solid #dee2e6 !important;
    border-right: none !important;
    color: #212529 !important;
}

body:not(.dark) .input-group-custom .form-control-custom:focus {
    border-left: 2px solid #007bff !important;
}

body:not(.dark) .btn-back {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 50%, #495057 100%) !important;
    color: #ffffff !important;
}

body:not(.dark) .btn-back:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 50%, #343a40 100%) !important;
    color: #ffffff !important;
}

body:not(.dark) .tour-card {
    background: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
}

body:not(.dark) .tour-header {
    color: #212529 !important;
    border-bottom: 1px solid #dee2e6 !important;
}

body:not(.dark) .tour-body {
    color: #212529 !important;
}

body:not(.dark) .schedule-details {
    background: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
}

body:not(.dark) .img-preview {
    border: 2px solid #dee2e6 !important;
}

body:not(.dark) .schedule-date-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23212529' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
}

body:not(.dark) .schedule-date-select:focus {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23007bff' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
}

body:not(.dark) .invalid-feedback,
body:not(.dark) .valid-feedback {
    color: #dc3545 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .login-card {
        padding: 2rem 1.5rem;
    }
    
    .login-title {
        font-size: 1.75rem;
    }
    
    .form-group-custom {
        margin-bottom: 1.5rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
    }
    
    .btn-login {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .btn-back {
        position: relative;
        margin-bottom: 1rem;
        width: 100%;
        justify-content: center;
    }
    
    .edit-header-wrapper {
        margin-bottom: 2rem;
    }
    
    .login-header {
        margin-top: 1rem;
    }
}
</style>

<script>
// Track schedule counters per tour
const scheduleCounters = {};

function addNewSchedule(attractionId, tourId) {
    const container = document.getElementById('new-schedules-' + attractionId + '-' + tourId);
    if (!container) return;
    
    if (!scheduleCounters[attractionId]) {
        scheduleCounters[attractionId] = {};
    }
    if (!scheduleCounters[attractionId][tourId]) {
        scheduleCounters[attractionId][tourId] = 0;
    }
    
    const index = scheduleCounters[attractionId][tourId]++;
    const today = new Date().toISOString().split('T')[0];
    
    const scheduleRow = document.createElement('div');
    scheduleRow.className = 'row g-2 mb-2';
    scheduleRow.id = 'schedule-row-' + attractionId + '-' + tourId + '-' + index;
    scheduleRow.innerHTML = `
        <div class="col-md-4">
            <input type="date" class="form-control-custom form-control-sm-custom" 
                   name="tour_schedules[${tourId}][new][${index}][tour_date]" 
                   min="${today}" required>
        </div>
        <div class="col-md-3">
            <input type="time" class="form-control-custom form-control-sm-custom" 
                   name="tour_schedules[${tourId}][new][${index}][tour_time]" required>
        </div>
        <div class="col-md-3">
            <input type="number" min="1" class="form-control-custom form-control-sm-custom" 
                   placeholder="Available spaces" 
                   name="tour_schedules[${tourId}][new][${index}][available_spots]" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn-add-schedule w-100" 
                    onclick="removeScheduleRow('schedule-row-${attractionId}-${tourId}-${index}')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.appendChild(scheduleRow);
}

function removeScheduleRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
    }
}

function showScheduleDetails(attractionId, tourId, scheduleId) {
    const detailsDiv = document.getElementById('schedule-details-' + attractionId + '-' + tourId);
    const selectElement = document.getElementById('schedule-select-' + attractionId + '-' + tourId);
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    if (scheduleId && selectedOption) {
        const date = selectedOption.getAttribute('data-date');
        const time = selectedOption.getAttribute('data-time');
        const spots = selectedOption.getAttribute('data-spots');
        
        // Show the details div
        detailsDiv.style.display = 'block';
        
        // Set the values
        document.getElementById('schedule-date-' + attractionId + '-' + tourId).value = date;
        document.getElementById('schedule-time-' + attractionId + '-' + tourId).value = time;
        document.getElementById('schedule-spots-' + attractionId + '-' + tourId).value = spots;
        
        // Set the name attribute with the correct schedule ID
        document.getElementById('schedule-spots-' + attractionId + '-' + tourId).name = 
            'tour_schedules[' + tourId + '][existing][' + scheduleId + '][available_spots]';
        
        // Store the schedule ID
        document.getElementById('selected-schedule-id-' + attractionId + '-' + tourId).value = scheduleId;
    } else {
        // Hide the details div
        detailsDiv.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            form.classList.add('was-validated');
        }, false);
    }
});
</script>

