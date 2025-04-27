<?php

include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/includes/functions.php');
include(BASE_PATH . '/admin-panel/layout/includes/header.php');
include(BASE_PATH . "/admin-panel/layout/includes/sidebar.php");

$postId = isset($_GET['post_id']) ? $_GET['post_id'] : null;
if ($postId) {
    try {
        $stmnt = $db->prepare("SELECT * FROM posts WHERE id=:id");
        $stmnt->execute(['id' => $postId]);
        if ($stmnt->rowCount() > 0) {
            $singlePost = $stmnt->fetch();
            $categoryId = $singlePost['category_id'];
            $postCategory = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
        } else {
        }
    } catch (PDOException $e) {
    }
}

?>

<div class="main col-md-9 col-lg-10 mt-3">

    <?php if (empty($singlePost)): ?>
        <div class="alert alert-danger" role="alert">
            no post found!
        </div>
    <?php else: ?>
        <!-- single post -->
        <div class="alert alert-primary" role="alert">
            <div class="d-flex gap-2 justify-content-center">
                <a class="btn btn-secondary" href="edit.php">Edit</a>

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
include(BASE_PATH . "/admin-panel/layout/includes/footer.php");

?>