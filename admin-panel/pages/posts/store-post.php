<?php
include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/includes/functions.php');

if (!($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit']))) {
    header("Location: " . BASE_URL . "/admin-panel/pages/posts/create.php");
    exit;
}

// Validate inputs
$userId = 1;
$title = $categoryId = $body = "";
$image = null;

if (empty($_POST['title'])) {
    $_SESSION['post_create']['error']['title'] = "Title is required";
} else {
    $title = test_form_input($_POST['title']);
}

if (empty($_POST['body'])) {
    $_SESSION['post_create']['error']['body'] = "Body is required";
} else {
    $body = test_form_input($_POST['body']);
}

$categoryId = test_form_input($_POST['categoryId']);

// Validate image
if (isset($_FILES['image']) && $_FILES['image']['error'] !== 4) {
    $file = $_FILES['image'];
    $upload_dir = BASE_PATH . '/uploads/posts/';
    $uploadResult = imageUpload($file, $upload_dir);

    if ($uploadResult !== null) {
        $_SESSION['post_create']['error']['image'] = $uploadResult;
    } else {
        $image = time() . '_' . basename($file["name"]);
    }
} else {
    $_SESSION['post_create']['error']['image'] = "Image is required";
}

// If no errors, insert into database
if (empty($_SESSION['post_create']['error'])) {
    try {
        $stmt = $db->prepare("INSERT INTO posts (title, category_id, image, body, user_id) VALUES (:title, :category_id, :image, :body, :user_id)");
        $stmt->execute([
            'title' => $title,
            'category_id' => $categoryId,
            'image' => $image,
            'body' => $body,
            'user_id' => $userId
        ]);
        $newPostId = $db->lastInsertId();
        $_SESSION['post_create']['success'] = "You successfully created a post!";
        header("Location: single.php?id=$newPostId");
    } catch (PDOException $e) {
        $_SESSION['post_create']['error']['action'] = "Error in creating the post! ";
        header("Location: create.php");
        exit();
    }
} else {
    header("Location: create.php");
    exit();
}
