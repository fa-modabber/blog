<?php
try {
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM categories";
    $categories = $db->query($query);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<div class="col-lg-4">

    <!-- Section: search -->
    <div class="card search mb-3">
        <div class="card-body">
            <h5 class="card-title">Search in Blog</h5>
            <form action="">
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"
                            style="font-size: 1rem; color: cornflowerblue;"></i></span>
                    <input type="text" class="form-control" placeholder="search ..."
                        aria-label="Username" aria-describedby="basic-addon1">
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
            <?php if ($categories->rowCount() > 0): ?>
                <?php foreach ($categories as $category): ?>
                    <li class="list-group-item">
                        <a href="index.php?category=<?= $category['id'] ?>" class="link-body-emphasis text-decoration-none
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

            <?php
            $inputNameErrors = [];
            $inputEmailErrors = [];
            if (isset($_POST['subscribe'])) {
                if (empty(trim($_POST['name']))) {
                    array_push($inputNameErrors, "name is necessary");
                }
                if (empty(trim($_POST['email']))) {
                    array_push($inputEmailErrors, "name is necessary");
                }
            }
            ?>

            <form method="POST" >
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name">


                    <?php if (!empty($inputNameErrors)): ?>
                        <?php foreach ($inputNameErrors as $error): ?>
                            <div class="invalid-feedback">
                                <?= $error ?>
                            </div>

                        <?php endforeach ?>
                    <?php endif ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
                    <div class="invalid-feedback">
                        Please provide a valid zip.
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-secondary" type="submit" name="subscribe">Submit</button>
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