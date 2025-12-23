<?php
// Contact Us view. Expects: $success_message, $error_message
?>

<!-- Hero Section -->
<section class="bg-gradient-primary text-white py-5 mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="mb-4">
                    <i class="fas fa-envelope fa-4x mb-3 opacity-75"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">Contact Us</h1>
                <p class="lead mb-0">We'd love to hear from you. Get in touch with our team!</p>
            </div>
        </div>
    </div>
</section>

<div class="container mb-5">
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Contact Information -->
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">Get in Touch</h3>
                    <p class="text-muted mb-4">Have a question or need assistance? We're here to help! Reach out to us through any of the following methods.</p>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Email Us</h5>
                                <p class="text-muted mb-0">
                                    <a href="mailto:<?php echo SUPPORT_EMAIL; ?>" class="text-decoration-none"><?php echo SUPPORT_EMAIL; ?></a>
                                </p>
                                <small class="text-muted">We typically respond within 24 hours</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                <i class="fas fa-phone text-success"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Call Us</h5>
                                <p class="text-muted mb-0">
                                    <a href="tel:<?php echo str_replace([' ', '(', ')', '-'], '', SUPPORT_PHONE); ?>" class="text-decoration-none"><?php echo SUPPORT_PHONE; ?></a>
                                </p>
                                <small class="text-muted">Mon-Fri: 9 AM - 6 PM</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                <i class="fas fa-clock text-info"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Business Hours</h5>
                                <p class="text-muted mb-0">
                                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                                    Saturday - Sunday: 10:00 AM - 4:00 PM
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div>
                        <h5 class="fw-bold mb-3">Follow Us</h5>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-decoration-none">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fab fa-facebook-f text-primary"></i>
                                </div>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fab fa-twitter text-info"></i>
                                </div>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fab fa-instagram text-danger"></i>
                                </div>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fab fa-linkedin-in text-primary"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">Send us a Message</h3>
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Your Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="subject" name="subject" 
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="6" 
                                      required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-paper-plane me-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Information -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="row text-center">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                            <h5 class="fw-bold">24/7 Support</h5>
                            <p class="text-muted mb-0">Our support team is available to help you anytime you need assistance.</p>
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                            <h5 class="fw-bold">Secure & Safe</h5>
                            <p class="text-muted mb-0">Your information is protected with industry-standard security measures.</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-reply fa-3x text-info mb-3"></i>
                            <h5 class="fw-bold">Quick Response</h5>
                            <p class="text-muted mb-0">We aim to respond to all inquiries within 24 hours during business days.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

