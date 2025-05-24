<?php

require_once 'C:/xampp/htdocs/weblog-project/init.php';


$commentId = $_GET['id'] ?? null;

if (empty($commentId)) {
    abort404();
}

try {
  $result = acceptyCommentById($db, $commentId);
  if ($result)
    flash(entity: 'comment', action: 'accept', result: 'success');
  else
    flash(entity: 'comment', action: 'accept', result: 'error');
} catch (PDOException $e) {
  flash(entity: 'comment', action: 'accept', result: 'error');
}

header("Location: " . BASE_URL . "/admin-panel/pages/comments/view/index.php");
exit();
