<?php
require_once __DIR__ . '/BaseController.php';

class AboutController extends BaseController
{
    public function index(): void
    {
        $this->render('about/index', [], 'About Us');
    }
}

