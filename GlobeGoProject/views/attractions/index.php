<?php
// Attractions list view.
// Expects: $attractions (array), $categories (array)
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Discover Amazing Attractions</h1>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="<?php echo $_GET['search'] ?? ''; ?>" placeholder="Search attractions...">
                            </div>
                            <div class="col-md-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       value="<?php echo $_GET['location'] ?? ''; ?>" placeholder="Enter location...">
                            </div>
                            <div class="col-md-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category; ?>" 
                                                <?php echo (($_GET['category'] ?? '') === $category) ? 'selected' : ''; ?>>
                                            <?php echo $category; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="attractions.php" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Attractions Grid -->
    <div class="row">
        <?php if (empty($attractions)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No attractions found</h4>
                    <p>Try adjusting your search criteria or <a href="guide/create-tour.php">create a tour</a> for an attraction!</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($attractions as $attraction_item): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo $attraction_item['image_url'] ?: 'assets/images/default-attraction.jpg'; ?>" 
                         class="card-img-top" alt="<?php echo $attraction_item['name']; ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $attraction_item['name']; ?></h5>
                        <p class="card-text flex-grow-1"><?php echo substr($attraction_item['description'], 0, 120) . '...'; ?></p>
                        
                        <div class="mb-2">
                            <span class="badge bg-info"><?php echo $attraction_item['category']; ?></span>
                            <?php if ($attraction_item['rating'] > 0): ?>
                                <div class="rating mt-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $attraction_item['rating'] ? '' : 'text-muted'; ?>"></i>
                                    <?php endfor; ?>
                                    <small class="text-muted ms-1">(<?php echo $attraction_item['total_reviews']; ?>)</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt"></i> <?php echo $attraction_item['location']; ?>
                            </small>
                            <a href="attraction-details.php?id=<?php echo $attraction_item['id']; ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Load More Button -->
    <?php if (count($attractions) >= 12): ?>
    <div class="row">
        <div class="col-12 text-center">
            <button class="btn btn-outline-primary">Load More Attractions</button>
        </div>
    </div>
    <?php endif; ?>
</div>


