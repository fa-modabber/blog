<?php
$commentId = $_GET['id'] ?? null;

if (empty($commentId)) {
    abort404();
}

try {
    $result = deletecommentById($db, $commentId);
    if ($result) {
        flash('comment', 'delete', 'success');
    } else {
        flash(entity: 'comment', action: 'delete', result: 'not_found');
    }
} catch (PDOException $e) {
    flash(entity: 'comment', action: 'delete', result: 'error');
}

header("Location: " . BASE_URL . "/admin-panel/pages/comments/view/index.php");
exit;
