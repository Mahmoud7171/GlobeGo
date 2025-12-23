<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/Attraction.php';
require_once __DIR__ . '/../classes/Tour.php';

class AttractionController extends BaseController
{
    public function index(): void
    {
        $attraction = new Attraction($this->db);

        // Build filters exactly as in original attractions.php
        $filters = [];
        if (!empty($_GET['location'])) {
            $filters['location'] = sanitize($_GET['location']);
        }
        if (!empty($_GET['category'])) {
            $filters['category'] = sanitize($_GET['category']);
        }
        if (!empty($_GET['search'])) {
            $filters['search'] = sanitize($_GET['search']);
        }

        $attractions = $attraction->getAttractions($filters);
        $categories = $attraction->getCategories();

        $this->render('attractions/index', [
            'attractions' => $attractions,
            'categories' => $categories,
        ], 'Attractions');
    }

    public function showDetails(): void
    {
        $attraction_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if (!$attraction_id) {
            redirect(SITE_URL . '/attractions.php');
        }

        $attraction = new Attraction($this->db);
        $attraction_details = $attraction->getAttractionById($attraction_id);

        if (!$attraction_details) {
            redirect(SITE_URL . '/attractions.php');
        }

        // Tours for this attraction (same as original)
        $tour = new Tour($this->db);
        $attraction_tours = $tour->getToursByAttraction($attraction_id);

        $this->render('attractions/details', [
            'attraction_id' => $attraction_id,
            'attraction_details' => $attraction_details,
            'attraction_tours' => $attraction_tours,
        ], $attraction_details['name']);
    }
}


