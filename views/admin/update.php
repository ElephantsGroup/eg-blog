<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\Blog */

$this->title = Yii::t('app', 'Update Blog') . ' # ' . $model->id . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = ['label' => Yii::t('blog', 'Blogs'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('blog', 'Update');
?>
<div class="blog-update">

    <h1><?= Yii::t('blog', 'Update Blog') . ' #' . $model->id ?></h1>

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
