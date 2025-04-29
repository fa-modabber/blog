<?php

include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/admin-panel/layout/includes/header.php');
include(BASE_PATH . "/admin-panel/layout/includes/sidebar.php");

$postId = isset($_GET['id']) ? $_GET['id'] : null;
if ($postId) {
    try {
        $stmnt = $db->prepare("SELECT * FROM posts WHERE id=:id");
        $stmnt->execute(['id' => $postId]);
        if ($stmnt->rowCount() > 0) {
            $post = $stmnt->fetch();
            $categoryId = $post['category_id'];
            $postCategory = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
        } else {
        }
    } catch (PDOException $e) {
    }

    $editSuccess = $_SESSION['post_update']['success'] ?? "";
    $createSuccess = $_SESSION['post_create']['success'] ?? "";
    unset($_SESSION['post_update']['success'], $_SESSION['post_create']['success']);
}

?>

<div class="main col-md-9 col-lg-10 mt-3">

    <?php if (empty($post)): ?>
        <div class="alert alert-danger" role="alert">
            no post found!
        </div>
    <?php else: ?>
        <?php if (!empty($editSuccess)): ?>
            <div class="alert alert-success" role="alert">
                <?= $editSuccess ?>
            </div>
        <?php endif ?>
        <!-- single post -->
        <div class="alert alert-primary" role="alert">
            <div class="d-flex gap-2 justify-content-center">
                <a class="btn btn-secondary" href="<?= BASE_URL ?>/admin-panel/pages/posts/edit.php?id=<?= $postId ?>">Edit</a>

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
                                <a class="btn btn-danger" href="./index.php?action=delete&id=<?= $postId ?>">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3 mt-3">
            <img src="/weblog-project/uploads/posts/<?= $post['image'] ?>" class="card-img-top" alt="post image">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">
                        <?= $post['title'] ?>
                    </h5>
                    <div><span class="badge text-bg-secondary">
                            <?= $postCategory['title'] ?>
                        </span></div>
                </div>
                <p class="text-justify">
                    <?= $post['body'] ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

</div>
</div>
</div>
</section>

<?php
include(BASE_PATH . "/admin-panel/layout/includes/footer.php");

?>