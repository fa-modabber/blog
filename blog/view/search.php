<?php
require_once 'C:/xampp/htdocs/weblog-project/init.php';

include(BASE_PATH . "/blog/view/includes/header.php");
include(BASE_PATH . "/blog/view/includes/navbar.php");
include(BASE_PATH . "/blog/view/includes/slider.php");

$formErrors=[];
$searchKeyword = $_GET['search'] ?? null;
if (!empty(trim($searchKeyword))) {
    try {
        $posts = searchInPosts($db, $searchKeyword);
        if (empty($posts)) {
            $formErrors['search_not_found'] = "no post found for search expression {$searchKeyword}";
        }
    } catch (PDOException $e) {
        flash('post','fetch','error');
    }
} else {
    $formErrors['no_search_term'] = "Please enter a search term.";
}

?>

<section class="content mt-4">
    <div class="row">
        <div class="col-lg-8">
                        <?php display_flash_messages(); ?>

            <?php if (isset($formErrors['no_search_term'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $formErrors['no_search_term'] ?>
                </div>
            <?php else: ?>
                <div class="alert alert-primary" role="alert">
                    Search Result for Posts with [<?= htmlspecialchars($searchKeyword) ?>]
                </div>
                <?php if (isset($formErrors['search_not_found'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $formErrors['search_not_found'] ?>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <?php foreach ($posts as $post): ?>
                            <?php
                            $userFullName = $post['first_name'] . " " . $post['last_name'];
                            ?>
                            <div class="col">
                                <div class="card">
                                    <img src="<?= BASE_URL ?>/uploads/posts/<?= $post['image'] ?>" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title">
                                                <?= $post['title'] ?>
                                            </h5>
                                            <div><span class="badge text-bg-secondary">
                                                    <?= $post['category_title'] ?>
                                                </span></div>
                                        </div>
                                        <p class="card-text">
                                            <?= substr($post['body'], 0, 200) . "..." ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="<?= BASE_URL ?>/blog/single-post.php?id=<?= $post['id'] ?>" class="btn btn-dark">view</a>
                                            <p class="mb-0">writer: <?= $userFullName ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- sidebar -->
        <?php
        include(BASE_PATH . "/blog/view/includes/sidebar.php");
        ?>
    </div>
</section>

<?php
include(BASE_PATH . "/blog/view/includes/footer.php");

?>