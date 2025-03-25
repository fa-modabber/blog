<?php
include "../includes/config.php";
include "../includes/db.php";
include "./layout/includes/header.php";
include "./layout/includes/navbar.php";

if (isset($_GET['post'])) {
    $postId = $_GET['post'];
    $query = "SELECT * FROM posts WHERE id=:id";
    $post = $db->prepare($query);
    $post->execute(['id' => $postId]);
    $post = $post->fetch(PDO::FETCH_ASSOC);
    $categoryId = $post['category_id'];
    $postCategory = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
    $userId = $post['user_id'];
    $user = $db->query("SELECT * FROM users WHERE id=$userId")->fetch();
    $userFullName = $user['first_name'] . " " . $user['last_name'];
}

?>

<section class="content mt-4">
    <div class="row">
        <div class="col-lg-8">

            <?php if ($post->rowCount() == 0): ?>
                <div class="alert alert-danger" role="alert">
                    no post found!
                </div>
            <?php else: ?>
                <div class="single-post">
                    <div>
                        <img src="../uploads/posts/<?= $post['image'] ?>" class="img-fluid rounded" alt="post image">
                    </div>
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">
                            <?= $post['title'] ?>
                        </h5>
                        <div><span class="badge text-bg-secondary">
                                <?= $postCategory['title'] ?>
                            </span></div>
                    </div>
                    <div>
                        <?= $post['body'] ?>
                    </div>
                    <p class="mb-0">writer: <?= $userFullName ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- sidebar -->
<?php
include "./layout/includes/sidebar.php";
?>
</div>
</section>

<?php
include "./layout/includes/footer.php";
?>