<?php

include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include (BASE_PATH . '/includes/db.php');
include (BASE_PATH . '/includes/functions.php');
include (BASE_PATH .'/admin-panel/layout/includes/header.php');
include (BASE_PATH ."/admin-panel/layout/includes/sidebar.php");

$postId = isset($_GET['post_id']) ? $_GET['post_id'] : null;
if ($postId) {
    $singlePost = $db->prepare("SELECT * FROM posts WHERE id=:id");
    $singlePost->execute(['id' => $postId]);
    $singlePost = $singlePost->fetch();
    $categoryId = $singlePost['category_id'];
    $postCategory = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
}

?>

<div class="main col-md-9 col-lg-10">

    <?php if (empty($singlePost)): ?>
        <div class="alert alert-danger" role="alert">
            no post found!
        </div>
    <?php else: ?>
        <!-- single post -->
        <div class="card mb-3 mt-3">
            <img src="/weblog-project/uploads/posts/<?= $singlePost['image'] ?>" class="card-img-top" alt="post image">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">
                        <?= $singlePost['title'] ?>
                    </h5>
                    <div><span class="badge text-bg-secondary">
                            <?= $postCategory['title'] ?>
                        </span></div>
                </div>
                <p class="text-justify">
                    <?= $singlePost['body'] ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

</div>
</div>
</div>
</section>

<?php
include (BASE_PATH ."/admin-panel/layout/includes/footer.php");

?>