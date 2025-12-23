<?php
require_once __DIR__ . '/BaseController.php';

class HelpController extends BaseController
{
    public function index(): void
    {
        $this->render('help/index', [], 'Help Center');
    }
}

