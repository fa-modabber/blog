<?php
include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/admin-panel/layout/includes/header.php');
include(BASE_PATH . "/admin-panel/layout/includes/sidebar.php");

$postId = isset($_GET['post_id']) ? $_GET['post_id'] : null;
if ($postId) {
    try {
        $stmnt = $db->prepare("SELECT * FROM posts WHERE id=:id");
        $stmnt->execute(['id' => $postId]);
        if ($stmnt->rowCount() > 0) {
            $post = $stmnt->fetch();
            $categoryId = $post['category_id'];
            $categories = $db->query("SELECT * FROM categories");
        } else {
        }
    } catch (PDOException $e) {
    }
}
?>

<div class="main col-md-9 col-lg-10 mt-3">
    <?php if (empty($post)): ?>
        <div class="alert alert-danger" role="alert">
            no post found!
        </div>
    <?php else: ?>
        <h1>edit post</h1>
        <form method="post" action="" class="row g-3" enctype="multipart/form-data">
            <div class="col-sm-6 mb-4">
                <label for="exampleInputTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="exampleInputTitle" name="title" value="<?= $post['title'] ?>">
                <div class="red-feedback">
                    <?= "test"; ?>
                </div>
            </div>
            <div class="col-sm-6 mb-4">
                <label for="exampleSelect" class="form-label">Category</label>
                <select class="form-select" aria-label="Default select example" id="exampleSelect" name="categoryId">
                    <?php foreach ($categories as $category) : ?>
                        <option <?= ($category['id'] == $categoryId) ? 'selected' : "" ?> value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-6 mb-4">
                <img src="<?= BASE_URL ?>/uploads/posts/<?= $post['image'] ?>" class="img-thumbnail" alt="post image">
                <input type="file" class="form-control" name="image">
                <div class="red-feedback">
                    <?= "" ?>
                </div>
            </div>
            <div class="col-sm-12 mb-4">
                <label for="formControlTextarea" class="form-label">Post Body</label>
                <textarea class="form-control" id="FormControlTextarea" rows="3" name="body" ><?= $post['body'] ?></textarea>
                <div class="red-feedback">
                    <?= "" ?>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-dark" name="submit">Edit</button>
            </div>
        </form>
    <?php endif; ?>

</div>

</div>
</div>
</section>

<?php
include(BASE_PATH . "/admin-panel/layout/includes/footer.php");

?>