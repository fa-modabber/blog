<?php

include "./include/config.php";
include "./include/db.php";

try {

    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM categories";
    $categories = $db->query($query);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>


<body>
    <div class="container">
        <section class="navbar-section">
            <div class="top-nav d-flex flex-column flex-md-row justify-content-md-between align-items-center py-3">
                <a href="" class="brand fw-bold">My Weblog</a>
                <nav>
                    <?php if ($categories->rowCount() > 0): ?>
                        <?php foreach ($categories as $category): ?>
                            <a href="" class="fw-bold">
                                <?= $category['title'] ?>
                            </a>
                        <?php endforeach ?>
                    <?php endif ?>
                </nav>
            </div>
            <hr>
        </section>