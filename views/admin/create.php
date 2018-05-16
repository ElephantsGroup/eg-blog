<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\Blog */

$this->title = Yii::t('blog', 'Create Blog') . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = ['label' => Yii::t('blog', 'Blogs'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = Yii::t('blog', 'Create Blog');
?>
<div class="blog-create">

    <h1><?= Yii::t('blog', 'Create Blog') ?></h1>

    <?= $this->render('_form_create', [
        'model' => $model,
        'translation' => $translation,
    ]) ?>

</div>
