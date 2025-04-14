<?php

include(__DIR__ . "/../../../includes/config.php");
include(__DIR__ . "/../../../includes/db.php");
include(__DIR__ . "/../../../includes/functions.php");
include "../../layout/includes/header.php";
include "../../layout/includes/sidebar.php";

$userId = 1;
if (isset($userId)) {
    try {
        $query = "SELECT * FROM posts WHERE user_id=:id";
        $posts = $db->prepare($query);
        $posts->execute(["id" => $userId]);
    } catch (PDOException $e) {
        $_SESSION['error'] = "problem in loading posts";
    }
}

$postLoadError = $_SESSION['error'] ?? "";
?>

<!-- main section -->
<div class="main col-md-9 col-lg-10">

    <div class="d-flex justify-content-between">
        <h1>Posts</h1>
        <a class="btn btn-dark" href="./create.php"> ایجاد مقاله</a>
    </div>
</div>
</div>
</div>
</section>

<?php
include "../../layout/includes/footer.php";
?>