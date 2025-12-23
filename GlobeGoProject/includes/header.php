<?php
// Ensure Language class is available
if (!class_exists('Language')) {
    require_once __DIR__ . '/../helpers/Language.php';
    Language::init();
}

$currentLang = Language::getCurrentLang();
$isRTL = Language::isRTL();
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>" dir="<?php echo $isRTL ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo SITE_URL; ?>/assets/css/style.css" rel="stylesheet">
    <?php if ($isRTL): ?>
    <!-- RTL Support -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { direction: rtl; text-align: right; }
        .navbar-nav { flex-direction: row-reverse; }
        .ms-auto { margin-left: 0 !important; margin-right: auto !important; }
        .me-auto { margin-right: 0 !important; margin-left: auto !important; }
        .me-2 { margin-left: 0.5rem !important; margin-right: 0 !important; }
        .ms-2 { margin-right: 0.5rem !important; margin-left: 0 !important; }
        .dropdown-menu { text-align: right; }
        .dropdown-menu-end { left: 0 !important; right: auto !important; }
        .input-group-text { border-left: 0; border-right: 1px solid #ced4da; }
        .btn-group > .btn:not(:first-child) { border-top-right-radius: 0; border-bottom-right-radius: 0; border-top-left-radius: 0.25rem; border-bottom-left-radius: 0.25rem; }
        .btn-group > .btn:not(:last-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; border-top-right-radius: 0.25rem; border-bottom-right-radius: 0.25rem; }
    </style>
    <?php endif; ?>
    <style>
        /* Language Switcher Styles */
        .language-switcher-btn {
            min-width: 45px;
            min-height: 45px;
            font-size: 1.3rem;
            line-height: 1;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            color: #212529;
            transition: all 0.3s ease;
        }
        .language-switcher-btn:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
        }
        .language-switcher-btn:after {
            margin-left: 0.5rem;
        }
        .language-flag {
            display: inline-block;
            font-size: 1.3rem;
            line-height: 1;
        }
        .language-flag-item {
            display: inline-block;
            font-size: 1.1rem;
            margin-right: 0.5rem;
            width: 24px;
            text-align: center;
        }
        .language-name-item {
            display: inline-block;
        }
        .dropdown-item.active {
            background-color: #0d6efd;
            color: #fff;
        }
        .dropdown-item.active i {
            color: #fff;
        }
        .dropdown-item.active .language-flag-item,
        .dropdown-item.active .language-name-item {
            color: #fff;
        }
        
        /* Dark Mode Styles for Language Switcher */
        body.dark .language-switcher-btn {
            background-color: #2a2d32;
            color: #e3e6ea;
            border-color: #3b3f45;
        }
        body.dark .language-switcher-btn:hover {
            background-color: #3b3f45;
            color: #ffffff;
        }
        body.dark .language-switcher-btn:focus {
            background-color: #3b3f45;
            color: #ffffff;
            box-shadow: 0 0 0 0.2rem rgba(93, 173, 226, 0.25);
        }
        body.dark .dropdown-menu {
            background-color: #1b1e22;
            border-color: #3b3f45;
        }
        body.dark .dropdown-item {
            color: #e3e6ea;
        }
        body.dark .dropdown-item:hover {
            background-color: #2a2d32;
            color: #ffffff;
        }
        body.dark .dropdown-item.active {
            background-color: #0d6efd;
            color: #fff;
        }
        body.dark .dropdown-item.active .language-name-item {
            color: #fff;
        }
        body.dark .dropdown-divider {
            border-top-color: #3b3f45;
        }
        
        /* RTL adjustments for language switcher */
        <?php if ($isRTL): ?>
        .language-flag-item {
            margin-right: 0;
            margin-left: 0.5rem;
        }
        <?php endif; ?>
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top navbar-lakbay">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo SITE_URL; ?>/index.php">
                <img src="<?php echo SITE_URL; ?>/images/logo.png" alt="Logo" height="28" class="me-2">
                <span class="fw-bold">GlobeGo</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto align-items-lg-center">
                    <?php
                    // Get current page filename
                    $current_page = basename($_SERVER['PHP_SELF']);
                    
                    // Define navigation items
                    $nav_items = [
                        ['url' => 'index.php', 'text' => Language::t('nav.home')],
                        ['url' => 'tours.php', 'text' => Language::t('nav.destinations')],
                        ['url' => 'offers.php', 'text' => Language::t('nav.special_offers'), 'glow' => true],
                        ['url' => 'about.php', 'text' => Language::t('nav.about_us')]
                    ];
                    
                    foreach ($nav_items as $item):
                        $is_active = ($current_page === $item['url'] || 
                                     ($item['url'] === 'index.php' && ($current_page === 'index.php' || $current_page === '')));
                        $glow_class = isset($item['glow']) && $item['glow'] ? 'nav-link-glow' : '';
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $is_active ? 'active' : ''; ?> <?php echo $glow_class; ?>" href="<?php echo SITE_URL . '/' . $item['url']; ?>">
                            <?php echo $item['text']; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                    <?php if (isLoggedIn()): ?>
                        <?php
                        $dashboard_active = ($current_page === 'dashboard.php');
                        $create_tour_active = ($current_page === 'create-tour.php');
                        $admin_dashboard_active = ($current_page === 'dashboard_mvc.php');
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $dashboard_active ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/dashboard.php"><?php echo Language::t('nav.dashboard'); ?></a>
                        </li>
                        <?php if (isGuide()): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $create_tour_active ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/guide/create-tour.php"><?php echo Language::t('nav.create_tour'); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $admin_dashboard_active ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/admin/dashboard_mvc.php"><?php echo Language::t('nav.admin_panel'); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <!-- Language Switcher - Hidden for Admins -->
                    <?php if (!isAdmin()): ?>
                    <li class="nav-item me-2">
                        <div class="dropdown">
                            <button class="btn btn-light border-0 rounded-circle shadow-sm dropdown-toggle language-switcher-btn" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="<?php echo Language::t('common.select_language'); ?>" title="<?php echo Language::getLanguageName(); ?>">
                                <span class="language-flag"><?php echo Language::getLanguageFlag(); ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                                <?php foreach (Language::getAllLanguagesWithNames() as $code => $lang): ?>
                                <li>
                                    <a class="dropdown-item <?php echo $currentLang === $code ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/switch-language.php?lang=<?php echo $code; ?>" title="<?php echo $lang['name']; ?>">
                                        <span class="language-flag-item"><?php echo $lang['flag']; ?></span>
                                        <span class="language-name-item"><?php echo $lang['name']; ?></span>
                                        <?php if ($currentLang === $code): ?>
                                            <i class="fas fa-check ms-2"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item me-2">
                        <button id="themeToggle" class="btn btn-light border-0 rounded-circle shadow-sm" type="button" aria-label="Toggle theme">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/profile.php"><?php echo Language::t('nav.profile'); ?></a></li>
                                <?php if (isTourist()): ?>
                                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/tourist/itinerary.php"><?php echo Language::t('nav.my_itinerary'); ?></a></li>
                                <?php endif; ?>
                                <?php if (isGuide()): ?>
                                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/guide/my-tours.php"><?php echo Language::t('nav.my_tours'); ?></a></li>
                                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/guide/bookings.php"><?php echo Language::t('nav.my_bookings'); ?></a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/auth/logout.php"><?php echo Language::t('nav.logout'); ?></a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item me-2"><a class="btn btn-outline-dark rounded-pill px-3 topbar-btn" href="<?php echo SITE_URL; ?>/auth/register.php"><?php echo Language::t('nav.sign_up'); ?></a></li>
                        <li class="nav-item me-2"><a class="btn btn-primary rounded-pill px-3 topbar-btn" href="<?php echo SITE_URL; ?>/auth/register-guide.php"><i class="fas fa-user-tie me-1"></i><?php echo Language::t('nav.become_guide'); ?></a></li>
                        <li class="nav-item"><a class="btn btn-dark rounded-pill px-3 topbar-btn" href="<?php echo SITE_URL; ?>/auth/login.php"><?php echo Language::t('nav.login'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Multi-Ticket Discount Banner -->
    <div class="multi-ticket-banner" id="multiTicketBanner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <div class="banner-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="banner-content">
                            <strong class="banner-title"><?php echo Language::t('home.discount_banner_title'); ?></strong>
                            <span class="banner-text"><?php echo Language::t('home.discount_banner_text'); ?></span>
                            <small class="banner-disclaimer d-block mt-1" style="opacity: 0.85; font-size: 0.85rem;">
                                <i class="fas fa-info-circle me-1"></i><?php echo Language::t('home.discount_banner_disclaimer'); ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 text-end mt-2 mt-md-0">
                    <div class="discount-badges d-flex gap-2 justify-content-end flex-wrap">
                        <span class="badge discount-badge-5"><?php echo Language::t('home.tickets_2'); ?> = 5% OFF</span>
                        <span class="badge discount-badge-10"><?php echo Language::t('home.tickets_3'); ?> = 10% OFF</span>
                        <span class="badge discount-badge-15"><?php echo Language::t('home.tickets_4'); ?> = 15% OFF</span>
                    </div>
                </div>
            </div>
        </div>
        <button class="banner-close" id="closeBanner" aria-label="<?php echo Language::t('common.close'); ?>">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <style>
    /* Multi-Ticket Discount Banner */
    .multi-ticket-banner {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border-bottom: 2px solid rgba(93, 173, 226, 0.3);
        padding: 0.75rem 0;
        position: sticky;
        top: 56px; /* Below navbar */
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .multi-ticket-banner.hidden {
        display: none;
    }
    
    .banner-icon {
        font-size: 1.5rem;
        color: #5dade2;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .banner-content {
        color: #ffffff;
    }
    
    .banner-title {
        color: #ffffff;
        font-size: 1rem;
        margin-right: 0.5rem;
    }
    
    .banner-text {
        color: #e3e6ea;
        font-size: 0.9rem;
    }
    
    .banner-disclaimer {
        color: #9ca3af !important;
        font-size: 0.85rem !important;
        font-style: italic;
        line-height: 1.4;
    }
    
    .banner-disclaimer i {
        color: #5dade2;
        font-size: 0.9rem;
    }
    
    .discount-badges .badge {
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }
    
    .discount-badge-5 {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 50%, #f57c00 100%);
        color: #000;
    }
    
    .discount-badge-10 {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
        color: #fff;
    }
    
    .discount-badge-15 {
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
        color: #fff;
    }
    
    .discount-badges .badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    
    .banner-close {
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 1.2rem;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.3s ease;
        padding: 0.25rem 0.5rem;
    }
    
    .banner-close:hover {
        opacity: 1;
    }
    
    /* Light Mode Banner Styles */
    body:not(.dark) .multi-ticket-banner {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%) !important;
        border-bottom: 2px solid rgba(0, 123, 255, 0.2) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    }
    
    body:not(.dark) .banner-icon {
        color: #007bff !important;
    }
    
    body:not(.dark) .banner-content {
        color: #212529 !important;
    }
    
    body:not(.dark) .banner-title {
        color: #212529 !important;
        font-weight: 700;
    }
    
    body:not(.dark) .banner-text {
        color: #495057 !important;
    }
    
    body:not(.dark) .banner-disclaimer {
        color: #6c757d !important;
        opacity: 0.9 !important;
    }
    
    body:not(.dark) .banner-disclaimer i {
        color: #007bff !important;
    }
    
    body:not(.dark) .banner-close {
        color: #212529 !important;
        opacity: 0.6;
    }
    
    body:not(.dark) .banner-close:hover {
        opacity: 1;
        color: #000000 !important;
    }
    
    @media (max-width: 768px) {
        .multi-ticket-banner {
            top: 0;
            padding: 0.5rem 0;
        }
        
        .banner-content {
            font-size: 0.85rem;
        }
        
        .banner-title {
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .discount-badges {
            justify-content: center !important;
        }
        
        .discount-badges .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
        }
        
        .banner-close {
            right: 0.5rem;
        }
    }
    
    /* Glowing effect for Special offers nav link - Gold */
    .nav-link-glow {
        position: relative;
        color: #ffd700 !important;
        font-weight: 600;
        text-shadow: 0 0 10px rgba(255, 215, 0, 0.8),
                     0 0 20px rgba(255, 215, 0, 0.6),
                     0 0 30px rgba(255, 215, 0, 0.4),
                     0 0 40px rgba(255, 193, 7, 0.3);
        animation: glow-pulse-gold 2s ease-in-out infinite alternate;
    }
    
    .nav-link-glow:hover {
        color: #ffed4e !important;
        text-shadow: 0 0 15px rgba(255, 215, 0, 1),
                     0 0 25px rgba(255, 215, 0, 0.8),
                     0 0 35px rgba(255, 215, 0, 0.6),
                     0 0 45px rgba(255, 193, 7, 0.4);
    }
    
    .nav-link-glow.active {
        color: #ffd700 !important;
        text-shadow: 0 0 10px rgba(255, 215, 0, 1),
                     0 0 20px rgba(255, 215, 0, 0.8),
                     0 0 30px rgba(255, 215, 0, 0.6),
                     0 0 40px rgba(255, 193, 7, 0.4);
    }
    
    @keyframes glow-pulse-gold {
        0% {
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.8),
                         0 0 20px rgba(255, 215, 0, 0.6),
                         0 0 30px rgba(255, 215, 0, 0.4),
                         0 0 40px rgba(255, 193, 7, 0.3);
        }
        100% {
            text-shadow: 0 0 15px rgba(255, 215, 0, 1),
                         0 0 25px rgba(255, 215, 0, 0.8),
                         0 0 35px rgba(255, 215, 0, 0.6),
                         0 0 45px rgba(255, 193, 7, 0.5);
        }
    }
    </style>
    
    <script>
    // Close banner functionality
    document.addEventListener('DOMContentLoaded', function() {
        const banner = document.getElementById('multiTicketBanner');
        const closeBtn = document.getElementById('closeBanner');
        
        if (closeBtn && banner) {
            closeBtn.addEventListener('click', function() {
                banner.style.transition = 'opacity 0.3s ease';
                banner.style.opacity = '0';
                setTimeout(function() {
                    banner.classList.add('hidden');
                }, 300);
            });
        }
        
        // Smooth sliding underline for navigation
        function updateNavUnderline(targetLink) {
            // Get the first navbar-nav (main navigation, not user menu) - use :first-of-type
            const navNav = document.querySelector('#navbarNav > .navbar-nav:first-of-type');
            
            if (!navNav) {
                console.warn('Navigation nav not found');
                return;
            }
            
            // Use target link if provided, otherwise use active link from main nav
            const linkToUse = targetLink || navNav.querySelector('.nav-link.active');
            
            if (linkToUse) {
                const navNavRect = navNav.getBoundingClientRect();
                const linkRect = linkToUse.getBoundingClientRect();
                
                const left = linkRect.left - navNavRect.left;
                const width = linkRect.width;
                const centerOffset = width * 0.1; // 10% offset to center the 80% width
                
                // Set CSS variables for smooth animation
                navNav.style.setProperty('--underline-left', (left + centerOffset) + 'px');
                navNav.style.setProperty('--underline-width', (width * 0.8) + 'px');
            }
        }
        
        // Wait for DOM to be fully ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    updateNavUnderline();
                }, 200);
            });
        } else {
            setTimeout(function() {
                updateNavUnderline();
            }, 200);
        }
        
        // Update on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                updateNavUnderline();
            }, 100);
        });
        
        // Make underline follow cursor on hover - only for main navigation links
        setTimeout(function() {
            const navNav = document.querySelector('#navbarNav > .navbar-nav:first-of-type');
            if (!navNav) return;
            
            const navLinks = navNav.querySelectorAll('.nav-link');
            let hoveredLink = null;
            
            navLinks.forEach(link => {
                // On mouse enter, move underline to hovered link
                link.addEventListener('mouseenter', function(e) {
                    e.stopPropagation();
                    hoveredLink = this;
                    updateNavUnderline(this);
                });
                
                // On mouse leave, return underline to active link
                link.addEventListener('mouseleave', function(e) {
                    e.stopPropagation();
                    hoveredLink = null;
                    updateNavUnderline();
                });
                
                // Update when clicking nav links (for smooth transition)
                link.addEventListener('click', function() {
                    // Small delay to allow active class to update
                    setTimeout(function() {
                        updateNavUnderline();
                    }, 50);
                });
            });
            
            // Also handle mouse leave on the entire nav to reset
            navNav.addEventListener('mouseleave', function() {
                hoveredLink = null;
                updateNavUnderline();
            });
        }, 300);
    });
    </script>
    
