<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $mobile = htmlspecialchars($_POST['mobile']);
    $service = htmlspecialchars($_POST['service']);
    $message = htmlspecialchars($_POST['message']);

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format. Please provide a valid email.'); window.location.href='appointment.html';</script>";
        exit;
    }

    // Email to the business (you)
    $to = "arjuncableconverters@gmail.com";  // Your email where form data will be sent
    $subject = "New Appointment Request from $name";
    
    // HTML formatted body for business email with logo
    $body = "
    <html>
    <head>
        <title>$subject</title>
    </head>
    <body>
        <p><strong>You have received a new appointment request:</strong></p>
        <p><img src='https://jashmainfosoft.com/img/jasma-logo-removebg-preview.png' alt='Company Logo' style='width: 150px; height: auto;'/></p>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Mobile:</strong> $mobile</p>
        <p><strong>Service:</strong> $service</p>
        <p><strong>Message:</strong> $message</p>
    </body>
    </html>";

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Email server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Gmail's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'arjuncableconverters@gmail.com'; // Your SMTP username (Gmail)
        $mail->Password = 'mtrlfujdiyxxryjz'; // Your App-Specific Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Use 587 for TLS (or 465 for SSL)

        // Recipients (your email)
        $mail->setFrom('arjuncableconverters@gmail.com', 'JASHMA INFO SOFT'); // Set the "from" address (your email)
        $mail->addAddress($to); // Add the recipient's email address

        // Content
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send email to the business (you)
        if (!$mail->send()) {
            echo "<script>alert('Sorry, there was an error with the submission. Please try again later.'); window.location.href='appointment.html';</script>";
            exit;
        }

        // Send confirmation email to the customer
        $replySubject = "Appointment Confirmation";
        
        // HTML formatted body for customer email with logo
        $replyBody = "
        <html>
        <head>
            <title>$replySubject</title>
        </head>
        <body>
            <p><strong>Dear $name,</strong></p>
            <p>Thank you for booking an appointment with us. We have received your request for the service: $service.</p>
            <p>We will contact you soon to discuss your requirements.</p>
            <p><img src='https://jashmainfosoft.com/img/jasma-logo-removebg-preview.png' alt='Company Logo' style='width: 150px; height: auto;'/></p>
            <p><strong>Best regards,</strong><br/>Jashma Info Soft</p>
        </body>
        </html>";

        // Prepare email to customer (just a thank you message with logo)
        $mail->clearAddresses();  // Clear previous recipient
        $mail->addAddress($email);  // Add customer's email address for confirmation
        $mail->Subject = $replySubject;
        $mail->Body = $replyBody;

        // Send confirmation email to customer
        if (!$mail->send()) {
            echo "<script>alert('Failed to send confirmation email.'); window.location.href='appointment.html';</script>";
            exit;
        }

        // Redirect after successful submission
        echo "<script>alert('Your appointment request has been submitted. We will get back to you soon!'); window.location.href='index.php';</script>";

    } catch (Exception $e) {
        // Handle any errors
        echo "<script>alert('Mailer Error: {$mail->ErrorInfo}. Please try again later.'); window.location.href='appointment.html';</script>";
    }
}
?>
