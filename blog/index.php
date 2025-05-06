<?php
include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/blog/layout/includes/header.php');
include(BASE_PATH . '/blog/layout/includes/navbar.php');
include(BASE_PATH . '/blog/layout/includes/slider.php');

$categoryId = isset($_GET['category']) ? $_GET['category'] : null;
if (isset($categoryId)) {
    $posts = fetchPostsByCategory($db, $categoryId);
} else {
    $posts = fetchAllPosts($db);
}

if (is_string($posts)) {
    $errors['retrieve'] = 'problem in retrieving data!';
}

if (empty($posts)) {
    $errors['retrieve'] = 'no post found!';
}

?>

<section class="content mt-4">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <?php if (isset($errors['retrieve'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors['retrieve'] ?>
                </div>
            <?php else: ?>
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
                                        <a href="<?= BASE_URL ?>/blog/single-post.php?id=<?= $post['id'] ?>" class="btn btn-dark">view</a>
                                        <p class="mb-0">writer: <?= $userFullName ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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