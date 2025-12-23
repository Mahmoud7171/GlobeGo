<?php
// Admin attractions view
// Expects: $attractions (array), $categories (array)
?>
<div class="container mt-4">
    <?php if (isset($_SESSION['admin_message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['admin_message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['admin_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
        unset($_SESSION['admin_message']);
        unset($_SESSION['admin_message_type']);
        ?>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-map-marker-alt me-2"></i>Manage Attractions</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttractionModal">
                    <i class="fas fa-plus me-2"></i>Add New Attraction
                </button>
            </div>
        </div>
    </div>

    <!-- Attractions Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Attractions (<?php echo isset($total_items) ? $total_items : count($attractions ?? []); ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($attractions)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                    <h5>No attractions found</h5>
                    <p class="mb-0">Get started by adding your first attraction.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Category</th>
                                <th>Rating</th>
                                <th>Related Tours</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attractions as $attr): ?>
                                <tr>
                                    <td><?php echo $attr['id']; ?></td>
                                    <td>
                                        <?php if (!empty($attr['image_url'])): ?>
                                            <?php 
                                            // Handle both relative and absolute image URLs
                                            $image_url = $attr['image_url'];
                                            // Check if it's already a full URL
                                            if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
                                                // It's a relative path
                                                if (substr($image_url, 0, 1) === '/') {
                                                    // Absolute path from root
                                                    $image_url = SITE_URL . $image_url;
                                                } else {
                                                    // Relative path - prepend SITE_URL
                                                    $image_url = SITE_URL . '/' . ltrim($image_url, '/');
                                                }
                                            }
                                            ?>
                                            <img src="<?php echo htmlspecialchars($image_url); ?>" 
                                                 alt="<?php echo htmlspecialchars($attr['name']); ?>" 
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;"
                                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'60\' height=\'60\'%3E%3Crect width=\'60\' height=\'60\' fill=\'%236c757d\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'white\' font-size=\'20\'%3E%3Ctspan%3E%26%23128247%3B%3C/tspan%3E%3C/text%3E%3C/svg%3E';">
                                        <?php else: ?>
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px; border-radius: 4px;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($attr['name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($attr['location']); ?></td>
                                    <td>
                                        <?php if ($attr['category']): ?>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($attr['category']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($attr['rating'] > 0): ?>
                                            <span class="text-warning">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star<?php echo $i <= $attr['rating'] ? '' : '-o'; ?>"></i>
                                                <?php endfor; ?>
                                            </span>
                                            <small class="text-muted">(<?php echo $attr['rating']; ?>)</small>
                                        <?php else: ?>
                                            <span class="text-muted">No ratings</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo count($attr['related_tours'] ?? []); ?> tour(s)</span>
                                    </td>
                                    <td><?php echo isset($attr['created_at']) ? date('M j, Y', strtotime($attr['created_at'])) : 'N/A'; ?></td>
                                    <td onclick="event.stopPropagation();">
                                        <div class="d-flex gap-2">
                                            <a href="?id=<?php echo $attr['id']; ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="?delete=<?php echo $attr['id']; ?>" 
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to delete this attraction?<?php echo !empty($attr['related_tours']) ? ' Note: This attraction has ' . count($attr['related_tours']) . ' associated tour(s).' : ''; ?>');">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Collapsible row for editing attraction -->
                                <tr class="collapse" id="collapseAttraction<?php echo $attr['id']; ?>" data-bs-parent="tbody">
                                    <td colspan="9">
                                        <div class="edit-form-container">
                                            <h6 class="edit-form-title"><i class="fas fa-edit me-2"></i>Edit Attraction</h6>
                                            <form method="POST" action="" class="edit-form">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="attraction_id" value="<?php echo $attr['id']; ?>">
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="edit_name_<?php echo $attr['id']; ?>" class="form-label-edit">
                                                            <i class="fas fa-tag me-2"></i>Name <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control-edit" id="edit_name_<?php echo $attr['id']; ?>" 
                                                               name="name" value="<?php echo htmlspecialchars($attr['name']); ?>" 
                                                               placeholder="Enter attraction name"
                                                               required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="edit_location_<?php echo $attr['id']; ?>" class="form-label-edit">
                                                            <i class="fas fa-map-marker-alt me-2"></i>Location <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control-edit" id="edit_location_<?php echo $attr['id']; ?>" 
                                                               name="location" value="<?php echo htmlspecialchars($attr['location']); ?>" 
                                                               placeholder="Enter location"
                                                               required>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="edit_description_<?php echo $attr['id']; ?>" class="form-label-edit">
                                                        <i class="fas fa-align-left me-2"></i>Description <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea class="form-control-edit" id="edit_description_<?php echo $attr['id']; ?>" 
                                                              name="description" rows="4" 
                                                              placeholder="Enter attraction description"
                                                              required><?php echo htmlspecialchars($attr['description']); ?></textarea>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="edit_category_<?php echo $attr['id']; ?>" class="form-label-edit">
                                                            <i class="fas fa-folder me-2"></i>Category
                                                        </label>
                                                        <input type="text" class="form-control-edit" id="edit_category_<?php echo $attr['id']; ?>" 
                                                               name="category" value="<?php echo htmlspecialchars($attr['category'] ?? ''); ?>" 
                                                               placeholder="Enter category"
                                                               list="categoryList<?php echo $attr['id']; ?>">
                                                        <datalist id="categoryList<?php echo $attr['id']; ?>">
                                                            <?php foreach ($categories as $cat): ?>
                                                                <option value="<?php echo htmlspecialchars($cat); ?>">
                                                            <?php endforeach; ?>
                                                        </datalist>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="edit_image_url_<?php echo $attr['id']; ?>" class="form-label-edit">
                                                            <i class="fas fa-image me-2"></i>Image URL or Path
                                                        </label>
                                                        <input type="text" class="form-control-edit" id="edit_image_url_<?php echo $attr['id']; ?>" 
                                                               name="image_url" value="<?php echo htmlspecialchars($attr['image_url'] ?? ''); ?>"
                                                               placeholder="images/example.jpg or https://example.com/image.jpg">
                                                        <?php if (!empty($attr['image_url'])): ?>
                                                            <?php 
                                                            // Handle both relative and absolute image URLs for preview
                                                            $preview_url = $attr['image_url'];
                                                            if (!filter_var($preview_url, FILTER_VALIDATE_URL)) {
                                                                if (substr($preview_url, 0, 1) === '/') {
                                                                    $preview_url = SITE_URL . $preview_url;
                                                                } else {
                                                                    $preview_url = SITE_URL . '/' . ltrim($preview_url, '/');
                                                                }
                                                            }
                                                            ?>
                                                            <small class="text-muted-edit d-block mt-2">Current image:</small>
                                                            <img src="<?php echo htmlspecialchars($preview_url); ?>" 
                                                                 alt="Current image" class="img-preview-edit mt-2"
                                                                 onerror="this.style.display='none';">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="edit_latitude_<?php echo $attr['id']; ?>" class="form-label-edit">
                                                            <i class="fas fa-globe me-2"></i>Latitude
                                                        </label>
                                                        <input type="number" step="any" class="form-control-edit" id="edit_latitude_<?php echo $attr['id']; ?>" 
                                                               name="latitude" value="<?php echo htmlspecialchars($attr['latitude'] ?? ''); ?>"
                                                               placeholder="Enter latitude">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="edit_longitude_<?php echo $attr['id']; ?>" class="form-label-edit">
                                                            <i class="fas fa-globe me-2"></i>Longitude
                                                        </label>
                                                        <input type="number" step="any" class="form-control-edit" id="edit_longitude_<?php echo $attr['id']; ?>" 
                                                               name="longitude" value="<?php echo htmlspecialchars($attr['longitude'] ?? ''); ?>"
                                                               placeholder="Enter longitude">
                                                    </div>
                                                </div>

                                                <?php if (!empty($attr['related_tours'])): ?>
                                                    <div class="mb-4">
                                                        <h6 class="form-label-edit mb-3"><i class="fas fa-route me-2"></i>Manage Related Tours</h6>
                                                        <?php foreach ($attr['related_tours'] as $tour): ?>
                                                            <div class="tour-card-edit mb-3">
                                                                <div class="tour-header-edit">
                                                                    <strong><?php echo htmlspecialchars($tour['title']); ?></strong> 
                                                                    by <?php echo htmlspecialchars($tour['first_name'] . ' ' . $tour['last_name']); ?>
                                                                    <span class="badge bg-<?php echo $tour['status'] === 'active' ? 'success' : 'secondary'; ?> ms-2">
                                                                        <?php echo htmlspecialchars($tour['status']); ?>
                                                                    </span>
                                                                </div>
                                                                <div class="tour-body-edit">
                                                                    <!-- Tour Price -->
                                                                    <div class="mb-3">
                                                                        <label for="tour_price_<?php echo $attr['id']; ?>_<?php echo $tour['id']; ?>" class="form-label-edit">Price per Person</label>
                                                                        <div class="input-group-edit">
                                                                            <span class="input-group-text-edit">$</span>
                                                                            <input type="number" step="0.01" min="0" 
                                                                                   class="form-control-edit" 
                                                                                   id="tour_price_<?php echo $attr['id']; ?>_<?php echo $tour['id']; ?>" 
                                                                                   name="tour_prices[<?php echo $tour['id']; ?>]" 
                                                                                   value="<?php echo htmlspecialchars($tour['price'] ?? '0'); ?>">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <!-- Existing Schedules -->
                                                                    <div class="mb-3">
                                                                        <label class="form-label-edit">Select Date to Update</label>
                                                                        <?php if (!empty($tour['schedules'])): ?>
                                                                            <select class="form-control-edit schedule-date-select" 
                                                                                    id="schedule-select-<?php echo $attr['id']; ?>-<?php echo $tour['id']; ?>"
                                                                                    onchange="showScheduleDetails(<?php echo $attr['id']; ?>, <?php echo $tour['id']; ?>, this.value)">
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
                                                                            <div id="schedule-details-<?php echo $attr['id']; ?>-<?php echo $tour['id']; ?>" class="schedule-details-edit mt-3" style="display: none;">
                                                                                <input type="hidden" id="selected-schedule-id-<?php echo $attr['id']; ?>-<?php echo $tour['id']; ?>" value="">
                                                                                <div class="row">
                                                                                    <div class="col-md-6 mb-3">
                                                                                        <label class="form-label-edit">Date</label>
                                                                                        <input type="date" class="form-control-edit" 
                                                                                               id="schedule-date-<?php echo $attr['id']; ?>-<?php echo $tour['id']; ?>" 
                                                                                               readonly>
                                                                                    </div>
                                                                                    <div class="col-md-6 mb-3">
                                                                                        <label class="form-label-edit">Time</label>
                                                                                        <input type="time" class="form-control-edit" 
                                                                                               id="schedule-time-<?php echo $attr['id']; ?>-<?php echo $tour['id']; ?>" 
                                                                                               readonly>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label class="form-label-edit">Available Spaces</label>
                                                                                    <input type="number" min="0" class="form-control-edit" 
                                                                                           id="schedule-spots-<?php echo $attr['id']; ?>-<?php echo $tour['id']; ?>" 
                                                                                           name="">
                                                                                </div>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <p class="text-muted-edit small mb-0">No schedules found for this tour.</p>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    
                                                                    <!-- Add New Schedule -->
                                                                    <div class="mb-3">
                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                            <label class="form-label-edit mb-0">Add New Dates</label>
                                                                            <button type="button" class="btn-add-schedule-edit" 
                                                                                    onclick="addNewSchedule(<?php echo $attr['id']; ?>, <?php echo $tour['id']; ?>)">
                                                                                <i class="fas fa-plus me-1"></i>Add Date
                                                                            </button>
                                                                        </div>
                                                                        <div id="new-schedules-<?php echo $attr['id']; ?>-<?php echo $tour['id']; ?>">
                                                                            <!-- New schedule rows will be added here -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="d-flex justify-content-end gap-2 mt-4">
                                                    <button type="button" class="btn-edit-cancel" 
                                                            onclick="document.getElementById('collapseAttraction<?php echo $attr['id']; ?>').classList.remove('show');">
                                                        <i class="fas fa-times me-2"></i>CANCEL
                                                    </button>
                                                    <button type="submit" class="btn-edit-submit">
                                                        <i class="fas fa-save me-2"></i>UPDATE ATTRACTION
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if (isset($total_pages) && $total_pages > 1): ?>
                <div class="pagination-wrapper mt-4" data-total-pages="<?php echo $total_pages; ?>" data-current-page="<?php echo $current_page; ?>">
                    <div class="d-flex flex-column align-items-center gap-3">
                        <!-- Page Info - Centered -->
                        <div class="pagination-info text-center">
                            <small class="text-muted">
                                <?php 
                                    // Get page directly from URL to ensure it updates correctly
                                    $display_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    $display_page = max(1, min($display_page, $total_pages)); // Ensure it's within valid range
                                    $per_page = (int)$items_per_page;
                                    $total = (int)$total_items;
                                    
                                    // Calculate start and end based on actual current page
                                    if ($total > 0 && $display_page >= 1) {
                                        $start = (($display_page - 1) * $per_page) + 1;
                                        $end = min($display_page * $per_page, $total);
                                    } else {
                                        $start = 0;
                                        $end = 0;
                                    }
                                ?>
                                Showing <strong><?php echo $start; ?></strong> 
                                to <strong><?php echo $end; ?></strong> 
                                of <strong><?php echo $total; ?></strong> attractions
                            </small>
                        </div>
                        
                        <!-- Pagination Buttons - Centered -->
                        <nav>
                            <ul class="pagination-custom mb-0">
                            <!-- Previous Button -->
                            <?php 
                                // Get page directly from URL to avoid variable scope issues
                                $page_from_get = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $current_page_for_prev = max(1, $page_from_get);
                            ?>
                            <?php if ($current_page_for_prev > 1): 
                                $prev_page = $current_page_for_prev - 1;
                                $prev_page = max(1, $prev_page);
                                $prev_url = $prev_page > 1 ? '?page=' . $prev_page : '';
                            ?>
                            <li class="pagination-item" id="prev-btn-li" style="pointer-events: auto !important;">
                                <a class="pagination-link pagination-nav" id="prev-btn-link" href="attractions.php<?php echo htmlspecialchars($prev_url); ?>" style="pointer-events: auto !important; cursor: pointer !important; text-decoration: none !important; display: inline-block !important;">
                                    <i class="fas fa-chevron-left me-1"></i> Previous
                                </a>
                            </li>
                            <?php else: ?>
                            <li class="pagination-item disabled" id="prev-btn-li-disabled">
                                <span class="pagination-link pagination-nav disabled">
                                    <i class="fas fa-chevron-left me-1"></i> Previous
                                </span>
                            </li>
                            <?php endif; ?>
                            
                            <!-- Page Numbers -->
                            <?php
                            // Calculate which pages to show (show 2 pages before and after current)
                            $current_page_int = isset($current_page) ? (int)$current_page : 1;
                            $total_pages_int = (int)$total_pages;
                            $start_page = max(1, $current_page_int - 2);
                            $end_page = min($total_pages_int, $current_page_int + 2);
                            
                            // Show first page if not in range
                            if ($start_page > 1): 
                                $first_url = '';
                            ?>
                                <li class="pagination-item">
                                    <a class="pagination-link" href="attractions.php<?php echo htmlspecialchars($first_url); ?>">1</a>
                                </li>
                                <?php if ($start_page > 2): ?>
                                    <li class="pagination-item">
                                        <span class="pagination-link pagination-ellipsis" data-expand-start="2" data-expand-end="<?php echo $start_page - 1; ?>" title="Click to show pages 2-<?php echo $start_page - 1; ?>">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php 
                            // Display page numbers in the calculated range
                            for ($i = $start_page; $i <= $end_page; $i++): 
                                $page_url = $i > 1 ? '?page=' . $i : '';
                            ?>
                                <li class="pagination-item <?php echo $i === $current_page_int ? 'active' : ''; ?>">
                                    <a class="pagination-link <?php echo $i === $current_page_int ? 'active' : ''; ?>" href="attractions.php<?php echo htmlspecialchars($page_url); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php 
                            // Show last page if not in range
                            if ($end_page < $total_pages_int): 
                                if ($end_page < $total_pages_int - 1):
                            ?>
                                <li class="pagination-item">
                                    <span class="pagination-link pagination-ellipsis" data-expand-start="<?php echo $end_page + 1; ?>" data-expand-end="<?php echo $total_pages_int - 1; ?>" title="Click to show pages <?php echo $end_page + 1; ?>-<?php echo $total_pages_int - 1; ?>">...</span>
                                </li>
                            <?php 
                                endif;
                                $last_url = '?page=' . $total_pages_int;
                            ?>
                                <li class="pagination-item">
                                    <a class="pagination-link" href="attractions.php<?php echo htmlspecialchars($last_url); ?>">
                                        <?php echo $total_pages_int; ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <!-- Next Button -->
                            <?php 
                                // Get page directly from URL to avoid variable scope issues
                                $page_from_get_next = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $current_page_for_next = max(1, $page_from_get_next);
                                $is_last_page = $current_page_for_next >= $total_pages_int;
                            ?>
                            <?php if (!$is_last_page): 
                                $next_page = $current_page_for_next + 1;
                                $next_url = '?page=' . $next_page;
                            ?>
                            <li class="pagination-item">
                                <a class="pagination-link pagination-nav" href="attractions.php<?php echo htmlspecialchars($next_url); ?>">
                                    Next <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </li>
                            <?php else: ?>
                            <li class="pagination-item disabled">
                                <span class="pagination-link pagination-nav disabled">
                                    Next <i class="fas fa-chevron-right ms-1"></i>
                                </span>
                            </li>
                            <?php endif; ?>
                                </ul>
                            </nav>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Ensure modal text is visible in both dark and light mode */
    .modal-title,
    .modal label,
    .modal .form-label {
        color: var(--bs-body-color, #212529) !important;
        font-weight: 500;
    }
    
    .modal input::placeholder,
    .modal textarea::placeholder {
        color: var(--bs-secondary-color, #6c757d) !important;
        opacity: 0.7;
    }
    
    .modal input[type="text"],
    .modal input[type="url"],
    .modal input[type="number"],
    .modal textarea,
    .modal .form-control {
        color: var(--bs-body-color, #212529) !important;
        background-color: var(--bs-body-bg, #ffffff) !important;
    }
    
    .modal .text-danger {
        color: #dc3545 !important;
    }
    
    /* Collapsible row styles */
    .hover-row:hover {
        background-color: rgba(var(--bs-emphasis-color-rgb, 0, 0, 0), 0.05) !important;
    }
    .collapse-icon {
        transition: transform 0.3s ease;
    }
    .collapse-icon.rotated {
        transform: rotate(180deg);
    }
    
    /* Ensure form labels and inputs in collapsed rows are visible in both modes */
    .collapse .form-label {
        color: #212529 !important;
        font-weight: 500;
    }
    
    .collapse .form-control,
    .collapse input[type="text"],
    .collapse input[type="url"],
    .collapse input[type="number"],
    .collapse textarea {
        color: #212529 !important;
        background-color: #ffffff !important;
    }
    
    .collapse .form-control::placeholder,
    .collapse textarea::placeholder {
        color: #6c757d !important;
        opacity: 0.7;
    }
    
    /* Ensure card body text is visible in light mode */
    .collapse .card-body,
    .collapse .card-body *:not(.text-danger):not(.text-primary):not(.text-success):not(.text-warning):not(.text-info) {
        color: #212529 !important;
    }
    
    .collapse .text-muted {
        color: #6c757d !important;
    }
    
    /* Dark mode specific styles */
    body.dark .collapse .form-label {
        color: #e3e6ea !important;
        font-weight: 500;
    }
    
    body.dark .collapse .form-control,
    body.dark .collapse input[type="text"],
    body.dark .collapse input[type="url"],
    body.dark .collapse input[type="number"],
    body.dark .collapse textarea {
        color: #212529 !important;
        background-color: #ffffff !important;
    }
    
    body.dark .collapse .form-control::placeholder,
    body.dark .collapse textarea::placeholder {
        color: #6c757d !important;
        opacity: 0.7;
    }
    
    /* Dark mode card body text */
    body.dark .collapse .card-body,
    body.dark .collapse .card-body h6,
    body.dark .collapse .card-body label,
    body.dark .collapse .card-body small,
    body.dark .collapse .card-body *:not(input):not(textarea):not(.form-control):not(.text-danger):not(.text-primary):not(.text-success):not(.text-warning):not(.text-info):not(.badge) {
        color: #e3e6ea !important;
    }
    
    body.dark .collapse .text-muted {
        color: #9ca3af !important;
    }
    
    /* Background for collapsed edit panel - adapts to theme */
    body.dark .collapse .bg-light {
        background-color: #1b1e22 !important;
    }
    
    /* Related Tours alert text visibility - use dark color for contrast against light blue background */
    .related-tours-alert,
    .related-tours-alert *,
    .related-tours-alert strong,
    .related-tours-alert ul,
    .related-tours-alert li,
    .related-tours-alert span,
    .related-tours-alert i {
        color: #000000 !important;
        font-weight: 500;
    }
    
    /* Dark mode - change alert background to dark and use light text */
    body.dark .related-tours-alert.alert-info {
        background-color: #0d4a5c !important;
        border-color: #0a3d4c !important;
    }
    
    body.dark .related-tours-alert,
    body.dark .related-tours-alert *,
    body.dark .related-tours-alert strong,
    body.dark .related-tours-alert ul,
    body.dark .related-tours-alert li,
    body.dark .related-tours-alert span,
    body.dark .related-tours-alert i {
        color: #ffffff !important;
        font-weight: 500;
    }
    
    /* Edit Form Container - Login/Signup Style */
    .edit-form-container {
        background: rgba(43, 48, 64, 0.95);
        border-radius: 20px;
        padding: 2rem;
        margin: 1rem 0;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .edit-form-title {
        color: #e3e6ea;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .edit-form-title i {
        color: #5dade2;
    }
    
    /* Form Label Edit Style */
    .form-label-edit {
        display: block;
        font-weight: 600;
        color: #e3e6ea;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-label-edit i {
        color: #5dade2;
    }
    
    /* Form Control Edit Style - Dark Input Fields */
    .form-control-edit {
        width: 100%;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        border: 2px solid #3b3f45;
        border-radius: 10px;
        background-color: #383e4a;
        color: #e3e6ea;
        transition: all 0.3s ease;
    }
    
    .form-control-edit:focus {
        outline: none;
        border-color: #5dade2;
        box-shadow: 0 0 0 0.2rem rgba(93, 173, 226, 0.2);
        background-color: #383e4a;
    }
    
    .form-control-edit::placeholder {
        color: #6c757d;
    }
    
    .form-control-edit.is-valid {
        border-color: #28a745;
    }
    
    .form-control-edit.is-invalid {
        border-color: #dc3545;
    }
    
    /* Text Muted Edit */
    .text-muted-edit {
        color: #9ca3af !important;
    }
    
    /* Image Preview Edit */
    .img-preview-edit {
        max-width: 200px;
        max-height: 150px;
        border-radius: 8px;
        border: 2px solid #3b3f45;
    }
    
    /* Edit Form Buttons */
    .btn-edit-submit {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
        border: none;
        color: #fff;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 700;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
        cursor: pointer;
    }
    
    .btn-edit-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
        background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
        color: #fff;
    }
    
    .btn-edit-cancel {
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
        cursor: pointer;
    }
    
    .btn-edit-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.6);
        background: linear-gradient(135deg, #5a6268 0%, #495057 50%, #343a40 100%);
        color: #fff;
    }
    
    /* Tour Card Edit */
    .tour-card-edit {
        background: rgba(56, 62, 74, 0.5);
        border: 1px solid #3b3f45;
        border-radius: 12px;
        padding: 1.5rem;
    }
    
    .tour-header-edit {
        color: #e3e6ea;
        font-size: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #3b3f45;
    }
    
    .tour-body-edit {
        color: #e3e6ea;
    }
    
    /* Input Group Edit */
    .input-group-edit {
        display: flex;
        align-items: center;
    }
    
    .input-group-text-edit {
        padding: 0.875rem 0.75rem;
        background-color: #383e4a;
        border: 2px solid #3b3f45;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #e3e6ea;
        font-weight: 600;
    }
    
    .input-group-edit .form-control-edit {
        border-left: none;
        border-radius: 0 10px 10px 0;
    }
    
    .input-group-edit .form-control-edit:focus {
        border-left: 2px solid #5dade2;
    }
    
    /* Schedule Table Edit */
    .schedule-table-edit {
        overflow-x: auto;
    }
    
    .table-edit {
        width: 100%;
        border-collapse: collapse;
        background-color: #383e4a;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .table-edit thead {
        background-color: #2b3040;
    }
    
    .table-edit th {
        padding: 0.75rem;
        text-align: left;
        color: #e3e6ea;
        font-weight: 600;
        font-size: 0.875rem;
        border-bottom: 2px solid #3b3f45;
    }
    
    .table-edit td {
        padding: 0.75rem;
        color: #e3e6ea;
        border-bottom: 1px solid #3b3f45;
        font-size: 0.875rem;
    }
    
    .table-edit tbody tr:last-child td {
        border-bottom: none;
    }
    
    .table-edit input.form-control-edit {
        background-color: #2b3040;
        border-color: #3b3f45;
    }
    
    .form-control-sm-edit {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    /* Add Schedule Button Edit */
    .btn-add-schedule-edit {
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
    
    .btn-add-schedule-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
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
    .schedule-details-edit {
        background: rgba(56, 62, 74, 0.5);
        border: 1px solid #3b3f45;
        border-radius: 12px;
        padding: 1.5rem;
    }
    
    /* Dark Modal Styles - Matching Edit Attraction */
    .modal-content-dark {
        background: #2b3040 !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }
    
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7) !important;
    }
    
    .modal-header-dark {
        background: #2b3040 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.5rem 2rem;
    }
    
    .modal-title-dark {
        color: #ffffff !important;
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin: 0;
    }
    
    .modal-title-dark i {
        color: #5dade2;
    }
    
    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 1;
    }
    
    .modal-body-dark {
        background: #2b3040 !important;
        padding: 2rem;
        color: #e3e6ea;
    }
    
    .modal-footer-dark {
        background: #2b3040 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    
    /* Modal Form Controls - Dark Style */
    .modal-content-dark .form-label-custom {
        display: block;
        font-weight: 600;
        color: #ffffff !important;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .modal-content-dark .form-label-custom i {
        color: #5dade2;
    }
    
    .modal-content-dark .text-danger {
        color: #ff6b6b !important;
    }
    
    .modal-content-dark .form-control-custom {
        width: 100%;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        border: 2px solid #3b3f45;
        border-radius: 10px;
        background-color: #383e4a;
        color: #e3e6ea;
        transition: all 0.3s ease;
    }
    
    .modal-content-dark .form-control-custom:focus {
        outline: none;
        border-color: #5dade2;
        box-shadow: 0 0 0 0.2rem rgba(93, 173, 226, 0.2);
        background-color: #383e4a;
    }
    
    .modal-content-dark .form-control-custom::placeholder {
        color: #6c757d;
    }
    
    .modal-content-dark .form-control-custom.is-valid {
        border-color: #28a745;
    }
    
    .modal-content-dark .form-control-custom.is-invalid {
        border-color: #dc3545;
    }
    
    .modal-content-dark .invalid-feedback {
        display: none;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #dc3545;
    }
    
    .modal-content-dark .form-control-custom.is-invalid ~ .invalid-feedback {
        display: block;
    }
    
    .modal-content-dark .was-validated .form-control-custom:invalid ~ .invalid-feedback {
        display: block;
    }
    
    /* Modal Buttons */
    .btn-modal-submit {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
        border: none;
        color: #fff;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 700;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
        cursor: pointer;
    }
    
    .btn-modal-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
        background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
        color: #fff;
    }
    
    .btn-modal-submit:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(0, 123, 255, 0.4);
    }
    
    .btn-modal-cancel {
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
        cursor: pointer;
    }
    
    .btn-modal-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.6);
        background: linear-gradient(135deg, #5a6268 0%, #495057 50%, #343a40 100%);
        color: #fff;
    }
    
    .btn-modal-cancel:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(108, 117, 125, 0.4);
    }
    
    /* Custom Pagination Styles - Matching GlobeGo Design */
    .pagination-wrapper {
        padding: 1.5rem 0;
        display: flex;
        justify-content: center;
    }

    .pagination-info {
        font-size: 0.95rem;
        text-align: center;
        width: 100%;
    }

    .pagination-info strong {
        color: var(--primary-color);
        font-weight: 600;
    }

    .pagination-custom {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination-item {
        margin: 0;
    }

    .pagination-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0.5rem 1rem;
        background: #fff;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        color: #495057;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .pagination-link:hover:not(.disabled):not(.active) {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border-color: #007bff;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    .pagination-link.active {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border-color: #007bff;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    /* Only disable actual disabled elements - be very specific */
    .pagination-item.disabled .pagination-link.disabled,
    .pagination-item.disabled span.pagination-link.disabled:not(.pagination-ellipsis) {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none !important;
    }

    /* Ensure ellipsis is always clickable */
    .pagination-item:has(.pagination-ellipsis),
    .pagination-item.disabled:has(.pagination-ellipsis) {
        pointer-events: auto !important;
    }

    .pagination-item .pagination-ellipsis {
        pointer-events: auto !important;
        cursor: pointer !important;
    }

    /* CRITICAL: Ensure ALL links in non-disabled items are clickable - override everything */
    .pagination-item:not(.disabled) a.pagination-link {
        pointer-events: auto !important;
        cursor: pointer !important;
        text-decoration: none !important;
        display: inline-block !important;
    }

    /* Force Previous and Next buttons to be clickable when not disabled */
    #prev-btn-link,
    .pagination-item:not(.disabled) .pagination-nav {
        pointer-events: auto !important;
        cursor: pointer !important;
    }

    /* Make sure disabled state only applies to actual disabled items */
    .pagination-item.disabled span.pagination-link {
        pointer-events: none !important;
        cursor: not-allowed !important;
    }

    .pagination-nav {
        padding: 0.5rem 1.25rem;
        font-weight: 600;
    }

    .pagination-ellipsis {
        border: 2px solid #e9ecef !important;
        background: #fff !important;
        cursor: pointer !important;
        pointer-events: auto !important;
        color: #495057 !important;
        min-width: 40px !important;
        height: 40px !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 25px !important;
        transition: all 0.3s ease !important;
    }

    .pagination-ellipsis:hover {
        background: rgba(0, 123, 255, 0.1) !important;
        border-color: #007bff !important;
        color: #007bff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2) !important;
    }

    /* Dark Mode Support */
    body.dark .pagination-link {
        background: #1b1e22;
        border-color: #3b3f45;
        color: #e3e6ea;
    }

    body.dark .pagination-link:hover:not(.disabled):not(.active) {
        background: linear-gradient(45deg, #0056b3, #004085);
        border-color: #5dade2;
        color: #fff;
    }

    body.dark .pagination-link.active {
        background: linear-gradient(45deg, #5dade2, #007bff);
        border-color: #5dade2;
        color: #fff;
    }

    body.dark .pagination-info strong {
        color: #5dade2;
    }

    body.dark .pagination-ellipsis {
        color: #9ca3af;
        background: transparent;
    }

    @media (max-width: 576px) {
        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
        }
        
        .pagination-info {
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .pagination-custom {
            justify-content: center;
        }
        
        .pagination-link {
            min-width: 36px;
            height: 36px;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        
        .pagination-nav {
            padding: 0.4rem 1rem;
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
            <input type="date" class="form-control form-control-sm" 
                   name="tour_schedules[${tourId}][new][${index}][tour_date]" 
                   min="${today}" required>
        </div>
        <div class="col-md-3">
            <input type="time" class="form-control form-control-sm" 
                   name="tour_schedules[${tourId}][new][${index}][tour_time]" required>
        </div>
        <div class="col-md-3">
            <input type="number" min="1" class="form-control form-control-sm" 
                   placeholder="Available spaces" 
                   name="tour_schedules[${tourId}][new][${index}][available_spots]" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-outline-danger w-100" 
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
    // Handle collapse icon rotation
    const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
    collapseElements.forEach(function(element) {
        const targetId = element.getAttribute('data-bs-target');
        const iconId = element.querySelector('.collapse-icon')?.id;
        
        if (iconId && targetId) {
            const icon = document.getElementById(iconId);
            const collapseTarget = document.querySelector(targetId);
            
            if (icon && collapseTarget) {
                collapseTarget.addEventListener('show.bs.collapse', function() {
                    icon.classList.add('rotated');
                });
                
                collapseTarget.addEventListener('hide.bs.collapse', function() {
                    icon.classList.remove('rotated');
                });
            }
        }
    });
    
    // Handle Add Attraction Modal form validation
    const addAttractionForm = document.querySelector('#addAttractionModal .needs-validation');
    if (addAttractionForm) {
        addAttractionForm.addEventListener('submit', function(event) {
            if (!addAttractionForm.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                const firstInvalid = addAttractionForm.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            addAttractionForm.classList.add('was-validated');
        }, false);
    }
});
</script>

<!-- Add Attraction Modal -->
<div class="modal fade" id="addAttractionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-dark">
            <div class="modal-header modal-header-dark">
                <h5 class="modal-title modal-title-dark"><i class="fas fa-plus me-2"></i>ADD NEW ATTRACTION</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" class="needs-validation" novalidate>
                <div class="modal-body modal-body-dark">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group-custom mb-4">
                        <label for="add_name" class="form-label-custom">
                            <i class="fas fa-tag me-2"></i>Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control-custom" id="add_name" name="name" 
                               placeholder="Enter attraction name"
                               required>
                        <div class="invalid-feedback">
                            Name is required.
                        </div>
                    </div>
                    
                    <div class="form-group-custom mb-4">
                        <label for="add_description" class="form-label-custom">
                            <i class="fas fa-align-left me-2"></i>Description <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control-custom" id="add_description" name="description" 
                                  rows="4" 
                                  placeholder="Enter attraction description"
                                  required></textarea>
                        <div class="invalid-feedback">
                            Description is required.
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom mb-4">
                                <label for="add_location" class="form-label-custom">
                                    <i class="fas fa-map-marker-alt me-2"></i>Location <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control-custom" id="add_location" name="location" 
                                       placeholder="Enter location"
                                       required>
                                <div class="invalid-feedback">
                                    Location is required.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom mb-4">
                                <label for="add_category" class="form-label-custom">
                                    <i class="fas fa-folder me-2"></i>Category
                                </label>
                                <input type="text" class="form-control-custom" id="add_category" name="category" 
                                       placeholder="Enter category"
                                       list="categoryList">
                                <datalist id="categoryList">
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom mb-4">
                                <label for="add_latitude" class="form-label-custom">
                                    <i class="fas fa-globe me-2"></i>Latitude
                                </label>
                                <input type="number" step="any" class="form-control-custom" id="add_latitude" name="latitude" 
                                       placeholder="Enter latitude">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom mb-4">
                                <label for="add_longitude" class="form-label-custom">
                                    <i class="fas fa-globe me-2"></i>Longitude
                                </label>
                                <input type="number" step="any" class="form-control-custom" id="add_longitude" name="longitude" 
                                       placeholder="Enter longitude">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group-custom mb-4">
                        <label for="add_image_url" class="form-label-custom">
                            <i class="fas fa-image me-2"></i>Image URL or Path
                        </label>
                        <input type="text" class="form-control-custom" id="add_image_url" name="image_url" 
                               placeholder="images/example.jpg or https://example.com/image.jpg">
                    </div>
                </div>
                <div class="modal-footer modal-footer-dark">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>CANCEL
                    </button>
                    <button type="submit" class="btn-modal-submit">
                        <i class="fas fa-plus me-2"></i>ADD ATTRACTION
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



