<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use elephantsGroup\blog\models\BlogCategory;
use elephantsGroup\blog\models\BlogCategoryTranslation;
/* @var $this yii\web\View */
/* @var $searchModel elephantsGroup\blog\models\BlogCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('blog', 'Blog Categories') .  ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = Yii::t('blog', 'Blog Category');
?>
<div class="blog-category-index">

    <h1><?= Yii::t('blog', 'Blog Categories'); ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('blog', 'Create Blog Category'), ['create', 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-success']) ?>
    </p>

    <?php

    $module_base = \Yii::$app->getModule('base');
    $columns_d = [];
    $language = array_keys($module_base->languages);

    foreach ($language as $item)
    {
        $columns_d [] = [
            'format' => 'raw',
            'label' => $module_base::t($item, 'coding'),
            'value' => function ($model) use($item)  {
                return (
                BlogCategoryTranslation::findOne(['cat_id' => $model->id, 'language'=> $item])
                    ? Html::a(Yii::t('blog', 'Edit'), ['/blog/category-translation/update', 'cat_id'=>$model->id, 'language' => $item , 'lang'=>Yii::$app->controller->language]) .
                    ' / ' . Html::a(Yii::t('blog', 'Delete'), ['/blog/category-translation/delete', 'cat_id'=>$model->id, 'language' => $item, 'lang'=>Yii::$app->controller->language])
                    : Html::a(Yii::t('blog', 'Create'), ['/blog/category-translation/create', 'cat_id'=>$model->id, 'language'=> $item, 'lang'=>Yii::$app->controller->language])
                );
            },
        ];

    }

    $columns = [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'name',
        'title',
        [
            'attribute' => 'status',
            'format' => 'raw',
            //'label' => Yii::t('user', 'Role'),
            'filter' => BlogCategory::getStatus(),
            //'sortable' => true,
            'value' => function ($model) { return BlogCategory::getStatus()[$model->status]; },
        ],
        [
        'class' => 'yii\grid\ActionColumn',
        //'template' => '{view} {update} {delete}',
        'buttons' => [
            'view' => function ($url, $model)
            {
                $label = '<span class="glyphicon glyphicon-eye-open"></span>';
                $url = ['/blog/category-admin/view', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
                return Html::a($label, $url);
            },
            'update' => function ($url, $model)
            {
                $label = '<span class="glyphicon glyphicon-pencil"></span>';
                $url = ['/blog/category-admin/update', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
                return Html::a($label, $url);
            },
            'delete' => function ($url, $model)
            {
                $label = '<span class="glyphicon glyphicon-trash"></span>';
                $url = ['/blog/category-admin/delete', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
                $options = [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post'
                ];
                return Html::a($label, $url, $options);
            },
        ],
    ],
    ];

    array_splice($columns,5,0,$columns_d);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns
    ]); ?>
</div>
