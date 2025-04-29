<?php
include "../includes/config.php";
include "../includes/db.php";
include "./layout/includes/header.php";
include "./layout/includes/navbar.php";
include "./layout/includes/slider.php";

if (isset($_GET['category'])) {
    $categoryId = $_GET['category'];
    $query = "SELECT * FROM posts where category_id= :cid ORDER BY id DESC";
    $posts = $db->prepare($query);
    $posts->execute(['cid' => $categoryId]);
} else {
    $query = "SELECT * FROM posts ORDER BY id DESC";
    $posts = $db->query($query);
}

?>

<section class="content mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php if ($posts->rowCount() > 0): ?>
                    <?php foreach ($posts as $post): ?>
                        <?php
                        $categoryId = $post['category_id'];
                        $postCategory = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                        $userId = $post['user_id'];
                        $user = $db->query("SELECT * FROM users WHERE id=$userId")->fetch();
                        $userFullName = $user['first_name'] . " " . $user['last_name'];
                        ?>
                        <div class="col">
                            <div class="card">
                                <img src="<?= BASE_URL ?>/uploads/posts/<?= $post['image'] ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title">
                                            <?= $post['title'] ?>
                                        </h5>
                                        <div><span class="badge text-bg-secondary">
                                                <?= $postCategory['title'] ?>
                                            </span></div>
                                    </div>
                                    <p class="card-text">
                                        <?= substr($post['body'], 0, 200) . "..." ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?= BASE_URL ?>/blog/single-post.php?id=<?= $post['id'] ?>" class="btn btn-dark">view</a>
                                        <p class="mb-0">writer: <?= $userFullName ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col">
                        <div class="alert alert-danger" role="alert">
                            no post found!
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <!-- sidebar -->
        <?php
        include(BASE_PATH . "/blog/layout/includes/sidebar.php");

        ?>
    </div>
</section>
<?php
include(BASE_PATH . "/blog/layout/includes/footer.php");

ob_end_flush();
?>