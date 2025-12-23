<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/Tour.php';
require_once __DIR__ . '/../classes/Attraction.php';

class HomeController extends BaseController
{
    public function index(): void
    {
        try {
            $tour = new Tour($this->db);
            $attraction = new Attraction($this->db);

            $featured_tours = $tour->getFeaturedTours();
            $popular_attractions = $attraction->getPopularAttractions();
            $hero_destinations = $tour->getHeroDestinations();

            // Ensure arrays are never null
            if (!is_array($featured_tours)) {
                $featured_tours = [];
            }
            if (!is_array($popular_attractions)) {
                $popular_attractions = [];
            }
            if (!is_array($hero_destinations)) {
                $hero_destinations = [];
            }

            $this->render('home/index', [
                'featured_tours' => $featured_tours,
                'popular_attractions' => $popular_attractions,
                'hero_destinations' => $hero_destinations,
            ], 'Discover Amazing Tours & Attractions');
        } catch (Exception $e) {
            // Log error and show empty arrays
            error_log("HomeController error: " . $e->getMessage());
            $this->render('home/index', [
                'featured_tours' => [],
                'popular_attractions' => [],
                'hero_destinations' => [],
            ], 'Discover Amazing Tours & Attractions');
        }
    }
}


