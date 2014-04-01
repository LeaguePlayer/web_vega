
<div class="categories_box">
    <?php foreach ( $this->categories as $category ): ?>
        <div class="category">
            <? $image_url = $category['icon'] ? $category['icon'] : $this->getAssetsUrl().'/img/no_photo.gif' ?>
            <?= CHtml::image($image_url) ?>
            <p><?= $category['label'] ?></p>
            <a href="<?= $category['url'] ?>"></a>
        </div>
    <?php endforeach ?>
</div>


<div class="brands">
    <img src="img/brands/logo1.png">
    <img src="img/brands/logo2.png">
    <img src="img/brands/logo3.png">
    <img src="img/brands/logo4.png">
    <img src="img/brands/logo5.png">
    <img src="img/brands/logo6.png">
    <img src="img/brands/logo7.png">
    <img src="img/brands/logo8.png">
    <img src="img/brands/logo9.png">
</div>