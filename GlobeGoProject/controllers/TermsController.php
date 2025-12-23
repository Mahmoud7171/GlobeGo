<?php
require_once __DIR__ . '/BaseController.php';

class TermsController extends BaseController
{
    public function index(): void
    {
        $this->render('terms/index', [], 'Terms of Service');
    }
}

