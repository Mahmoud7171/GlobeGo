<?php
require_once __DIR__ . '/../config/config.php';

class BaseController
{
    /**
     * @var PDO
     */
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Render a view with optional data and page title.
     *
     * @param string $view Relative view path under views/ without .php extension (e.g. 'home/index')
     * @param array  $data Associative array of variables to extract for the view
     * @param string|null $pageTitle Optional page title
     */
    protected function render(string $view, array $data = [], ?string $pageTitle = null): void
    {
        if ($pageTitle !== null) {
            $page_title = $pageTitle;
        }

        // Make $data keys available as local variables in the view
        if (!empty($data)) {
            extract($data, EXTR_SKIP);
        }

        include __DIR__ . '/../includes/header.php';
        include __DIR__ . '/../views/' . $view . '.php';
        include __DIR__ . '/../includes/footer.php';
    }
}


