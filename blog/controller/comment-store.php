<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

$name = $body = "";
$formErrors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['postComment'])) {
    $postId = $_POST['postId'];
    $name = test_form_input($_POST['name']) ?? '';
    if (empty($name)) {
        $formErrors['name'] = "Name is necessary";
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $formErrors['name'] = "Only letters and white space allowed";
        }
    }

    $body = test_form_input($_POST['body']) ?? '';
    if (empty($_POST['body'])) {
        $formErrors['body']  = "Comment text is necessary";
    }
    
    $_SESSION['comment']['create']['form']['error'] = $formErrors;

    if (empty($formErrors)) {
        try {
            $result = storeComment($db, $name, $body, $postId);
            if (!$result) {
                flash('comment', 'create', 'error');
            } else {
                flash('comment', 'create', 'success');
            }
        } catch (PDOException $e) {
            flash('comment', 'create', 'error');
        }
    }
}

header("Location: " . BASE_URL . "/blog/view/post-single.php?id=" . $postId);

exit;
