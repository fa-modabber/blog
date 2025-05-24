<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

if (!($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit']))) {
    header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/create.php");
    exit;
}

// Validate inputs
$userId = $_SESSION['user_id'];
$title = $categoryId = $body = "";
$image = null;
$formErrors = [];

$title = test_form_input($_POST['title']) ?? '';

if (empty($_POST['title'])) {
    $formErrors['title'] = "Title is required";
}

$body = test_form_input($_POST['body']);

if (empty($_POST['body'])) {
    $formErrors['body'] = "Body is required";
}

$categoryId = test_form_input($_POST['categoryId']);

// Validate image
if (isset($_FILES['image']) && $_FILES['image']['error'] !== 4) {
    $file = $_FILES['image'];
    $upload_dir = BASE_PATH . '/uploads/posts/';
    $uploadResult = imageUpload($file, $upload_dir);

    if ($uploadResult !== null) {
        $formErrors['image'] = $uploadResult;
    } else {
        $image = time() . '_' . basename($file["name"]);
    }
} else {
    $formErrors['image'] = "Image is required";
}

$_SESSION['post']['create']['form']['error'] = $formErrors;

// If no errors, insert into database
if (!empty($formErrors)) {
    header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/create.php");
    exit();
}

try {
    $newPostId = storePost($db, $title, $categoryId, $image, $body, $userId);
    if ($newPostId !== false) {
        flash('post', 'create', 'success');
        header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/single.php?id=$newPostId");
        exit();
    } else {
        flash('post', 'create', 'error');
        header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/create.php");
        exit();
    }
} catch (PDOException $e) {
    flash('post', 'create', 'error');
    header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/create.php");
    exit();
}
