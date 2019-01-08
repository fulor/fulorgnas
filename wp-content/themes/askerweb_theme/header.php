<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?php bloginfo('name'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="robots" content="index, follow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="page">
    <div class="modal-wrapper">
        <div id="popup__form" class="modal-block">
            <div class="close__modal"></div>
            <form id="sendform" class="modal-form" name="mainForm">
                <input type="hidden" name="title" value="">
                <input type="hidden" name="model" value="">
                <input name="name" type="text" required placeholder="Ваше имя">
                <input name="phone" type="text" required placeholder="Ваш телефон">
                <button class="submit" type="submit">Отправить</button>
            </form>
        </div>
        <div id="thank" class="modal-block">
            <div class="close__modal"></div>
            <div class="block-messages">Спасибо! Наш менеджер свяжется<br/>с Вами в ближайшее время.</div>
        </div>
    </div>
</div>
<header class="site-header">
    <div class="container-wrapper">
        <div class="head-info">

        </div>
        <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'container_class' => 'primary-menu')); ?>
    </div>
</header>
<div id="main">