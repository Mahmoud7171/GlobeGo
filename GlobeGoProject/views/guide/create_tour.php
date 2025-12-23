<?php
// Guide create tour view.
// Expects: $error_message, $success_message, $attractions
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Create New Tour</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Tour Information</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Tour Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?php echo isset($_POST['title']) ? $_POST['title'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Historical" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Historical') ? 'selected' : ''; ?>>Historical</option>
                                        <option value="Food Tour" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Food Tour') ? 'selected' : ''; ?>>Food Tour</option>
                                        <option value="Walking Tour" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Walking Tour') ? 'selected' : ''; ?>>Walking Tour</option>
                                        <option value="Adventure" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Adventure') ? 'selected' : ''; ?>>Adventure</option>
                                        <option value="Cultural" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Cultural') ? 'selected' : ''; ?>>Cultural</option>
                                        <option value="Nature" <?php echo (isset($_POST['category']) && $_POST['category'] === 'Nature') ? 'selected' : ''; ?>>Nature</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Tour Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label">Price per Person ($) *</label>
                                    <input type="number" class="form-control" id="price" name="price" 
                                           step="0.01" min="0" value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="duration_hours" class="form-label">Duration (hours) *</label>
                                    <input type="number" class="form-control" id="duration_hours" name="duration_hours" 
                                           min="1" max="24" value="<?php echo isset($_POST['duration_hours']) ? $_POST['duration_hours'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="max_participants" class="form-label">Max Participants *</label>
                                    <input type="number" class="form-control" id="max_participants" name="max_participants" 
                                           min="1" max="50" value="<?php echo isset($_POST['max_participants']) ? $_POST['max_participants'] : ''; ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="meeting_point" class="form-label">Meeting Point *</label>
                            <input type="text" class="form-control" id="meeting_point" name="meeting_point" 
                                   value="<?php echo isset($_POST['meeting_point']) ? $_POST['meeting_point'] : ''; ?>" required>
                            <small class="form-text text-muted">Provide specific location details (e.g., "In front of the main entrance")</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="attraction_id" class="form-label">Related Attraction (Optional)</label>
                            <select class="form-select" id="attraction_id" name="attraction_id">
                                <option value="">Select an attraction</option>
                                <?php foreach ($attractions as $attraction_item): ?>
                                    <option value="<?php echo $attraction_item['id']; ?>" 
                                            <?php echo (isset($_POST['attraction_id']) && $_POST['attraction_id'] == $attraction_item['id']) ? 'selected' : ''; ?>>
                                        <?php echo $attraction_item['name'] . ' - ' . $attraction_item['location']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="image_url" class="form-label">Tour Image URL (Optional)</label>
                            <input type="url" class="form-control" id="image_url" name="image_url" 
                                   value="<?php echo isset($_POST['image_url']) ? $_POST['image_url'] : ''; ?>">
                            <small class="form-text text-muted">Provide a direct link to an image (recommended size: 800x600px)</small>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">Create Tour</button>
                            <a href="../dashboard.php" class="btn btn-outline-secondary btn-lg">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>Tips for Creating Great Tours</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-lightbulb text-warning"></i> Write a compelling title that highlights what makes your tour special</li>
                        <li class="mb-2"><i class="fas fa-lightbulb text-warning"></i> Include detailed descriptions of what tourists will see and experience</li>
                        <li class="mb-2"><i class="fas fa-lightbulb text-warning"></i> Set competitive prices based on similar tours in your area</li>
                        <li class="mb-2"><i class="fas fa-lightbulb text-warning"></i> Provide clear meeting point instructions</li>
                        <li class="mb-2"><i class="fas fa-lightbulb text-warning"></i> Use high-quality images to showcase your tour</li>
                        <li class="mb-2"><i class="fas fa-lightbulb text-warning"></i> Keep group sizes reasonable for better experience</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>What's Next?</h5>
                </div>
                <div class="card-body">
                    <p>After creating your tour, you'll need to:</p>
                    <ol>
                        <li>Add available dates and times</li>
                        <li>Wait for admin approval</li>
                        <li>Start receiving bookings!</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const price = parseFloat(document.getElementById('price').value);
        const duration = parseInt(document.getElementById('duration_hours').value);
        const maxParticipants = parseInt(document.getElementById('max_participants').value);
        
        if (price <= 0) {
            e.preventDefault();
            GlobeGo.showAlert('Price must be greater than 0.', 'danger');
            return;
        }
        
        if (duration <= 0 || duration > 24) {
            e.preventDefault();
            GlobeGo.showAlert('Duration must be between 1 and 24 hours.', 'danger');
            return;
        }
        
        if (maxParticipants <= 0 || maxParticipants > 50) {
            e.preventDefault();
            GlobeGo.showAlert('Maximum participants must be between 1 and 50.', 'danger');
            return;
        }
    });

    // Image preview
    const imageUrlInput = document.getElementById('image_url');
    const imagePreview = document.createElement('img');
    imagePreview.className = 'img-fluid mt-2';
    imagePreview.style.maxHeight = '200px';
    imagePreview.style.display = 'none';
    imageUrlInput.parentNode.appendChild(imagePreview);

    imageUrlInput.addEventListener('input', function() {
        if (this.value) {
            imagePreview.src = this.value;
            imagePreview.style.display = 'block';
            imagePreview.onerror = function() {
                this.style.display = 'none';
            };
        } else {
            imagePreview.style.display = 'none';
        }
    });
});
</script>


