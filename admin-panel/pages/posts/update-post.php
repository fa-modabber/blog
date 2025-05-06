<?php
include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/includes/functions.php');

$postId = isset($_POST['id']) ? $_POST['id'] : null;

if (!($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit']) && isset($postId))) {
    header("Location: " . BASE_URL . "/admin-panel/pages/posts/index.php");
    exit;
}

$oldPost = fetchPostById($db, $id);

if (is_string($oldPost)) {
    $_SESSION['post_update']['error']['retrieve'] = 'problem in retrieving data!';
    header("Location: " . BASE_URL . "/admin-panel/pages/posts/index.php");
    exit;
}

if (empty($oldPost)) {
    $_SESSION['post_update']['error']['retrieve'] = 'post not found!';
    header("Location: " . BASE_URL . "/admin-panel/pages/posts/index.php");
    exit;
}

// Validate inputs
$title = $categoryId = $body = "";
$image = null;
if (empty($_POST['title'])) {
    $_SESSION['post_update']['error']['title'] = "Title is required";
} else {
    $title = test_form_input($_POST['title']);
}

if (empty($_POST['body'])) {
    $_SESSION['post_update']['error']['body'] = "Body is required";
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
        $_SESSION['post_update']['error']['image'] = $uploadResult;
    } else {
        $image = time() . '_' . basename($file["name"]);
        $oldImage = $upload_dir . $oldPost['image'];
        unlink($oldImage);
    }
} else {
    $_SESSION['post_update']['error']['image'] = "Image is required";
}

// If no errors, update
if (empty($_SESSION['post_update']['error'])) {
    try {
        $stmt = $db->prepare("UPDATE posts SET title=:title, category_id=:category_id, image=:image, body=:body WHERE id=:id");
        $stmt->execute([
            'id' => $postId,
            'title' => $title,
            'category_id' => $categoryId,
            'image' => $image,
            'body' => $body,
        ]);
        $_SESSION['post_update']['success'] = "You successfully updated a post!";
    } catch (PDOException $e) {
        $_SESSION['post_update']['error']['action'] = "Error in updating the post!";
    }
}
header("Location: " . BASE_URL . "/admin-panel/pages/posts/edit.php?id=" . $postId);
exit();
