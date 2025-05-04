<?php

include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/includes/functions.php');
include(BASE_PATH . '/admin-panel/layout/includes/header.php');
include(BASE_PATH . "/admin-panel/layout/includes/sidebar.php");

$categories = fetchAllCategories($db);

if (is_string($categories)) {
    $errors['retrieve'] = 'problem in retrieving data!';
    exit;
}

$errors = [
    'title' => $_SESSION['post_create']['error']['title'] ?? '',
    'body' => $_SESSION['post_create']['error']['body'] ?? '',
    'categoryId' => $_SESSION['post_create']['error']['categoryId'] ?? '',
    'image' => $_SESSION['post_create']['error']['image'] ?? ''
];

unset($_SESSION['post_create']['error']);
?>

<!-- main section -->
<div class="main col-md-9 col-lg-10 mt-3">
    <?php if (isset($errors['retrieve'])): ?>
        <div class="alert alert-danger" role="alert">
            <?= $errors['retrieve'] ?>
        </div>
    <?php else: ?>
        <h1>create post</h1>
        <form method="post" action="store-post.php" class="row g-3" enctype="multipart/form-data">

            <div class="col-sm-6 mb-4">
                <label for="exampleInputTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="exampleInputTitle" name="title">
                <div class="red-feedback">
                    <?= $errors['title'] ?>
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
                    <?= $errors['image'] ?>
                </div>
            </div>
            <div class="col-sm-12 mb-4">
                <label for="formControlTextarea" class="form-label">Post Body</label>
                <textarea class="form-control" id="FormControlTextarea" rows="3" name="body"></textarea>
                <div class="red-feedback">
                    <?= $errors['body'] ?>
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
include(BASE_PATH . "/admin-panel/layout/includes/footer.php");

?>