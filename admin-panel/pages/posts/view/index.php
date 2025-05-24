<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

include(BASE_PATH . '/admin-panel/pages/includes/header.php');
include(BASE_PATH . "/admin-panel/pages/includes/sidebar.php");

$userId = 1;

try {
    $posts = fetchPostsByUserId($db, $userId);
    // if (empty($posts))  flash('post', 'fetch', 'not_found');

} catch (PDOException $e) {
    flash('post', 'fetch', 'error');
}
?>

<!-- main section -->
<div class="main col-md-9 col-lg-10 mt-3">

    <?php
    display_flash_messages();
    ?>

    <div class="d-flex justify-content-between align-items-center">
        <h1>Posts</h1>
        <a class="btn btn-dark" href="<?= BASE_URL ?>/admin-panel/pages/posts/view/create.php"> ایجاد مقاله</a>
    </div>
    <?php if (!empty($posts)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Title</th>
                        <th scope="col">Writer</th>
                        <th scope="col">Operation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= $post['created_at'] ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/admin-panel/pages/posts/view/single.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
                            </td>
                            <td>Otto</td>
                            <td>
                                <div class="d-grid gap-2 d-md-block">
                                    <a class="btn btn-secondary" href="<?= BASE_URL ?>/admin-panel/pages/posts/view/edit.php?id=<?= $post['id'] ?>&source=index-posts">Edit</a>

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
                                                    <a class="btn btn-danger" href="<?= BASE_URL ?>/admin-panel/pages/posts/controller/delete.php?id=<?= $post['id'] ?>">Delete</a>
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
            no post found!
        </div>
    <?php endif; ?>

</div>
</div>
</div>
</section>

<?php
include(BASE_PATH . "/admin-panel/pages/includes/footer.php");

?>