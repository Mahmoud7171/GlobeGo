<?php
// Forgot password view. Expects: $error_message, $success_message
?>

<div class="login-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="login-card">
                    <div class="login-header">
                        <h2 class="login-title">FORGOT PASSWORD</h2>
                        <p class="login-subtitle">Enter your email address and we'll send you a password reset link</p>
                    </div>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger alert-custom"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success alert-custom">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($success_message)): ?>
                    <form method="POST" action="" class="needs-validation login-form" novalidate>
                        <div class="form-group-custom mb-4">
                            <label for="email" class="form-label-custom">
                                <i class="fas fa-envelope me-2"></i>Email Address
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
                        
                        <button type="submit" class="btn-login w-100">
                            <i class="fas fa-paper-plane me-2"></i>SEND RESET LINK
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <div class="login-footer text-center mt-4">
                        <p class="mb-2">
                            <a href="login.php" class="register-link">
                                <i class="fas fa-arrow-left me-1"></i>Back to Login
                            </a>
                        </p>
                        <p class="mb-0">Don't have an account? <a href="register.php" class="register-link">Register here</a></p>
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
    const emailInput = document.getElementById('email');
    
    if (form && emailInput) {
        let emailTouched = false;
        const emailGroup = emailInput.closest('.form-group-custom');
        const emailFeedback = emailGroup ? emailGroup.querySelector('.invalid-feedback') : null;
        
        emailInput.addEventListener('blur', function() {
            emailTouched = true;
            validateEmail();
        });
        
        emailInput.addEventListener('input', function() {
            if (emailTouched) {
                validateEmail();
            }
        });
        
        function validateEmail() {
            const emailValue = emailInput.value.trim();
            
            if (emailValue === '') {
                emailInput.classList.remove('is-invalid');
                emailInput.setCustomValidity('');
                if (emailFeedback) emailFeedback.style.display = 'none';
                return;
            }
            
            const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
            
            if (emailRegex.test(emailValue)) {
                emailInput.classList.remove('is-invalid');
                emailInput.setCustomValidity('');
                if (emailFeedback) emailFeedback.style.display = 'none';
            } else {
                emailInput.classList.add('is-invalid');
                emailInput.setCustomValidity('Please enter a valid email address');
                if (emailFeedback) emailFeedback.style.display = 'block';
            }
        }
        
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


