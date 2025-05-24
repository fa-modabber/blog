<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

include(BASE_PATH . '/admin-panel/pages/includes/header.php');
include(BASE_PATH . "/admin-panel/pages/includes/sidebar.php");

$postId = $_GET['id'] ?? null;

if (empty($postId)) {
    abort404();
}

try {
    $oldPost = fetchPostById($db, $postId);
    $categoryId = $oldPost['category_id'] ?? '';
    if (empty($oldPost)) {
        flash('post', 'update', 'not_found');
    }
} catch (PDOException $e) {
    flash('post', 'fetch', 'error');
}

try {
    $categories = fetchCategories($db);
} catch (PDOException $e) {
    flash('category', 'fetch', 'error');
}

$formErrors = [
    'title' => $_SESSION['post']['edit']['form']['error']['title'] ?? '',
    'body' => $_SESSION['post']['edit']['form']['error']['body'] ?? '',
    'categoryId' => $_SESSION['post']['edit']['form']['error']['categoryId'] ?? '',
    'image' => $_SESSION['post']['edit']['form']['error']['image'] ?? '',
];

unset($_SESSION['post']['edit']['form']['error']);
?>

<div class="main col-md-9 col-lg-10 mt-3">
    <?php display_flash_messages(); ?>

    <?php if (!empty($oldPost)): ?>
        <h1>edit post</h1>
        <form method="post" action="<?= BASE_URL ?>/admin-panel/pages/posts/controller/update.php" class="row g-3" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($postId) ?>">
            <div class="col-sm-6 mb-4">
                <label for="exampleInputTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="exampleInputTitle" name="title" value="<?= $oldPost['title'] ?>">
                <div class="red-feedback">
                    <?= $formErrors['title'] ?>
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
                <img src="<?= BASE_URL ?>/uploads/posts/<?= $oldPost['image'] ?>" class="img-thumbnail" alt="post image">
                <input type="file" class="form-control" name="image">
                <div class="red-feedback">
                    <?= $formErrors['image'] ?>
                </div>
            </div>
            <div class="col-sm-12 mb-4">
                <label for="formControlTextarea" class="form-label">Post Body</label>
                <textarea class="form-control" id="FormControlTextarea" rows="3" name="body"><?= $oldPost['body'] ?></textarea>
                <div class="red-feedback">
                    <?= $formErrors['body'] ?>
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
include(BASE_PATH . "/admin-panel/pages/includes/footer.php");

?>