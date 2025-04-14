<?php

include(__DIR__ . "/../includes/config.php");
include(__DIR__ . "/../includes/db.php");
include(__DIR__ . "/../includes/functions.php");
include "./layout/includes/header.php";
include "./layout/includes/sidebar.php";

$userId = 1;
try {
    $query = "SELECT * FROM posts WHERE user_id=:id";
    $posts = $db->prepare($query);
    $posts->execute(["id" => $userId]);
} catch (PDOException $e) {
}

?>


<?php
include "./layout/includes/footer.php";
?>
