<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';


if (!($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup']))) {
    header("Location: " . BASE_URL . "/admin-panel/pages/auth/view/signup.php");
    exit;
}

// Validate inputs
$email = $password = "";
$formErrors = [];

$email = test_form_input($_POST['email']) ?? '';

if (empty($email)) {
    $formErrors['email'] = "Email is required";
} else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors['email'] = "Invalid email format";
    }
}

$password = test_form_input($_POST['password']) ?? '';

if (empty($password)) {
    $formErrors['password'] = "password is required";
} else {
    if (strlen($password) < 5) {
        $formErrors['password'] = "password should have at least 5 characters";
    }
}

$_SESSION['user']['create']['form']['error'] = $formErrors;

if (empty($formErrors)) {
    try {
        $result = storeUser($db, $email, $password);
        if (!$result) {
            flash('user', 'create', 'error');
        } else {
            flash('user', 'create', 'success');
        }
    } catch (PDOException $e) {
        flash('newsletter', 'create', 'error');
    }
}

header("Location: " . BASE_URL . "/admin-panel/pages/auth/view/signup.php");

exit();
