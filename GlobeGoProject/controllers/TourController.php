<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/Tour.php';

class TourController extends BaseController
{
    public function index(): void
    {
        $tour = new Tour($this->db);

        // Build filters exactly as in the existing tours.php
        $filters = [];
        if (!empty($_GET['location'])) {
            $filters['location'] = sanitize($_GET['location']);
        }
        if (!empty($_GET['category'])) {
            $filters['category'] = sanitize($_GET['category']);
        }
        if (!empty($_GET['max_price'])) {
            $filters['max_price'] = (float) $_GET['max_price'];
        }

        $tours = $tour->getTours($filters);

        $this->render('tours/index', [
            'tours' => $tours,
            'filters' => $filters,
        ], 'Tours');
    }

    public function showDetails(): void
    {
        $tour_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if (!$tour_id) {
            redirect(SITE_URL . '/tours.php');
        }

        $tour = new Tour($this->db);
        $tour_details = $tour->getTourById($tour_id);

        if (!$tour_details) {
            redirect(SITE_URL . '/tours.php');
        }

        $tour_schedules = $tour->getTourSchedules($tour_id);

        $this->render('tours/details', [
            'tour_id' => $tour_id,
            'tour_details' => $tour_details,
            'tour_schedules' => $tour_schedules,
        ], $tour_details['title']);
    }

    public function showOffers(): void
    {
        $tour = new Tour($this->db);
        
        // Get only the three specific offers: Egypt, India, and Shibuya (Japan)
        $offers = [];
        
        // Get all active tours first
        $allTours = $tour->getTours([]);
        
        // Find Egypt tour - search by title first (more reliable), then location
        $egypt = null;
        foreach ($allTours as $t) {
            if (stripos($t['title'], 'Egyptian') !== false || stripos($t['title'], 'Egypt') !== false) {
                $egypt = $t;
                break;
            }
        }
        if (!$egypt) {
            foreach ($allTours as $t) {
                if (stripos($t['location'] ?? '', 'Egypt') !== false || stripos($t['location'] ?? '', 'Cairo') !== false) {
                    $egypt = $t;
                    break;
                }
            }
        }
        if ($egypt) {
            $egypt['original_price'] = $egypt['price'];
            $egypt['discount_percent'] = 15;
            $egypt['discounted_price'] = round($egypt['price'] * 0.85, 2); // 15% discount
            // Ensure location is set to Cairo, Egypt
            $egypt['location'] = 'Cairo, Egypt';
            $offers[] = $egypt;
        }
        
        // Find India tour - search by title first, then location
        $india = null;
        foreach ($allTours as $t) {
            if (stripos($t['title'], 'Taj Mahal') !== false || stripos($t['title'], 'India') !== false) {
                $india = $t;
                break;
            }
        }
        if (!$india) {
            foreach ($allTours as $t) {
                if (stripos($t['location'] ?? '', 'India') !== false || stripos($t['location'] ?? '', 'Agra') !== false) {
                    $india = $t;
                    break;
                }
            }
        }
        if ($india) {
            $india['original_price'] = $india['price'];
            $india['discount_percent'] = 20;
            $india['discounted_price'] = round($india['price'] * 0.80, 2); // 20% discount
            // Ensure location is set to Agra, India
            $india['location'] = 'Agra, India';
            $offers[] = $india;
        }
        
        // Find Shibuya/Japan tour - search by title first, then location
        $japan = null;
        foreach ($allTours as $t) {
            if (stripos($t['title'], 'Shibuya') !== false) {
                $japan = $t;
                break;
            }
        }
        if (!$japan) {
            foreach ($allTours as $t) {
                if (stripos($t['title'], 'Japan') !== false || stripos($t['title'], 'Tokyo') !== false) {
                    $japan = $t;
                    break;
                }
            }
        }
        if (!$japan) {
            foreach ($allTours as $t) {
                if (stripos($t['location'] ?? '', 'Japan') !== false || stripos($t['location'] ?? '', 'Tokyo') !== false || stripos($t['location'] ?? '', 'Shibuya') !== false) {
                    $japan = $t;
                    break;
                }
            }
        }
        if ($japan) {
            $japan['original_price'] = $japan['price'];
            $japan['discount_percent'] = 25;
            $japan['discounted_price'] = round($japan['price'] * 0.75, 2); // 25% discount
            // Ensure location is set to Tokyo, Japan
            $japan['location'] = 'Tokyo, Japan';
            $offers[] = $japan;
        }

        $this->render('tours/offers', [
            'offers' => $offers,
        ], 'Special Offers');
    }
}


