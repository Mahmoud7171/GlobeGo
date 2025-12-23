<?php
/**
 * Email Template Helper
 * Provides consistent HTML email templates with blue theme for all GlobeGo emails
 */
class EmailTemplate {
    
    /**
     * Generate HTML email wrapper with blue theme
     */
    private static function getEmailWrapper(string $title, string $content, string $type = 'default'): string {
        $bluePrimary = '#007bff';
        $blueSecondary = '#0056b3';
        $blueLight = '#e7f3ff';
        $grayLight = '#f8f9fa';
        $textColor = '#333333';
        
        return '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title) . '</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" style="max-width: 600px; width: 100%; border-collapse: collapse; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header with Blue Background -->
                    <tr>
                        <td style="background: linear-gradient(135deg, ' . $bluePrimary . ' 0%, ' . $blueSecondary . ' 100%); padding: 30px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                🌍 GlobeGo
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Content Area -->
                    <tr>
                        <td style="padding: 40px;">
                            ' . $content . '
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: ' . $grayLight . '; padding: 30px 40px; text-align: center; border-top: 1px solid #dee2e6;">
                            <p style="margin: 0 0 10px 0; color: ' . $textColor . '; font-size: 14px;">
                                <strong>GlobeGo</strong><br>
                                Discover amazing places and book unforgettable tours
                            </p>
                            <p style="margin: 10px 0 0 0; color: #6c757d; font-size: 12px;">
                                © ' . date('Y') . ' GlobeGo. All rights reserved.<br>
                                <a href="' . (defined('SITE_URL') ? SITE_URL : '#') . '" style="color: ' . $bluePrimary . '; text-decoration: none;">Visit our website</a>
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }
    
    /**
     * Format price with currency symbol
     */
    private static function formatPrice($amount): string {
        return '$' . number_format((float)$amount, 2, '.', ',');
    }
    
    /**
     * Format date
     */
    private static function formatDate($date): string {
        return date('F j, Y', strtotime($date));
    }
    
    /**
     * Format time
     */
    private static function formatTime($time): string {
        return date('g:i A', strtotime($time));
    }
    
    /**
     * Booking Created Email Template
     */
    public static function bookingCreated(array $data, array $bookingDetails = []): string {
        $bookingRef = $data['booking_reference'] ?? 'N/A';
        $tourName = $bookingDetails['tour_title'] ?? 'Tour';
        $tourDate = isset($bookingDetails['tour_date']) ? self::formatDate($bookingDetails['tour_date']) : 'TBD';
        $tourTime = isset($bookingDetails['tour_time']) ? self::formatTime($bookingDetails['tour_time']) : 'TBD';
        $location = $bookingDetails['location'] ?? 'Location TBD';
        $guideName = $bookingDetails['guide_name'] ?? 'Your Guide';
        $participants = $bookingDetails['num_participants'] ?? 1;
        $totalPrice = $bookingDetails['total_price'] ?? 0;
        $paymentMethod = $bookingDetails['payment_method'] ?? 'N/A';
        $paymentStatus = $bookingDetails['payment_status'] ?? 'pending';
        $meetingPoint = $bookingDetails['meeting_point'] ?? 'To be confirmed';
        $duration = $bookingDetails['duration_hours'] ?? 0;
        $cancellationFee = number_format($totalPrice * 0.25, 2);
        
        $paymentMethodDisplay = ucfirst($paymentMethod);
        if ($paymentMethod === 'visa') {
            $paymentMethodDisplay = 'Visa Card';
        } elseif ($paymentMethod === 'paypal') {
            $paymentMethodDisplay = 'PayPal';
        }
        
        $content = '
            <h2 style="margin: 0 0 20px 0; color: #007bff; font-size: 24px;">Booking Confirmed! ✅</h2>
            
            <p style="margin: 0 0 20px 0; color: #333; font-size: 16px; line-height: 1.6;">
                Thank you for booking with <strong>GlobeGo</strong>! We\'re excited to have you join us on this amazing adventure.
            </p>
            
            <div style="background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0; color: #0056b3; font-weight: bold; font-size: 16px;">
                    Booking Reference: <span style="color: #333;">' . htmlspecialchars($bookingRef) . '</span>
                </p>
            </div>
            
            <h3 style="margin: 30px 0 15px 0; color: #333; font-size: 20px; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Tour Details</h3>
            
            <table role="presentation" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px; width: 40%;"><strong>Tour Name:</strong></td>
                    <td style="padding: 12px 0; color: #333; font-size: 14px;">' . htmlspecialchars($tourName) . '</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px;"><strong>Location:</strong></td>
                    <td style="padding: 12px 0; color: #333; font-size: 14px;">' . htmlspecialchars($location) . '</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px;"><strong>Date & Time:</strong></td>
                    <td style="padding: 12px 0; color: #333; font-size: 14px;">' . htmlspecialchars($tourDate) . ' at ' . htmlspecialchars($tourTime) . '</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px;"><strong>Duration:</strong></td>
                    <td style="padding: 12px 0; color: #333; font-size: 14px;">' . htmlspecialchars($duration) . ' hours</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px;"><strong>Meeting Point:</strong></td>
                    <td style="padding: 12px 0; color: #333; font-size: 14px;">' . htmlspecialchars($meetingPoint) . '</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px;"><strong>Participants:</strong></td>
                    <td style="padding: 12px 0; color: #333; font-size: 14px;">' . htmlspecialchars($participants) . ' ' . ($participants == 1 ? 'person' : 'people') . '</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px;"><strong>Guide:</strong></td>
                    <td style="padding: 12px 0; color: #333; font-size: 14px;">' . htmlspecialchars($guideName) . '</td>
                </tr>
            </table>
            
            <h3 style="margin: 30px 0 15px 0; color: #333; font-size: 20px; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Payment Information</h3>
            
            <table role="presentation" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px;"><strong>Total Amount:</strong></td>
                    <td style="padding: 12px 0; color: #007bff; font-size: 18px; font-weight: bold;">' . self::formatPrice($totalPrice) . '</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px;"><strong>Payment Method:</strong></td>
                    <td style="padding: 12px 0; color: #333; font-size: 14px;">' . htmlspecialchars($paymentMethodDisplay) . '</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #666; font-size: 14px;"><strong>Payment Status:</strong></td>
                    <td style="padding: 12px 0; color: ' . ($paymentStatus === 'paid' ? '#28a745' : '#ffc107') . '; font-size: 14px; font-weight: bold;">' . ucfirst($paymentStatus) . '</td>
                </tr>
            </table>
            
            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #856404; font-size: 18px;">⚠️ Important: Cancellation Policy</h3>
                <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                    <strong>Cancellation Fee:</strong> If you need to cancel your booking, a cancellation fee of <strong>' . self::formatPrice($cancellationFee) . ' (25% of the ticket price)</strong> will apply.<br><br>
                    Please contact us as soon as possible if you need to make any changes to your booking. Full refunds are only available for cancellations made 48 hours or more before the tour date (minus the 25% cancellation fee).<br><br>
                    <strong>Contact us:</strong> If you have any questions or need to cancel, please reach out to our support team at <a href="mailto:' . (defined('SUPPORT_EMAIL') ? SUPPORT_EMAIL : 'support@globego.com') . '" style="color: #007bff; text-decoration: none;">' . (defined('SUPPORT_EMAIL') ? SUPPORT_EMAIL : 'support@globego.com') . '</a>
                </p>
            </div>
            
            <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0; color: #0c5460; font-size: 14px; line-height: 1.6;">
                    <strong>What\'s Next?</strong><br>
                    We will notify you once your guide confirms the tour. You can view and manage your booking from your <a href="' . (defined('SITE_URL') ? SITE_URL : '#') . '/dashboard.php" style="color: #007bff; text-decoration: none; font-weight: bold;">GlobeGo Dashboard</a>.
                </p>
            </div>
            
            <p style="margin: 30px 0 0 0; color: #333; font-size: 16px; line-height: 1.6;">
                We look forward to providing you with an unforgettable experience!<br><br>
                Best regards,<br>
                <strong>The GlobeGo Team</strong>
            </p>
        ';
        
        return self::getEmailWrapper('Booking Confirmed - ' . $bookingRef, $content);
    }
    
    /**
     * Booking Cancelled Email Template
     */
    public static function bookingCancelled(array $data, array $bookingDetails = []): string {
        $bookingRef = $data['booking_reference'] ?? 'N/A';
        $tourName = $bookingDetails['tour_title'] ?? 'Tour';
        $cancellationFee = $bookingDetails['cancellation_fee'] ?? 0;
        $refundAmount = $bookingDetails['refund_amount'] ?? 0;
        
        $content = '
            <h2 style="margin: 0 0 20px 0; color: #dc3545; font-size: 24px;">Booking Cancelled</h2>
            
            <p style="margin: 0 0 20px 0; color: #333; font-size: 16px; line-height: 1.6;">
                Your booking has been cancelled as requested.
            </p>
            
            <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0; color: #721c24; font-weight: bold; font-size: 16px;">
                    Booking Reference: <span style="color: #333;">' . htmlspecialchars($bookingRef) . '</span>
                </p>
                <p style="margin: 10px 0 0 0; color: #721c24; font-size: 14px;">
                    Tour: ' . htmlspecialchars($tourName) . '
                </p>
            </div>
            
            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #856404; font-size: 18px;">Cancellation Fee Applied</h3>
                <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                    As per our cancellation policy, a cancellation fee of <strong>' . self::formatPrice($cancellationFee) . ' (25% of the ticket price)</strong> has been applied.<br><br>
                    ' . ($refundAmount > 0 ? 'Your refund of <strong>' . self::formatPrice($refundAmount) . '</strong> will be processed to your original payment method within 5-7 business days.' : 'No refund is available for this cancellation.') . '
                </p>
            </div>
            
            <p style="margin: 20px 0 0 0; color: #333; font-size: 16px; line-height: 1.6;">
                If this cancellation was made in error or you\'d like to book another tour, please visit our <a href="' . (defined('SITE_URL') ? SITE_URL : '#') . '/tours.php" style="color: #007bff; text-decoration: none; font-weight: bold;">Tours page</a>.<br><br>
                Best regards,<br>
                <strong>The GlobeGo Team</strong>
            </p>
        ';
        
        return self::getEmailWrapper('Booking Cancelled - ' . $bookingRef, $content);
    }
    
    /**
     * Booking Confirmed Email Template (by guide)
     */
    public static function bookingConfirmed(array $data, array $bookingDetails = []): string {
        $bookingRef = $data['booking_reference'] ?? 'N/A';
        $tourName = $bookingDetails['tour_title'] ?? 'Tour';
        
        $content = '
            <h2 style="margin: 0 0 20px 0; color: #28a745; font-size: 24px;">Great News! Your Tour is Confirmed! 🎉</h2>
            
            <p style="margin: 0 0 20px 0; color: #333; font-size: 16px; line-height: 1.6;">
                Your guide has confirmed your booking! Everything is set for your upcoming adventure.
            </p>
            
            <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0; color: #155724; font-weight: bold; font-size: 16px;">
                    Booking Reference: <span style="color: #333;">' . htmlspecialchars($bookingRef) . '</span>
                </p>
                <p style="margin: 10px 0 0 0; color: #155724; font-size: 14px;">
                    Tour: ' . htmlspecialchars($tourName) . '
                </p>
            </div>
            
            <p style="margin: 20px 0 0 0; color: #333; font-size: 16px; line-height: 1.6;">
                You can view all the details of your booking from your <a href="' . (defined('SITE_URL') ? SITE_URL : '#') . '/dashboard.php" style="color: #007bff; text-decoration: none; font-weight: bold;">GlobeGo Dashboard</a> or itinerary.<br><br>
                We can\'t wait to see you on the tour!<br><br>
                Best regards,<br>
                <strong>The GlobeGo Team</strong>
            </p>
        ';
        
        return self::getEmailWrapper('Booking Confirmed - ' . $bookingRef, $content);
    }
    
    /**
     * Password Reset Email Template
     */
    public static function passwordReset(string $email, string $resetToken, string $resetLink): string {
        $content = '
            <h2 style="margin: 0 0 20px 0; color: #007bff; font-size: 24px;">Password Reset Request</h2>
            
            <p style="margin: 0 0 20px 0; color: #333; font-size: 16px; line-height: 1.6;">
                We received a request to reset your password for your GlobeGo account.
            </p>
            
            <div style="background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0 0 15px 0; color: #0056b3; font-size: 14px;">
                    Click the button below to reset your password:
                </p>
                <div style="text-align: center; margin: 20px 0;">
                    <a href="' . htmlspecialchars($resetLink) . '" style="display: inline-block; background-color: #007bff; color: #ffffff; padding: 14px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">Reset Password</a>
                </div>
                <p style="margin: 15px 0 0 0; color: #0056b3; font-size: 12px;">
                    Or copy and paste this link into your browser:<br>
                    <span style="word-break: break-all; color: #333;">' . htmlspecialchars($resetLink) . '</span>
                </p>
            </div>
            
            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                    <strong>⚠️ Important:</strong><br>
                    • This link will expire in 24 hours<br>
                    • If you didn\'t request a password reset, please ignore this email<br>
                    • Your password will not change until you click the link above and create a new one
                </p>
            </div>
            
            <p style="margin: 20px 0 0 0; color: #333; font-size: 16px; line-height: 1.6;">
                If you continue to have problems, please contact our support team at <a href="mailto:' . (defined('SUPPORT_EMAIL') ? SUPPORT_EMAIL : 'support@globego.com') . '" style="color: #007bff; text-decoration: none;">' . (defined('SUPPORT_EMAIL') ? SUPPORT_EMAIL : 'support@globego.com') . '</a><br><br>
                Best regards,<br>
                <strong>The GlobeGo Team</strong>
            </p>
        ';
        
        return self::getEmailWrapper('Password Reset Request', $content);
    }
    
    /**
     * Welcome Email Template (for new user registration)
     */
    public static function welcomeEmail(string $firstName, string $lastName, string $userRole = 'tourist'): string {
        $fullName = trim($firstName . ' ' . $lastName);
        $roleDisplay = ucfirst($userRole);
        
        // Customize message based on role
        $welcomeMessage = '';
        $nextSteps = '';
        
        if ($userRole === 'guide') {
            $welcomeMessage = 'Thank you for applying to become a guide with <strong>GlobeGo</strong>! We\'re excited to have you join our community of expert tour guides.';
            $nextSteps = '
                <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 20px; margin: 20px 0; border-radius: 4px;">
                    <h3 style="margin: 0 0 10px 0; color: #0c5460; font-size: 18px;">📋 What\'s Next?</h3>
                    <p style="margin: 0; color: #0c5460; font-size: 14px; line-height: 1.6;">
                        Our team will review your application and reach out to you via email to schedule an interview. Please check your email regularly for updates.<br><br>
                        Once approved, you\'ll be able to create and manage your own tours, connect with travelers, and share your passion for exploring the world!
                    </p>
                </div>
            ';
        } else {
            $welcomeMessage = 'Welcome to <strong>GlobeGo</strong>! We\'re thrilled to have you join our community of travelers and explorers.';
            $nextSteps = '
                <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 20px; margin: 20px 0; border-radius: 4px;">
                    <h3 style="margin: 0 0 10px 0; color: #0c5460; font-size: 18px;">🚀 Get Started</h3>
                    <p style="margin: 0; color: #0c5460; font-size: 14px; line-height: 1.6;">
                        • <a href="' . (defined('SITE_URL') ? SITE_URL : '#') . '/tours.php" style="color: #007bff; text-decoration: none; font-weight: bold;">Browse amazing tours</a> and discover new destinations<br>
                        • <a href="' . (defined('SITE_URL') ? SITE_URL : '#') . '/attractions.php" style="color: #007bff; text-decoration: none; font-weight: bold;">Explore attractions</a> around the world<br>
                        • <a href="' . (defined('SITE_URL') ? SITE_URL : '#') . '/dashboard.php" style="color: #007bff; text-decoration: none; font-weight: bold;">Visit your dashboard</a> to manage your bookings and profile
                    </p>
                </div>
            ';
        }
        
        $content = '
            <h2 style="margin: 0 0 20px 0; color: #007bff; font-size: 24px;">Welcome to GlobeGo! 🎉</h2>
            
            <p style="margin: 0 0 20px 0; color: #333; font-size: 16px; line-height: 1.6;">
                Hello ' . htmlspecialchars($firstName) . ',<br><br>
                ' . $welcomeMessage . '
            </p>
            
            <div style="background-color: #e7f3ff; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0; color: #0056b3; font-weight: bold; font-size: 16px;">
                    Your Account Details:
                </p>
                <p style="margin: 10px 0 0 0; color: #333; font-size: 14px; line-height: 1.6;">
                    <strong>Name:</strong> ' . htmlspecialchars($fullName) . '<br>
                    <strong>Account Type:</strong> ' . htmlspecialchars($roleDisplay) . '
                </p>
            </div>
            
            ' . $nextSteps . '
            
            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                    <strong>💡 Need Help?</strong><br>
                    If you have any questions or need assistance, our support team is here to help!<br>
                    Email us at <a href="mailto:' . (defined('SUPPORT_EMAIL') ? SUPPORT_EMAIL : 'support@globego.com') . '" style="color: #007bff; text-decoration: none;">' . (defined('SUPPORT_EMAIL') ? SUPPORT_EMAIL : 'support@globego.com') . '</a> or call us at <strong>' . (defined('SUPPORT_PHONE') ? SUPPORT_PHONE : '+1 (555) 123-4567') . '</strong>
                </p>
            </div>
            
            <p style="margin: 30px 0 0 0; color: #333; font-size: 16px; line-height: 1.6;">
                We\'re excited to be part of your journey!<br><br>
                Best regards,<br>
                <strong>The GlobeGo Team</strong>
            </p>
        ';
        
        return self::getEmailWrapper('Welcome to GlobeGo', $content);
    }
}
?>




