<?php
// Reset password view. Expects: $error_message, $success_message, $token, $token_valid
?>

<div class="login-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="login-card">
                    <div class="login-header">
                        <h2 class="login-title">RESET PASSWORD</h2>
                        <p class="login-subtitle">Enter your new password below</p>
                    </div>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-custom"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success alert-custom">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
                            <div class="mt-3">
                                <a href="login.php" class="btn-login w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>GO TO LOGIN
                                </a>
                            </div>
                        </div>
                    <?php elseif ($token_valid): ?>
                    <form method="POST" action="" class="needs-validation login-form" novalidate>
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        
                        <div class="form-group-custom mb-4">
                            <label for="password" class="form-label-custom">
                                <i class="fas fa-lock me-2"></i>New Password
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password" class="form-control-custom" id="password" name="password" 
                                       placeholder="Enter your new password"
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
                                <i class="fas fa-lock me-2"></i>Confirm New Password
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password" class="form-control-custom" id="confirm_password" name="confirm_password" 
                                       placeholder="Confirm your new password"
                                       minlength="6"
                                       required>
                                <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                            </div>
                            <div class="invalid-feedback">
                                Passwords must match.
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-login w-100">
                            <i class="fas fa-key me-2"></i>RESET PASSWORD
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <div class="login-footer text-center mt-4">
                        <p class="mb-0">
                            <a href="login.php" class="register-link">
                                <i class="fas fa-arrow-left me-1"></i>Back to Login
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Login Container */
.login-container {
    min-height: calc(100vh - 200px);
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    padding: 2rem 0;
}

/* Login Card */
.login-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 3rem 2.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

body.dark .login-card {
    background: rgba(26, 26, 46, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Login Header */
.login-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.login-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
}

body.dark .login-title {
    color: #e3e6ea;
}

.login-subtitle {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 0;
}

body.dark .login-subtitle {
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

/* Login Button - Blue/Black Theme */
.btn-login {
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
    text-decoration: none;
    display: block;
    text-align: center;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
    background: linear-gradient(135deg, #0056b3 0%, #004085 50%, #003366 100%);
    color: #fff;
}

.btn-login:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.4);
}

.btn-login:focus {
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
    outline: none;
}

/* Login Footer */
.login-footer {
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

body.dark .login-footer {
    border-top-color: #3b3f45;
}

.login-footer p {
    color: #6c757d;
    margin: 0;
}

body.dark .login-footer p {
    color: #9ca3af;
}

.register-link,
.forgot-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.register-link:hover,
.forgot-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

body.dark .register-link,
body.dark .forgot-link {
    color: #5dade2;
}

body.dark .register-link:hover,
body.dark .forgot-link:hover {
    color: #4a9fd1;
}

/* Alert Custom */
.alert-custom {
    border-radius: 10px;
    border: none;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

body.dark .alert-success {
    background-color: #1e4620;
    color: #90ee90;
}

/* Invalid/Valid Feedback */
.invalid-feedback,
.valid-feedback {
    display: none;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #dc3545;
}

.form-control-custom.is-invalid ~ .invalid-feedback {
    display: block;
}

.was-validated .form-control-custom:invalid ~ .invalid-feedback {
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .login-card {
        padding: 2rem 1.5rem;
    }
    
    .login-title {
        font-size: 1.75rem;
    }
    
    .form-group-custom {
        margin-bottom: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    
    // Password toggle functionality
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
    
    // Password match validation
    function validatePasswordMatch() {
        if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
            confirmPasswordInput.classList.add('is-invalid');
        } else {
            confirmPasswordInput.setCustomValidity('');
            confirmPasswordInput.classList.remove('is-invalid');
        }
    }
    
    if (passwordInput && confirmPasswordInput) {
        passwordInput.addEventListener('input', validatePasswordMatch);
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    }
    
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            form.classList.add('was-validated');
        }, false);
    }
});
</script>


