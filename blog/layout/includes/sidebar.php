<?php
include_once '../includes/functions.php';

try {
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT * FROM categories";
    $categories = $db->query($query);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// newsletter form handling
$newsletterName = $newsletterEmail = "";
$newsletterNameError = $newsletterEmailError = "";
$newsLetterSuccess = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subscribe'])) {
    $_SESSION['errors'] = [];
    if (empty($_POST['name'])) {
        $_SESSION['errors']['name'] = "Name is necessary";
    } else {
        $newsletterName = test_form_input($_POST['name']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $newsletterName)) {
            $_SESSION['errors']['name'] = "Only letters and white space allowed";
        }
    }

    if (empty($_POST['email'])) {
        $_SESSION['errors']['email'] = "Email is necessary";
    } else {
        $newsletterEmail = test_form_input($_POST['email']);
        if (!filter_var($newsletterEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errors']['email'] = "Invalid email format";
        }
    }

    if (empty($_SESSION['errors'])) {
        $subscribeInsert = $db->prepare("INSERT INTO subscribers (name, email) VALUES (:name,:email)");
        $subscribeInsert->execute(['name' => $newsletterName, 'email' => $newsletterEmail]);
        $_SESSION['success'] = "You successfully joined the newsletter!";
    }
    header("Location: " . $_SERVER['PHP_SELF'], true, 303);
    exit();
}

$newsletterNameError = $_SESSION['errors']['name'] ?? "";
$newsletterEmailError = $_SESSION['errors']['email'] ?? "";
$newsLetterSuccess = $_SESSION['success'] ?? "";

unset($_SESSION['errors'], $_SESSION['success']);


?>

<div class="col-lg-4">

    <!-- Section: search -->
    <div class="card search mb-3">
        <div class="card-body">
            <h5 class="card-title">Search in Blog</h5>
            <form action="/weblog-project/blog/search.php" method="GET">
                <div class="input-group">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon1"><i class="bi bi-search"
                            style="font-size: 1rem; color: cornflowerblue;"></i></button>
                    <input type="text" class="form-control" placeholder="search ..."
                        aria-label="Username" aria-describedby="basic-addon1" name="search">
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
            <?php if (!empty($newsLetterSuccess)): ?>
                <div class="alert alert-success" role="alert">
                    <?= $newsLetterSuccess ?>
                </div>
            <?php endif ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <div class="red-feedback">
                        <?= $newsletterNameError ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
                    <div class="red-feedback">
                        <?= $newsletterEmailError ?>
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