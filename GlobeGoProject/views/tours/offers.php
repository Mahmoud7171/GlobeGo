<?php
// View for offers page
// Expects: $offers (array) - Only 3 specific offers: Egypt, India, and Shibuya
?>

<style>
.offer-card-container {
    position: relative;
    margin-bottom: 3rem;
    padding: 4px;
    border-radius: 24px;
    background: linear-gradient(135deg, #ff6b6b, #ffd93d, #6bcf7f, #4d96ff, #9b59b6, #ff6b6b);
    background-size: 400% 400%;
    animation: glowingRibbon 3s ease infinite;
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: visible;
    transition: transform 0.3s ease;
}

.offer-card-container:hover {
    transform: translateY(-10px);
}

@keyframes glowingRibbon {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

.offer-card {
    position: relative;
    border-radius: 20px;
    overflow: visible;
    background: #fff;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    transition: box-shadow 0.3s ease;
    height: 100%;
    z-index: 1;
    display: flex;
    flex-direction: column;
}

.offer-card-container:hover .offer-card {
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

/* Animated Fire Symbol with Ignition */
.fire-symbol {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10;
    font-size: 32px;
    animation: fireIgnite 3s ease-in-out infinite;
    filter: drop-shadow(0 0 10px rgba(255, 69, 0, 0.8));
    pointer-events: none;
}

@keyframes fireIgnite {
    0%, 100% {
        transform: scale(1) rotate(-5deg);
        opacity: 0.8;
        filter: drop-shadow(0 0 8px rgba(255, 69, 0, 0.6));
    }
    25% {
        transform: scale(1.2) rotate(5deg);
        opacity: 1;
        filter: drop-shadow(0 0 15px rgba(255, 140, 0, 1)) drop-shadow(0 0 25px rgba(255, 69, 0, 0.8));
    }
    50% {
        transform: scale(1.15) rotate(-3deg);
        opacity: 0.95;
        filter: drop-shadow(0 0 20px rgba(255, 165, 0, 1)) drop-shadow(0 0 30px rgba(255, 69, 0, 0.9));
    }
    75% {
        transform: scale(1.25) rotate(4deg);
        opacity: 1;
        filter: drop-shadow(0 0 18px rgba(255, 140, 0, 1)) drop-shadow(0 0 28px rgba(255, 69, 0, 0.85));
    }
}

/* Discount Badge */
.discount-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
    color: white;
    padding: 8px 14px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
    z-index: 9;
    animation: badgePulse 2s ease-in-out infinite;
    white-space: nowrap;
}

@keyframes badgePulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.6);
    }
}

.offer-image-wrapper {
    position: relative;
    width: 100%;
    overflow: hidden;
    border-radius: 20px 20px 0 0;
}

.offer-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    display: block;
}

.offer-content {
    padding: 25px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.offer-title {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #2c3e50;
}

.offer-description {
    color: #7f8c8d;
    margin-bottom: 20px;
    line-height: 1.6;
    min-height: 48px;
}

.offer-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 10px;
}

.offer-location {
    color: #34495e;
    font-size: 14px;
}

.offer-location i {
    color: #e74c3c;
    margin-right: 5px;
}

.offer-guide {
    color: #7f8c8d;
    font-size: 14px;
}

.offer-pricing {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.original-price {
    text-decoration: line-through;
    color: #95a5a6;
    font-size: 18px;
}

.discounted-price {
    color: #28a745 !important;
    font-size: 28px;
    font-weight: bold;
}

.offer-duration {
    color: #7f8c8d;
    font-size: 14px;
}

.offer-duration i {
    margin-right: 5px;
}

.btn-reserve {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    border: none;
    border-radius: 10px;
    color: #000;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.btn-reserve:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
    color: #000;
    background: linear-gradient(135deg, #ff9800, #ffc107);
}

/* Dark Mode Styles for Offer Cards */
body.dark .offer-card {
    background-color: #1f2428 !important;
    color: #e3e6ea !important;
    border-color: #3a4148 !important;
}

body.dark .offer-card-container {
    background: linear-gradient(135deg, #ff6b6b, #ffd93d, #6bcf7f, #4d96ff, #9b59b6, #ff6b6b);
}

body.dark .offer-title {
    color: #ffffff !important;
}

body.dark .offer-description {
    color: #b8bcc0 !important;
}

body.dark .offer-meta {
    background-color: #2a2f35 !important;
    color: #e3e6ea !important;
}

body.dark .offer-location {
    color: #e3e6ea !important;
}

body.dark .offer-location i {
    color: #ff6b6b !important;
}

body.dark .offer-guide {
    color: #b8bcc0 !important;
}

body.dark .offer-guide i {
    color: #b8bcc0 !important;
}

body.dark .original-price {
    color: #8a8f94 !important;
}

body.dark .discounted-price {
    color: #28a745 !important;
}

body.dark .offer-duration {
    color: #b8bcc0 !important;
}

body.dark .offer-duration i {
    color: #b8bcc0 !important;
}

body.dark .btn-reserve {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    color: #000 !important;
}

body.dark .btn-reserve:hover {
    background: linear-gradient(135deg, #ff9800, #ffc107) !important;
    color: #000 !important;
}

body.dark .discount-badge {
    background: linear-gradient(135deg, #ff6b6b, #ee5a6f) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.6) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .offer-image {
        height: 250px;
    }
    
    .fire-symbol {
        font-size: 24px;
        top: 12px;
        right: 12px;
    }
    
    .discount-badge {
        padding: 6px 12px;
        font-size: 12px;
        top: 12px;
        left: 12px;
    }
}
</style>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 fw-bold mb-3">Special Offers</h1>
            <p class="lead text-muted">Exclusive deals on amazing destinations</p>
        </div>
    </div>

    <div class="row justify-content-center align-items-stretch">
        <?php if (empty($offers)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>No special offers available at the moment</h4>
                    <p class="mb-0">Check back soon for amazing deals!</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($offers as $offer): 
                // Determine which sound file to use based on offer location/title
                $sound_file = null;
                $offer_class = '';
                
                // Check for Egypt
                if ((isset($offer['location']) && stripos($offer['location'], 'Egypt') !== false) ||
                    (isset($offer['title']) && stripos($offer['title'], 'Egypt') !== false)) {
                    $sound_file = SITE_URL . '/assets/sounds/Egypt.mp3';
                    $offer_class = 'egypt-offer-card';
                }
                // Check for India/Taj Mahal
                elseif ((isset($offer['location']) && (stripos($offer['location'], 'India') !== false || stripos($offer['location'], 'Agra') !== false)) ||
                        (isset($offer['title']) && (stripos($offer['title'], 'India') !== false || stripos($offer['title'], 'Taj Mahal') !== false))) {
                    $sound_file = SITE_URL . '/assets/sounds/India.mp3';
                    $offer_class = 'india-offer-card';
                }
                // Check for Japan/Shibuya
                elseif ((isset($offer['location']) && (stripos($offer['location'], 'Japan') !== false || stripos($offer['location'], 'Tokyo') !== false || stripos($offer['location'], 'Shibuya') !== false)) ||
                        (isset($offer['title']) && (stripos($offer['title'], 'Japan') !== false || stripos($offer['title'], 'Shibuya') !== false))) {
                    $sound_file = SITE_URL . '/assets/sounds/japan.mp3';
                    $offer_class = 'japan-offer-card';
                }
            ?>
                <div class="col-lg-4 col-md-6 mb-4 d-flex">
                    <div class="offer-card-container w-100">
                        <div class="offer-card <?php echo $offer_class; ?>" <?php echo $sound_file ? 'data-sound-file="' . htmlspecialchars($sound_file) . '"' : ''; ?>>
                            <!-- Offer Image Wrapper -->
                            <div class="offer-image-wrapper">
                                <img src="<?php echo htmlspecialchars($offer['image_url'] ?? 'assets/images/default-tour.jpg'); ?>" 
                                     class="offer-image" 
                                     alt="<?php echo htmlspecialchars($offer['title'] ?? ''); ?>">
                                
                                <!-- Animated Fire Symbol -->
                                <div class="fire-symbol">🔥</div>
                                
                                <!-- Discount Badge -->
                                <?php if (isset($offer['discount_percent'])): ?>
                                    <div class="discount-badge">
                                        -<?php echo $offer['discount_percent']; ?>% OFF
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Offer Content -->
                            <div class="offer-content">
                                <h3 class="offer-title"><?php echo htmlspecialchars($offer['title'] ?? ''); ?></h3>
                                <p class="offer-description">
                                    <?php echo htmlspecialchars(substr($offer['description'] ?? '', 0, 100)) . '...'; ?>
                                </p>
                                
                                <div class="offer-meta">
                                    <div class="offer-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo !empty($offer['location']) ? htmlspecialchars($offer['location']) : 'Location not specified'; ?>
                                    </div>
                                    <div class="offer-guide">
                                        <i class="fas fa-user"></i>
                                        <?php echo htmlspecialchars(trim(($offer['first_name'] ?? '') . ' ' . ($offer['last_name'] ?? ''))); ?>
                                    </div>
                                </div>
                                
                                <div class="offer-pricing">
                                    <?php if (isset($offer['discounted_price']) && isset($offer['original_price'])): ?>
                                        <div>
                                            <span class="original-price"><?php echo formatPrice($offer['original_price']); ?></span>
                                            <span class="discounted-price ms-3"><?php echo formatPrice($offer['discounted_price']); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="discounted-price"><?php echo formatPrice($offer['price'] ?? 0); ?></span>
                                    <?php endif; ?>
                                    <div class="offer-duration">
                                        <i class="fas fa-clock"></i>
                                        <?php echo ($offer['duration_hours'] ?? 0); ?>h
                                    </div>
                                </div>
                                
                                <?php 
                                    // Build reserve URL with discount info
                                    $reserveUrl = 'reserve-tour.php?id=' . ($offer['id'] ?? '');
                                    if (isset($offer['discount_percent']) && isset($offer['discounted_price'])) {
                                        $reserveUrl .= '&discount=' . $offer['discount_percent'] . '&discounted_price=' . $offer['discounted_price'] . '&original_price=' . $offer['original_price'];
                                    }
                                ?>
                                <a href="<?php echo htmlspecialchars($reserveUrl); ?>" class="btn btn-reserve mt-auto">
                                    <i class="fas fa-fire me-2"></i>Reserve Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all offer cards with sound files (Egypt, India, Japan)
    const offerCards = document.querySelectorAll('.offer-card[data-sound-file]');
    
    offerCards.forEach(function(card) {
        const soundFile = card.getAttribute('data-sound-file');
        if (soundFile) {
            // Create audio element for this card
            const audio = new Audio(soundFile);
            audio.loop = true; // Loop the audio while hovering
            audio.volume = 0; // Start with volume 0 for fade in
            
            let fadeInterval = null;
            let isHovering = false;
            
            // Fade in function
            function fadeIn() {
                if (isHovering) return; // Prevent multiple intervals
                isHovering = true;
                audio.play().catch(error => {
                    console.log('Audio play failed:', error);
                });
                
                clearInterval(fadeInterval);
                fadeInterval = setInterval(function() {
                    if (audio.volume < 1) {
                        audio.volume = Math.min(audio.volume + 0.1, 1);
                    } else {
                        clearInterval(fadeInterval);
                    }
                }, 50); // Fade in over ~500ms (10 steps * 50ms)
            }
            
            // Fade out function
            function fadeOut() {
                isHovering = false;
                clearInterval(fadeInterval);
                fadeInterval = setInterval(function() {
                    if (audio.volume > 0) {
                        audio.volume = Math.max(audio.volume - 0.1, 0);
                    } else {
                        audio.pause();
                        audio.currentTime = 0; // Reset to beginning
                        clearInterval(fadeInterval);
                    }
                }, 50); // Fade out over ~500ms (10 steps * 50ms)
            }
            
            // Event listeners for this card
            card.addEventListener('mouseenter', fadeIn);
            card.addEventListener('mouseleave', fadeOut);
        }
    });
});
</script>