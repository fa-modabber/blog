<?php
include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/includes/functions.php');

$userId = 1;
$title = $categoryId = $body = "";
$image = null;
$postId = isset($_POST['id']) ? $_POST['id'] : null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit']) && isset($postId)) {
    try {
        $stmnt = $db->prepare("SELECT * FROM posts WHERE id=:id");
        $stmnt->execute(['id' => $postId]);
        if ($stmnt->rowCount() > 0) {
            $oldPost = $stmnt->fetch();
        } else {
            $_SESSION['post_update']['error']['retrieve'] = "post not found!";
        }
    } catch (PDOException $e) {
    }

    // Validate inputs
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
        $stmt = $db->prepare("INSERT INTO posts (title, category_id, image, body, user_id) VALUES (:title, :category_id, :image, :body, :user_id)");
        $stmt->execute([
            'title' => $title,
            'category_id' => $categoryId,
            'image' => $image,
            'body' => $body,
            'user_id' => $userId
        ]);
        $_SESSION['post_update']['success'] = "You successfully updated a post!";
    }
    header("Location: create.php");
    exit();
}
