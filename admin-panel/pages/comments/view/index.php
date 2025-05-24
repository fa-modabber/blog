<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

include(BASE_PATH . '/admin-panel/pages/includes/header.php');
include(BASE_PATH . "/admin-panel/pages/includes/sidebar.php");

$userId = $_SESSION['user_id'];

try {
    $comments = fetchCommentsByUserId($db, $userId);
} catch (PDOException $e) {
    flash('comment', 'fetch', 'error');
}

?>

<!-- main section -->
<div class="main col-md-9 col-lg-10 mt-3">

    <?php
    display_flash_messages();
    ?>

    <?php if (!empty($comments)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Comment Text</th>
                        <th scope="col">Status</th>
                        <th scope="col">Operation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td><?= $comment['author'] ?></td>
                            <td><?= $comment['body'] ?></td>
                            <td></td>
                            <td>
                                <div class="d-grid gap-2 d-md-block">
                                    <?php if ($comment['is_accepted'] == 0): ?>
                                        <a class="btn btn-secondary" href="<?= BASE_URL ?>/admin-panel/pages/comments/controller/accept.php?id=<?= $comment['id'] ?>">wait for accept</a>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-success disabled">accepted</button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Delete
                                    </button>
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete a Comment</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this comment?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <a class="btn btn-danger" href="<?= BASE_URL ?><?= BASE_URL ?>/admin-panel/pages/comments/controller/delete.php?id=<?= $comment['id'] ?>">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            no comment found!
        </div>
    <?php endif; ?>

</div>
</div>
</div>
</section>

<?php
include(BASE_PATH . "/admin-panel/pages/includes/footer.php");
?>