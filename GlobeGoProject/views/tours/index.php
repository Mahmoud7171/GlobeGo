<?php
// View for listing tours. Expects:
// - $tours (array)
// - $filters (array)
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Discover Amazing Tours</h1>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="filter-container">
                <form method="GET" action="" class="tour-filter-form">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="location" class="form-label fw-semibold mb-2">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>Destination
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="location" name="location" 
                                       value="<?php echo $_GET['location'] ?? ''; ?>" placeholder="Where do you want to go?">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="category" class="form-label fw-semibold mb-2">
                                <i class="fas fa-list me-2 text-primary"></i>Category
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-tags text-muted"></i>
                                </span>
                                <select class="form-select border-start-0 ps-0" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <option value="Historical" <?php echo (($_GET['category'] ?? '') === 'Historical') ? 'selected' : ''; ?>>Historical</option>
                                    <option value="Food Tour" <?php echo (($_GET['category'] ?? '') === 'Food Tour') ? 'selected' : ''; ?>>Food Tour</option>
                                    <option value="Walking Tour" <?php echo (($_GET['category'] ?? '') === 'Walking Tour') ? 'selected' : ''; ?>>Walking Tour</option>
                                    <option value="Adventure" <?php echo (($_GET['category'] ?? '') === 'Adventure') ? 'selected' : ''; ?>>Adventure</option>
                                    <option value="Cultural" <?php echo (($_GET['category'] ?? '') === 'Cultural') ? 'selected' : ''; ?>>Cultural</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="max_price" class="form-label fw-semibold mb-2">
                                <i class="fas fa-dollar-sign me-2 text-primary"></i>Max Price
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-money-bill-wave text-muted"></i>
                                </span>
                                <input type="number" class="form-control border-start-0 ps-0" id="max_price" name="max_price" 
                                       value="<?php echo $_GET['max_price'] ?? ''; ?>" placeholder="Maximum price">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                                <a href="tours.php" class="btn btn-outline-light">
                                    <i class="fas fa-times me-2"></i>Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tours Grid -->
    <div class="row">
        <?php if (empty($tours)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No tours found</h4>
                    <p>Try adjusting your filters or <a href="guide/create-tour.php">become a guide</a> to create the first tour!</p>
                </div>
            </div>
        <?php else: ?>
            <?php 
            $tour_count = 0;
            $total_tours = count($tours);
            foreach ($tours as $tour_item): 
                $tour_count++;
                $is_hidden = $tour_count > 6 ? 'tour-item-hidden' : '';
            ?>
            <div class="col-lg-4 col-md-6 mb-4 tour-item <?php echo $is_hidden; ?>" data-tour-index="<?php echo $tour_count; ?>">
                <div class="card h-100 shadow-sm">
                    <?php 
                    // Handle both relative and absolute image URLs
                    $tour_image_url = $tour_item['image_url'] ?: 'assets/images/default-tour.jpg';
                    if (!filter_var($tour_image_url, FILTER_VALIDATE_URL) && !empty($tour_image_url)) {
                        // It's a relative path
                        if (substr($tour_image_url, 0, 1) === '/') {
                            // Absolute path from root
                            $tour_image_url = SITE_URL . $tour_image_url;
                        } else {
                            // Relative path - prepend SITE_URL
                            $tour_image_url = SITE_URL . '/' . ltrim($tour_image_url, '/');
                        }
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($tour_image_url); ?>" 
                         class="card-img-top" alt="<?php echo htmlspecialchars($tour_item['title']); ?>" style="height: 200px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='<?php echo SITE_URL; ?>/assets/images/default-tour.jpg';">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $tour_item['title']; ?></h5>
                        <p class="card-text flex-grow-1"><?php echo substr($tour_item['description'], 0, 100) . '...'; ?></p>
                        
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt"></i> <?php echo !empty($tour_item['location']) ? htmlspecialchars($tour_item['location']) : 'Location not specified'; ?><br>
                                <i class="fas fa-user"></i> <?php echo $tour_item['first_name'] . ' ' . $tour_item['last_name']; ?>
                                <?php if (isset($tour_item['verified']) && $tour_item['verified']): ?>
                                    <i class="fas fa-check-circle text-success" title="Verified Guide"></i>
                                <?php endif; ?>
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-price fw-bold"><?php echo formatPrice($tour_item['price']); ?></span>
                            <small class="text-muted d-flex align-items-center gap-3">
                                <span><i class="fas fa-clock"></i> <?php echo $tour_item['duration_hours']; ?>h</span>
                                <span><i class="fas fa-users"></i> Max <?php echo $tour_item['max_participants']; ?></span>
                            </small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="tour-details.php?id=<?php echo $tour_item['id']; ?>" class="btn btn-primary flex-grow-1">View Details</a>
                            <?php if (!isAdmin()): ?>
                                <?php if (isLoggedIn() && isTourist()): ?>
                                    <a href="reserve-tour.php?id=<?php echo $tour_item['id']; ?>" class="btn btn-warning">Reserve</a>
                                <?php else: ?>
                                    <a href="auth/login.php?redirect=reserve-tour.php?id=<?php echo $tour_item['id']; ?>" class="btn btn-warning">Reserve</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if ($total_tours > 6): ?>
                <div class="col-12 text-center mt-4" id="tour-controls">
                    <button class="btn btn-outline-primary" id="loadMoreTours">
                        <i class="fas fa-chevron-down me-2"></i>Load More Tours (<?php echo $total_tours - 6; ?> more)
                    </button>
                    <button class="btn btn-outline-secondary d-none" id="showLessTours">
                        <i class="fas fa-chevron-up me-2"></i>Show Less
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</div>

<style>
    .tour-item-hidden {
        display: none !important;
    }
    
    /* Enhanced Filter Section Styling */
    .filter-container {
        background: #ffffff;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.1);
        overflow: visible;
    }
    
    /* Dark mode filter container */
    body.dark .filter-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .tour-filter-form .form-label {
        color: #495057;
        font-size: 0.85rem;
        letter-spacing: 0.3px;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    /* Dark mode support for labels */
    body.dark .tour-filter-form .form-label {
        color: #e9ecef;
    }
    
    /* Ensure labels are visible in light mode */
    body:not(.dark) .tour-filter-form .form-label {
        color: #212529 !important;
    }
    
    .tour-filter-form .input-group {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .tour-filter-form .input-group:focus-within {
        box-shadow: 0 4px 16px rgba(13, 110, 253, 0.3);
        transform: translateY(-2px);
    }
    
    .tour-filter-form .input-group-text {
        border: none;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }
    
    /* Dark mode input-group-text (icon boxes) */
    body.dark .tour-filter-form .input-group-text {
        background-color: #2d3748 !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #e9ecef !important;
    }
    
    body.dark .tour-filter-form .input-group-text i {
        color: #adb5bd !important;
    }
    
    .tour-filter-form .form-control,
    .tour-filter-form .form-select {
        border: none;
        padding: 0.5rem 0.75rem;
        background-color: #fff;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        color: #212529;
    }
    
    .tour-filter-form .form-control:focus,
    .tour-filter-form .form-select:focus {
        box-shadow: none;
        background-color: #fff;
        border-color: transparent;
    }
    
    /* Dark mode form controls */
    body.dark .tour-filter-form .form-control,
    body.dark .tour-filter-form .form-select {
        background-color: #2d3748 !important;
        color: #e9ecef !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    body.dark .tour-filter-form .form-control:focus,
    body.dark .tour-filter-form .form-select:focus {
        background-color: #374151 !important;
        border-color: rgba(13, 110, 253, 0.5) !important;
        color: #ffffff !important;
    }
    
    body.dark .tour-filter-form .form-control::placeholder {
        color: #9ca3af !important;
    }
    
    .tour-filter-form .btn-primary {
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
        transition: all 0.3s ease;
        font-weight: 600;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        height: auto;
    }
    
    .tour-filter-form .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.5);
    }
    
    .tour-filter-form .btn-outline-light {
        border: 2px solid rgba(0, 0, 0, 0.2);
        color: #212529;
        background-color: #ffffff;
        font-weight: 600;
        transition: all 0.3s ease;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        height: auto;
    }
    
    .tour-filter-form .btn-outline-light:hover {
        background-color: #f8f9fa;
        border-color: rgba(0, 0, 0, 0.3);
        color: #212529;
        transform: translateY(-2px);
    }
    
    /* Dark mode clear button */
    body.dark .tour-filter-form .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        background-color: transparent;
    }
    
    body.dark .tour-filter-form .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        color: #fff;
    }
    
    @media (max-width: 768px) {
        .filter-container {
            padding: 1.5rem;
        }
        
        .tour-filter-form .btn-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load More / Show Less Tours Functionality
    const loadMoreBtn = document.getElementById('loadMoreTours');
    const showLessBtn = document.getElementById('showLessTours');
    const tourItems = document.querySelectorAll('.tour-item');
    
    if (loadMoreBtn && showLessBtn && tourItems.length > 6) {
        loadMoreBtn.addEventListener('click', function() {
            // Show all hidden tours
            tourItems.forEach(function(item) {
                item.classList.remove('tour-item-hidden');
            });
            
            // Hide "Load More" and show "Show Less"
            loadMoreBtn.classList.add('d-none');
            showLessBtn.classList.remove('d-none');
        });
        
        showLessBtn.addEventListener('click', function() {
            // Hide tours beyond the first 6
            tourItems.forEach(function(item, index) {
                if (index >= 6) {
                    item.classList.add('tour-item-hidden');
                }
            });
            
            // Show "Load More" and hide "Show Less"
            loadMoreBtn.classList.remove('d-none');
            showLessBtn.classList.add('d-none');
            
            // Scroll to top of tours section
            document.querySelector('.row').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }
});
</script>


