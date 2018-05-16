<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\blog\models\BlogTranslation;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogTranslation */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('blog', 'Blog Translations')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-translation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'blog_id' => $model->blog_id, 'language' => $model->language, 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'blog_id' => $model->blog_id, 'language' => $model->language, 'lang'=>Yii::$app->controller->language], [
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
            'blog_id',
            [
                'attribute'  => 'language',
                'value'  => $module_base->Languages[$model->language],
                //'filter' => Lookup::items('SubjectType'),
            ],
            'title',
            'subtitle',
            'intro:ntext',
            'description:ntext',
        ],
    ]) ?>

</div>
