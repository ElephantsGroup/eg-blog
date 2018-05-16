<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogTranslation;
use elephantsGroup\blog\models\BlogCategory;
use elephantsGroup\blog\models\BlogCategoryTranslation;
use elephantsGroup\jdf\Jdf;
use elephantsGroup\user\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel elephantsGroup\blog\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('blog', 'Blog List') . ' - ' . Yii::t('config', 'Company Name') . ' - ' . Yii::t('config', 'description');
$this->params['breadcrumbs'][] = Yii::t('blog', 'Blog List');
?>
<div class="blog-index">

    <h1><?= Yii::t('blog', 'Blog List') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('blog', 'Create Blog'), ['create', 'lang' => Yii::$app->controller->language], ['class' => 'btn btn-success']) ?>
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
            'value' => function ($model) use($item) {
                return (
                BlogTranslation::findOne(['blog_id' => $model->id, 'language'=> $item])
                    ? Html::a(Yii::t('blog', 'Edit'), ['/blog/translation/update', 'blog_id'=>$model->id, 'language' => $item , 'lang'=>Yii::$app->controller->language]) .
                    ' / ' . Html::a(Yii::t('blog', 'Delete'), ['/blog/translation/delete', 'blog_id'=>$model->id, 'language' => $item, 'lang'=>Yii::$app->controller->language])
                    : Html::a(Yii::t('blog', 'Create'), ['/blog/translation/create', 'blog_id'=>$model->id, 'language'=> $item, 'lang'=>Yii::$app->controller->language])
                );
            },
        ];
    }
    $columns = [
        ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'filter' => ArrayHelper::map(
                    BlogCategory::find()
                        ->select(['id', BlogCategoryTranslation::tableName() . '.title AS title'])
                        ->joinWith('translations')
                        ->where(['language' => Yii::$app->controller->language])
                        ->all(),
                    'id',
                    function($array, $key){ return BlogCategoryTranslation::findOne(['cat_id'=>$array->id, 'language'=>Yii::$app->controller->language])->title; }
                ),
                'value' => function ($model) {
                    $cat = BlogCategory::findOne($model->category_id);
                    $value = $cat->name;
                    $translate = BlogCategoryTranslation::findOne(['cat_id'=>$model->category_id, 'language'=>Yii::$app->language]);
                    if($translate)
                        $value = $translate->title;
                    return $value;
                },
            ],
            //'update_time',
            //'creation_time',
            //'archive_time',
            'views',
            'title',
            [
                'attribute' => 'author_id',
                'format' => 'raw',
                'filter' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
                //'label' => Yii::t('user', 'Role'),
                //'sortable' => true,
                'value' => function ($model) { return User::findOne($model->author_id)->username; },
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => Blog::getStatus(),
                //'label' => Yii::t('user', 'Role'),
                //'sortable' => true,
                'value' => function ($model) { return Blog::getStatus()[$model->status]; },
            ],
            [
                'class' => 'yii\grid\ActionColumn',

            ]
        ];

    array_splice($columns,7,0,$columns_d);

//    $action_columns = [
//        'class' => 'yii\grid\ActionColumn',
//        //'template' => '{view} {update} {delete}',
//        'buttons' => [
//            'view' => function ($url, $model)
//            {
//                $label = '<span class="glyphicon glyphicon-eye-open"></span>';
//                $url = ['/blog/admin/view', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
//                return Html::a($label, $url);
//            },
//            'update' => function ($url, $model)
//            {
//                $label = '<span class="glyphicon glyphicon-pencil"></span>';
//                $url = ['/blog/admin/update', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
//                return Html::a($label, $url);
//            },
//            'delete' => function ($url, $model)
//            {
//                $label = '<span class="glyphicon glyphicon-trash"></span>';
//                $url = ['/blog/admin/delete', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
//                $options = [
//                    'title' => Yii::t('yii', 'Delete'),
//                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
//                    'data-method' => 'post'
//                ];
//                return Html::a($label, $url, $options);
//            },
//        ],
//    ];
//
//    array_splice($columns,10,0,$action_columns);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
