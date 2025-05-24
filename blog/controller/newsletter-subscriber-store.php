<?php
if (!($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subscribe']))) {
    header("Location: " . BASE_URL . "/blog/view/includes/slider.php");
    exit;
}

// Validate inputs
$name = $email = "";
$formErrors = [];

$name = test_form_input($_POST['name']) ?? '';

if (empty($name)) {
    $formErrors = "Name is required";
} else {
    if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $formErrors = "Only letters and white space allowed";
    }
}

$email = test_form_input($_POST['email']) ?? '';

if (empty($email)) {
    $formErrors = "Email is required";
} else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors = "Invalid email format";
    }
}

if (empty($formErrors)) {
    try {
        $result = storeNewsletterSubscriber($db, $name, $email);
        if (!$result) {
            flash('newsletter', 'create', 'error');
        } else {
            flash('newsletter', 'create', 'success');
        }
    } catch (PDOException $e) {
        flash('newsletter', 'create', 'error');
    }
}

    $_SESSION['newsletter']['create']['form']['error'] = $formErrors;

header("Location: " . BASE_URL . "/blog/view/includes/sidebar.php");
exit();
