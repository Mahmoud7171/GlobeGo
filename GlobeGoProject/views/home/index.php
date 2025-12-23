<?php
// View for the home page. Expects:
// - $featured_tours (array)
// - $popular_attractions (array)
?>

<!-- Hero Section with Slideshow -->
<section class="hero-section text-white py-5">
    <div class="hero-slideshow">
        <?php 
        $hero_destinations = $hero_destinations ?? [];
        if (empty($hero_destinations)) {
            // Fallback destinations only if no destinations found in database
            $hero_destinations = [
                ['image_url' => 'images/Paris.png', 'location' => 'Paris, France', 'city' => 'Paris', 'tour_title' => 'PARIS CITY', 'description' => 'Experience world‑class art, food, and romantic views this season.'],
                ['image_url' => 'images/NYC.png', 'location' => 'New York, USA', 'city' => 'New York', 'tour_title' => 'NEW YORK CITY', 'description' => 'Discover the city that never sleeps with iconic landmarks and vibrant culture.'],
                ['image_url' => 'images/Rome.png', 'location' => 'Rome, Italy', 'city' => 'Rome', 'tour_title' => 'ROME', 'description' => 'Explore ancient history and timeless beauty in the Eternal City.']
            ];
        }
        foreach ($hero_destinations as $index => $destination): 
            $image_url = strpos($destination['image_url'], 'http') === 0 ? $destination['image_url'] : SITE_URL . '/' . ltrim($destination['image_url'], '/');
        ?>
        <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
             style="background-image: url('<?php echo htmlspecialchars($image_url); ?>');"
             data-city="<?php echo htmlspecialchars($destination['city'] ?? ''); ?>"
             data-location="<?php echo htmlspecialchars($destination['location'] ?? ''); ?>"
             data-title="<?php echo htmlspecialchars($destination['tour_title'] ?? $destination['city'] ?? ''); ?>"
             data-description="<?php echo htmlspecialchars($destination['description'] ?? ''); ?>">
        </div>
        <?php endforeach; ?>
        <div class="hero-overlay"></div>
    </div>
    
    <div class="container position-relative">
        <!-- Search with Glassmorphism -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="tours.php" method="GET" class="hero-search">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text border-0 pe-0"><i class="fas fa-location-dot"></i></span>
                        <input type="text" class="form-control border-0" id="search-input" name="location" placeholder="<?php echo Language::t('home.hero_search_placeholder'); ?>" autocomplete="off" role="combobox" aria-autocomplete="none">
                        <button class="btn btn-primary px-4 border-0" type="submit"><i class="fas fa-search me-2"></i><?php echo Language::t('common.search'); ?></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row align-items-end min-vh-75">
            <div class="col-lg-7 col-md-8">
                <div class="d-flex align-items-center mb-3 opacity-75 small hero-location"><i class="fas fa-location-dot me-2"></i><span class="hero-location-text">Paris, France</span></div>
                <h1 class="display-2 fw-bold mb-3 hero-title hero-title-text">PARIS<br>CITY</h1>
                <p class="lead mb-4 hero-subtitle hero-description-text">Experience world‑class art, food, and romantic views this season.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="tours.php?location=Paris" class="btn btn-light btn-lg px-4 hero-explore-btn"><?php echo Language::t('common.explore'); ?></a>
                    <a href="#popular" class="btn btn-outline-light btn-lg px-4"><i class="far fa-bookmark me-2"></i><?php echo Language::t('common.save'); ?></a>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block">
                <div class="hero-cards">
                    <?php 
                    // Randomly select 2 different destinations for cards (exclude the first one shown in main slide)
                    $card_destinations = [];
                    if (count($hero_destinations) > 1) {
                        // Get all destinations except the first one (which is the main slide)
                        $available_destinations = array_slice($hero_destinations, 1);
                        // Shuffle to randomize
                        shuffle($available_destinations);
                        // Take first 2
                        $card_destinations = array_slice($available_destinations, 0, 2);
                    } elseif (count($hero_destinations) > 0) {
                        // If only 1 destination, use it for one card
                        $card_destinations = [$hero_destinations[0]];
                    }
                    foreach ($card_destinations as $card_dest): 
                        $card_image = strpos($card_dest['image_url'], 'http') === 0 ? $card_dest['image_url'] : SITE_URL . '/' . ltrim($card_dest['image_url'], '/');
                    ?>
                    <a href="tours.php?location=<?php echo urlencode($card_dest['city'] ?? ''); ?>" class="hero-card shadow-sm">
                        <img src="<?php echo htmlspecialchars($card_image); ?>" alt="<?php echo htmlspecialchars($card_dest['tour_title'] ?? ''); ?>">
                        <div class="hero-card-caption">
                            <div class="small opacity-75"><?php echo htmlspecialchars($card_dest['location'] ?? ''); ?></div>
                            <div class="fw-bold"><?php echo htmlspecialchars($card_dest['tour_title'] ?? $card_dest['city'] ?? ''); ?></div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Multi-Ticket Discount Promo Section -->
<section class="discount-promo-section py-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="promo-icon-wrapper">
                        <i class="fas fa-gift promo-icon"></i>
                    </div>
                    <div>
                        <h3 class="text-white mb-1 promo-title"><?php echo Language::t('home.save_more_title'); ?></h3>
                        <p class="text-white-50 mb-0 promo-subtitle"><?php echo Language::t('home.save_more_desc'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-5">
                <div class="discount-tiers">
                    <div class="discount-tier tier-5">
                        <div class="tier-badge">5%</div>
                        <div class="tier-label"><?php echo Language::t('home.tickets_2'); ?></div>
                    </div>
                    <div class="discount-tier tier-10">
                        <div class="tier-badge">10%</div>
                        <div class="tier-label"><?php echo Language::t('home.tickets_3'); ?></div>
                    </div>
                    <div class="discount-tier tier-15">
                        <div class="tier-badge">15%</div>
                        <div class="tier-label"><?php echo Language::t('home.tickets_4'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.discount-promo-section {
    border-top: 2px solid rgba(93, 173, 226, 0.3);
    border-bottom: none;
}

.promo-icon-wrapper {
    width: 60px;
    height: 60px;
    background: rgba(93, 173, 226, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(93, 173, 226, 0.4);
}

.promo-icon {
    font-size: 2rem;
    color: #5dade2;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.promo-title {
    font-size: 1.5rem;
    font-weight: 700;
}

.promo-subtitle {
    font-size: 1rem;
    opacity: 0.9;
}

.discount-tiers {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.discount-tier {
    text-align: center;
    padding: 1rem;
    border-radius: 15px;
    min-width: 100px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.discount-tier:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.tier-5 {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 50%, #f57c00 100%);
}

.tier-10 {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
}

.tier-15 {
    background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
}

.tier-badge {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.tier-5 .tier-badge {
    color: #000;
}

.tier-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tier-5 .tier-label {
    color: #000;
}

/* Light mode overrides for discount promo section */
body:not(.dark) .discount-promo-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%) !important;
    border-top: 2px solid rgba(13, 110, 253, 0.2) !important;
    border-bottom: none !important;
}

body:not(.dark) .promo-icon-wrapper {
    background: rgba(13, 110, 253, 0.1) !important;
    border: 2px solid rgba(13, 110, 253, 0.3) !important;
}

body:not(.dark) .promo-icon {
    color: #007bff !important;
}

body:not(.dark) .promo-title {
    color: #212529 !important;
}

body:not(.dark) .promo-subtitle {
    color: #495057 !important;
    opacity: 1 !important;
}

@media (max-width: 768px) {
    .promo-title {
        font-size: 1.25rem;
    }
    
    .promo-subtitle {
        font-size: 0.9rem;
    }
    
    .discount-tiers {
        justify-content: center;
    }
    
    .discount-tier {
        min-width: 80px;
        padding: 0.75rem;
    }
    
    .tier-badge {
        font-size: 1.5rem;
    }
}
</style>

<!-- Search Section -->
<section class="search-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4"><?php echo Language::t('home.find_perfect_tour'); ?></h3>
                        <form action="tours.php" method="GET">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="destination" class="form-label"><?php echo Language::t('home.destination'); ?></label>
                                    <input type="text" class="form-control" id="destination" name="location" placeholder="<?php echo Language::t('home.destination_placeholder'); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="category" class="form-label"><?php echo Language::t('home.category'); ?></label>
                                    <select class="form-select" id="category" name="category">
                                        <option value=""><?php echo Language::t('home.all_categories'); ?></option>
                                        <option value="Historical">Historical</option>
                                        <option value="Food Tour">Food Tour</option>
                                        <option value="Walking Tour">Walking Tour</option>
                                        <option value="Adventure">Adventure</option>
                                        <option value="Cultural">Cultural</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="date" class="form-label"><?php echo Language::t('home.date'); ?></label>
                                    <input type="date" class="form-control" id="date" name="date">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> <?php echo Language::t('common.search'); ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Tours -->
<section class="featured-tours py-5">
    <div class="container">
        <h2 class="text-center mb-5"><?php echo Language::t('home.featured_tours'); ?></h2>
        <?php if (!empty($featured_tours)): ?>
        <div class="row">
            <?php foreach ($featured_tours as $tour_item): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo htmlspecialchars($tour_item['image_url'] ?: 'assets/images/default-tour.jpg'); ?>" 
                         class="card-img-top" alt="<?php echo htmlspecialchars($tour_item['title']); ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($tour_item['title']); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($tour_item['description'], 0, 100)) . '...'; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-price fw-bold"><?php echo formatPrice($tour_item['price']); ?></span>
                            <small class="text-muted"><?php echo $tour_item['duration_hours']; ?>h</small>
                        </div>
                        <a href="tour-details.php?id=<?php echo $tour_item['id']; ?>" class="btn btn-primary mt-3"><?php echo Language::t('common.view_details'); ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info text-center">
            <p class="mb-0"><?php echo Language::t('home.no_featured_tours'); ?></p>
        </div>
        <?php endif; ?>
        <div class="text-center mt-4">
            <a href="tours.php" class="btn btn-outline-primary"><?php echo Language::t('common.view_all'); ?> <?php echo Language::t('footer.tours'); ?></a>
        </div>
    </div>
</section>

<!-- Popular Attractions -->
<section id="popular" class="popular-attractions py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5"><?php echo Language::t('home.popular_attractions'); ?></h2>
        <?php if (!empty($popular_attractions)): ?>
        <div class="row">
            <?php foreach ($popular_attractions as $attraction_item): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <img src="<?php echo htmlspecialchars($attraction_item['image_url'] ?: 'assets/images/default-attraction.jpg'); ?>" 
                         class="card-img-top" alt="<?php echo htmlspecialchars($attraction_item['name']); ?>" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title"><?php echo htmlspecialchars($attraction_item['name']); ?></h6>
                        <p class="card-text small"><?php echo htmlspecialchars(substr($attraction_item['description'], 0, 80)) . '...'; ?></p>
                        <small class="text-muted"><?php echo htmlspecialchars($attraction_item['location']); ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info text-center">
            <p class="mb-0"><?php echo Language::t('home.no_attractions'); ?></p>
        </div>
        <?php endif; ?>
        <div class="text-center mt-4">
            <a href="attractions.php" class="btn btn-outline-primary"><?php echo Language::t('common.view_all'); ?> <?php echo Language::t('footer.attractions'); ?></a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="how-it-works py-5">
    <div class="container">
        <h2 class="text-center mb-5"><?php echo Language::t('home.how_it_works'); ?></h2>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-search fa-2x"></i>
                </div>
                <h4><?php echo Language::t('home.discover'); ?></h4>
                <p><?php echo Language::t('home.discover_desc'); ?></p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-calendar-check fa-2x"></i>
                </div>
                <h4><?php echo Language::t('home.book'); ?></h4>
                <p><?php echo Language::t('home.book_desc'); ?></p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-map-marked-alt fa-2x"></i>
                </div>
                <h4><?php echo Language::t('common.explore'); ?></h4>
                <p><?php echo Language::t('home.explore_desc'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4"><?php echo Language::t('home.ready_adventure'); ?></h2>
        <p class="lead mb-4"><?php echo Language::t('home.ready_desc'); ?></p>
        <a href="auth/register.php" class="btn btn-light btn-lg"><?php echo Language::t('home.get_started'); ?></a>
    </div>
</section>


