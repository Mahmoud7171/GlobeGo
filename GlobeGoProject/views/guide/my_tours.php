<?php
// Guide "My Tours" view.
// Expects: $tourModel (Tour instance), $user_tours (array)
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>My Tours</h1>
                <a href="create-tour.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Tour
                </a>
            </div>
        </div>
    </div>

    <?php if (empty($user_tours)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                        <h3>No Tours Created Yet</h3>
                        <p class="text-muted mb-4">Start sharing your local knowledge by creating your first tour.</p>
                        <a href="create-tour.php" class="btn btn-primary btn-lg">Create Your First Tour</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($user_tours as $tour_item): ?>
                <?php
                $tour_stats = $tourModel->getTourStats($tour_item['id']);
                $upcoming_schedules = $tourModel->getTourSchedules($tour_item['id']);
                ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo $tour_item['image_url'] ?: '../assets/images/default-tour.jpg'; ?>" 
                             class="card-img-top" alt="<?php echo $tour_item['title']; ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $tour_item['title']; ?></h5>
                            <p class="card-text flex-grow-1"><?php echo substr($tour_item['description'], 0, 100) . '...'; ?></p>
                            
                            <div class="mb-3">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Price</small>
                                        <strong><?php echo formatPrice($tour_item['price']); ?></strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Duration</small>
                                        <strong><?php echo $tour_item['duration_hours']; ?>h</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Bookings</small>
                                        <strong><?php echo $tour_stats['total_bookings'] ?? 0; ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-<?php echo $tour_item['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($tour_item['status']); ?>
                                </span>
                                <span class="badge bg-info"><?php echo $tour_item['category']; ?></span>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    <a href="../tour-details.php?id=<?php echo $tour_item['id']; ?>" 
                                       class="btn btn-outline-primary btn-sm flex-grow-1">View</a>
                                    <a href="edit-tour.php?id=<?php echo $tour_item['id']; ?>" 
                                       class="btn btn-outline-secondary btn-sm">Edit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> <?php echo count($upcoming_schedules); ?> upcoming dates
                                <?php if (!empty($tour_item['attraction_name'])): ?>
                                    <br><i class="fas fa-map-marker-alt"></i> <?php echo $tour_item['attraction_name']; ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


