<?php
$full_slider = get_theme_option('slider_home');
?>
<section class="section-slider">
    <div class="container-wrapper">
        <div class="top-slider">
            <?php foreach ($full_slider as $item):?>
            <div class="slide-item" data-desktop="<?= $item['full_slider']?>" data-mobile="<?= $item['full_slider_mobile']?>">

            </div>
            <?php endforeach?>
        </div>
        <div class="nav-topslider">
            <a href="#" class="prev"></a>
            <a href="#" class="next"></a>
        </div>
    </div>
</section>
