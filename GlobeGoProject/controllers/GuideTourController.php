<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/Tour.php';
require_once __DIR__ . '/../classes/Attraction.php';

class GuideTourController extends BaseController
{
    private function requireGuide(): void
    {
        if (!isLoggedIn() || !isGuide()) {
            redirect(SITE_URL . '/auth/login.php');
        }
    }

    public function create(): void
    {
        $this->requireGuide();

        $error_message = '';
        $success_message = '';

        $attraction = new Attraction($this->db);
        $attractions = $attraction->getAttractions();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour = new Tour($this->db);
            
            // Sanitize input (same as original)
            $title = sanitize($_POST['title'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $price = (float) ($_POST['price'] ?? 0);
            $duration_hours = (int) ($_POST['duration_hours'] ?? 0);
            $max_participants = (int) ($_POST['max_participants'] ?? 0);
            $meeting_point = sanitize($_POST['meeting_point'] ?? '');
            $category = sanitize($_POST['category'] ?? '');
            $attraction_id = !empty($_POST['attraction_id']) ? (int) $_POST['attraction_id'] : null;
            $image_url = sanitize($_POST['image_url'] ?? '');
            
            // Validation
            if (empty($title) || empty($description) || empty($price) || empty($duration_hours) || 
                empty($max_participants) || empty($meeting_point) || empty($category)) {
                $error_message = "All required fields must be filled.";
            } elseif ($price <= 0) {
                $error_message = "Price must be greater than 0.";
            } elseif ($duration_hours <= 0 || $duration_hours > 24) {
                $error_message = "Duration must be between 1 and 24 hours.";
            } elseif ($max_participants <= 0 || $max_participants > 50) {
                $error_message = "Maximum participants must be between 1 and 50.";
            } else {
                // Set tour properties
                $tour->guide_id = $_SESSION['user_id'];
                $tour->attraction_id = $attraction_id;
                $tour->title = $title;
                $tour->description = $description;
                $tour->price = $price;
                $tour->duration_hours = $duration_hours;
                $tour->max_participants = $max_participants;
                $tour->meeting_point = $meeting_point;
                $tour->category = $category;
                $tour->image_url = $image_url;
                
                // Create tour
                if ($tour->create()) {
                    $success_message = "Tour created successfully! You can now add schedules for your tour.";
                } else {
                    $error_message = "Failed to create tour. Please try again.";
                }
            }
        }

        $this->render('guide/create_tour', [
            'error_message' => $error_message,
            'success_message' => $success_message,
            'attractions' => $attractions,
        ], 'Create New Tour');
    }

    public function myTours(): void
    {
        $this->requireGuide();

        $tour = new Tour($this->db);
        $booking = new Booking($this->db);

        $user_tours = $tour->getToursByGuide($_SESSION['user_id']);

        // We keep per-tour stats logic in the view as before (it calls getTourStats/getTourSchedules)
        $this->render('guide/my_tours', [
            'tourModel' => $tour,
            'user_tours' => $user_tours,
        ], 'My Tours');
    }
}


