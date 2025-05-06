<?php
include(BASE_PATH . '/includes/functions.php');

$categories = fetchAllCategories($db);
if (is_string($categories)) {
    $errors['retrieve'] = 'problem in retrieving data!';
}

if (empty($categories)) {
    $errors['retrieve'] = 'no category found!';
}

?>

<body>
    <div class="container">
        <section class="navbar-section">
            <div class="top-nav d-flex flex-column flex-md-row justify-content-md-between align-items-center py-3">
                <a href="<?= BASE_URL ?>/blog/index.php" class="brand fw-bold">My Weblog</a>
                <?php if (isset($errors['retrieve'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors['retrieve'] ?>
                    </div>
                <?php else: ?>
                    <nav>
                        <?php foreach ($categories as $category): ?>
                            <a href="<?= BASE_URL ?>/blog/index.php?category=<?= $category['id'] ?>" class=" <?= ((isset($_GET['category'])) && ($_GET['category'] == $category['id'])) ? 'fw-bold' : ''; ?> ">
                                <?= $category['title'] ?>
                            </a>
                        <?php endforeach ?>
                    <?php endif ?>
                    </nav>
            </div>
            <hr>
        </section>