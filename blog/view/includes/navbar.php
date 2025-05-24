<?php

try {
    $categories = fetchCategories($db);
    if (empty($categories)) {
        flash('category', 'fetch', 'not_found');
    }
} catch (PDOException $e) {
    flash('category', 'fetch', 'error');
}

?>

<body>
    <div class="container">
        <section class="navbar-section">
            <?php display_flash_messages(); ?>

            <div class="top-nav d-flex flex-column flex-md-row justify-content-md-between align-items-center py-3">
                <a href="<?= BASE_URL ?>/blog/index.php" class="brand fw-bold">My Weblog</a>
                <?php if (!empty($categories)): ?>
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