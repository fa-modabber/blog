<?php

require_once 'C:/xampp/htdocs/weblog-project/init.php';

//layout
include(BASE_PATH . '/blog/view/includes/header.php');
include(BASE_PATH . '/blog/view/includes/navbar.php');
include(BASE_PATH . '/blog/view/includes/slider.php');

$categoryId = $_GET['category'] ?? null;

try {
    $posts = isset($categoryId)
        ? fetchPostsWithDetailsByCategory($db, $categoryId)
        : fetchPostsWithDetails($db);
} catch (PDOException $e) {
    flash('post', 'fetch', 'error');
}

?>

<section class="content mt-4">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <?php display_flash_messages(); ?>
            <?php if (!empty($posts)): ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php foreach ($posts as $post): ?>
                        <?php
                        $userFullName = $post['first_name'] . ' ' . $post['last_name'];
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
                                                <?= $post['category_title'] ?>
                                            </span></div>
                                    </div>
                                    <p class="card-text">
                                        <?= substr($post['body'], 0, 200) . "..." ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?= BASE_URL ?>/blog/view/post-single.php?id=<?= $post['id'] ?>" class="btn btn-dark">view</a>
                                        <p class="mb-0">writer: <?= $userFullName ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            <?php else:  ?>
                <div class="alert alert-warning" role="alert">
                        no post found for this category
                    </div>
               
            <?php endif ?>
        </div>

        <!-- sidebar -->
        <?php
        include(BASE_PATH . "/blog/view/includes/sidebar.php");
        ?>
    </div>
</section>
<?php
include(BASE_PATH . "/blog/view/includes/footer.php");

ob_end_flush();
?>