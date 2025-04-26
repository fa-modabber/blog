<?php
include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/includes/functions.php');
include(BASE_PATH . '/admin-panel/layout/includes/header.php');
include(BASE_PATH . "/admin-panel/layout/includes/sidebar.php");

$userId = 1;
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $postId = $_GET['id'];
    try {
        $query = $db->prepare('DELETE FROM posts WHERE id = :id');
        $query->execute(['id' => $postId]);
        if ($query->rowCount() > 0) {
            flash(entity: 'post', action: 'delete', result: 'success');
        } else {
            flash(entity: 'post', action: 'delete', result: 'notFound');
        }
    } catch (PDOException $e) {
        flash(entity: 'post', action: 'delete', result: 'error');
    }
    header("Location:index.php");
    exit;
}

try {
    $query = "SELECT * FROM posts WHERE user_id=:id ORDER BY id DESC";
    $posts = $db->prepare($query);
    $posts->execute(["id" => $userId]);
} catch (PDOException $e) {
    flash(entity: 'post', action: 'retrieve', result: 'error');
}
?>

<!-- main section -->
<div class="main col-md-9 col-lg-10">

    <?php
    flash();
    ?>

    <div class="d-flex justify-content-between align-items-center">
        <h1>Posts</h1>
        <a class="btn btn-dark" href="./create.php"> ایجاد مقاله</a>
    </div>
    <?php if ($posts->rowCount() > 0): ?>
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
                                <a href="<?= BASE_URL ?>/admin-panel/pages/posts/single.php?post_id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
                            </td>
                            <td>Otto</td>
                            <td>
                                <div class="d-grid gap-2 d-md-block">
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
                                                    <a class="btn btn-danger" href="index.php?action=delete&id=<?= $post['id'] ?>">Delete</a>
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
        <div class="col">
            <div class="alert alert-danger" role="alert">
                no post found!
            </div>
        </div>
    <?php endif ?>

</div>
</div>
</div>
</section>

<?php
include (BASE_PATH ."/admin-panel/layout/includes/footer.php");

?>