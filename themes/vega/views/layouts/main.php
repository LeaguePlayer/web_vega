<?php

$cs = Yii::app()->clientScript;
$assets_path = $this->getAssetsUrl();

$cs->registerCoreScript('jquery');

$cs->registerCssFile('http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic|Marmelad&subset=latin,cyrillic,cyrillic-ext');
$cs->registerCssFile($assets_path.'/vendor/fancybox/jquery.fancybox.css');
$cs->registerCssFile($assets_path.'/vendor/formstyler/jquery.formstyler.css');
$cs->registerCssFile($assets_path.'/css/reset.css');
$cs->registerCssFile($assets_path.'/css/style.css');

$cs->registerScriptFile($assets_path.'/vendor/fancybox/jquery.fancybox.pack.js', CClientScript::POS_END);
$cs->registerScriptFile($assets_path.'/vendor/formstyler/jquery.formstyler.min.js', CClientScript::POS_END);
$cs->registerScriptFile($assets_path.'/js/jquery.color.js', CClientScript::POS_END);
$cs->registerScriptFile($assets_path.'/js/script.js', CClientScript::POS_END);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?= $this->title ?></title>
</head>

<body>

<!-- Окно авторизации -->
<div class="auth_box">
    <h2>Авторизация</h2>
    <input type="text" id="auth_email" placeholder="e-mail">
    <input type="password" id="auth_password" placeholder="пароль">
    <a href="#" class="register">Зарегистрироваться</a><a href="#" class="remind">Напомнить пароль</a><a href="#" class="login orange_button">Вход</a>
</div>

<!-- Окно регистрации -->
<div class="register_box">
    <h2>Регистрация</h2>
    <form>
        <div class="registration">
            <div class="row">
                <label>Категория <span>*</span></label>
                <input type="radio" id="retail" name="category" checked><label class="check" for="retail">Розничный покупатель</label>
                <input type="radio" id="gross" name="category"><label class="check" for="gross">Оптовый покупатель</label>
            </div>
            <div class="row"><label for="surname">Ваша фамилия: </label><input type="text" class="input" id="surname"></div>
            <div class="row"><label for="name">Ваше имя: <span>*</span></label><input type="text" class="input" id="name"></div>
            <div class="row"><label for="email">Ваш e-mail: <span>*</span></label><input type="text" class="input" id="email"></div>
            <div class="row"><label for="password">Ваш пароль: <span>*</span></label><input type="password" class="input" id="password"></div>
            <div class="row"><label for="repassword">Повторите пароль: <span>*</span></label><input type="password" class="input" id="repassword"></div>
            <div class="row"><label for="phone">Номер телефона: <span>*</span></label><input type="text" class="input" id="phone">
                <p class="notice">Можно добавить позже в настройках аккаунта</p>
            </div>
            <div class="row">
                <label>Адрес доставки:</label>
                <div class="adress">
                    <div class="row"><label for="street">Улица:</label><input class="input" type="text" id="street"></div>
                    <label for="home">Дом:</label><input class="input adr" type="text" id="home">
                    <label for="housing">Корпус:</label><input class="input adr" type="text" id="housing">
                    <label for="flat" class="flat_lb">Квартира:</label><input class="input adr" type="text" id="flat">
                </div>
                <p class="notice">Можно добавить позже в настройках аккаунта</p>
            </div>
            <div class="row"><p class="required"><span>*</span> Поля, отмеченные звездочками, обязательны для заполнения</p><a href="" class="orange_button">Зарегистрироваться</a><p class="security">Сайт даёт гарантию на неразглашение
                    личной информации.</p></div>
        </div>
    </form>
</div>

<div class="register_box2" style="display: none">
    <h2>Регистрация</h2>
    <form class="registration">
        <div class="row">
            <label>Категория <span>*</span></label>
            <input type="radio" id="retail" name="category" checked><label class="check" for="retail">Розничный покупатель</label>
            <input type="radio" id="gross" name="category"><label class="check" for="gross">Оптовый покупатель</label>
        </div>
        <div class="row"><label for="company_name">Название организации: <span>*</span></label><input type="text" class="input" id="company_name"></div>
        <div class="row"><label for="">Юридический адрес: <span>*</span></label><input type="text" class="input" id=""></div>
        <div class="row"><label for="">Фактический адрес:</label><input type="text" class="input" id=""><p class="notice">Можно добавить позже в настройках аккаунта</p></div>
        <div class="row"><label for="">ИНН: <span>*</span></label><input type="text" class="input" id=""></div>
        <div class="row"><p class="contact_name">Контактное лицо:</p></div>
        <div class="row"><label for="">ФИО: <span>*</span></label><input type="text" class="input" id=""></div>
        <div class="row"><label for="">Номер телефона:</label><input type="text" class="input" id=""></div>
        <div class="row"><label for="">E-mail: <span>*</span></label><input type="text" class="input" id=""></div>
        <div class="row"><label for="">Пароль: <span>*</span></label><input type="text" class="input" id=""></div>
        <div class="row"><label for="">Повторите пароль: <span>*</span></label><input type="text" class="input" id="company_name"></div>
        <div class="row"><p class="required"><span>*</span> Поля, отмеченные звездочками, обязательны для заполнения</p><a href="" class="orange_button">Зарегистрироваться</a><p class="security">Сайт даёт гарантию на неразглашение
                личной информации.</p></div>
    </form>
</div>

<div class="width_1024">
    <a class="logo" href="/"></a>
    <div class="header_menu">
        <a class="opt current" href="#">Опт</a>
        <a class="roznica" href="">Розница</a>
        <a class="auth" href="auth_box">Авторизация ></a>
    </div>
    <div class="width_1024">
        <div class="content">
            <div class="content_top">
                <div class="navigation">
                    <a href="/" class="home<?= $this->is_home() ? ' opened' : '' ?>"></a>
                    <?php foreach ($this->categories as $category): ?>
                        <a <?= $category['active'] ? 'class="opened"' : '' ?> href="<?= $category['url'] ?>"><span><?= $category['label'] ?></span></a>
                    <?php endforeach ?>
                    <a href="cart.html" class="cart"><span class="total">0</span></a>
                </div>
            </div>

            <div class="content_body_wrap">
                <div class="shadow"></div>
                <div class="content_body">
                    <div class="search-box">
                        <form>
                            <input type="text" placeholder="Поиск..." class="search">
                            <button type="submit">Искать<img src="img/search.png"></button>
                        </form>
                    </div>

                    <?= $content ?>

                </div>
            </div>

            <div class="content_footer">
                <ul class="links">
                    <li><a href="#">О компании</a></li>
                    <li><a href="#">О компании</a></li>
                    <li><a href="#">О компании</a></li>
                </ul>
                <a class="social" href="#"><img src="img/social.png"></a>
                <a href="#" class="developer">Разработка -</a>
            </div>
        </div>
    </div>
</div>
<div class="bottom_shadow"></div>
</body>
</html>