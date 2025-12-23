<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/User.php';

class ProfileController extends BaseController
{
    public function index(): void
    {
        // Ensure user is logged in
        if (!isLoggedIn()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $error_message = '';
        $success_message = '';

        $user = new User($this->db);
        $user->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle profile update (same logic as original)
            $first_name = sanitize($_POST['first_name'] ?? '');
            $last_name = sanitize($_POST['last_name'] ?? '');
            $phone = sanitize($_POST['phone'] ?? '');
            $bio = sanitize($_POST['bio'] ?? '');
            $languages = sanitize($_POST['languages'] ?? '');
            $profile_image = sanitize($_POST['profile_image'] ?? '');
            
            if (empty($first_name) || empty($last_name)) {
                $error_message = "First name and last name are required.";
            } else {
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->phone = $phone;
                $user->bio = $bio;
                $user->languages = $languages;
                $user->profile_image = $profile_image;
                
                if ($user->updateProfile()) {
                    $success_message = "Profile updated successfully!";
                    $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
                } else {
                    $error_message = "Failed to update profile. Please try again.";
                }
            }
        }

        $this->render('profile/index', [
            'user' => $user,
            'error_message' => $error_message,
            'success_message' => $success_message,
        ], 'Profile');
    }
}


