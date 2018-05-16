<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\blog\models\BlogTranslation;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogCategoryTranslation */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('blog_cat', 'Blog Category Translations')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-category-translation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'cat_id' => $model->cat_id, 'language' => $model->language, 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'cat_id' => $model->cat_id, 'language' => $model->language, 'lang'=>Yii::$app->controller->language], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    $module_base = \Yii::$app->getModule('base');
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cat_id',
            [
                'attribute'  => 'language',
                'value'  => $module_base->languages[$model->language],
                //'filter' => Lookup::items('SubjectType'),
            ],
            'title',
        ],
    ]) ?>

</div>
