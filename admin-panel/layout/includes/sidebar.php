<?php
$path = $_SERVER['REQUEST_URI'];

?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Section -->
            <div class="sidebar col-md-3 col-lg-2 bg-secondary-subtle">
                <div
                    class="offcanvas-md offcanvas-end"
                    tabindex="-1"
                    id="offcanvasResponsive"
                    aria-labelledby="offcanvasResponsiveLabel">
                    <div class="offcanvas-header">
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="offcanvas"
                            data-bs-target="#offcanvasResponsive"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="<?= BASE_URL ?>/admin-panel/index.php"><i class="bi bi-house-fill fs-4 pe-2"></i>
                                    <span class="fw-bold <?= strpos($path, '/admin-panel/index.php') ? 'text-primary': '' ?>">Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>/admin-panel/pages/posts/index.php">
                                    <i class="bi bi-book-half fs-4 pe-2"></i>
                                    <span class="fw-bold <?= str_contains($path, 'posts') ? 'text-primary': '' ?>">Posts</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-bookshelf fs-4 pe-2"></i>
                                    <span class="fw-bold">Categories</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-chat-right-text-fill fs-4 pe-2"></i>
                                    <span class="fw-bold">Comments</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-x-square-fill fs-4 pe-2"></i>
                                    <span class="fw-bold">Exit</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>