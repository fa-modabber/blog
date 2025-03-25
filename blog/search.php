<?php
include "../includes/config.php";
include "../includes/db.php";
include "./layout/includes/header.php";
include "./layout/includes/navbar.php";
include "./layout/includes/slider.php";

if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $query = "SELECT * FROM posts WHERE body LIKE :keyword OR title LIKE :keyword ORDER BY id DESC";
    $posts = $db->prepare($query);
    $posts->execute(['keyword' => "%$keyword%"]);
}

?>

<section class="content mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="alert alert-primary" role="alert">
                Search Result for Posts with [<?= $keyword ?>]
            </div>
            <?php if ($posts->rowCount() == 0): ?>
                <div class="alert alert-danger" role="alert">
                    no post found!
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
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
                                <img src="../uploads/posts/<?= $post['image'] ?>" class="card-img-top" alt="...">
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
                                        <a href="/weblog-project/blog/single-post.php?post=<?php $post['id'] ?>" class="btn btn-dark">view</a>
                                        <p class="mb-0">writer: <?= $userFullName ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- sidebar -->
        <?php
        include "./layout/includes/sidebar.php";
        ?>
    </div>
</section>

<?php
include "./layout/includes/footer.php";
?>