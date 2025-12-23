<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Tour.php';

class AdminGuidesController extends BaseController
{
    public function index(): void
    {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || !isAdmin()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $tour = new Tour($this->db);
        $user = new User($this->db);

        // Handle guide info update
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_guide') {
            $guide_id = (int)$_POST['guide_id'];
            $bio = sanitize($_POST['bio'] ?? '');
            $languages = sanitize($_POST['languages'] ?? '');

            // Get guide info first
            $guide_query = "SELECT id, first_name, last_name, phone, profile_image FROM users WHERE id = :id";
            $guide_stmt = $this->db->prepare($guide_query);
            $guide_stmt->bindParam(":id", $guide_id);
            $guide_stmt->execute();
            $guide_data = $guide_stmt->fetch(PDO::FETCH_ASSOC);

            if ($guide_data) {
                // Update guide using User class
                $guide = new User($this->db);
                $guide->id = $guide_id;
                $guide->first_name = $guide_data['first_name'];
                $guide->last_name = $guide_data['last_name'];
                $guide->phone = $guide_data['phone'] ?? '';
                $guide->profile_image = $guide_data['profile_image'] ?? '';
                $guide->bio = $bio;
                $guide->languages = $languages;

                if ($guide->updateProfile()) {
                    $_SESSION['admin_message'] = "Guide information updated successfully!";
                    $_SESSION['admin_message_type'] = 'success';
                } else {
                    $_SESSION['admin_message'] = "Failed to update guide information.";
                    $_SESSION['admin_message_type'] = 'danger';
                }
            } else {
                $_SESSION['admin_message'] = "Guide not found.";
                $_SESSION['admin_message_type'] = 'danger';
            }

            header("Location: " . SITE_URL . '/admin/guides.php');
            exit();
        }

        // Get all tours with guide information
        $query = "SELECT t.*, 
                         u.id as guide_id, u.first_name, u.last_name, u.email, u.phone, 
                         u.profile_image, u.bio, u.languages, u.verified,
                         a.name as attraction_name, a.location as attraction_location
                  FROM tours t
                  LEFT JOIN users u ON t.guide_id = u.id
                  LEFT JOIN attractions a ON t.attraction_id = a.id
                  ORDER BY t.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $tours_with_guides = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group tours by guide to show unique guides
        $guides_map = [];
        foreach ($tours_with_guides as $tour_data) {
            $guide_id = $tour_data['guide_id'];
            if (!isset($guides_map[$guide_id])) {
                $guides_map[$guide_id] = [
                    'guide_id' => $guide_id,
                    'first_name' => $tour_data['first_name'],
                    'last_name' => $tour_data['last_name'],
                    'email' => $tour_data['email'],
                    'phone' => $tour_data['phone'],
                    'profile_image' => $tour_data['profile_image'],
                    'bio' => $tour_data['bio'],
                    'languages' => $tour_data['languages'],
                    'verified' => $tour_data['verified'],
                    'tours' => []
                ];
            }
            $guides_map[$guide_id]['tours'][] = [
                'tour_id' => $tour_data['id'],
                'title' => $tour_data['title'],
                'category' => $tour_data['category'],
                'attraction_name' => $tour_data['attraction_name'],
                'attraction_location' => $tour_data['attraction_location']
            ];
        }

        $this->render('admin/guides', [
            'guides_map' => $guides_map,
        ], 'Manage Tour Guides');
    }
}



