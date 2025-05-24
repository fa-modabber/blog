<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';


$formErrors = [
    'email' => $_SESSION['user']['fetch']['form']['error']['email'] ?? "",
    'password' => $_SESSION['user']['fetch']['form']['error']['password'] ?? "",
];

unset($_SESSION['user']['fetch']['form']['error']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin-panel/assets/css/styles.css" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
        crossorigin="anonymous" />
</head>

<body>
    <main class="form-signin w-50 m-auto mb-3 mt-3">
        <?php display_flash_messages(); ?>
        <form method="post" action="<?= BASE_URL ?>/admin-panel/pages/auth/controller/user-login.php">
            <div class="fs-2 fw-bold text-center mb-4">Best Weblog</div>
            <div class="mb-3">
                <label class="form-label">email</label>
                <input type="email" class="form-control" name="email" />
                <div class="red-feedback">
                    <?= $formErrors['email'] ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">password</label>
                <input type="password" class="form-control" name="password" />
                <div class="red-feedback">
                    <?= $formErrors['password'] ?>
                </div>
            </div>
            <button class="w-100 btn btn-dark mt-4" type="submit" name="login">
                login
            </button>
        </form>
    </main>
</body>

</html>
<?php
include(BASE_PATH . "/admin-panel/pages/includes/footer.php");
?>