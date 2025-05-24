<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/admin-panel/pages/auth/view/login.php");
    exit;
}

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
    <header class="navbar navbar-expand-md sticky-top bg-secondary">
        <a class="navbar-brand text-white " href="#">Amin Panel</a>
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasResponsive"
            aria-controls="offcanvasResponsive"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </header>