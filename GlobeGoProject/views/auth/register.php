<?php
// Register view. Expects: $error_message, $success_message
?>

<div class="register-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="register-card">
                    <div class="register-header">
                        <h2 class="register-title">CREATE ACCOUNT</h2>
                        <p class="register-subtitle">Join GlobeGo and start exploring the world</p>
                    </div>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-custom"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success alert-custom"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="needs-validation register-form" novalidate>
                        <div class="form-group-custom mb-4">
                            <label for="first_name" class="form-label-custom">
                                <i class="fas fa-user me-2"></i>First Name
                            </label>
                            <input type="text" class="form-control-custom" id="first_name" name="first_name" 
                                   value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" 
                                   placeholder="Enter your first name"
                                   required>
                            <div class="invalid-feedback">
                                Please enter your first name.
                            </div>
                        </div>
                        
                        <div class="form-group-custom mb-4">
                            <label for="last_name" class="form-label-custom">
                                <i class="fas fa-user me-2"></i>Last Name
                            </label>
                            <input type="text" class="form-control-custom" id="last_name" name="last_name" 
                                   value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" 
                                   placeholder="Enter your last name"
                                   required>
                            <div class="invalid-feedback">
                                Please enter your last name.
                            </div>
                        </div>
                        
                        <div class="form-group-custom mb-4">
                            <label for="email" class="form-label-custom">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <input type="email" class="form-control-custom" id="email" name="email" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                   placeholder="Enter your email address"
                                   pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                                   required>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>
                        
                        <div class="form-group-custom mb-4">
                            <label for="phone" class="form-label-custom">
                                <i class="fas fa-phone me-2"></i>Phone Number
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
                        
                        <input type="hidden" name="role" value="tourist">
                        
                        <div class="form-group-custom mb-4">
                            <label for="password" class="form-label-custom">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password" class="form-control-custom" id="password" name="password" 
                                       placeholder="Enter your password (min. 6 characters)"
                                       minlength="6"
                                       required>
                                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                            </div>
                            <div class="invalid-feedback">
                                Password must be at least 6 characters long.
                            </div>
                        </div>
                        
                        <div class="form-group-custom mb-4">
                            <label for="confirm_password" class="form-label-custom">
                                <i class="fas fa-lock me-2"></i>Confirm Password
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
                        
                        <button type="submit" class="btn-register w-100">
                            <i class="fas fa-user-plus me-2"></i>REGISTER
                        </button>
                    </form>
                    
                    <div class="register-footer text-center mt-4">
                        <p class="mb-0">Already have an account? <a href="login.php" class="login-link">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Register Container */
.register-container {
    min-height: calc(100vh - 200px);
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    padding: 2rem 0;
}

/* Register Card */
.register-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 3rem 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

body.dark .register-card {
    background: rgba(26, 26, 46, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Register Header */
.register-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.register-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
}

body.dark .register-title {
    color: #e3e6ea;
}

.register-subtitle {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 0;
}

body.dark .register-subtitle {
    color: #9ca3af;
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

.form-control-custom.is-valid {
    border-color: #28a745;
}

.form-control-custom.is-invalid {
    border-color: #dc3545;
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
    margin-top: 0.5rem;
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

/* Ensure seamless connection */
.input-group-custom .country-code-selector .country-code-btn {
    border-right: none;
}

.input-group-custom .phone-input {
    border-left: none;
}

/* When phone input is focused, highlight the entire group */
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

/* Ensure same height */
.input-group-custom .country-code-btn,
.input-group-custom .phone-input {
    height: auto;
    min-height: calc(0.875rem * 2 + 1rem + 2px);
}

/* Register Button - Blue/Black Theme */
.btn-register {
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

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
    background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
    color: #fff;
}

.btn-register:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.4);
}

.btn-register:focus {
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
    outline: none;
}

/* Register Footer */
.register-footer {
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

body.dark .register-footer {
    border-top-color: #3b3f45;
}

.register-footer p {
    color: #6c757d;
    margin: 0;
}

body.dark .register-footer p {
    color: #9ca3af;
}

.login-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.login-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

body.dark .login-link {
    color: #5dade2;
}

body.dark .login-link:hover {
    color: #4a9fd1;
}

/* Alert Custom */
.alert-custom {
    border-radius: 10px;
    border: none;
    margin-bottom: 1.5rem;
}

/* Invalid/Valid Feedback - Hidden by default */
.invalid-feedback,
.valid-feedback {
    display: none;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #dc3545;
}

/* Show invalid feedback only when field has is-invalid class */
.form-control-custom.is-invalid ~ .invalid-feedback {
    display: block;
}

/* For phone number field - invalid feedback is after input-group */
.form-group-custom:has(.form-control-custom.is-invalid) .invalid-feedback {
    display: block;
}

/* Show on form submit if invalid */
.was-validated .form-control-custom:invalid ~ .invalid-feedback {
    display: block;
}

.was-validated .form-group-custom:has(.form-control-custom:invalid) .invalid-feedback {
    display: block;
}

.valid-feedback {
    display: none !important;
}

/* Responsive */
@media (max-width: 768px) {
    .register-card {
        padding: 2rem 1.5rem;
    }
    
    .register-title {
        font-size: 1.75rem;
    }
    
    .form-group-custom {
        margin-bottom: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('.needs-validation');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (registerForm) {
        // Real-time email validation - only show errors when user has entered invalid data
        if (emailInput) {
            let emailTouched = false;
            const emailFeedback = emailInput.parentElement.querySelector('.invalid-feedback');
            
            emailInput.addEventListener('blur', function() {
                emailTouched = true;
                const emailValue = this.value.trim();
                
                if (emailValue === '') {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                    if (emailFeedback) emailFeedback.style.display = 'none';
                    return;
                }
                
                // Enhanced email validation regex
                const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
                
                if (emailRegex.test(emailValue)) {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                    if (emailFeedback) emailFeedback.style.display = 'none';
                } else {
                    this.classList.add('is-invalid');
                    this.setCustomValidity('Please enter a valid email address (e.g., user@example.com)');
                    if (emailFeedback) emailFeedback.style.display = 'block';
                }
            });
            
            emailInput.addEventListener('input', function() {
                // Only validate on input if field has been touched (user interacted with it)
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
                        this.setCustomValidity('Please enter a valid email address (e.g., user@example.com)');
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
        
        // Countries with codes and flags - Alphabetically organized
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
        
        // Find US as default (or first country if US not found)
        const defaultCountryIndex = countries.findIndex(c => c.code === '+1' && c.name === 'United States');
        let selectedCountry = defaultCountryIndex !== -1 ? countries[defaultCountryIndex] : countries[0];
        
        // Populate country code dropdown
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
                    
                    // Update active state
                    countryCodeMenu.querySelectorAll('.country-code-item').forEach(i => i.classList.remove('active'));
                    item.classList.add('active');
                    
                    // Update full phone
                    updateFullPhone();
                    
                    // Close dropdown
                    const dropdown = bootstrap.Dropdown.getInstance(countryCodeBtn);
                    if (dropdown) dropdown.hide();
                });
                countryCodeMenu.appendChild(item);
            });
            
            // Set first item as active
            if (countryCodeMenu.firstElementChild) {
                countryCodeMenu.firstElementChild.classList.add('active');
            }
        }
        
        // Update full phone number (country code + phone)
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
        
        // Real-time phone number validation - only show errors when user has entered invalid data
        if (phoneInput) {
            let phoneTouched = false;
            const phoneGroup = phoneInput.closest('.form-group-custom');
            const phoneFeedback = phoneGroup ? phoneGroup.querySelector('.invalid-feedback') : null;
            
            phoneInput.addEventListener('blur', function() {
                phoneTouched = true;
                const phoneValue = this.value.replace(/\D/g, ''); // Remove all non-digits
                
                if (phoneValue === '') {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                    fullPhoneInput.value = '';
                    if (phoneFeedback) phoneFeedback.style.display = 'none';
                    return;
                }
                
                // Phone validation: must have at least 7 digits (minimum for most countries)
                // Maximum reasonable length is 15 digits (international standard)
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
                // Only validate on input if field has been touched (user interacted with it)
                if (phoneTouched) {
                    const phoneValue = this.value.replace(/\D/g, ''); // Remove all non-digits
                    
                    if (phoneValue === '') {
                        this.classList.remove('is-invalid');
                        this.setCustomValidity('');
                        fullPhoneInput.value = '';
                        if (phoneFeedback) phoneFeedback.style.display = 'none';
                        return;
                    }
                    
                    // Phone validation: must have at least 7 digits (minimum for most countries)
                    // Maximum reasonable length is 15 digits (international standard)
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
                    // Update full phone even if not validated yet
                    updateFullPhone();
                }
            });
        }
        
        // Update full phone when country code changes
        if (phoneInput) {
            phoneInput.addEventListener('input', updateFullPhone);
        }
        
        // Password confirmation validation - only show errors when user has entered data
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
                    // Only show error if field has been touched
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
        
        // Password Toggle Functionality
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
        
        // Form submission validation
        registerForm.addEventListener('submit', function(event) {
            if (!registerForm.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Focus on first invalid field
                const firstInvalid = registerForm.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            registerForm.classList.add('was-validated');
        }, false);
    }
});
</script>

