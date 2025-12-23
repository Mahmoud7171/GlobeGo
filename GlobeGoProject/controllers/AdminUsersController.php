<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/User.php';

class AdminUsersController extends BaseController
{
    public function index(): void
    {
        // Check if user is logged in and is admin (same as original admin/users.php)
        if (!isLoggedIn() || !isAdmin()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $user = new User($this->db);

        // Handle user actions (approve, reject, verify_guide)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['user_id'])) {
            $user_id = (int)$_POST['user_id'];
            $action = sanitize($_POST['action']);
            
            $success = false;
            $message = '';
            
            try {
                switch ($action) {
                    case 'approve':
                        // Check if it's a guide before approving
                        $check_query = "SELECT role FROM users WHERE id = :id";
                        $check_stmt = $this->db->prepare($check_query);
                        $check_stmt->bindParam(":id", $user_id);
                        $check_stmt->execute();
                        $user_data = $check_stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if (!$user_data) {
                            $message = "User not found.";
                        } else {
                            $success = $user->approveUser($user_id);
                            if ($success && $user_data && $user_data['role'] === 'guide') {
                                $message = "Tour guide approved and verified successfully! They can now log in and create tours.";
                            } else {
                                $message = $success ? "User approved successfully!" : "Failed to approve user. Please try again.";
                            }
                        }
                        break;
                    case 'reject':
                        $success = $user->rejectUser($user_id);
                        $message = $success ? "Tour guide application rejected." : "Failed to reject application. Please try again.";
                        break;
                    case 'suspend':
                        $duration_days = isset($_POST['duration_days']) ? (int)$_POST['duration_days'] : 0;
                        if ($duration_days <= 0) {
                            $message = "Please specify a valid suspension duration.";
                        } else {
                            $success = $user->suspendUser($user_id, $duration_days);
                            $message = $success ? "User suspended for {$duration_days} day(s) successfully!" : "Failed to suspend user.";
                        }
                        break;
                    case 'unsuspend':
                        $success = $user->unsuspendUser($user_id);
                        $message = $success ? "User unsuspended successfully!" : "Failed to unsuspend user.";
                        break;
                    case 'delete':
                        $success = $user->deleteUser($user_id);
                        if (!$success) {
                            $message = "Cannot delete admin users or user not found.";
                        } else {
                            $message = "User deleted successfully!";
                        }
                        break;
                    case 'verify_guide':
                        $success = $user->verifyGuide($user_id);
                        $message = $success ? "Guide verified successfully!" : "Failed to verify guide.";
                        break;
                }
            } catch (Exception $e) {
                $message = "Error: " . $e->getMessage();
                error_log("AdminUsersController error: " . $e->getMessage());
            }
            
            // Set session message
            if ($success) {
                $_SESSION['admin_message'] = $message;
                $_SESSION['admin_message_type'] = 'success';
            } else {
                $_SESSION['admin_message'] = $message;
                $_SESSION['admin_message_type'] = 'danger';
            }
            
            // Redirect back to dashboard or users page - preserve page number
            $redirect_url = isset($_POST['redirect']) ? sanitize($_POST['redirect']) : 'users_mvc.php';
            if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                $redirect_url .= '?page=' . (int)$_GET['page'];
            }
            header("Location: " . SITE_URL . '/admin/' . $redirect_url);
            exit();
        }

        $all_users = $user->getAllUsers();

        $status_filter = $_GET['status'] ?? 'all';
        $role_filter = $_GET['role'] ?? 'all';
        $verified_filter = $_GET['verified'] ?? 'all';

        $filtered_users = $all_users;

        if ($status_filter !== 'all') {
            $filtered_users = array_filter($filtered_users, function($u) use ($status_filter) {
                return $u['status'] === $status_filter;
            });
        }

        if ($role_filter !== 'all') {
            $filtered_users = array_filter($filtered_users, function($u) use ($role_filter) {
                return $u['role'] === $role_filter;
            });
        }

        if ($verified_filter !== 'all') {
            $verified_value = $verified_filter === '1';
            $filtered_users = array_filter($filtered_users, function($u) use ($verified_value) {
                return (isset($u['verified']) ? $u['verified'] : 0) == $verified_value;
            });
        }

        // Reset array keys after filtering
        $filtered_users = array_values($filtered_users);

        // Pagination settings
        $items_per_page = 5;
        $total_items = count($filtered_users);
        $total_pages = $total_items > 0 ? ceil($total_items / $items_per_page) : 1;
        $current_page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $total_pages)) : 1;
        
        // Calculate offset
        $offset = ($current_page - 1) * $items_per_page;
        
        // Get paginated users
        $paginated_users = array_slice($filtered_users, $offset, $items_per_page);

        $this->render('admin/users', [
            'all_users' => $all_users,
            'filtered_users' => $paginated_users,
            'status_filter' => $status_filter,
            'role_filter' => $role_filter,
            'verified_filter' => $verified_filter,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'total_items' => $total_items,
            'items_per_page' => $items_per_page,
        ], 'Manage Users');
    }
}
