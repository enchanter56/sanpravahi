<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Updated Sanitization (FILTER_SANITIZE_STRING is deprecated in PHP 8.1+)
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST["message"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); // Tells the browser it was a user error
        echo "Invalid email format";
        exit;
    }

    $to = "info@sanpravahi.com"; 
    $subject = "Contact Form Submission from $name";
    
    $body = "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n\n";
    $body .= "Message:\n" . $message;

    // 2. The "From" Header Fix
    // Use an email address from your own domain (e.g., info@sanpravahi.com)
    // Using the sender's email in the 'From' field often triggers spam filters.
    $from_email = "no-reply@sanpravahi.com"; 

    $headers = "From: $from_email" . "\r\n" .
               "Reply-To: " . $email . "\r\n" . // This ensures you reply to the user
               "Content-Type: text/plain; charset=UTF-8" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // 3. Send and Response
    if (mail($to, $subject, $body, $headers)) {
        echo "Requirement sent successfully!";
    } else {
        http_response_code(500); // Tells the browser the server failed
        echo "Requirement sending failed.";
    }
} else {
    echo "Invalid request.";
}
?>