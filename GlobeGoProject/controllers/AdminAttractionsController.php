<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/Attraction.php';
require_once __DIR__ . '/../classes/Tour.php';
require_once __DIR__ . '/../classes/User.php';

class AdminAttractionsController extends BaseController
{
    public function index(): void
    {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || !isAdmin()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $attraction = new Attraction($this->db);
        $tour = new Tour($this->db);

        // Handle delete
        if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
            $attraction_id = (int)$_GET['delete'];
            
            // Check if attraction has associated tours
            $related_tours = $tour->getToursByAttraction($attraction_id);
            if (!empty($related_tours)) {
                $_SESSION['admin_message'] = "Cannot delete attraction. There are tours associated with this attraction. Please remove or reassign tours first.";
                $_SESSION['admin_message_type'] = 'danger';
            } else {
                try {
                    if ($attraction->delete($attraction_id)) {
                        $_SESSION['admin_message'] = "Attraction deleted successfully.";
                        $_SESSION['admin_message_type'] = 'success';
                    } else {
                        $_SESSION['admin_message'] = "Error deleting attraction.";
                        $_SESSION['admin_message_type'] = 'danger';
                    }
                } catch (Exception $e) {
                    $_SESSION['admin_message'] = "Error deleting attraction: " . $e->getMessage();
                    $_SESSION['admin_message_type'] = 'danger';
                    error_log("AdminAttractionsController delete error: " . $e->getMessage());
                }
            }
            // Preserve page parameter if set
            $redirect_url = SITE_URL . '/admin/attractions.php';
            if (isset($_GET['page']) && (int)$_GET['page'] > 1) {
                $redirect_url .= '?page=' . (int)$_GET['page'];
            }
            redirect($redirect_url);
        }

        // Handle form submission (add/edit)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = sanitize($_POST['action'] ?? '');
            $attraction_id = isset($_POST['attraction_id']) ? (int)$_POST['attraction_id'] : 0;

            if ($action === 'add' || $action === 'edit') {
                $name = sanitize($_POST['name'] ?? '');
                $description = sanitize($_POST['description'] ?? '');
                $location = sanitize($_POST['location'] ?? '');
                $latitude = !empty($_POST['latitude']) ? sanitize($_POST['latitude']) : null;
                $longitude = !empty($_POST['longitude']) ? sanitize($_POST['longitude']) : null;
                $category = sanitize($_POST['category'] ?? '');
                $image_url = sanitize($_POST['image_url'] ?? '');

                // Validation
                $errors = [];
                if (empty($name)) {
                    $errors[] = "Name is required.";
                }
                if (empty($description)) {
                    $errors[] = "Description is required.";
                }
                if (empty($location)) {
                    $errors[] = "Location is required.";
                }

                if (empty($errors)) {
                    $attraction_obj = new Attraction($this->db);
                    $attraction_obj->name = $name;
                    $attraction_obj->description = $description;
                    $attraction_obj->location = $location;
                    $attraction_obj->latitude = $latitude;
                    $attraction_obj->longitude = $longitude;
                    $attraction_obj->category = $category ?: null;
                    $attraction_obj->image_url = $image_url ?: null;

                    try {
                        if ($action === 'edit' && $attraction_id > 0) {
                            $attraction_obj->id = $attraction_id;
                            if ($attraction_obj->update()) {
                                // Update all related tours to use the new attraction image
                                if (!empty($image_url)) {
                                    try {
                                        $update_tours_stmt = $this->db->prepare("UPDATE tours SET image_url = ? WHERE attraction_id = ?");
                                        $update_tours_stmt->execute([$image_url, $attraction_id]);
                                    } catch (PDOException $e) {
                                        error_log("Error updating related tours images: " . $e->getMessage());
                                        // Don't fail the attraction update if tour update fails
                                    }
                                }
                                
                                // Update tour prices if provided
                                if (isset($_POST['tour_prices']) && is_array($_POST['tour_prices'])) {
                                    foreach ($_POST['tour_prices'] as $tour_id => $price) {
                                        $tour_id = (int)$tour_id;
                                        $price = (float)$price;
                                        if ($tour_id > 0 && $price > 0) {
                                            try {
                                                $update_price_stmt = $this->db->prepare("UPDATE tours SET price = ? WHERE id = ? AND attraction_id = ?");
                                                $update_price_stmt->execute([$price, $tour_id, $attraction_id]);
                                            } catch (PDOException $e) {
                                                error_log("Error updating tour price: " . $e->getMessage());
                                            }
                                        }
                                    }
                                }
                                
                                // Handle tour schedules
                                if (isset($_POST['tour_schedules']) && is_array($_POST['tour_schedules'])) {
                                    foreach ($_POST['tour_schedules'] as $tour_id => $schedules) {
                                        $tour_id = (int)$tour_id;
                                        if ($tour_id > 0 && is_array($schedules)) {
                                            // Update existing schedules
                                            if (isset($schedules['existing']) && is_array($schedules['existing'])) {
                                                foreach ($schedules['existing'] as $schedule_id => $schedule_data) {
                                                    $schedule_id = (int)$schedule_id;
                                                    $available_spots = isset($schedule_data['available_spots']) ? (int)$schedule_data['available_spots'] : null;
                                                    
                                                    if ($schedule_id > 0 && $available_spots !== null && $available_spots >= 0) {
                                                        try {
                                                            $update_schedule_stmt = $this->db->prepare("UPDATE tour_schedules SET available_spots = ? WHERE id = ? AND tour_id = ?");
                                                            $update_schedule_stmt->execute([$available_spots, $schedule_id, $tour_id]);
                                                        } catch (PDOException $e) {
                                                            error_log("Error updating schedule: " . $e->getMessage());
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            // Add new schedules
                                            if (isset($schedules['new']) && is_array($schedules['new'])) {
                                                foreach ($schedules['new'] as $new_schedule) {
                                                    if (!empty($new_schedule['tour_date']) && !empty($new_schedule['tour_time']) && isset($new_schedule['available_spots'])) {
                                                        $tour_date = sanitize($new_schedule['tour_date']);
                                                        $tour_time = sanitize($new_schedule['tour_time']);
                                                        $available_spots = (int)$new_schedule['available_spots'];
                                                        
                                                        if ($available_spots > 0 && strtotime($tour_date) >= strtotime('today')) {
                                                            try {
                                                                $insert_schedule_stmt = $this->db->prepare("INSERT INTO tour_schedules (tour_id, tour_date, tour_time, available_spots, status) VALUES (?, ?, ?, ?, 'available')");
                                                                $insert_schedule_stmt->execute([$tour_id, $tour_date, $tour_time, $available_spots]);
                                                            } catch (PDOException $e) {
                                                                error_log("Error adding schedule: " . $e->getMessage());
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                $_SESSION['admin_message'] = "Attraction updated successfully. Related tours and schedules have been updated.";
                                $_SESSION['admin_message_type'] = 'success';
                            } else {
                                $_SESSION['admin_message'] = "Error updating attraction.";
                                $_SESSION['admin_message_type'] = 'danger';
                            }
                        } else {
                            if ($attraction_obj->create()) {
                                $_SESSION['admin_message'] = "Attraction added successfully.";
                                $_SESSION['admin_message_type'] = 'success';
                            } else {
                                $_SESSION['admin_message'] = "Error adding attraction.";
                                $_SESSION['admin_message_type'] = 'danger';
                            }
                        }
                        // Preserve page parameter if set
                        $redirect_url = SITE_URL . '/admin/attractions.php';
                        if (isset($_GET['page']) && (int)$_GET['page'] > 1) {
                            $redirect_url .= '?page=' . (int)$_GET['page'];
                        }
                        redirect($redirect_url);
                    } catch (Exception $e) {
                        $_SESSION['admin_message'] = "Error: " . $e->getMessage();
                        $_SESSION['admin_message_type'] = 'danger';
                        error_log("AdminAttractionsController save error: " . $e->getMessage());
                    }
                } else {
                    $_SESSION['admin_message'] = implode(' ', $errors);
                    $_SESSION['admin_message_type'] = 'danger';
                }
            }
        }

        // Get all attractions
        $all_attractions = $attraction->getAttractions([]);
        $categories = $attraction->getCategories();

        // Pagination settings
        $items_per_page = 5;
        $total_items = count($all_attractions);
        $total_pages = $total_items > 0 ? ceil($total_items / $items_per_page) : 1;

        // Get current page from URL - ensure it's always valid
        $page_from_url = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $current_page = max(1, min($page_from_url, $total_pages));

        // Ensure all are integers and current_page is NEVER 0
        $items_per_page = (int)$items_per_page;
        $total_items = (int)$total_items;
        $total_pages = (int)$total_pages;
        $current_page = max(1, (int)$current_page); // Force minimum of 1

        // Calculate offset
        $offset = ((int)$current_page - 1) * (int)$items_per_page;

        // Get paginated attractions
        $paginated_attractions = array_slice($all_attractions, $offset, $items_per_page);

        // Get tours for each paginated attraction (for display) with schedules
        $attractions_with_tours = [];
        foreach ($paginated_attractions as $attr) {
            $related_tours = $tour->getToursByAttraction($attr['id']);
            // Fetch schedules for each tour
            foreach ($related_tours as &$related_tour) {
                $related_tour['schedules'] = $tour->getTourSchedules($related_tour['id']);
            }
            $attr['related_tours'] = $related_tours;
            $attractions_with_tours[] = $attr;
        }

        $this->render('admin/attractions', [
            'attractions' => $attractions_with_tours,
            'categories' => $categories,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'total_items' => $total_items,
            'items_per_page' => $items_per_page,
        ], 'Manage Attractions');
    }

    public function showEditForm(): void
    {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || !isAdmin()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        $attraction_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$attraction_id) {
            // Preserve page parameter if set
            $redirect_url = SITE_URL . '/admin/attractions.php';
            if (isset($_GET['page']) && (int)$_GET['page'] > 1) {
                $redirect_url .= '?page=' . (int)$_GET['page'];
            }
            redirect($redirect_url);
        }

        $attraction = new Attraction($this->db);
        $tour = new Tour($this->db);
        
        $attraction_details = $attraction->getAttractionById($attraction_id);
        
        if (!$attraction_details) {
            // Preserve page parameter if set
            $redirect_url = SITE_URL . '/admin/attractions.php';
            if (isset($_GET['page']) && (int)$_GET['page'] > 1) {
                $redirect_url .= '?page=' . (int)$_GET['page'];
            }
            redirect($redirect_url);
        }

        // Get related tours with schedules
        $related_tours = $tour->getToursByAttraction($attraction_id);
        // Fetch schedules for each tour (including past dates for admin view)
        foreach ($related_tours as &$related_tour) {
            $schedule_query = "SELECT * FROM tour_schedules WHERE tour_id = ? ORDER BY tour_date DESC, tour_time DESC";
            $schedule_stmt = $this->db->prepare($schedule_query);
            $schedule_stmt->execute([$related_tour['id']]);
            $related_tour['schedules'] = $schedule_stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $categories = $attraction->getCategories();

        $this->render('admin/attractions_edit', [
            'attraction' => $attraction_details,
            'related_tours' => $related_tours,
            'categories' => $categories,
        ], 'Edit Attraction');
    }
}



