<?php

$errors = [];
$errorMessage = '';

if (!empty($_POST)) {
    // Sanitize all input to prevent XSS
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8') : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8') : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8') : '';

    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }

    if (empty($message)) {
        $errors[] = 'Message is required';
    }

    if (empty($errors)) {
        $toEmail = 'greentropikal@outlook.com';
        $emailSubject = 'New email from your contact form';

        // Use a safe From header to prevent email header injection
        $headers = [
            'From' => 'noreply@' . $_SERVER['HTTP_HOST'],
            'Reply-To' => $email,
            'Content-type' => 'text/plain; charset=UTF-8',
            'X-Mailer' => 'PHP/' . phpversion()
        ];

        $bodyParagraphs = [
            "Name: {$name}",
            "Phone: {$phone}",
            "Email: {$email}",
            "",
            "Message:",
            $message
        ];
        $body = implode(PHP_EOL, $bodyParagraphs);

        if (mail($toEmail, $emailSubject, $body, $headers)) {
            header('Location: thank-you.html');
            exit;
        } else {
            $errorMessage = '<p style="color: red;">Oops, something went wrong. Please try again later.</p>';
        }
    } else {
        // Errors are already sanitized, safe to display
        $allErrors = implode('<br>', $errors);
        $errorMessage = '<p style="color: red;">' . $allErrors . '</p>';
    }
}

?>