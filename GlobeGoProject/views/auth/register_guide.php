<?php
// Guide registration view. Expects: $error_message, $success_message
?>

<div class="guide-register-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="guide-register-card">
                    <div class="guide-register-header">
                        <h2 class="guide-register-title">APPLY TO BECOME A TOUR GUIDE</h2>
                        <p class="guide-register-subtitle">Join our team of expert guides and share your passion with travelers</p>
                    </div>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-custom"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success alert-custom">
                            <h5><i class="fas fa-check-circle me-2"></i>Application Submitted!</h5>
                            <p class="mb-0"><?php echo $success_message; ?></p>
                            <hr>
                            <p class="mb-0"><strong>Next Steps:</strong></p>
                            <ul class="mb-0">
                                <li>Check your email regularly for interview scheduling</li>
                                <li>Our team will contact you within 3-5 business days</li>
                                <li>Make sure your email address is correct</li>
                            </ul>
                            <div class="mt-3">
                                <a href="<?php echo SITE_URL; ?>/index.php" class="btn-guide-submit">Return to Homepage</a>
                            </div>
                        </div>
                    <?php else: ?>
                    
                    <form method="POST" action="<?php echo SITE_URL; ?>/auth/register-guide.php" class="needs-validation guide-register-form" novalidate>
                        <h5 class="section-title mb-4">
                            <i class="fas fa-user me-2"></i>Personal Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="first_name" class="form-label-custom">
                                        <i class="fas fa-user me-2"></i>First Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control-custom" id="first_name" name="first_name" 
                                           value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" 
                                           placeholder="Enter your first name"
                                           required>
                                    <div class="invalid-feedback">
                                        Please enter your first name.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="last_name" class="form-label-custom">
                                        <i class="fas fa-user me-2"></i>Last Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control-custom" id="last_name" name="last_name" 
                                           value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" 
                                           placeholder="Enter your last name"
                                           required>
                                    <div class="invalid-feedback">
                                        Please enter your last name.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="email" class="form-label-custom">
                                        <i class="fas fa-envelope me-2"></i>Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control-custom" id="email" name="email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                           placeholder="Enter your email address"
                                           pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                                           required>
                                    <small class="form-text-custom">We'll use this to contact you about your application</small>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="phone" class="form-label-custom">
                                        <i class="fas fa-phone me-2"></i>Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group-custom">
                                        <div class="country-code-selector">
                                            <button type="button" class="btn country-code-btn" 
                                                    id="countryCodeBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="country-flag" id="countryFlag">🇺🇸</span>
                                                <span class="country-code" id="countryCode">+1</span>
                                                <i class="fas fa-chevron-down ms-2"></i>
                                            </button>
                                            <ul class="dropdown-menu country-code-menu" id="countryCodeMenu" aria-labelledby="countryCodeBtn">
                                                <!-- Country codes will be populated by JavaScript -->
                                            </ul>
                                        </div>
                                        <input type="tel" class="form-control-custom phone-input" id="phone" name="phone" 
                                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                                               placeholder="Enter your phone number"
                                               pattern="[0-9\s\-\(\)]{7,}"
                                               required>
                                        <input type="hidden" id="full_phone" name="full_phone" value="">
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter a valid phone number.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-custom mb-4">
                            <label for="date_of_birth" class="form-label-custom">
                                <i class="fas fa-calendar me-2"></i>Date of Birth <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control-custom" id="date_of_birth" name="date_of_birth" 
                                   value="<?php echo isset($_POST['date_of_birth']) ? htmlspecialchars($_POST['date_of_birth']) : ''; ?>" 
                                   max="<?php echo date('Y-m-d'); ?>"
                                   required>
                            <div class="invalid-feedback">
                                Please enter a valid date of birth (cannot be in the future).
                            </div>
                        </div>

                        <h6 class="address-section-title mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>Address Information <span class="text-danger">*</span>
                        </h6>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group-custom mb-4">
                                    <label for="street" class="form-label-custom">Street Address</label>
                                    <input type="text" class="form-control-custom" id="street" name="street" 
                                           value="<?php echo isset($_POST['street']) ? htmlspecialchars($_POST['street']) : ''; ?>" 
                                           placeholder="Enter street address"
                                           required>
                                    <div class="invalid-feedback">
                                        Please enter your street address.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group-custom mb-4">
                                    <label for="building" class="form-label-custom">Building/Apt</label>
                                    <input type="text" class="form-control-custom" id="building" name="building" 
                                           value="<?php echo isset($_POST['building']) ? htmlspecialchars($_POST['building']) : ''; ?>" 
                                           placeholder="Apt, Suite, etc.">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="city" class="form-label-custom">City</label>
                                    <input type="text" class="form-control-custom" id="city" name="city" 
                                           value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" 
                                           placeholder="Enter city"
                                           required>
                                    <div class="invalid-feedback">
                                        Please enter your city.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-custom mb-4">
                                    <label for="state" class="form-label-custom">State/Province</label>
                                    <input type="text" class="form-control-custom" id="state" name="state" 
                                           value="<?php echo isset($_POST['state']) ? htmlspecialchars($_POST['state']) : ''; ?>" 
                                           placeholder="State/Province"
                                           required>
                                    <div class="invalid-feedback">
                                        Please enter your state/province.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group-custom mb-4">
                                    <label for="postal_code" class="form-label-custom">Postal Code</label>
                                    <input type="text" class="form-control-custom" id="postal_code" name="postal_code" 
                                           value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code']) : ''; ?>" 
                                           placeholder="Postal Code"
                                           required>
                                    <div class="invalid-feedback">
                                        Please enter your postal code.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-custom mb-4">
                            <label for="home_country" class="form-label-custom">
                                <i class="fas fa-globe me-2"></i>Home Country <span class="text-danger">*</span>
                            </label>
                            <select class="form-control-custom" id="home_country" name="home_country" required>
                                <option value="">Select your home country</option>
                                <option value="Egypt" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Egypt') ? 'selected' : ''; ?>>🇪🇬 Egypt</option>
                                <option value="Saudi Arabia" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Saudi Arabia') ? 'selected' : ''; ?>>🇸🇦 Saudi Arabia</option>
                                <option value="United States" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'United States') ? 'selected' : ''; ?>>🇺🇸 United States</option>
                                <option value="United Kingdom" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'United Kingdom') ? 'selected' : ''; ?>>🇬🇧 United Kingdom</option>
                                <option value="United Arab Emirates" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'United Arab Emirates') ? 'selected' : ''; ?>>🇦🇪 United Arab Emirates</option>
                                <option value="India" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'India') ? 'selected' : ''; ?>>🇮🇳 India</option>
                                <option value="Pakistan" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Pakistan') ? 'selected' : ''; ?>>🇵🇰 Pakistan</option>
                                <option value="Jordan" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Jordan') ? 'selected' : ''; ?>>🇯🇴 Jordan</option>
                                <option value="Lebanon" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Lebanon') ? 'selected' : ''; ?>>🇱🇧 Lebanon</option>
                                <option value="Kuwait" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Kuwait') ? 'selected' : ''; ?>>🇰🇼 Kuwait</option>
                                <option value="Qatar" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Qatar') ? 'selected' : ''; ?>>🇶🇦 Qatar</option>
                                <option value="Bahrain" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Bahrain') ? 'selected' : ''; ?>>🇧🇭 Bahrain</option>
                                <option value="Oman" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Oman') ? 'selected' : ''; ?>>🇴🇲 Oman</option>
                                <option value="Other" <?php echo (isset($_POST['home_country']) && $_POST['home_country'] === 'Other') ? 'selected' : ''; ?>>🌍 Other (Passport)</option>
                            </select>
                            <small class="form-text-custom">Select your country of origin for ID validation</small>
                            <div class="invalid-feedback">
                                Please select your home country.
                            </div>
                        </div>

                        <div class="form-group-custom mb-4">
                            <label for="national_id" class="form-label-custom">
                                <i class="fas fa-id-card me-2"></i>National ID / Passport Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control-custom" id="national_id" name="national_id" 
                                   value="<?php echo isset($_POST['national_id']) ? htmlspecialchars($_POST['national_id']) : ''; ?>" 
                                   placeholder="Enter your ID/Passport number"
                                   required>
                            <small class="form-text-custom" id="national_id_hint">Government-issued identification number</small>
                            <div class="invalid-feedback" id="national_id_feedback">
                                Please enter a valid National ID or Passport number.
                            </div>
                        </div>

                        <hr class="section-divider my-5">
                        <h5 class="section-title mb-4">
                            <i class="fas fa-shield-alt me-2"></i>Background Check
                        </h5>
                        
                        <div class="form-group-custom mb-4">
                            <div class="form-check-custom" id="criminal_records_container">
                                <input class="form-check-input-custom" type="checkbox" id="criminal_records" name="criminal_records" value="1"
                                       <?php echo (isset($_POST['criminal_records']) && $_POST['criminal_records'] == '1') ? 'checked' : ''; ?>
                                       required>
                                <label class="form-check-label-custom" for="criminal_records">
                                    I confirm that I have <strong>NO</strong> criminal records or pending charges
                                </label>
                            </div>
                            <div class="invalid-feedback" id="criminal_records_feedback">
                                You must confirm that you have no criminal records.
                            </div>
                            <small class="form-text-custom">All guides must pass a background check. False information will result in immediate rejection.</small>
                        </div>

                        <hr class="section-divider my-5">
                        <h5 class="section-title mb-4">
                            <i class="fas fa-lock me-2"></i>Account Security
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="password" class="form-label-custom">
                                        <i class="fas fa-lock me-2"></i>Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control-custom" id="password" name="password" 
                                               placeholder="Enter your password (min. 6 characters)"
                                               minlength="6"
                                               required>
                                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                                    </div>
                                    <small class="form-text-custom">Minimum 6 characters</small>
                                    <div class="invalid-feedback">
                                        Password must be at least 6 characters long.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom mb-4">
                                    <label for="confirm_password" class="form-label-custom">
                                        <i class="fas fa-lock me-2"></i>Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="password-input-wrapper">
                                        <input type="password" class="form-control-custom" id="confirm_password" name="confirm_password" 
                                               placeholder="Confirm your password"
                                               required>
                                        <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                                    </div>
                                    <div class="invalid-feedback">
                                        Passwords do not match.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info alert-custom">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Application Process:</strong> After submitting your application, our team will review your information and contact you via email to schedule an interview. Please ensure your email address is correct and check your inbox regularly.
                        </div>
                        
                        <button type="submit" class="btn-guide-submit w-100">
                            <i class="fas fa-paper-plane me-2"></i>SUBMIT APPLICATION
                        </button>
                    </form>
                    
                    <?php endif; ?>
                    
                    <div class="guide-register-footer text-center mt-4">
                        <p class="mb-2">Already have an account? <a href="<?php echo SITE_URL; ?>/auth/login.php" class="login-link">Login here</a></p>
                        <p class="mb-0">Want to register as a tourist? <a href="<?php echo SITE_URL; ?>/auth/register.php" class="register-link">Sign up here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Guide Register Container */
.guide-register-container {
    min-height: calc(100vh - 200px);
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    padding: 2rem 0;
}

/* Guide Register Card */
.guide-register-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 3rem 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

body.dark .guide-register-card {
    background: rgba(26, 26, 46, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Guide Register Header */
.guide-register-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.guide-register-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
}

body.dark .guide-register-title {
    color: #e3e6ea;
}

.guide-register-subtitle {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 0;
}

body.dark .guide-register-subtitle {
    color: #9ca3af;
}

/* Section Titles */
.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #212529;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e9ecef;
}

body.dark .section-title {
    color: #e3e6ea;
    border-bottom-color: #3b3f45;
}

.address-section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #212529;
    margin-top: 1rem;
}

body.dark .address-section-title {
    color: #e3e6ea;
}

.section-divider {
    border-color: #e9ecef;
    opacity: 0.3;
}

body.dark .section-divider {
    border-color: #3b3f45;
}

/* Form Styles */
.form-label-custom {
    display: block;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

body.dark .form-label-custom {
    color: #e3e6ea;
}

.form-label-custom i {
    color: #007bff;
}

.form-control-custom {
    width: 100%;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    background-color: #fff;
    color: #212529;
    transition: all 0.3s ease;
}

body.dark .form-control-custom {
    background-color: #1b1e22;
    border-color: #3b3f45;
    color: #e3e6ea;
}

.form-control-custom:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    background-color: #fff;
}

body.dark .form-control-custom:focus {
    background-color: #1b1e22;
    border-color: #5dade2;
    box-shadow: 0 0 0 0.2rem rgba(93, 173, 226, 0.2);
}

.form-control-custom::placeholder {
    color: #adb5bd;
}

body.dark .form-control-custom::placeholder {
    color: #6c757d;
}

.form-control-custom.is-invalid {
    border-color: #dc3545;
}

.form-text-custom {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #6c757d;
}

body.dark .form-text-custom {
    color: #9ca3af;
}

/* Password Input Wrapper */
.password-input-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #007bff;
}

body.dark .password-toggle {
    color: #9ca3af;
}

body.dark .password-toggle:hover {
    color: #5dade2;
}

/* Country Code Selector */
.country-code-selector {
    position: relative;
    display: flex;
    align-items: stretch;
}

.country-code-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0.875rem 1rem;
    border: 2px solid #e9ecef;
    border-right: none;
    border-radius: 10px 0 0 10px;
    background-color: #fff;
    min-width: 110px;
    justify-content: center;
    color: #212529;
    font-weight: 600;
    transition: all 0.3s ease;
    height: 100%;
    margin: 0;
}

body.dark .country-code-btn {
    background-color: #1b1e22;
    border-color: #3b3f45;
    color: #e3e6ea;
}

.country-code-btn:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
    color: #212529;
}

body.dark .country-code-btn:hover {
    background-color: #2a2d32;
    border-color: #5dade2;
    color: #e3e6ea;
}

.country-code-btn:focus,
.country-code-btn:active {
    box-shadow: none;
    border-color: #007bff;
    outline: none;
}

body.dark .country-code-btn:focus,
body.dark .country-code-btn:active {
    border-color: #5dade2;
}

.country-flag {
    font-size: 1.2rem;
    line-height: 1;
}

.country-code {
    font-weight: 600;
}

.country-code-menu {
    max-height: 300px;
    overflow-y: auto;
    min-width: 280px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    border: 1px solid #e9ecef;
}

body.dark .country-code-menu {
    background-color: #1b1e22;
    border-color: #3b3f45;
}

.country-code-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.country-code-item:hover {
    background-color: #f8f9fa;
}

body.dark .country-code-item:hover {
    background-color: #2a2d32;
}

.country-code-item.active {
    background-color: #e7f1ff;
}

body.dark .country-code-item.active {
    background-color: #1a3a5a;
}

.country-code-item .flag {
    font-size: 1.2rem;
    width: 24px;
    text-align: center;
}

.country-code-item .code {
    font-weight: 600;
    min-width: 50px;
    color: #212529;
}

body.dark .country-code-item .code {
    color: #e3e6ea;
}

.country-code-item .name {
    flex: 1;
    color: #6c757d;
    font-size: 0.9rem;
}

body.dark .country-code-item .name {
    color: #9ca3af;
}

.input-group-custom {
    display: flex;
    align-items: stretch;
    width: 100%;
}

.phone-input {
    border-left: none;
    border-radius: 0 10px 10px 0;
    flex: 1;
}

.input-group-custom:focus-within .country-code-btn {
    border-color: #007bff;
    z-index: 1;
}

body.dark .input-group-custom:focus-within .country-code-btn {
    border-color: #5dade2;
}

.input-group-custom:focus-within .phone-input {
    border-color: #007bff;
    border-left: 2px solid #007bff;
}

body.dark .input-group-custom:focus-within .phone-input {
    border-color: #5dade2;
    border-left: 2px solid #5dade2;
}

.input-group-custom .country-code-btn,
.input-group-custom .phone-input {
    height: auto;
    min-height: calc(0.875rem * 2 + 1rem + 2px);
}

/* Guide Submit Button */
.btn-guide-submit {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 50%, #004085 100%);
    border: none;
    color: #fff;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 700;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
    margin-top: 1rem;
}

.btn-guide-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
    background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
    color: #fff;
}

.btn-guide-submit:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.4);
}

.btn-guide-submit:focus {
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
    outline: none;
}

/* Form Check Custom */
.form-check-custom {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.form-check-custom:has(input:invalid) {
    border-color: #dc3545;
}

body.dark .form-check-custom {
    background-color: #1b1e22;
    border-color: #3b3f45;
}

body.dark .form-check-custom:has(input:invalid),
body.dark .form-check-custom.is-invalid {
    border-color: #dc3545;
}

.form-check-custom.is-invalid {
    border-color: #dc3545;
}

.form-check-input-custom {
    margin-top: 0.25rem;
    width: 1.25rem;
    height: 1.25rem;
    cursor: pointer;
    flex-shrink: 0;
}

.form-check-label-custom {
    flex: 1;
    color: #212529;
    cursor: pointer;
    margin: 0;
}

body.dark .form-check-label-custom {
    color: #e3e6ea;
}

/* Guide Register Footer */
.guide-register-footer {
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

body.dark .guide-register-footer {
    border-top-color: #3b3f45;
}

.guide-register-footer p {
    color: #6c757d;
    margin: 0;
}

body.dark .guide-register-footer p {
    color: #9ca3af;
}

.login-link,
.register-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.login-link:hover,
.register-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

body.dark .login-link,
body.dark .register-link {
    color: #5dade2;
}

body.dark .login-link:hover,
body.dark .register-link:hover {
    color: #4a9fd1;
}

/* Alert Custom */
.alert-custom {
    border-radius: 10px;
    border: none;
    margin-bottom: 1.5rem;
}

/* Invalid/Valid Feedback */
.invalid-feedback {
    display: none;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #dc3545;
    width: 100%;
}

.form-control-custom.is-invalid ~ .invalid-feedback,
.form-group-custom:has(.form-control-custom.is-invalid) .invalid-feedback {
    display: block;
}

.was-validated .form-control-custom:invalid ~ .invalid-feedback,
.was-validated .form-group-custom:has(.form-control-custom:invalid) .invalid-feedback {
    display: block;
}

/* Criminal records checkbox invalid feedback */
#criminal_records_feedback {
    display: none;
    margin-top: 0.5rem;
}

.form-check-custom.is-invalid ~ #criminal_records_feedback,
.was-validated #criminal_records:invalid ~ #criminal_records_feedback,
.form-group-custom:has(.form-check-custom.is-invalid) #criminal_records_feedback {
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .guide-register-card {
        padding: 2rem 1.5rem;
    }
    
    .guide-register-title {
        font-size: 1.75rem;
    }
    
    .form-group-custom {
        margin-bottom: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const guideForm = document.querySelector('.needs-validation');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const dateOfBirthInput = document.getElementById('date_of_birth');
    const nationalIdInput = document.getElementById('national_id');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    // Set max date to today for date of birth
    if (dateOfBirthInput) {
        dateOfBirthInput.setAttribute('max', new Date().toISOString().split('T')[0]);
    }
    
    if (guideForm) {
        // Real-time email validation
        if (emailInput) {
            let emailTouched = false;
            const emailGroup = emailInput.closest('.form-group-custom');
            const emailFeedback = emailGroup ? emailGroup.querySelector('.invalid-feedback') : null;
            
            emailInput.addEventListener('blur', function() {
                emailTouched = true;
                const emailValue = this.value.trim();
                
                if (emailValue === '') {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                    if (emailFeedback) emailFeedback.style.display = 'none';
                    return;
                }
                
                const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
                
                if (emailRegex.test(emailValue)) {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                    if (emailFeedback) emailFeedback.style.display = 'none';
                } else {
                    this.classList.add('is-invalid');
                    this.setCustomValidity('Please enter a valid email address');
                    if (emailFeedback) emailFeedback.style.display = 'block';
                }
            });
            
            emailInput.addEventListener('input', function() {
                if (emailTouched) {
                    const emailValue = this.value.trim();
                    
                    if (emailValue === '') {
                        this.classList.remove('is-invalid');
                        this.setCustomValidity('');
                        if (emailFeedback) emailFeedback.style.display = 'none';
                        return;
                    }
                    
                    const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
                    
                    if (emailRegex.test(emailValue)) {
                        this.classList.remove('is-invalid');
                        this.setCustomValidity('');
                        if (emailFeedback) emailFeedback.style.display = 'none';
                    } else {
                        this.classList.add('is-invalid');
                        this.setCustomValidity('Please enter a valid email address');
                        if (emailFeedback) emailFeedback.style.display = 'block';
                    }
                }
            });
        }
        
        // Country Code Selector
        const countryCodeBtn = document.getElementById('countryCodeBtn');
        const countryCodeMenu = document.getElementById('countryCodeMenu');
        const countryFlag = document.getElementById('countryFlag');
        const countryCode = document.getElementById('countryCode');
        const fullPhoneInput = document.getElementById('full_phone');
        
        const countries = [
            { flag: '🇦🇪', code: '+971', name: 'United Arab Emirates' },
            { flag: '🇦🇷', code: '+54', name: 'Argentina' },
            { flag: '🇦🇺', code: '+61', name: 'Australia' },
            { flag: '🇦🇹', code: '+43', name: 'Austria' },
            { flag: '🇧🇩', code: '+880', name: 'Bangladesh' },
            { flag: '🇧🇪', code: '+32', name: 'Belgium' },
            { flag: '🇧🇷', code: '+55', name: 'Brazil' },
            { flag: '🇨🇦', code: '+1', name: 'Canada' },
            { flag: '🇨🇳', code: '+86', name: 'China' },
            { flag: '🇨🇴', code: '+57', name: 'Colombia' },
            { flag: '🇩🇰', code: '+45', name: 'Denmark' },
            { flag: '🇪🇬', code: '+20', name: 'Egypt' },
            { flag: '🇪🇹', code: '+251', name: 'Ethiopia' },
            { flag: '🇫🇮', code: '+358', name: 'Finland' },
            { flag: '🇫🇷', code: '+33', name: 'France' },
            { flag: '🇩🇪', code: '+49', name: 'Germany' },
            { flag: '🇬🇭', code: '+233', name: 'Ghana' },
            { flag: '🇬🇷', code: '+30', name: 'Greece' },
            { flag: '🇭🇰', code: '+852', name: 'Hong Kong' },
            { flag: '🇮🇩', code: '+62', name: 'Indonesia' },
            { flag: '🇮🇳', code: '+91', name: 'India' },
            { flag: '🇮🇷', code: '+98', name: 'Iran' },
            { flag: '🇮🇶', code: '+964', name: 'Iraq' },
            { flag: '🇮🇪', code: '+353', name: 'Ireland' },
            { flag: '🇮🇱', code: '+972', name: 'Israel' },
            { flag: '🇮🇹', code: '+39', name: 'Italy' },
            { flag: '🇯🇵', code: '+81', name: 'Japan' },
            { flag: '🇯🇴', code: '+962', name: 'Jordan' },
            { flag: '🇰🇪', code: '+254', name: 'Kenya' },
            { flag: '🇰🇼', code: '+965', name: 'Kuwait' },
            { flag: '🇱🇧', code: '+961', name: 'Lebanon' },
            { flag: '🇲🇾', code: '+60', name: 'Malaysia' },
            { flag: '🇲🇦', code: '+212', name: 'Morocco' },
            { flag: '🇲🇽', code: '+52', name: 'Mexico' },
            { flag: '🇳🇬', code: '+234', name: 'Nigeria' },
            { flag: '🇳🇱', code: '+31', name: 'Netherlands' },
            { flag: '🇳🇿', code: '+64', name: 'New Zealand' },
            { flag: '🇳🇴', code: '+47', name: 'Norway' },
            { flag: '🇴🇲', code: '+968', name: 'Oman' },
            { flag: '🇵🇰', code: '+92', name: 'Pakistan' },
            { flag: '🇵🇭', code: '+63', name: 'Philippines' },
            { flag: '🇵🇱', code: '+48', name: 'Poland' },
            { flag: '🇶🇦', code: '+974', name: 'Qatar' },
            { flag: '🇷🇴', code: '+40', name: 'Romania' },
            { flag: '🇷🇺', code: '+7', name: 'Russia' },
            { flag: '🇸🇦', code: '+966', name: 'Saudi Arabia' },
            { flag: '🇸🇬', code: '+65', name: 'Singapore' },
            { flag: '🇿🇦', code: '+27', name: 'South Africa' },
            { flag: '🇰🇷', code: '+82', name: 'South Korea' },
            { flag: '🇪🇸', code: '+34', name: 'Spain' },
            { flag: '🇱🇰', code: '+94', name: 'Sri Lanka' },
            { flag: '🇸🇪', code: '+46', name: 'Sweden' },
            { flag: '🇨🇭', code: '+41', name: 'Switzerland' },
            { flag: '🇹🇼', code: '+886', name: 'Taiwan' },
            { flag: '🇹🇿', code: '+255', name: 'Tanzania' },
            { flag: '🇹🇭', code: '+66', name: 'Thailand' },
            { flag: '🇹🇷', code: '+90', name: 'Turkey' },
            { flag: '🇺🇦', code: '+380', name: 'Ukraine' },
            { flag: '🇬🇧', code: '+44', name: 'United Kingdom' },
            { flag: '🇺🇸', code: '+1', name: 'United States' },
            { flag: '🇻🇳', code: '+84', name: 'Vietnam' },
            { flag: '🇾🇪', code: '+967', name: 'Yemen' }
        ];
        
        let selectedCountry = countries.find(c => c.code === '+1' && c.name === 'United States') || countries[0];
        
        if (countryCodeMenu) {
            countries.forEach(country => {
                const item = document.createElement('li');
                item.className = 'country-code-item';
                item.innerHTML = `
                    <span class="flag">${country.flag}</span>
                    <span class="code">${country.code}</span>
                    <span class="name">${country.name}</span>
                `;
                item.addEventListener('click', function() {
                    selectedCountry = country;
                    countryFlag.textContent = country.flag;
                    countryCode.textContent = country.code;
                    
                    countryCodeMenu.querySelectorAll('.country-code-item').forEach(i => i.classList.remove('active'));
                    item.classList.add('active');
                    
                    updateFullPhone();
                    
                    const dropdown = bootstrap.Dropdown.getInstance(countryCodeBtn);
                    if (dropdown) dropdown.hide();
                });
                countryCodeMenu.appendChild(item);
            });
            
            if (countryCodeMenu.firstElementChild) {
                countryCodeMenu.firstElementChild.classList.add('active');
            }
        }
        
        function updateFullPhone() {
            if (phoneInput && fullPhoneInput) {
                const phoneValue = phoneInput.value.trim();
                if (phoneValue) {
                    fullPhoneInput.value = selectedCountry.code + phoneValue;
                } else {
                    fullPhoneInput.value = '';
                }
            }
        }
        
        // Phone validation
        if (phoneInput) {
            let phoneTouched = false;
            const phoneGroup = phoneInput.closest('.form-group-custom');
            const phoneFeedback = phoneGroup ? phoneGroup.querySelector('.invalid-feedback') : null;
            
            phoneInput.addEventListener('blur', function() {
                phoneTouched = true;
                const phoneValue = this.value.replace(/\D/g, '');
                
                if (phoneValue === '') {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                    fullPhoneInput.value = '';
                    if (phoneFeedback) phoneFeedback.style.display = 'none';
                    return;
                }
                
                if (phoneValue.length >= 7 && phoneValue.length <= 15) {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                    updateFullPhone();
                    if (phoneFeedback) phoneFeedback.style.display = 'none';
                } else {
                    this.classList.add('is-invalid');
                    this.setCustomValidity('Please enter a valid phone number (7-15 digits)');
                    fullPhoneInput.value = '';
                    if (phoneFeedback) phoneFeedback.style.display = 'block';
                }
            });
            
            phoneInput.addEventListener('input', function() {
                if (phoneTouched) {
                    const phoneValue = this.value.replace(/\D/g, '');
                    
                    if (phoneValue === '') {
                        this.classList.remove('is-invalid');
                        this.setCustomValidity('');
                        fullPhoneInput.value = '';
                        if (phoneFeedback) phoneFeedback.style.display = 'none';
                        return;
                    }
                    
                    if (phoneValue.length >= 7 && phoneValue.length <= 15) {
                        this.classList.remove('is-invalid');
                        this.setCustomValidity('');
                        updateFullPhone();
                        if (phoneFeedback) phoneFeedback.style.display = 'none';
                    } else {
                        this.classList.add('is-invalid');
                        this.setCustomValidity('Please enter a valid phone number (7-15 digits)');
                        fullPhoneInput.value = '';
                        if (phoneFeedback) phoneFeedback.style.display = 'block';
                    }
                } else {
                    updateFullPhone();
                }
            });
        }
        
        // Date of birth validation
        if (dateOfBirthInput) {
            dateOfBirthInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate > today) {
                    this.classList.add('is-invalid');
                    this.setCustomValidity('Date of birth cannot be in the future');
                } else {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                }
            });
        }
        
        // Country-specific National ID validation functions
        function validateEgyptianID(id) {
            // Egyptian National ID: 14 digits
            // Format: YYMMDDGGSSSSSC
            // YY: Year (last 2 digits), MM: Month, DD: Day
            // GG: Governorate code (01-27), SSSS: Serial number, C: Check digit
            if (!/^\d{14}$/.test(id)) {
                return { valid: false, message: 'Egyptian National ID must be exactly 14 digits' };
            }
            
            // Extract components
            const century = parseInt(id[0]);
            const year = parseInt(id.substring(1, 3));
            const month = parseInt(id.substring(3, 5));
            const day = parseInt(id.substring(5, 7));
            const governorate = parseInt(id.substring(7, 9));
            
            // Validate century (2 = 1900s, 3 = 2000s)
            if (century !== 2 && century !== 3) {
                return { valid: false, message: 'Invalid century digit in Egyptian National ID' };
            }
            
            // Validate month
            if (month < 1 || month > 12) {
                return { valid: false, message: 'Invalid month in Egyptian National ID' };
            }
            
            // Validate day
            if (day < 1 || day > 31) {
                return { valid: false, message: 'Invalid day in Egyptian National ID' };
            }
            
            // Validate governorate (01-27 for Egypt)
            if (governorate < 1 || governorate > 27) {
                return { valid: false, message: 'Invalid governorate code in Egyptian National ID' };
            }
            
            return { valid: true, message: '' };
        }
        
        function validateSaudiID(id) {
            // Saudi National ID: 10 digits
            if (!/^\d{10}$/.test(id)) {
                return { valid: false, message: 'Saudi National ID must be exactly 10 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validateUSSSN(id) {
            // US SSN: 9 digits (can be formatted as XXX-XX-XXXX or just 9 digits)
            const cleaned = id.replace(/[-\s]/g, '');
            if (!/^\d{9}$/.test(cleaned)) {
                return { valid: false, message: 'US SSN must be 9 digits (format: XXX-XX-XXXX or 123456789)' };
            }
            // Check for invalid SSNs (000-xx-xxxx, xxx-00-xxxx, xxx-xx-0000)
            if (cleaned.substring(0, 3) === '000' || cleaned.substring(3, 5) === '00' || cleaned.substring(5) === '0000') {
                return { valid: false, message: 'Invalid US SSN format' };
            }
            return { valid: true, message: '' };
        }
        
        function validateUKNINO(id) {
            // UK National Insurance Number: Format AA123456A (2 letters, 6 digits, 1 letter)
            const cleaned = id.replace(/\s/g, '').toUpperCase();
            if (!/^[A-Z]{2}\d{6}[A-Z]$/.test(cleaned)) {
                return { valid: false, message: 'UK National Insurance Number must be in format: AB123456C' };
            }
            return { valid: true, message: '' };
        }
        
        function validateUAEID(id) {
            // UAE Emirates ID: 15 digits
            if (!/^\d{15}$/.test(id)) {
                return { valid: false, message: 'UAE Emirates ID must be exactly 15 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validateIndianAadhaar(id) {
            // Indian Aadhaar: 12 digits
            if (!/^\d{12}$/.test(id)) {
                return { valid: false, message: 'Indian Aadhaar must be exactly 12 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validatePakistaniCNIC(id) {
            // Pakistani CNIC: 13 digits (can be formatted as XXXXX-XXXXXXX-X)
            const cleaned = id.replace(/[-\s]/g, '');
            if (!/^\d{13}$/.test(cleaned)) {
                return { valid: false, message: 'Pakistani CNIC must be 13 digits (format: XXXXX-XXXXXXX-X or 1234567890123)' };
            }
            return { valid: true, message: '' };
        }
        
        function validateJordanianID(id) {
            // Jordanian National Number: 10 digits
            if (!/^\d{10}$/.test(id)) {
                return { valid: false, message: 'Jordanian National Number must be exactly 10 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validateLebaneseID(id) {
            // Lebanese ID: Variable format, typically 11 digits or alphanumeric
            if (!/^[A-Za-z0-9]{8,15}$/.test(id)) {
                return { valid: false, message: 'Lebanese ID must be 8-15 alphanumeric characters' };
            }
            return { valid: true, message: '' };
        }
        
        function validateKuwaitiID(id) {
            // Kuwaiti Civil ID: 12 digits
            if (!/^\d{12}$/.test(id)) {
                return { valid: false, message: 'Kuwaiti Civil ID must be exactly 12 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validateQatariID(id) {
            // Qatari ID: 11 digits
            if (!/^\d{11}$/.test(id)) {
                return { valid: false, message: 'Qatari ID must be exactly 11 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validateBahrainiID(id) {
            // Bahraini CPR: 9 digits
            if (!/^\d{9}$/.test(id)) {
                return { valid: false, message: 'Bahraini CPR must be exactly 9 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validateOmaniID(id) {
            // Omani ID: 9 digits
            if (!/^\d{9}$/.test(id)) {
                return { valid: false, message: 'Omani ID must be exactly 9 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validatePassport(id) {
            // Generic passport validation: alphanumeric, 5-15 characters
            if (!/^[A-Za-z0-9\s\-]{5,15}$/.test(id)) {
                return { valid: false, message: 'Passport number must be 5-15 alphanumeric characters' };
            }
            return { valid: true, message: '' };
        }
        
        // Get validation function based on country
        function getNationalIDValidator(country) {
            const validators = {
                'Egypt': validateEgyptianID,
                'Saudi Arabia': validateSaudiID,
                'United States': validateUSSSN,
                'United Kingdom': validateUKNINO,
                'United Arab Emirates': validateUAEID,
                'India': validateIndianAadhaar,
                'Pakistan': validatePakistaniCNIC,
                'Jordan': validateJordanianID,
                'Lebanon': validateLebaneseID,
                'Kuwait': validateKuwaitiID,
                'Qatar': validateQatariID,
                'Bahrain': validateBahrainiID,
                'Oman': validateOmaniID,
                'Other': validatePassport
            };
            return validators[country] || validatePassport;
        }
        
        // Update placeholder, hint, and maxlength based on country
        function updateNationalIDPlaceholder(country) {
            const nationalIdInput = document.getElementById('national_id');
            const nationalIdHint = document.getElementById('national_id_hint');
            
            if (!nationalIdInput || !nationalIdHint) return;
            
            const placeholders = {
                'Egypt': 'Enter 14-digit Egyptian National ID',
                'Saudi Arabia': 'Enter 10-digit Saudi National ID',
                'United States': 'Enter US SSN (XXX-XX-XXXX)',
                'United Kingdom': 'Enter UK NINO (AB123456C)',
                'United Arab Emirates': 'Enter 15-digit UAE Emirates ID',
                'India': 'Enter 12-digit Indian Aadhaar',
                'Pakistan': 'Enter 13-digit Pakistani CNIC',
                'Jordan': 'Enter 10-digit Jordanian National Number',
                'Lebanon': 'Enter Lebanese ID (8-15 characters)',
                'Kuwait': 'Enter 12-digit Kuwaiti Civil ID',
                'Qatar': 'Enter 11-digit Qatari ID',
                'Bahrain': 'Enter 9-digit Bahraini CPR',
                'Oman': 'Enter 9-digit Omani ID',
                'Other': 'Enter your passport number'
            };
            
            const hints = {
                'Egypt': '14-digit Egyptian National ID (format: YYMMDDGGSSSSSC)',
                'Saudi Arabia': '10-digit Saudi National ID',
                'United States': 'US Social Security Number (9 digits)',
                'United Kingdom': 'UK National Insurance Number (format: AB123456C)',
                'United Arab Emirates': '15-digit UAE Emirates ID',
                'India': '12-digit Indian Aadhaar number',
                'Pakistan': '13-digit Pakistani CNIC (format: XXXXX-XXXXXXX-X)',
                'Jordan': '10-digit Jordanian National Number',
                'Lebanon': 'Lebanese ID (8-15 alphanumeric characters)',
                'Kuwait': '12-digit Kuwaiti Civil ID',
                'Qatar': '11-digit Qatari ID',
                'Bahrain': '9-digit Bahraini CPR',
                'Oman': '9-digit Omani ID',
                'Other': 'Enter your passport number (5-15 alphanumeric characters)'
            };
            
            // Max length for each country (prevents typing more than allowed)
            const maxLengths = {
                'Egypt': 14,
                'Saudi Arabia': 10,
                'United States': 11, // 9 digits + 2 dashes (XXX-XX-XXXX)
                'United Kingdom': 9, // 2 letters + 6 digits + 1 letter
                'United Arab Emirates': 15,
                'India': 12,
                'Pakistan': 15, // 13 digits + 2 dashes (XXXXX-XXXXXXX-X)
                'Jordan': 10,
                'Lebanon': 15,
                'Kuwait': 12,
                'Qatar': 11,
                'Bahrain': 9,
                'Oman': 9,
                'Other': 15
            };
            
            nationalIdInput.placeholder = placeholders[country] || placeholders['Other'];
            nationalIdHint.textContent = hints[country] || hints['Other'];
            nationalIdInput.setAttribute('maxlength', maxLengths[country] || 15);
        }
        
        // National ID validation with country-specific rules
        const homeCountrySelect = document.getElementById('home_country');
        const nationalIdInput = document.getElementById('national_id');
        const nationalIdFeedback = document.getElementById('national_id_feedback');
        
        if (homeCountrySelect && nationalIdInput) {
            // Update placeholder when country changes
            homeCountrySelect.addEventListener('change', function() {
                updateNationalIDPlaceholder(this.value);
                // Clear validation when country changes
                nationalIdInput.classList.remove('is-invalid');
                if (nationalIdFeedback) nationalIdFeedback.style.display = 'none';
                nationalIdInput.value = '';
            });
            
            // Initialize placeholder
            if (homeCountrySelect.value) {
                updateNationalIDPlaceholder(homeCountrySelect.value);
            }
            
            let nationalIdTouched = false;
            
            function validateNationalID() {
                const country = homeCountrySelect.value;
                const idValue = nationalIdInput.value.trim();
                
                if (!country) {
                    nationalIdInput.setCustomValidity('Please select your home country first');
                    return false;
                }
                
                if (idValue === '') {
                    nationalIdInput.classList.remove('is-invalid');
                    nationalIdInput.setCustomValidity('');
                    if (nationalIdFeedback) nationalIdFeedback.style.display = 'none';
                    return true;
                }
                
                const validator = getNationalIDValidator(country);
                const result = validator(idValue);
                
                if (result.valid) {
                    nationalIdInput.classList.remove('is-invalid');
                    nationalIdInput.setCustomValidity('');
                    if (nationalIdFeedback) nationalIdFeedback.style.display = 'none';
                    return true;
                } else {
                    nationalIdInput.classList.add('is-invalid');
                    nationalIdInput.setCustomValidity(result.message);
                    if (nationalIdFeedback) {
                        nationalIdFeedback.textContent = result.message;
                        nationalIdFeedback.style.display = 'block';
                    }
                    return false;
                }
            }
            
            nationalIdInput.addEventListener('blur', function() {
                nationalIdTouched = true;
                validateNationalID();
            });
            
            nationalIdInput.addEventListener('input', function() {
                // Prevent typing more than maxlength (already handled by maxlength attribute)
                // But also validate in real-time if field has been touched
                if (nationalIdTouched) {
                    validateNationalID();
                }
            });
            
            // Also validate on keypress to show error immediately when typing
            nationalIdInput.addEventListener('keyup', function() {
                if (nationalIdTouched && this.value.trim() !== '') {
                    validateNationalID();
                }
            });
        }
        
        // Password validation
        if (passwordInput) {
            let passwordTouched = false;
            const passwordGroup = passwordInput.closest('.form-group-custom');
            const passwordFeedback = passwordGroup ? passwordGroup.querySelector('.invalid-feedback') : null;
            
            passwordInput.addEventListener('blur', function() {
                passwordTouched = true;
                const passwordValue = this.value;
                
                if (passwordValue === '') {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                    if (passwordFeedback) passwordFeedback.style.display = 'none';
                    return;
                }
                
                if (passwordValue.length >= 6) {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                    if (passwordFeedback) passwordFeedback.style.display = 'none';
                } else {
                    this.classList.add('is-invalid');
                    this.setCustomValidity('Password must be at least 6 characters long');
                    if (passwordFeedback) passwordFeedback.style.display = 'block';
                }
            });
            
            passwordInput.addEventListener('input', function() {
                if (passwordTouched) {
                    const passwordValue = this.value;
                    
                    if (passwordValue === '') {
                        this.classList.remove('is-invalid');
                        this.setCustomValidity('');
                        if (passwordFeedback) passwordFeedback.style.display = 'none';
                        return;
                    }
                    
                    if (passwordValue.length >= 6) {
                        this.classList.remove('is-invalid');
                        this.setCustomValidity('');
                        if (passwordFeedback) passwordFeedback.style.display = 'none';
                    } else {
                        this.classList.add('is-invalid');
                        this.setCustomValidity('Password must be at least 6 characters long');
                        if (passwordFeedback) passwordFeedback.style.display = 'block';
                    }
                }
            });
        }
        
        // Password confirmation validation
        if (passwordInput && confirmPasswordInput) {
            let confirmPasswordTouched = false;
            const confirmPasswordGroup = confirmPasswordInput.closest('.form-group-custom');
            const confirmPasswordFeedback = confirmPasswordGroup ? confirmPasswordGroup.querySelector('.invalid-feedback') : null;
            
            function validatePasswordMatch() {
                if (confirmPasswordInput.value === '') {
                    confirmPasswordInput.classList.remove('is-invalid');
                    confirmPasswordInput.setCustomValidity('');
                    if (confirmPasswordFeedback) confirmPasswordFeedback.style.display = 'none';
                    return;
                }
                
                if (passwordInput.value === confirmPasswordInput.value) {
                    confirmPasswordInput.classList.remove('is-invalid');
                    confirmPasswordInput.setCustomValidity('');
                    if (confirmPasswordFeedback) confirmPasswordFeedback.style.display = 'none';
                } else {
                    if (confirmPasswordTouched) {
                        confirmPasswordInput.classList.add('is-invalid');
                        confirmPasswordInput.setCustomValidity('Passwords do not match');
                        if (confirmPasswordFeedback) confirmPasswordFeedback.style.display = 'block';
                    }
                }
            }
            
            confirmPasswordInput.addEventListener('blur', function() {
                confirmPasswordTouched = true;
                validatePasswordMatch();
            });
            
            passwordInput.addEventListener('input', function() {
                if (confirmPasswordTouched && confirmPasswordInput.value !== '') {
                    validatePasswordMatch();
                }
            });
            
            confirmPasswordInput.addEventListener('input', function() {
                if (confirmPasswordTouched) {
                    validatePasswordMatch();
                }
            });
        }
        
        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
        
        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
        
        // Criminal records checkbox validation
        const criminalRecordsCheckbox = document.getElementById('criminal_records');
        const criminalRecordsContainer = document.getElementById('criminal_records_container');
        const criminalRecordsFeedback = document.getElementById('criminal_records_feedback');
        
        if (criminalRecordsCheckbox && criminalRecordsContainer && criminalRecordsFeedback) {
            criminalRecordsCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    criminalRecordsContainer.classList.remove('is-invalid');
                    criminalRecordsFeedback.style.display = 'none';
                    this.setCustomValidity('');
                } else {
                    criminalRecordsContainer.classList.add('is-invalid');
                    criminalRecordsFeedback.style.display = 'block';
                    this.setCustomValidity('You must confirm that you have no criminal records.');
                }
            });
        }
        
        // Form submission validation
        guideForm.addEventListener('submit', function(event) {
            // Validate all fields before submission
            let isValid = true;
            
            // Validate National ID with country-specific validation
            if (nationalIdInput && homeCountrySelect) {
                const country = homeCountrySelect.value;
                const idValue = nationalIdInput.value.trim();
                
                if (!country) {
                    homeCountrySelect.classList.add('is-invalid');
                    isValid = false;
                } else {
                    homeCountrySelect.classList.remove('is-invalid');
                }
                
                if (idValue === '') {
                    nationalIdInput.classList.add('is-invalid');
                    if (nationalIdFeedback) {
                        nationalIdFeedback.textContent = 'Please enter your National ID or Passport number';
                        nationalIdFeedback.style.display = 'block';
                    }
                    nationalIdInput.setCustomValidity('Please enter your National ID or Passport number');
                    isValid = false;
                } else if (country) {
                    const validator = getNationalIDValidator(country);
                    const result = validator(idValue);
                    
                    if (!result.valid) {
                        nationalIdInput.classList.add('is-invalid');
                        if (nationalIdFeedback) {
                            nationalIdFeedback.textContent = result.message;
                            nationalIdFeedback.style.display = 'block';
                        }
                        nationalIdInput.setCustomValidity(result.message);
                        isValid = false;
                    } else {
                        nationalIdInput.classList.remove('is-invalid');
                        if (nationalIdFeedback) nationalIdFeedback.style.display = 'none';
                        nationalIdInput.setCustomValidity('');
                    }
                }
            }
            
            // Validate criminal records checkbox
            if (criminalRecordsCheckbox && !criminalRecordsCheckbox.checked) {
                if (criminalRecordsContainer) criminalRecordsContainer.classList.add('is-invalid');
                if (criminalRecordsFeedback) criminalRecordsFeedback.style.display = 'block';
                criminalRecordsCheckbox.setCustomValidity('You must confirm that you have no criminal records.');
                isValid = false;
            } else if (criminalRecordsCheckbox && criminalRecordsCheckbox.checked) {
                if (criminalRecordsContainer) criminalRecordsContainer.classList.remove('is-invalid');
                if (criminalRecordsFeedback) criminalRecordsFeedback.style.display = 'none';
                criminalRecordsCheckbox.setCustomValidity('');
            }
            
            // Only prevent submission if there are validation errors
            if (!isValid) {
                event.preventDefault();
                event.stopPropagation();
                
                const firstInvalid = guideForm.querySelector('.is-invalid, :invalid');
                if (firstInvalid) {
                    // Scroll to the invalid field, not the top
                    setTimeout(function() {
                        firstInvalid.focus();
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 100);
                }
                guideForm.classList.add('was-validated');
                return false;
            }
            
            // If form is valid, allow submission to proceed
            guideForm.classList.add('was-validated');
        }, false);
    }
});
</script>
