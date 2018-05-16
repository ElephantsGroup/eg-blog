<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogCategory */

$this->title = Yii::t('app', 'Update Blog Category') . ' ' . $model->name . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = ['label' => Yii::t('blog', 'Blog Categories'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id, 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('blog', 'Update');
?>
<div class="blog-category-update">

    <h1><?= Yii::t('blog', 'Update blog Category') . ' ' . $model->name ?></h1>

    <?php
    if($translation)
        echo
        $this->render('_form_update_translate', [
            'model' => $model,
            'translation' => $translation,
        ]);
    else
        echo
        $this->render('_form_update', [
            'model' => $model,
        ]);
    ?>

</div>
