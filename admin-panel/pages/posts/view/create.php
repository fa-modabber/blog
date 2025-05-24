<?php

require_once 'C:/xampp/htdocs/weblog-project/init.php';

include(BASE_PATH . '/admin-panel/pages/includes/header.php');
include(BASE_PATH . "/admin-panel/pages/includes/sidebar.php");

try {
    $categories = fetchCategories($db);
    if (empty($categories)) {
        flash('category', 'fetch', 'not_found');
    }
} catch (PDOException $e) {
    flash('category', 'fetch', 'error');
}

$formErrors = [
    'title' => $_SESSION['post']['create']['form']['error']['title'] ?? '',
    'body' => $_SESSION['post']['create']['form']['error']['body'] ?? '',
    'categoryId' => $_SESSION['post']['create']['form']['error']['categoryId'] ?? '',
    'image' => $_SESSION['post']['create']['form']['error']['image'] ?? ''
];

unset($_SESSION['post']['create']['form']['error']);
?>

<!-- main section -->
<div class="main col-md-9 col-lg-10 mt-3 mb-3">
    <?php display_flash_messages(); ?>

    <?php if (!empty($categories)): ?>

        <h1>create post</h1>
        <form method="post" action="<?= BASE_URL ?>/admin-panel/pages/posts/controller/store.php" class="row g-3" enctype="multipart/form-data">

            <div class="col-sm-6 mb-4">
                <label for="exampleInputTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="exampleInputTitle" name="title">
                <div class="red-feedback">
                    <?= $formErrors['title'] ?>
                </div>
            </div>
            <div class="col-sm-6 mb-4">
                <label for="exampleSelect" class="form-label">Category</label>
                <select class="form-select" aria-label="Default select example" id="exampleSelect" name="categoryId">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-6 mb-4">
                <input type="file" class="form-control" name="image">
                <div class="red-feedback">
                    <?= $formErrors['image'] ?>
                </div>
            </div>
            <div class="col-sm-12 mb-4">
                <label for="formControlTextarea" class="form-label">Post Body</label>
                <textarea class="form-control" id="FormControlTextarea" rows="3" name="body"></textarea>
                <div class="red-feedback">
                    <?= $formErrors['body'] ?>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-dark" name="submit">create</button>
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