<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogCategory */

$this->title = Yii::t('blog', 'Create Blog Category') . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = ['label' => Yii::t('blog', 'Blog Categories'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('blog', 'Create Blog');

?>
<div class="blog-category-create">

    <h1><?= Yii::t('blog', 'Create Blog Category') ?></h1>

    <?= $this->render('_form_create', [
        'model' => $model,
        'translation' => $translation,
    ]) ?>

</div>
