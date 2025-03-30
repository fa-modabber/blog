<?php
include "../includes/config.php";
include "../includes/db.php";
include_once '../includes/functions.php';
include "./layout/includes/header.php";
include "./layout/includes/navbar.php";

$postId = isset($_GET['post_id']) ? $_GET['post_id'] : (isset($_POST['post_id']) ? $_POST['post_id'] : null);

if ($postId) {
    $singlePost = $db->prepare("SELECT * FROM posts WHERE id=:id");
    $singlePost->execute(['id' => $postId]);
    $singlePost = $singlePost->fetch();
    $categoryId = $singlePost['category_id'];
    $postCategory = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
    $userId = $singlePost['user_id'];
    $user = $db->query("SELECT * FROM users WHERE id=$userId")->fetch();
    $userFullName = $user['first_name'] . " " . $user['last_name'];

    $comments = $db->prepare("SELECT * FROM comments WHERE post_id=:id AND is_accepted='1'");
    $comments->execute(['id' => $postId]);


    // comment form handling
    $commentName = $commentBody = "";
    $commentNameError = $commentBodyError = "";
    $commentSuccess = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['postComment'])) {
        $_SESSION['errors'] = [];
        if (empty($_POST['commentName'])) {
            $_SESSION['errors']['commentName'] = "Name is necessary";
        } else {
            $commentName = test_form_input($_POST['commentName']);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $commentName)) {
                $_SESSION['errors']['commentName'] = "Only letters and white space allowed";
            }
        }

        if (empty($_POST['commentBody'])) {
            $_SESSION['errors']['commentBody'] = "Comment text is necessary";
        } else {
            $commentBody = test_form_input($_POST['commentBody']);
        }

        if (empty($_SESSION['errors'])) {
            $commentInsert = $db->prepare("INSERT INTO comments (name, body, post_id, is_accepted) VALUES (:name,:body,:post_id, 0)");
            $commentInsert->execute(['name' => $commentName, 'body' => $commentBody, 'post_id' => $postId]);
            $_SESSION['success'] = "You successfully Left a Comment!";
        }
        header("Location: single-post.php?post_id=" . $postId);
        exit;
    }

    $commentNameError = $_SESSION['errors']['commentName'] ?? "";
    $commentBodyError = $_SESSION['errors']['commentBody'] ?? "";
    $commentSuccess = $_SESSION['success'] ?? "";

    unset($_SESSION['errors'], $_SESSION['success']);
}
?>

<section class="content mt-4">
    <div class="row">
        <div class="col-lg-8">
            <?php if (empty($singlePost)): ?>
                <div class="alert alert-danger" role="alert">
                    no post found!
                </div>
            <?php else: ?>
                <!-- single post -->
                <div class="card mb-3">
                    <img src="../uploads/posts/<?= $singlePost['image'] ?>" class="card-img-top" alt="post image">
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
                        <?php if (!empty($commentSuccess)): ?>
                            <div class="alert alert-success" role="alert">
                                <?= $commentSuccess ?>
                            </div>
                        <?php endif ?>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="post_id" value="<?= htmlspecialchars($postId) ?>">
                            <div class="mb-3">
                                <label for="exampleInputName1" class="form-label">Name</label>
                                <input type="text" name="commentName" class="form-control" id="exampleInputName1" aria-label="name">
                                <div class="red-feedback">
                                    <?= $commentNameError ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputComment" class="form-label">Your Comment</label>
                                <textarea type="text" class="form-control" id="exampleInputComment" name="commentBody"></textarea>
                                <div class="red-feedback">
                                    <?= $commentBodyError ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark" name="postComment">Submit</button>
                        </form>
                    </div>
                </div>

                <!-- list of comments -->
                <p class=" fs-6"><b>Number of Comments: </b><?= $comments->rowCount() ?></p>

                <?php if ($comments->rowCount() > 0): ?>
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
            <?php endif; ?>
        </div>
        <!-- sidebar -->
        <?php
        include "./layout/includes/sidebar.php";
        ?>
    </div>
</section>




<?php
include "./layout/includes/footer.php";
?>