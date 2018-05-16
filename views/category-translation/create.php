<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogCategoryTranslation */

$this->title = Yii::t('app', 'Create Blog Category Translation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blog Category Translations')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-category-translation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
