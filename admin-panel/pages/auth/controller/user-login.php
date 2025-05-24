<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

echo "test";


if (!($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login']))) {
    header("Location: " . BASE_URL . "/admin-panel/pages/auth/view/login.php");
    exit;
}

// Validate inputs
$email = $password = "";
$formErrors = [];

$email = test_form_input($_POST['email']) ?? '';

if (empty($email)) {
    $formErrors['email'] = "Email is required";
}

$password = test_form_input($_POST['password']) ?? '';

if (empty($password)) {
    $formErrors['password'] = "password is required";
}

$_SESSION['user']['fetch']['form']['error'] = $formErrors;

if (empty($formErrors)) {
    try {
        $user = fetchUser($db, $email, $password);
        if (empty($user)) {
            flash('user', 'fetch', 'not_found');
            header("Location: " . BASE_URL . "/admin-panel/pages/auth/view/login.php");
            exit;
        } else {
            $_SESSION['user_id'] = $user['id'];
            header("Location: " . BASE_URL . "/admin-panel/index.php");
            exit;
        }
    } catch (PDOException $e) {
        flash('user', 'fetch', 'error');
    }
}
header("Location: " . BASE_URL . "/admin-panel/pages/auth/view/login.php");
exit;
