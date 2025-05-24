<?php

require_once 'C:/xampp/htdocs/weblog-project/init.php';

include(BASE_PATH . '/admin-panel/pages/includes/header.php');
include(BASE_PATH . "/admin-panel/pages/includes/sidebar.php");

$postId = $_GET['id'] ?? null;

if (empty($postId)) {
    abort404();
}

try {
    $post = fetchPostByIdWithCategory($db, $postId);
    if (empty($post)) {
        abort404();
    }
} catch (PDOException $e) {
    flash('post', 'fetch', 'error');
}

?>

<div class="main col-md-9 col-lg-10 mt-3">
    <?php display_flash_messages(); ?>

    <div class="alert alert-primary" role="alert">
        <div class="d-flex gap-2 justify-content-center">
            <a class="btn btn-secondary" href="<?= BASE_URL ?>/admin-panel/pages/posts/view/edit.php?id=<?= $postId ?>&source=single">Edit</a>

            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Delete
            </button>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete a Post</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this post?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <a class="btn btn-danger" href="<?= BASE_URL ?>/admin-panel/pages/posts/controller/delete.php?id=<?= $postId ?>">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3 mt-3">
        <img src="<?= BASE_URL ?>/uploads/posts/<?= $post['image'] ?>" class="card-img-top" alt="post image">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h5 class="card-title">
                    <?= $post['title'] ?>
                </h5>
                <div><span class="badge text-bg-secondary">
                        <?= $post['category_title'] ?>
                    </span></div>
            </div>
            <p class="text-justify">
                <?= $post['body'] ?>
            </p>
        </div>
    </div>

</div>
</div>
</div>
</section>

<?php
include(BASE_PATH . "/admin-panel/pages/includes/footer.php");

?>