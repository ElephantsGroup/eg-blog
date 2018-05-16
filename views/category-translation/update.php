<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogCategoryTranslation */

$this->title = Yii::t('app', 'Update Blog Category Translation') . ' : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blog Category Translations')];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'cat_id' => $model->cat_id, 'language' => $model->language]];
$this->params['breadcrumbs'][] = ['label' => $model->title];
$this->params['breadcrumbs'][] = Yii::t('blog_cat', 'Update');
?>
<div class="blog-category-translation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
