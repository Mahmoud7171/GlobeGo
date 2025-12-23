<?php
require_once __DIR__ . '/BaseController.php';

class ContactController extends BaseController
{
    public function index(): void
    {
        $success_message = '';
        $error_message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $subject = sanitize($_POST['subject'] ?? '');
            $message = sanitize($_POST['message'] ?? '');
            
            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                $error_message = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Please enter a valid email address.";
            } else {
                // Save contact report to database
                try {
                    $stmt = $this->db->prepare("
                        INSERT INTO contact_reports (name, email, subject, message, status) 
                        VALUES (?, ?, ?, ?, 'new')
                    ");
                    $stmt->execute([$name, $email, $subject, $message]);
                    
                    $success_message = "Thank you for contacting us! We'll get back to you as soon as possible.";
                    
                    // Clear form data
                    $_POST = [];
                } catch (PDOException $e) {
                    error_log("Error saving contact report: " . $e->getMessage());
                    $error_message = "Sorry, there was an error submitting your message. Please try again later.";
                }
            }
        }
        
        $this->render('contact/index', [
            'success_message' => $success_message,
            'error_message' => $error_message,
        ], 'Contact Us');
    }
}

