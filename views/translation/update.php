<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogTranslation */

$this->title = Yii::t('app', 'Update Blog Translation') . ' : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('blog', 'Blog Translations')];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'blog_id' => $model->blog_id, 'language' => $model->language, 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('blog', 'Update');
?>
<div class="blog-translation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
