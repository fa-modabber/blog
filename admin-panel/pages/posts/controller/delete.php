<?php

$postId = $_GET['id'] ?? null;
$source = $_GET['from'] ?? 'default';

if (empty($postId)) {
    abort404();
}

try {
    $result = deletePostById($db, $postId);
    if ($result) {
        flash('post', 'delete', 'success');
    } else {
        flash(entity: 'post', action: 'delete', result: 'not_found');
    }
} catch (PDOException $e) {
    flash(entity: 'post', action: 'delete', result: 'error');
}


switch ($source) {
    case 'index-posts':
        header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/index.php");
        exit;
    case 'single':
        header("Location: " . BASE_URL . "/admin-panel/pages/posts/view/single.php");
        exit;
    case 'index':
        header("Location: " . BASE_URL . "/admin-panel/pages/index.php");
        exit;
    default:
        header("Location: " . BASE_URL . "/admin-panel/pages/index.php");
        exit;
}
