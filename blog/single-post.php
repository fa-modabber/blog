<?php
include "../includes/config.php";
include "../includes/db.php";
include "./layout/includes/header.php";
include "./layout/includes/navbar.php";

if (isset($_GET['post'])) {
    $postId = $_GET['post'];
    $post = $db->prepare("SELECT * FROM posts WHERE id=:id");
    $post->execute(['id' => $postId]);
    $post = $post->fetch();

    $categoryId = $post['category_id'];
    $postCategory = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
    $userId = $post['user_id'];
    $user = $db->query("SELECT * FROM users WHERE id=$userId")->fetch();
    $userFullName = $user['first_name'] . " " . $user['last_name'];

    $comments = $db->prepare("SELECT * FROM comments WHERE post_id=:id AND is_accepted='1'");
    $comments->execute(['id' => $postId]);
}

?>

<section class="content mt-4">
    <div class="row">
        <div class="col-lg-8">
            <?php if (empty($post)): ?>
                <div class="alert alert-danger" role="alert">
                    no post found!
                </div>
            <?php else: ?>
                <!-- single post -->
                <div class="card">
                    <img src="../uploads/posts/<?= $post['image'] ?>" class="card-img-top" alt="post image">
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
                        <div>
                            <p class="mb-0 fw-semibold">writer: <?= $userFullName ?></p>
                        </div>
                    </div>
                </div>

                <hr class="bg-secondary border-2 border-top border-secondary" />

                <!-- comment form -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Write a Comment</h5>
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="exampleInputName1" class="form-label">Name</label>
                                <input type="text" class="form-control" id="exampleInputName1" aria-label="name">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            </div>
                            <button type="submit" class="btn btn-dark">Submit</button>
                        </form>
                    </div>
                </div>

                <!-- list of comments -->
                <p class="fw-bold fs-6">Number of Comments: <?= $comments->rowCount() ?></p>
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
                    <?php endforeach;  ?>
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