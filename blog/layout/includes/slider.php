<?php
$query = "SELECT * FROM posts_slider";
$sliders = $db->query($query);
?>

<section class="slider-section">
    <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <?php if ($sliders->rowCount() > 0): ?>
                <?php foreach ($sliders as $slider): ?>
                    <?php
                    $postId = $slider['post_id'];
                    $post = $db->query("SELECT * FROM posts WHERE id=$postId")->fetch();
                    ?>
                    <div class="carousel-item <?= ($slider['is_active']) ? 'active' : ''; ?> ">
                        <img src="<?= BASE_URL ?>/uploads/posts/<?= $post['image'] ?>" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block ">
                            <h5 class=""><?= $post['title'] ?></h5>
                            <p>
                                <?= substr($post['body'], 0, 200) . "..." ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>