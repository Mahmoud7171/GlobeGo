    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5" style="background: linear-gradient(135deg, #343a40 0%, #212529 100%) !important;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-white mb-3" style="color: #ffffff !important; font-weight: 600;"><i class="fas fa-globe-americas me-2"></i> GlobeGo</h5>
                    <p class="text-white-50" style="color: rgba(255, 255, 255, 0.8) !important; line-height: 1.6;"><?php echo Language::t('footer.description'); ?></p>
                </div>
                <div class="col-md-2">
                    <h6 class="text-white mb-3" style="color: #ffffff !important; font-weight: 600;"><?php echo Language::t('footer.quick_links'); ?></h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/tours.php" class="text-white-50" style="color: rgba(255, 255, 255, 0.8) !important; text-decoration: none; transition: color 0.3s ease;"><?php echo Language::t('footer.tours'); ?></a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/attractions.php" class="text-white-50" style="color: rgba(255, 255, 255, 0.8) !important; text-decoration: none; transition: color 0.3s ease;"><?php echo Language::t('footer.attractions'); ?></a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/about.php" class="text-white-50" style="color: rgba(255, 255, 255, 0.8) !important; text-decoration: none; transition: color 0.3s ease;"><?php echo Language::t('nav.about_us'); ?></a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/auth/register-guide.php" class="text-white-50" style="color: rgba(255, 255, 255, 0.8) !important; text-decoration: none; transition: color 0.3s ease;"><?php echo Language::t('nav.become_guide'); ?></a></li>
                    </ul>
                </div>
                <?php if (!isAdmin()): ?>
                <div class="col-md-2">
                    <h6 class="text-white mb-3" style="color: #ffffff !important; font-weight: 600;"><?php echo Language::t('footer.support'); ?></h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/help.php" class="text-white-50" style="color: rgba(255, 255, 255, 0.8) !important; text-decoration: none; transition: color 0.3s ease;"><?php echo Language::t('footer.help_center'); ?></a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/contact.php" class="text-white-50" style="color: rgba(255, 255, 255, 0.8) !important; text-decoration: none; transition: color 0.3s ease;"><?php echo Language::t('footer.contact_us'); ?></a></li>
                        <li class="mb-2"><a href="<?php echo SITE_URL; ?>/terms.php" class="text-white-50" style="color: rgba(255, 255, 255, 0.8) !important; text-decoration: none; transition: color 0.3s ease;"><?php echo Language::t('footer.terms'); ?></a></li>
                    </ul>
                </div>
                <?php endif; ?>
                <div class="col-md-4">
                    <h6 class="text-white mb-3" style="color: #ffffff !important; font-weight: 600;"><?php echo Language::t('footer.connect'); ?></h6>
                    <div class="social-links">
                        <a href="#" class="text-white me-3" style="color: #ffffff !important; font-size: 1.5rem; transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.color='#007bff'; this.style.transform='translateY(-3px)'" onmouseout="this.style.color='#ffffff'; this.style.transform='translateY(0)'"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3" style="color: #ffffff !important; font-size: 1.5rem; transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.color='#1DA1F2'; this.style.transform='translateY(-3px)'" onmouseout="this.style.color='#ffffff'; this.style.transform='translateY(0)'"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3" style="color: #ffffff !important; font-size: 1.5rem; transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.color='#E4405F'; this.style.transform='translateY(-3px)'" onmouseout="this.style.color='#ffffff'; this.style.transform='translateY(0)'"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-3" style="color: #ffffff !important; font-size: 1.5rem; transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.color='#0077B5'; this.style.transform='translateY(-3px)'" onmouseout="this.style.color='#ffffff'; this.style.transform='translateY(0)'"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.2) !important;">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-white-50 text-center mb-0" style="color: rgba(255, 255, 255, 0.7) !important;"><?php echo Language::t('footer.copyright'); ?></p>
                </div>
            </div>
        </div>
    </footer>
    
    <style>
    /* Footer Link Hover Effects */
    footer a.text-white-50:hover {
        color: #ffffff !important;
        text-decoration: underline !important;
    }
    
    /* Ensure footer is visible in both themes */
    footer {
        background: linear-gradient(135deg, #343a40 0%, #212529 100%) !important;
    }
    
    body.dark footer {
        background: linear-gradient(135deg, #343a40 0%, #212529 100%) !important;
    }
    
    /* Footer text visibility */
    footer h5,
    footer h6 {
        color: #ffffff !important;
        font-weight: 600;
    }
    
    footer p {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    footer a {
        transition: color 0.3s ease, transform 0.3s ease;
    }
    
    footer a:hover {
        color: #ffffff !important;
    }
    </style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Idle Screen Overlay -->
    <div id="idle-overlay" class="idle-overlay">
        <div class="idle-content">
            <div class="idle-message">
                <img src="<?php echo SITE_URL; ?>/images/hat.png" alt="Captain" />
                <span>And a message from your captain..</span>
            </div>
            <div class="idle-tips-container">
                <div class="idle-tip active" data-tip="0">
                    <i class="fas fa-quote-left"></i>
                    <p class="tip-text"></p>
                    <i class="fas fa-quote-right"></i>
                </div>
            </div>
        </div>
        <audio id="idle-audio" preload="auto" volume="1.0">
            <source src="<?php echo SITE_URL; ?>/Sounds/announcement.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    </div>
    
    <!-- Chatbot -->
    <div id="chatbot-container" class="chatbot-container">
        <div id="chatbot-window" class="chatbot-window">
            <div class="chatbot-header">
                    <div class="chatbot-header-content">
                        <i class="fas fa-robot me-2"></i>
                        <span><?php echo Language::t('chatbot.title'); ?></span>
                    </div>
                <button id="chatbot-close" class="chatbot-close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="chatbot-messages" class="chatbot-messages">
                <div class="chatbot-message bot-message">
                    <div class="message-content">
                        <i class="fas fa-robot me-2"></i>
                        <div>
                            <p><?php echo Language::t('chatbot.greeting'); ?></p>
                            <ul>
                                <li><?php echo Language::t('chatbot.refund_policies'); ?></li>
                                <li><?php echo Language::t('chatbot.special_offers'); ?></li>
                                <li><?php echo Language::t('chatbot.destinations'); ?></li>
                                <li><?php echo Language::t('chatbot.guide_info'); ?></li>
                                <li><?php echo Language::t('chatbot.payment'); ?></li>
                                <li><?php echo Language::t('chatbot.account_booking'); ?></li>
                            </ul>
                            <p><?php echo Language::t('chatbot.what_help'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chatbot-input-container">
                <input type="text" id="chatbot-input" class="chatbot-input" placeholder="<?php echo Language::t('chatbot.input_placeholder'); ?>" autocomplete="off">
                <button id="chatbot-send" class="chatbot-send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
        <button id="chatbot-toggle" class="chatbot-toggle-btn">
            <i class="fas fa-comments"></i>
            <span class="chatbot-badge">1</span>
        </button>
    </div>

    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
    
</body>
</html>
