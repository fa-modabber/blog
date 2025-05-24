<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

include(BASE_PATH . "/blog/view/includes/header.php");
include(BASE_PATH . "/blog/view/includes/navbar.php");

$postId = $_GET['id'] ?? ($_POST['id'] ?? null);
$formErrors=[];

if (empty($postId)) {
    abort404();
}

try {
    $post =  fetchPostWithDetailsById($db, $postId);
    if (empty($post)) {
        abort404();
    }
} catch (PDOException $e) {
    abort404();
}

$userFullName = $post['first_name'] . ' ' . $post['last_name'];

try {
    $comments = fetchCommentsAcceptedByPostId($db, $postId);
} catch (PDOException $e) {
    flash('comment', 'fetch', 'error');
}

$formErrors = [
    'name' => $_SESSION['comment']['create']['form']['error']['name'] ?? "",
    'body' => $_SESSION['comment']['create']['form']['error']['body'] ?? "",
];
unset($_SESSION['comment']['create']['form']['error']);

?>

<section class="content mt-4">
    <div class="row">
        <div class="col-lg-8">
            <?php display_flash_messages(); ?>

            <div class="card mb-3">
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
                    <div>
                        <p class="mb-0 fw-semibold">writer: <?= $userFullName ?></p>
                    </div>
                </div>
            </div>

            <!-- <hr class="bg-secondary border-2 border-top border-secondary" /> -->

            <!-- comment form -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Write a Comment</h5>

                    <form method="post" action="<?= BASE_URL ?>/blog/controller/comment-store.php">
                        <input type="hidden" name="postId" value="<?= htmlspecialchars($postId) ?>">
                        <div class="mb-3">
                            <label for="exampleInputName1" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="exampleInputName1" aria-label="name">
                            <div class="red-feedback">
                                <?= $formErrors['name'] ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputComment" class="form-label">Your Comment</label>
                            <textarea type="text" class="form-control" id="exampleInputComment" name="body"></textarea>
                            <div class="red-feedback">
                                <?= $formErrors['body'] ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark" name="postComment">Submit</button>
                    </form>
                </div>
            </div>

            <!-- list of comments -->
            <p class=" fs-6"><b>Number of Comments: </b><?= count($comments) ?></p>

            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment):  ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= $comment['name'] ?>
                            </h5>
                            <p class="card-text"><?= $comment['body'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    no comments yet!
                </div>
            <?php endif; ?>

        </div>
        <!-- sidebar -->
        <?php
        include(BASE_PATH . "/blog/view/includes/sidebar.php");
        ?>
    </div>
</section>




<?php
include(BASE_PATH . "/blog/view/includes/footer.php");

?>