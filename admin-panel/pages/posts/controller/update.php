<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

$postId = $_POST['id'] ?? null;

if (!($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit']) && isset($postId))) {
    header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/index.php");
    exit;
}

try {
    $oldPost = fetchPostById($db, $id);
    if (empty($oldPost)) {
        flash('post', 'update', 'not_found');
        header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/index.php");
        exit;
    }
} catch (PDOException $e) {
    flash('post', 'fetch', 'error');
    header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/index.php");
    exit;
}

// Validate inputs
$title = $categoryId = $body = "";
$image = $oldPost['image'];
$formErrors = [];

$title = test_form_input($_POST['title']) ?? '';
if (empty($title)) {
    $formErrors['title'] = "Title is required";
}

$body = test_form_input($_POST['body']) ?? '';
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
        // $oldImage = $upload_dir . $oldPost['image'];  //delete old image from server
        // unlink($oldImage);
    }
}

$_SESSION['post']['edit']['form']['error'] = $formErrors;

// If no form errors, update
if (empty($formErrors)) {
    try {
        $result = updatePostById($db, $postId, $title, $categoryId, $image, $body);
        if (!$result) {
            flash('post', 'update', 'error');
        } else {
            flash('post', 'update', 'success');
        }
    } catch (PDOException $e) {
        flash('post', 'update', 'error');
    }
}

header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/edit.php?id=" . $postId);
exit();
