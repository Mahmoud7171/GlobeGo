<?php
// Help Center view
?>

<!-- Hero Section -->
<section class="bg-gradient-primary text-white py-5 mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="mb-4">
                    <i class="fas fa-question-circle fa-4x mb-3 opacity-75"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">Help Center</h1>
                <p class="lead mb-0">Find answers to common questions and get the support you need</p>
            </div>
        </div>
    </div>
</section>

<div class="container mb-5">
    <!-- Search Bar -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-0" placeholder="Search for help articles..." id="helpSearch">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold mb-2">Quick Links</h2>
            <p class="text-muted">Get started quickly with these popular topics</p>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 text-center hover-lift" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-plus fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Getting Started</h5>
                    <p class="text-muted small mb-0">Learn how to create an account and book your first tour</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 text-center hover-lift" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-calendar-check fa-2x text-success"></i>
                    </div>
                    <h5 class="fw-bold">Booking Tours</h5>
                    <p class="text-muted small mb-0">Everything you need to know about booking and managing tours</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 text-center hover-lift" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-credit-card fa-2x text-info"></i>
                    </div>
                    <h5 class="fw-bold">Payments</h5>
                    <p class="text-muted small mb-0">Payment methods, refunds, and billing questions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 text-center hover-lift" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div class="card-body p-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-tie fa-2x text-warning"></i>
                    </div>
                    <h5 class="fw-bold">Become a Guide</h5>
                    <p class="text-muted small mb-0">Learn how to become a verified guide on GlobeGo</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Frequently Asked Questions</h2>
                <p class="text-muted">Find quick answers to the most common questions</p>
            </div>
            
            <div class="accordion" id="faqAccordion">
                <!-- Getting Started -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            <i class="fas fa-user-plus"></i> How do I create an account?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Creating an account is easy! Click on the "Sign-up" button in the top navigation, fill in your details (name, email, password), and you're all set. You can also sign up as a tour guide if you're interested in leading tours.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            <i class="fas fa-search"></i> How do I search for tours?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">You can search for tours in several ways: Use the search bar on the homepage to search by location, browse the "Destinations" page to see all available tours, or filter tours by attraction, price, or date to find exactly what you're looking for.</p>
                        </div>
                    </div>
                </div>

                <!-- Booking -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            <i class="fas fa-calendar"></i> How do I book a tour?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">To book a tour: 1) Find a tour you're interested in, 2) Click "View Details" or "Book Now", 3) Select your preferred date and time, 4) Choose the number of participants, 5) Complete the payment, and you're done! You'll receive a confirmation email with all the details.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                            <i class="fas fa-undo"></i> Can I cancel or modify my booking?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Yes! You can cancel or modify your booking from your dashboard. Go to "Your Booked Tours" section, click "View" on the booking you want to change, and follow the options. Please note that cancellation policies may vary depending on the tour and how close to the tour date you cancel.</p>
                        </div>
                    </div>
                </div>

                <!-- Payments -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                            <i class="fas fa-money-bill-wave"></i> What payment methods do you accept?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">We accept multiple payment methods including credit cards, debit cards, PayPal, and bank transfers. All payments are processed securely through our encrypted payment gateway to ensure your financial information is protected.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix">
                            <i class="fas fa-receipt"></i> Will I receive a receipt?
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Yes! After completing your booking, you'll receive a confirmation email with a receipt and all booking details. You can also view and download receipts from your dashboard under "Your Booked Tours".</p>
                        </div>
                    </div>
                </div>

                <!-- Guides -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSeven">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven">
                            <i class="fas fa-user-tie"></i> How do I become a tour guide?
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">To become a guide, click "Become a Guide" in the navigation and fill out the guide registration form. You'll need to provide additional information including your national ID, address, and answer questions about criminal records. After submission, our team will review your application and contact you via email to schedule an interview. Once approved, you'll be verified and can start creating tours!</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEight">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight">
                            <i class="fas fa-shield-alt"></i> Are guides verified?
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Yes! All guides go through a thorough verification process. We verify their identity, check their background, and conduct interviews to ensure they meet our quality standards. Only verified guides can create and lead tours on our platform.</p>
                        </div>
                    </div>
                </div>

                <!-- Account Issues -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingNine">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine">
                            <i class="fas fa-key"></i> I forgot my password. How do I reset it?
                        </button>
                    </h2>
                    <div id="collapseNine" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">Click on "Forgot your password?" on the login page, enter your email address, and we'll send you a password reset link. Make sure to check your spam folder if you don't see the email. The link will expire after 24 hours for security reasons.</p>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTen">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen">
                            <i class="fas fa-ban"></i> My account is suspended. What should I do?
                        </button>
                    </h2>
                    <div id="collapseTen" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p class="mb-0">If your account is suspended, you'll see a message when trying to log in with details about the suspension period. If you believe this is an error or need assistance, please contact our support team at <a href="mailto:<?php echo SUPPORT_EMAIL; ?>"><?php echo SUPPORT_EMAIL; ?></a> or call us at <?php echo SUPPORT_PHONE; ?>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-3">Still Need Help?</h3>
                    <p class="lead text-muted mb-4">Our support team is here to assist you. Get in touch with us!</p>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0">
                                <div class="card-body">
                                    <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                    <h5>Email Support</h5>
                                    <p class="text-muted mb-0">
                                        <a href="mailto:<?php echo SUPPORT_EMAIL; ?>"><?php echo SUPPORT_EMAIL; ?></a>
                                    </p>
                                    <small class="text-muted">We typically respond within 24 hours</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0">
                                <div class="card-body">
                                    <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                    <h5>Phone Support</h5>
                                    <p class="text-muted mb-0">
                                        <a href="tel:<?php echo str_replace([' ', '(', ')', '-'], '', SUPPORT_PHONE); ?>"><?php echo SUPPORT_PHONE; ?></a>
                                    </p>
                                    <small class="text-muted">Mon-Fri: 9 AM - 6 PM</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="<?php echo SITE_URL; ?>/contact.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-comments me-2"></i>Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Enhanced Accordion Styling */
.accordion-item {
    border: none !important;
    margin-bottom: 1rem;
    border-radius: 0.75rem !important;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.accordion-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.accordion-button {
    background-color: #f8f9fa !important;
    border: none !important;
    padding: 1.25rem 1.5rem !important;
    font-size: 1.05rem;
    font-weight: 600;
    color: #212529 !important;
    border-radius: 0.75rem !important;
    transition: all 0.3s ease;
}

.accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.accordion-button:not(.collapsed) i {
    color: #ffffff !important;
}

.accordion-button:hover {
    background-color: #e9ecef !important;
}

.accordion-button:not(.collapsed):hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a4190 100%) !important;
}

.accordion-button::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    width: 1.25rem;
    height: 1.25rem;
    transition: transform 0.3s ease;
}

.accordion-button:not(.collapsed)::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ffffff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    transform: rotate(180deg);
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
    border-color: transparent !important;
}

.accordion-body {
    background-color: #ffffff;
    padding: 1.5rem !important;
    line-height: 1.8;
    color: #495057;
    font-size: 1rem;
    border-top: 1px solid #e9ecef;
}

.accordion-item .accordion-button i {
    width: 24px;
    text-align: center;
    margin-right: 0.75rem;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.accordion-button:not(.collapsed) i {
    transform: scale(1.1);
}
</style>

<script>
// Simple search functionality
document.getElementById('helpSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm) || searchTerm === '') {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>

