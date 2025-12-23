<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/User.php';

class AuthController extends BaseController
{
    public function login(): void
    {
        // Redirect if already logged in
        if (isLoggedIn()) {
            if (isAdmin()) {
                redirect(SITE_URL . '/admin/dashboard_mvc.php');
            } else {
                redirect(SITE_URL . '/dashboard.php');
            }
        }

        $error_message = '';
        $suspension_info = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error_message = "Email and password are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Please enter a valid email address.";
            } else {
                $user = new User($this->db);
                $login_result = $user->login($email, $password);
                
                if (is_array($login_result) && $login_result['success']) {
                    // Set session variables
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_email'] = $user->email;
                    $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
                    $_SESSION['user_role'] = $user->role;
                    $_SESSION['user_verified'] = $user->verified;
                    
                    // Redirect based on role
                    if ($user->role === 'admin') {
                        redirect(SITE_URL . '/admin/dashboard_mvc.php');
                    } else {
                        redirect(SITE_URL . '/dashboard.php');
                    }
                } else {
                    // Check if user is suspended
                    if (is_array($login_result) && isset($login_result['suspended']) && $login_result['suspended']) {
                        $suspension_info = [
                            'suspend_until' => $login_result['suspend_until']
                        ];
                    } else {
                        $error_message = "Invalid email or password.";
                    }
                }
            }
        }

        $this->render('auth/login', [
            'error_message' => $error_message,
            'suspension_info' => $suspension_info,
        ], 'Login');
    }

    public function register(): void
    {
        $error_message = '';
        $success_message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User($this->db);
            
            // Sanitize input
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $first_name = sanitize($_POST['first_name'] ?? '');
            $last_name = sanitize($_POST['last_name'] ?? '');
            $phone = sanitize($_POST['phone'] ?? '');
            $full_phone = sanitize($_POST['full_phone'] ?? ''); // Country code + phone
            $role = sanitize($_POST['role'] ?? '');
            
            // Use full_phone if available, otherwise use phone
            $phone_to_store = !empty($full_phone) ? $full_phone : $phone;
            
            // Validation
            if (empty($email) || empty($password) || empty($first_name) || empty($last_name) || empty($phone)) {
                $error_message = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Invalid email format.";
            } elseif (!preg_match('/^[0-9\s\-\(\)]{7,}$/', $phone) || preg_match_all('/\d/', $phone) < 7) {
                $error_message = "Please enter a valid phone number (7-15 digits).";
            } elseif (strlen($password) < 6) {
                $error_message = "Password must be at least 6 characters long.";
            } elseif ($password !== $confirm_password) {
                $error_message = "Passwords do not match.";
            } elseif ($user->emailExists($email)) {
                $error_message = "Email already exists.";
            } else {
                // Set user properties
                $user->email = $email;
                $user->password = $password;
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->phone = $phone_to_store; // Store full phone with country code
                $user->role = $role;
                
                // Create user
                if ($user->register()) {
                    // Send welcome email
                    $this->sendWelcomeEmail($email, $first_name, $last_name, $role);
                    
                    // Automatically log in the user after successful registration
                    $login_result = $user->login($email, $password);
                    
                    if (is_array($login_result) && $login_result['success']) {
                        // Set session variables
                        $_SESSION['user_id'] = $user->id;
                        $_SESSION['user_email'] = $user->email;
                        $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
                        $_SESSION['user_role'] = $user->role;
                        $_SESSION['user_verified'] = $user->verified;
                        
                        // Redirect based on role
                        if ($user->role === 'admin') {
                            redirect(SITE_URL . '/admin/dashboard_mvc.php');
                        } else {
                            redirect(SITE_URL . '/dashboard.php');
                        }
                    } else {
                        // If auto-login fails, show success message (shouldn't happen, but just in case)
                        $success_message = "Registration successful! You can now login.";
                    }
                } else {
                    $error_message = "Registration failed. Please try again.";
                }
            }
        }

        $this->render('auth/register', [
            'error_message' => $error_message,
            'success_message' => $success_message,
        ], 'Register');
    }

    public function registerGuide(): void
    {
        $error_message = '';
        $success_message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User($this->db);
            
            // Sanitize input
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $first_name = sanitize($_POST['first_name'] ?? '');
            $last_name = sanitize($_POST['last_name'] ?? '');
            $phone = sanitize($_POST['phone'] ?? '');
            $full_phone = sanitize($_POST['full_phone'] ?? '');
            $home_country = sanitize($_POST['home_country'] ?? '');
            $national_id = sanitize($_POST['national_id'] ?? '');
            $date_of_birth = sanitize($_POST['date_of_birth'] ?? '');
            $street = sanitize($_POST['street'] ?? '');
            $building = sanitize($_POST['building'] ?? '');
            $city = sanitize($_POST['city'] ?? '');
            $state = sanitize($_POST['state'] ?? '');
            $postal_code = sanitize($_POST['postal_code'] ?? '');
            $criminal_records = isset($_POST['criminal_records']) ? 1 : 0;
            
            // Combine address fields (using home_country as the country)
            $address_parts = array_filter([$street, $building, $city, $state, $postal_code, $home_country]);
            $address = !empty($address_parts) ? implode(', ', $address_parts) : '';
            
            // Use full_phone if available, otherwise use phone
            $phone_to_store = !empty($full_phone) ? $full_phone : $phone;
            
            // Country-specific National ID validation function
            function validateNationalIDByCountry($id, $country) {
                $id = preg_replace('/[-\s]/', '', $id); // Remove dashes and spaces
                
                switch ($country) {
                    case 'Egypt':
                        // 14 digits, validate structure
                        if (!preg_match('/^\d{14}$/', $id)) {
                            return "Egyptian National ID must be exactly 14 digits";
                        }
                        $century = (int)$id[0];
                        $month = (int)substr($id, 3, 2);
                        $day = (int)substr($id, 5, 2);
                        $governorate = (int)substr($id, 7, 2);
                        if ($century !== 2 && $century !== 3) return "Invalid century digit in Egyptian National ID";
                        if ($month < 1 || $month > 12) return "Invalid month in Egyptian National ID";
                        if ($day < 1 || $day > 31) return "Invalid day in Egyptian National ID";
                        if ($governorate < 1 || $governorate > 27) return "Invalid governorate code in Egyptian National ID";
                        return true;
                        
                    case 'Saudi Arabia':
                        if (!preg_match('/^\d{10}$/', $id)) {
                            return "Saudi National ID must be exactly 10 digits";
                        }
                        return true;
                        
                    case 'United States':
                        if (!preg_match('/^\d{9}$/', $id)) {
                            return "US SSN must be 9 digits";
                        }
                        if (substr($id, 0, 3) === '000' || substr($id, 3, 2) === '00' || substr($id, 5) === '0000') {
                            return "Invalid US SSN format";
                        }
                        return true;
                        
                    case 'United Kingdom':
                        $id = strtoupper(preg_replace('/\s/', '', $id));
                        if (!preg_match('/^[A-Z]{2}\d{6}[A-Z]$/', $id)) {
                            return "UK National Insurance Number must be in format: AB123456C";
                        }
                        return true;
                        
                    case 'United Arab Emirates':
                        if (!preg_match('/^\d{15}$/', $id)) {
                            return "UAE Emirates ID must be exactly 15 digits";
                        }
                        return true;
                        
                    case 'India':
                        if (!preg_match('/^\d{12}$/', $id)) {
                            return "Indian Aadhaar must be exactly 12 digits";
                        }
                        return true;
                        
                    case 'Pakistan':
                        if (!preg_match('/^\d{13}$/', $id)) {
                            return "Pakistani CNIC must be 13 digits";
                        }
                        return true;
                        
                    case 'Jordan':
                        if (!preg_match('/^\d{10}$/', $id)) {
                            return "Jordanian National Number must be exactly 10 digits";
                        }
                        return true;
                        
                    case 'Lebanon':
                        if (!preg_match('/^[A-Za-z0-9]{8,15}$/', $id)) {
                            return "Lebanese ID must be 8-15 alphanumeric characters";
                        }
                        return true;
                        
                    case 'Kuwait':
                        if (!preg_match('/^\d{12}$/', $id)) {
                            return "Kuwaiti Civil ID must be exactly 12 digits";
                        }
                        return true;
                        
                    case 'Qatar':
                        if (!preg_match('/^\d{11}$/', $id)) {
                            return "Qatari ID must be exactly 11 digits";
                        }
                        return true;
                        
                    case 'Bahrain':
                        if (!preg_match('/^\d{9}$/', $id)) {
                            return "Bahraini CPR must be exactly 9 digits";
                        }
                        return true;
                        
                    case 'Oman':
                        if (!preg_match('/^\d{9}$/', $id)) {
                            return "Omani ID must be exactly 9 digits";
                        }
                        return true;
                        
                    case 'Other':
                    default:
                        if (!preg_match('/^[A-Za-z0-9\s\-]{5,15}$/', $id)) {
                            return "Passport number must be 5-15 alphanumeric characters";
                        }
                        return true;
                }
            }
            
            // Validation
            if (empty($email) || empty($password) || empty($first_name) || empty($last_name) || 
                empty($phone) || empty($home_country) || empty($national_id) || empty($date_of_birth) || 
                empty($street) || empty($city) || empty($state) || empty($postal_code)) {
                $error_message = "All required fields must be filled.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Invalid email format.";
            } elseif (!preg_match('/^[0-9\s\-\(\)]{7,}$/', $phone) || preg_match_all('/\d/', $phone) < 7) {
                $error_message = "Please enter a valid phone number (7-15 digits).";
            } elseif (empty($home_country)) {
                $error_message = "Please select your home country.";
            } else {
                $national_id_validation = validateNationalIDByCountry($national_id, $home_country);
                if ($national_id_validation !== true) {
                    $error_message = $national_id_validation;
                } elseif (!empty($date_of_birth) && strtotime($date_of_birth) > time()) {
                    $error_message = "Date of birth cannot be in the future.";
                } elseif (strlen($password) < 6) {
                    $error_message = "Password must be at least 6 characters long.";
                } elseif ($password !== $confirm_password) {
                    $error_message = "Passwords do not match.";
                } elseif ($user->emailExists($email)) {
                    $error_message = "Email already exists.";
                } else {
                    // Set user properties
                    $user->email = $email;
                    $user->password = $password;
                    $user->first_name = $first_name;
                    $user->last_name = $last_name;
                    $user->phone = $phone_to_store;
                    $user->national_id = $national_id;
                    $user->date_of_birth = $date_of_birth;
                    $user->address = $address;
                    $user->criminal_records = $criminal_records;
                    
                    // Create guide application
                    if ($user->registerGuide()) {
                        // Send welcome email
                        $this->sendWelcomeEmail($email, $first_name, $last_name, 'guide');
                        
                        $success_message = "Your guide application has been submitted successfully! Our team will review your application and reach out to you via email to schedule an interview. Please check your email regularly for updates.";
                    } else {
                        $error_message = "Registration failed. Please try again.";
                    }
                }
            }
        }

        $this->render('auth/register_guide', [
            'error_message' => $error_message,
            'success_message' => $success_message,
        ], 'Register as Tour Guide');
    }

    public function logout(): void
    {
        require_once __DIR__ . '/../config/config.php';
        // Destroy session and redirect home (same as original)
        session_destroy();
        redirect(SITE_URL . '/index.php');
    }

    public function forgotPassword(): void
    {
        // Redirect if already logged in
        if (isLoggedIn()) {
            if (isAdmin()) {
                redirect(SITE_URL . '/admin/dashboard_mvc.php');
            } else {
                redirect(SITE_URL . '/dashboard.php');
            }
        }

        $error_message = '';
        $success_message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            
            if (empty($email)) {
                $error_message = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Please enter a valid email address.";
            } else {
                $user = new User($this->db);
                
                // Create password reset token
                $token = $user->createPasswordResetToken($email);
                
                if ($token) {
                    // Generate reset link
                    $resetLink = SITE_URL . '/auth/reset-password.php?token=' . $token;
                    
                    // Send email
                    require_once __DIR__ . '/../helpers/EmailTemplate.php';
                    $emailBody = EmailTemplate::passwordReset($email, $token, $resetLink);
                    
                    $from = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'no-reply@localhost';
                    $fromName = defined('SITE_NAME') ? SITE_NAME : 'GlobeGo';
                    
                    $headers = "From: " . $fromName . " <" . $from . ">\r\n";
                    $headers .= "Reply-To: " . $from . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                    
                    $subject = 'Password Reset Request - ' . SITE_NAME;
                    
                    // Send email (use @ to suppress warnings if mail() isn't configured)
                    @mail($email, $subject, $emailBody, $headers);
                    
                    // Always show success message for security (don't reveal if email exists)
                    $success_message = "If an account with that email exists, we've sent a password reset link. Please check your email (including spam folder).";
                } else {
                    // Still show success for security (don't reveal if email exists)
                    $success_message = "If an account with that email exists, we've sent a password reset link. Please check your email (including spam folder).";
                }
            }
        }

        $this->render('auth/forgot_password', [
            'error_message' => $error_message,
            'success_message' => $success_message,
        ], 'Forgot Password');
    }

    public function resetPassword(): void
    {
        // Redirect if already logged in
        if (isLoggedIn()) {
            if (isAdmin()) {
                redirect(SITE_URL . '/admin/dashboard_mvc.php');
            } else {
                redirect(SITE_URL . '/dashboard.php');
            }
        }

        $token = $_GET['token'] ?? '';
        $error_message = '';
        $success_message = '';
        $token_valid = false;

        // Verify token
        if (!empty($token)) {
            $user = new User($this->db);
            if ($user->verifyPasswordResetToken($token)) {
                $token_valid = true;
            } else {
                $error_message = "Invalid or expired password reset token. Please request a new one.";
            }
        } else {
            $error_message = "No reset token provided.";
        }

        // Handle password reset form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token_valid) {
            $new_password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if (empty($new_password) || empty($confirm_password)) {
                $error_message = "Both password fields are required.";
            } elseif (strlen($new_password) < 6) {
                $error_message = "Password must be at least 6 characters long.";
            } elseif ($new_password !== $confirm_password) {
                $error_message = "Passwords do not match.";
            } else {
                $user = new User($this->db);
                if ($user->resetPassword($token, $new_password)) {
                    $success_message = "Your password has been reset successfully! You can now login with your new password.";
                    $token_valid = false; // Hide form after success
                } else {
                    $error_message = "Failed to reset password. The token may have expired. Please request a new one.";
                }
            }
        }

        $this->render('auth/reset_password', [
            'error_message' => $error_message,
            'success_message' => $success_message,
            'token' => $token,
            'token_valid' => $token_valid,
        ], 'Reset Password');
    }

    /**
     * Send welcome email to newly registered user
     */
    private function sendWelcomeEmail(string $email, string $firstName, string $lastName, string $role = 'tourist'): void {
        require_once __DIR__ . '/../helpers/EmailTemplate.php';
        
        $emailBody = EmailTemplate::welcomeEmail($firstName, $lastName, $role);
        
        $from = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'no-reply@localhost';
        $fromName = defined('SITE_NAME') ? SITE_NAME : 'GlobeGo';
        
        $headers = "From: " . $fromName . " <" . $from . ">\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $subject = 'Welcome to ' . SITE_NAME . '!';
        
        // Send email (use @ to suppress warnings if mail() isn't configured)
        @mail($email, $subject, $emailBody, $headers);
        
        // Log for debugging
        error_log('Welcome email sent to: ' . $email);
    }
}


