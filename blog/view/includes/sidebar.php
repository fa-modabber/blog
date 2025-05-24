<?php
$formErrors=[];
try {
    $categories = fetchCategories($db);
    if (empty($categories)) {
        flash('category', 'fetch', 'not_found');
    }
} catch (PDOException $e) {
    flash('category', 'fetch', 'error');
}

$formErrors= [
    'name' => $_SESSION['newsletter']['create']['form']['error']['name'] ?? "",
    'email' => $_SESSION['newsletter']['create']['form']['error']['email'] ?? "",
];

unset($_SESSION['newsletter']['create']['form']['error']);

?>

<div class="col-lg-4">
    <?php display_flash_messages(); ?>

    <!-- Section: search -->
    <div class="card search mb-3">
        <div class="card-body">
            <h5 class="card-title">Search in Blog</h5>
            <form method="GET" action="<?= BASE_URL ?>/blog/view/search.php">
                <div class="input-group">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon1"><i class="bi bi-search"
                            style="font-size: 1rem; color: cornflowerblue;"></i></button>
                    <input type="text" class="form-control" placeholder="search ..." name="search" required>
                </div>
            </form>
        </div>
    </div>

    <!-- Section: categories -->
    <div class="card categories mb-3">
        <div class="card-header fw-bold">
            Categories
        </div>
        <ul class="list-group list-group-flush">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <li class="list-group-item">
                        <a href="<?= BASE_URL ?>/blog/index.php?category=<?= $category['id'] ?>" class="link-body-emphasis text-decoration-none
                        <?= ((isset($_GET['category'])) && ($_GET['category'] == $category['id'])) ? 'fw-bold' : '' ?>
                        "><?= $category['title'] ?></a>
                    </li>
                <?php endforeach ?>
            <?php endif ?>

        </ul>
    </div>

    <!-- Section: newsletter -->
    <div class="card newsletter mb-3">
        <div class="card-body">
            <h5 class="card-title">Join Our Newsletter</h5>
            <form method="POST" action="<?= BASE_URL ?>/blog/controller/newsletter-subscriber-store.php">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <div class="red-feedback">
                        <?= $formErrors['name'] ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
                    <div class="red-feedback">
                        <?= $formErrors['email'] ?>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-secondary" type="submit" name="subscribe">Subscribe</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section: about us -->
    <div class="card about-us">
        <div class="card-body">
            <h5 class="card-title">About Us</h5>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolore nesciunt doloremque minus
            delectus! Deserunt laborum magni adipisci libero ipsam nihil aliquid voluptatibus eveniet
            quas vel, saepe quaerat consectetur asperiores dignissimos?
        </div>
    </div>

</div>