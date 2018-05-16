<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\blog\models\BlogCategory;
use elephantsGroup\blog\models\BlogCategoryTranslation;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogCategory */

$this->title = $model->name;
$translation = $model->translationByLang;
if($translation && $translation->title)
    $this->title = $translation->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('blog', 'Blog Categories'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = $this->title;

$base = Yii::$app->getModule('base');
?>
<div class="blog-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a($base::t('Update'), ['update', 'id' => $model->id, 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-primary']) ?>
        <?= Html::a($base::t('Delete'), ['delete', 'id' => $model->id, 'lang'=>Yii::$app->controller->language], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'logo',
                'value' => BlogCategory::$upload_url . $model->id . '/' . $model->logo,
                'format' => ['image'],
            ],
            [
                'attribute' => 'status',
                'value' => BlogCategory::getStatus()[$model->status],
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
