<?php
// View for tour details and inline booking form.
// Expects:
// - $tour_id
// - $tour_details
// - $tour_schedules
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Tour Images Gallery -->
            <div class="card mb-4">
                <?php 
                // Handle both relative and absolute image URLs
                $tour_image_url = $tour_details['image_url'] ?: 'assets/images/default-tour.jpg';
                if (!filter_var($tour_image_url, FILTER_VALIDATE_URL) && !empty($tour_details['image_url'])) {
                    // It's a relative path
                    if (substr($tour_image_url, 0, 1) === '/') {
                        // Absolute path from root
                        $tour_image_url = SITE_URL . $tour_image_url;
                    } else {
                        // Relative path - prepend SITE_URL
                        $tour_image_url = SITE_URL . '/' . ltrim($tour_image_url, '/');
                    }
                }
                
                // Function to find additional images
                function findAdditionalImages($main_image_url) {
                    $images = [$main_image_url]; // Start with main image
                    
                    // Extract base path and filename from URL
                    $parsed_url = parse_url($main_image_url);
                    if (isset($parsed_url['path'])) {
                        $image_path = $parsed_url['path'];
                    } else {
                        $image_path = $main_image_url;
                    }
                    
                    // Remove leading slash
                    $image_path = ltrim($image_path, '/');
                    // Remove 'GlobeGoProject/' if it's in the path (handle both with and without)
                    $image_path = preg_replace('#^GlobeGoProject/?#', '', $image_path);
                    
                    // Get directory and filename
                    $path_info = pathinfo($image_path);
                    $directory = isset($path_info['dirname']) ? $path_info['dirname'] : '';
                    // Handle case where dirname returns '.' (current directory) or empty
                    if ($directory === '.' || $directory === '' || $directory === 'GlobeGoProject') {
                        $directory = 'images';
                    }
                    $filename = isset($path_info['filename']) ? $path_info['filename'] : '';
                    $extension = isset($path_info['extension']) ? '.' . $path_info['extension'] : '';
                    
                    // Safety check
                    if (empty($filename)) {
                        return $images; // Can't find variants without a filename
                    }
                    
                    // Check for numbered variants (e.g., MachuPicchu2, MachuPicchu3, etc.)
                    // Get the project root directory (go up from views/tours/ to root)
                    $project_root = dirname(dirname(__DIR__));
                    $base_path = $project_root . DIRECTORY_SEPARATOR . $directory;
                    
                    // Normalize path separators for Windows
                    $base_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $base_path);
                    
                    for ($i = 2; $i <= 10; $i++) {
                        $variant_filename = $filename . $i . $extension;
                        $variant_path = $base_path . DIRECTORY_SEPARATOR . $variant_filename;
                        
                        if (file_exists($variant_path)) {
                            // Build URL for variant - use forward slashes for URLs
                            $variant_url = SITE_URL . '/' . str_replace('\\', '/', $directory) . '/' . $variant_filename;
                            $images[] = $variant_url;
                        }
                    }
                    
                    return $images;
                }
                
                $tour_images = findAdditionalImages($tour_image_url);
                $image_count = count($tour_images);
                ?>
                
                <div class="row g-2">
                    <!-- Main Image Display -->
                    <div class="<?php echo $image_count > 1 ? 'col-md-9' : 'col-12'; ?>">
                        <div class="position-relative" style="height: 500px; overflow: hidden; border-radius: 8px; background: #000;">
                            <div id="imageContainer" style="position: relative; width: 100%; height: 100%;">
                                <?php foreach ($tour_images as $index => $img_url): ?>
                                    <img class="main-tour-image <?php echo $index === 0 ? 'active' : ''; ?>" 
                                         src="<?php echo htmlspecialchars($img_url); ?>" 
                                         alt="<?php echo htmlspecialchars($tour_details['title']); ?> - Image <?php echo $index + 1; ?>" 
                                         data-index="<?php echo $index; ?>"
                                         style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: <?php echo $index === 0 ? '1' : '0'; ?>; transition: opacity 0.8s ease-in-out;"
                                         onerror="this.onerror=null; this.src='<?php echo SITE_URL; ?>/assets/images/default-tour.jpg';">
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if ($image_count > 1): ?>
                                <!-- Image Counter Badge -->
                                <div class="position-absolute bottom-0 end-0 m-3">
                                    <span class="badge bg-primary px-3 py-2" style="font-size: 0.9rem; backdrop-filter: blur(5px); background: rgba(13, 110, 253, 0.9);">
                                        <i class="fas fa-images me-1"></i><?php echo $image_count; ?> photos
                                    </span>
                                </div>
                                
                                <!-- Progress Indicator -->
                                <div id="progressBar" class="position-absolute bottom-0 start-0 w-100" style="height: 3px; background: rgba(255,255,255,0.2);">
                                    <div id="progressFill" style="height: 100%; background: linear-gradient(90deg, #0d6efd, #0dcaf0); width: 0%; transition: width 0.1s linear;"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Thumbnail Gallery (Right Side) -->
                    <?php if ($image_count > 1): ?>
                    <div class="col-md-3">
                        <div class="d-flex flex-column gap-2" style="height: 500px;">
                            <?php foreach ($tour_images as $index => $img_url): ?>
                                <div class="thumbnail-container position-relative" 
                                     data-image-index="<?php echo $index; ?>"
                                     style="cursor: pointer; border: 2px solid <?php echo $index === 0 ? '#0d6efd' : 'transparent'; ?>; border-radius: 8px; overflow: hidden; transition: all 0.3s ease;"
                                     onmouseover="this.style.borderColor='#0d6efd'; this.style.transform='scale(1.02)';"
                                     onmouseout="if(<?php echo $index; ?> !== currentImageIndex) { this.style.borderColor='transparent'; this.style.transform='scale(1)'; }">
                                    <img src="<?php echo htmlspecialchars($img_url); ?>" 
                                         class="w-100" 
                                         alt="Thumbnail <?php echo $index + 1; ?>"
                                         style="height: <?php echo 500 / min($image_count, 4) - 8; ?>px; object-fit: cover; display: block;"
                                         onerror="this.onerror=null; this.src='<?php echo SITE_URL; ?>/assets/images/default-tour.jpg';">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <script>
            // Gallery Slideshow Functionality with Enhanced Visual Effects
            (function() {
                'use strict';
                
                const mainImages = document.querySelectorAll('.main-tour-image');
                const thumbnails = document.querySelectorAll('.thumbnail-container');
                const progressFill = document.getElementById('progressFill');
                const imageCount = mainImages.length;
                
                if (imageCount <= 1 || thumbnails.length === 0) {
                    return; // Exit if only one image or no thumbnails
                }
                
                let currentImageIndex = 0;
                let autoRotateInterval = null;
                let progressInterval = null;
                const rotationSpeed = 5000; // 5 seconds per image (increased from 2 seconds)
                let progress = 0;
                
                // Make currentImageIndex available globally for hover effects
                window.currentImageIndex = currentImageIndex;
                
                // Function to change image with smooth crossfade
                function changeImage(index, instant = false) {
                    if (index < 0 || index >= imageCount) return;
                    
                    // Update main images with crossfade effect
                    mainImages.forEach((img, i) => {
                        if (i === index) {
                            img.style.opacity = '1';
                            img.classList.add('active');
                        } else {
                            img.style.opacity = '0';
                            img.classList.remove('active');
                        }
                    });
                    
                    // Update active thumbnail with smooth transition
                    thumbnails.forEach((thumb, i) => {
                        if (i === index) {
                            thumb.style.borderColor = '#0d6efd';
                            thumb.style.transform = 'scale(1.05)';
                            thumb.style.boxShadow = '0 4px 12px rgba(13, 110, 253, 0.4)';
                        } else {
                            thumb.style.borderColor = 'transparent';
                            thumb.style.transform = 'scale(1)';
                            thumb.style.boxShadow = 'none';
                        }
                    });
                    
                    currentImageIndex = index;
                    window.currentImageIndex = currentImageIndex;
                    
                    // Reset progress
                    if (!instant) {
                        progress = 0;
                        updateProgress();
                    }
                }
                
                // Function to update progress bar
                function updateProgress() {
                    if (progressFill) {
                        progressFill.style.width = (progress / rotationSpeed * 100) + '%';
                    }
                }
                
                // Function to go to next image
                function nextImage() {
                    currentImageIndex = (currentImageIndex + 1) % imageCount;
                    changeImage(currentImageIndex);
                }
                
                // Function to start auto-rotation with progress bar
                function startAutoRotate() {
                    stopAutoRotate(); // Clear any existing intervals
                    
                    progress = 0;
                    updateProgress();
                    
                    // Progress bar animation
                    const startTime = Date.now();
                    progressInterval = setInterval(function() {
                        const elapsed = Date.now() - startTime;
                        progress = elapsed % rotationSpeed;
                        updateProgress();
                    }, 50);
                    
                    // Image rotation
                    autoRotateInterval = setInterval(function() {
                        nextImage();
                    }, rotationSpeed);
                }
                
                // Function to stop auto-rotation
                function stopAutoRotate() {
                    if (autoRotateInterval) {
                        clearInterval(autoRotateInterval);
                        autoRotateInterval = null;
                    }
                    if (progressInterval) {
                        clearInterval(progressInterval);
                        progressInterval = null;
                    }
                    if (progressFill) {
                        progressFill.style.width = '0%';
                    }
                    progress = 0;
                }
                
                // Click functionality for thumbnails
                thumbnails.forEach((thumbnail, index) => {
                    thumbnail.addEventListener('click', function() {
                        changeImage(index, true);
                        stopAutoRotate();
                        setTimeout(startAutoRotate, 300);
                    });
                });
                
                // Pause auto-rotation on hover over gallery
                const galleryContainer = document.querySelector('.col-md-3');
                if (galleryContainer) {
                    galleryContainer.addEventListener('mouseenter', stopAutoRotate);
                    galleryContainer.addEventListener('mouseleave', startAutoRotate);
                }
                
                // Pause auto-rotation on hover over main image container
                const imageContainer = document.getElementById('imageContainer');
                if (imageContainer) {
                    imageContainer.addEventListener('mouseenter', stopAutoRotate);
                    imageContainer.addEventListener('mouseleave', startAutoRotate);
                }
                
                // Keyboard navigation
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        currentImageIndex = (currentImageIndex - 1 + imageCount) % imageCount;
                        changeImage(currentImageIndex, true);
                        stopAutoRotate();
                        setTimeout(startAutoRotate, 300);
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        currentImageIndex = (currentImageIndex + 1) % imageCount;
                        changeImage(currentImageIndex, true);
                        stopAutoRotate();
                        setTimeout(startAutoRotate, 300);
                    }
                });
                
                // Start auto-rotation after page load
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        startAutoRotate();
                    }, 800);
                });
                
                // Fallback: Start immediately if load event already fired
                if (document.readyState === 'complete') {
                    setTimeout(function() {
                        startAutoRotate();
                    }, 800);
                }
            })();
            </script>
            
            <style>
            .thumbnail-container {
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .thumbnail-container:hover {
                box-shadow: 0 6px 16px rgba(13, 110, 253, 0.3);
                transform: scale(1.03) !important;
            }
            
            .main-tour-image {
                will-change: opacity;
            }
            
            #progressBar {
                z-index: 10;
            }
            
            #progressFill {
                box-shadow: 0 0 10px rgba(13, 110, 253, 0.6);
            }
            
            /* Smooth image transitions */
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            .main-tour-image.active {
                animation: fadeIn 0.8s ease-in-out;
            }
            </style>

            <!-- Tour Details -->
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="card-title"><?php echo $tour_details['title']; ?></h1>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Duration:</strong> <?php echo $tour_details['duration_hours']; ?> hours</p>
                            <p><strong>Category:</strong> <?php echo $tour_details['category']; ?></p>
                            <p><strong>Meeting Point:</strong> <?php echo $tour_details['meeting_point']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Max Participants:</strong> <?php echo $tour_details['max_participants']; ?> people</p>
                            <p><strong>Location:</strong> <?php echo $tour_details['location']; ?></p>
                            <p><strong>Price:</strong> <span class="price-display"><?php echo formatPrice($tour_details['price']); ?></span> per person</p>
                        </div>
                    </div>

                    <h4>Description</h4>
                    <p><?php echo nl2br($tour_details['description']); ?></p>

                    <?php if ($tour_details['attraction_name']): ?>
                    <h4>Attraction</h4>
                    <p><strong><?php echo $tour_details['attraction_name']; ?></strong></p>
                    <p><?php echo $tour_details['attraction_description']; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Reviews <span id="review-count" class="badge bg-secondary ms-2">0</span></h5>
                </div>
                <div class="card-body">
                    <div id="reviews-container" style="min-height: 150px;">
                        <!-- Reviews will be dynamically loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Guide Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Your Guide</h5>
                </div>
                <div class="card-body text-center">
                    <img src="<?php echo $tour_details['profile_image'] ?: 'assets/images/default-avatar.jpg'; ?>" 
                         class="profile-image mb-3" alt="Guide Photo">
                    <h6><?php echo $tour_details['first_name'] . ' ' . $tour_details['last_name']; ?></h6>
                    <?php if (isset($tour_details['verified']) && $tour_details['verified']): ?>
                        <p class="text-success"><i class="fas fa-check-circle"></i> Verified Guide</p>
                    <?php endif; ?>
                    
                    <?php if ($tour_details['languages']): ?>
                        <p><strong>Languages:</strong> <?php echo $tour_details['languages']; ?></p>
                    <?php endif; ?>
                    
                    <?php if ($tour_details['bio']): ?>
                        <p class="small"><?php echo $tour_details['bio']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Debug: Check if fake reviews function exists
console.log('Tour Details Page Loaded');
console.log('initFakeReviews function exists:', typeof initFakeReviews === 'function');
console.log('Reviews container exists:', document.getElementById('reviews-container') !== null);

// Fallback: If initFakeReviews didn't run, try to call it manually
setTimeout(() => {
    if (typeof initFakeReviews === 'function') {
        const container = document.getElementById('reviews-container');
        if (container && container.innerHTML.trim() === '') {
            console.log('Manually calling initFakeReviews...');
            initFakeReviews();
        }
    } else {
        console.error('initFakeReviews function not found! Check if script.js loaded correctly.');
    }
}, 1000);
</script>


