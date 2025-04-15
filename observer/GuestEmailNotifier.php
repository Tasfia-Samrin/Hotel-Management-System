<?php
namespace Observer;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Observer\Observer;
use Dotenv\Dotenv;

class GuestEmailNotifier implements Observer {
    public string $renderedMessage = '';

    public function update(string $eventType, array $data): void {
        //  Load .env config
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $email = $data['email'] ?? 'guest@example.com';
        $name = $data['name'] ?? 'Guest';
        $bookingId = $data['booking_id'] ?? 'N/A';
        $room = $data['room'] ?? 'N/A';

        $subject = "Booking {$eventType} - Sunrise Hotel";
        $body = match ($eventType) {
            'confirmed' => "Dear $name,\n\nYour booking (ID: $bookingId) for Room $room has been confirmed.\nWe look forward to welcoming you!\n\nRegards,\nSunrise Hotel",
            'cancelled' => "Dear $name,\n\nYour booking (ID: $bookingId) for Room $room has been cancelled.\nIf you have any questions, please contact us.\n\nRegards,\nSunrise Hotel",
            default => "Dear $name,\n\nYour booking status has been updated.",
        };

        //  Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom($_ENV['MAIL_USERNAME'], 'Sunrise Hotel');
            $mail->addAddress($email, $name);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();

            $this->renderedMessage = "
                <div class='alert alert-success mt-3'>
                    📧 Email sent to <strong>$email</strong><br>
                    <strong>Subject:</strong> $subject
                </div>
            ";
        } catch (Exception $e) {
            $this->renderedMessage = "
                <div class='alert alert-danger mt-3'>
                    ❌ Failed to send email: <code>{$mail->ErrorInfo}</code>
                </div>
            ";
        }
    }
}
