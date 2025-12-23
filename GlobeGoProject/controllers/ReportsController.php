<?php
require_once __DIR__ . '/BaseController.php';

class ReportsController extends BaseController
{
    public function index(): void
    {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || !isAdmin()) {
            redirect(SITE_URL . '/auth/login.php');
        }

        // Handle status update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            $report_id = (int)($_POST['report_id'] ?? 0);
            $new_status = sanitize($_POST['status'] ?? '');
            
            if ($report_id > 0 && in_array($new_status, ['new', 'read', 'replied', 'archived'])) {
                try {
                    $stmt = $this->db->prepare("UPDATE contact_reports SET status = ? WHERE id = ?");
                    $stmt->execute([$new_status, $report_id]);
                    $_SESSION['admin_message'] = "Report status updated successfully.";
                    $_SESSION['admin_message_type'] = 'success';
                } catch (PDOException $e) {
                    $_SESSION['admin_message'] = "Error updating report status.";
                    $_SESSION['admin_message_type'] = 'danger';
                }
            }
            redirect(SITE_URL . '/admin/reports.php');
        }

        // Handle delete
        if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
            $report_id = (int)$_GET['delete'];
            try {
                $stmt = $this->db->prepare("DELETE FROM contact_reports WHERE id = ?");
                $stmt->execute([$report_id]);
                $_SESSION['admin_message'] = "Report deleted successfully.";
                $_SESSION['admin_message_type'] = 'success';
            } catch (PDOException $e) {
                $_SESSION['admin_message'] = "Error deleting report.";
                $_SESSION['admin_message_type'] = 'danger';
            }
            redirect(SITE_URL . '/admin/reports.php');
        }

        // Get filter
        $status_filter = $_GET['status'] ?? 'all';
        
        // Check if table exists, if not show message
        try {
            $check_table = $this->db->query("SHOW TABLES LIKE 'contact_reports'");
            if ($check_table->rowCount() == 0) {
                $_SESSION['admin_message'] = "Contact reports table not found. Please run the setup script: <a href='" . SITE_URL . "/create-contact-reports-table.php'>Create Table</a>";
                $_SESSION['admin_message_type'] = 'warning';
                $reports = [];
                $stats = ['total' => 0, 'new_count' => 0, 'read_count' => 0, 'replied_count' => 0, 'archived_count' => 0];
                $this->render('admin/reports', [
                    'reports' => $reports,
                    'stats' => $stats,
                    'status_filter' => $status_filter,
                ], 'Contact Reports');
                return;
            }
        } catch (PDOException $e) {
            // Table doesn't exist
            $_SESSION['admin_message'] = "Contact reports table not found. Please run the setup script: <a href='" . SITE_URL . "/create-contact-reports-table.php'>Create Table</a>";
            $_SESSION['admin_message_type'] = 'warning';
            $reports = [];
            $stats = ['total' => 0, 'new_count' => 0, 'read_count' => 0, 'replied_count' => 0, 'archived_count' => 0];
            $this->render('admin/reports', [
                'reports' => $reports,
                'stats' => $stats,
                'status_filter' => $status_filter,
            ], 'Contact Reports');
            return;
        }

        // Build query
        $query = "SELECT * FROM contact_reports";
        $params = [];
        
        if ($status_filter !== 'all') {
            $query .= " WHERE status = ?";
            $params[] = $status_filter;
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get statistics
        $stats_stmt = $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_count,
                SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_count,
                SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied_count,
                SUM(CASE WHEN status = 'archived' THEN 1 ELSE 0 END) as archived_count
            FROM contact_reports
        ");
        $stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);

        $this->render('admin/reports', [
            'reports' => $reports,
            'stats' => $stats,
            'status_filter' => $status_filter,
        ], 'Contact Reports');
    }
}

