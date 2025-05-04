<?php
include $_SERVER['DOCUMENT_ROOT'] . '/weblog-project/includes/config.php';
include(BASE_PATH . '/includes/db.php');
include(BASE_PATH . '/admin-panel/layout/includes/header.php');
include(BASE_PATH . "/admin-panel/layout/includes/sidebar.php");

$postId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$postId) {
    $errors['retrieve'] = 'post not found!';
    exit;
}

$post = fetchPostById($db, $id);

if (is_string($post)) {
    $errors['retrieve'] = 'problem in retrieving data!';
    exit;
}

if (is_null($post)) {
    $errors['retrieve'] = 'post not found!';
    exit;
}

$categoryId = $post['category_id'];

$categories = fetchAllCategories($db);

if (is_string($categories)) {
    $errors['retrieve'] = 'problem in retrieving data!';
    exit;
}

$errors = [
    'retrieve' => $_SESSION['post_update']['error']['retrieve'] ?? '',
    'title' => $_SESSION['post_update']['error']['title'] ?? '',
    'body' => $_SESSION['post_update']['error']['body'] ?? '',
    'categoryId' => $_SESSION['post_update']['error']['categoryId'] ?? '',
    'image' => $_SESSION['post_update']['error']['image'] ?? '',
    'action' => $_SESSION['post_update']['error']['action'] ?? '',
];

unset($_SESSION['post_update']['error']);
?>

<div class="main col-md-9 col-lg-10 mt-3">
    <?php if (isset($errors['retrieve'])): ?>
        <div class="alert alert-danger" role="alert">
            <?= $errors['retrieve'] ?>
        </div>
    <?php else: ?>
        
        <h1>edit post</h1>
        <form method="post" action="update-post.php" class="row g-3" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($postId) ?>">
            <div class="col-sm-6 mb-4">
                <label for="exampleInputTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="exampleInputTitle" name="title" value="<?= $post['title'] ?>">
                <div class="red-feedback">
                    <?= $errors['title'] ?>
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
                    <?= $errors['image'] ?>
                </div>
            </div>
            <div class="col-sm-12 mb-4">
                <label for="formControlTextarea" class="form-label">Post Body</label>
                <textarea class="form-control" id="FormControlTextarea" rows="3" name="body"><?= $post['body'] ?></textarea>
                <div class="red-feedback">
                    <?= $errors['body'] ?>
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