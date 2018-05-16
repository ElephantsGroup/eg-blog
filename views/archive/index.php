<?php
use elephantsGroup\blog\components\ArchivedBlog;
?>
<header>
    <div class="header-content">
        <div class="header-content-inner">
            <h1 id="homeHeading"><?= Yii::t('app', 'Blogs List')?></h1>
            <hr>
            <p><?= Yii::t('app', 'Blogs Description')?></p>
        </div>
    </div>
</header>

<div class="blog-default-index">
     <?= ArchivedBlog::widget() ?>
</div>
