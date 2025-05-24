<?php

try {
    $sliders = fetchSlidersWithDetails($db);
    if (empty($sliders)) {
        flash('post_slider', 'fetch', 'not_found');
    }
} catch (PDOException $e) {
    flash('post_slider', 'fetch', 'error');
}

?>

<section class="slider-section">
    <?php display_flash_messages(); ?>

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
            <?php if (!empty($sliders)): ?>

                <?php foreach ($sliders as $slider): ?>
                    <div class="carousel-item <?= ($slider['is_active']) ? 'active' : ''; ?> ">
                        <img src="<?= BASE_URL ?>/uploads/posts/<?= $slider['post_image'] ?>" class="d-block w-100" alt="slider image">
                        <div class="carousel-caption d-none d-md-block ">
                            <h5 class=""><?= $slider['post_title'] ?></h5>
                            <p>
                                <?= substr($slider['post_body'], 0, 200) . "..." ?>
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