<?php
// Attraction details view.
// Expects: $attraction_id, $attraction_details, $attraction_tours
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Attraction Image -->
            <div class="card mb-4">
                <img src="<?php echo $attraction_details['image_url'] ?: 'assets/images/default-attraction.jpg'; ?>" 
                     class="card-img-top" alt="<?php echo $attraction_details['name']; ?>" style="height: 400px; object-fit: cover;">
            </div>

            <!-- Attraction Details -->
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="card-title"><?php echo $attraction_details['name']; ?></h1>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Category:</strong> <?php echo $attraction_details['category']; ?></p>
                            <p><strong>Location:</strong> <?php echo $attraction_details['location']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <?php if ($attraction_details['rating'] > 0): ?>
                                <p><strong>Rating:</strong> 
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $attraction_details['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                    <?php endfor; ?>
                                    (<?php echo $attraction_details['total_reviews']; ?> reviews)
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($attraction_details['latitude'] && $attraction_details['longitude']): ?>
                                <p><strong>Coordinates:</strong> <?php echo $attraction_details['latitude']; ?>, <?php echo $attraction_details['longitude']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h4>Description</h4>
                    <p><?php echo nl2br($attraction_details['description']); ?></p>
                </div>
            </div>

            <!-- Tours for this attraction -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Tours at this Attraction</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($attraction_tours)): ?>
                        <p class="text-muted">No tours available for this attraction at the moment.</p>
                        <p class="small">Check back later or <a href="guide/create-tour.php">become a guide</a> to create a tour for this attraction!</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($attraction_tours as $tour_item): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo $tour_item['title']; ?></h6>
                                        <p class="card-text small"><?php echo substr($tour_item['description'], 0, 100) . '...'; ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-price fw-bold"><?php echo formatPrice($tour_item['price']); ?></span>
                                            <a href="tour-details.php?id=<?php echo $tour_item['id']; ?>" class="btn btn-outline-primary btn-sm">View Tour</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="tours.php?attraction=<?php echo $attraction_id; ?>" class="btn btn-primary">Find Tours</a>
                        <a href="attractions.php" class="btn btn-outline-secondary">Back to Attractions</a>
                    </div>
                </div>
            </div>

            <!-- Location Info -->
            <div class="card">
                <div class="card-header">
                    <h5>Location Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Address:</strong><br><?php echo $attraction_details['location']; ?></p>
                    
                    <?php if ($attraction_details['latitude'] && $attraction_details['longitude']): ?>
                        <div class="mt-3">
                            <a href="https://www.google.com/maps?q=<?php echo $attraction_details['latitude']; ?>,<?php echo $attraction_details['longitude']; ?>" 
                               target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-map-marker-alt"></i> View on Map
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


