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
$module = \Yii::$app->getModule('blog');
$this->title = Yii::t('blog', 'Blog List') . ' - ' . $module::t('config', 'Company Name') . ' - ' . $module::t('config', 'description');
$this->params['breadcrumbs'][] = $module::t('blog', 'Blog List');
?>
<div class="blog-index">

    <h1><?= $module::t('blog', 'Blog List') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a($module::t('blog', 'Create Blog'), ['create', 'lang' => Yii::$app->controller->language], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    $module = \Yii::$app->getModule('blog');
    $module_base = \Yii::$app->getModule('base');
    $columns_d = [];
    $language = array_keys($module_base->languages);

    foreach ($language as $item)
    {
        $columns_d [] = [
            'format' => 'raw',
            'label' => $module_base::t($item, 'coding'),
            'value' => function ($model) use($module, $module_base, $item) {
                return (
                BlogTranslation::findOne(['blog_id' => $model->id, 'language'=> $item])
                    ? Html::a(Yii::t('blog', 'Edit'), ['/blog/translation/update', 'blog_id'=>$model->id, 'language' => $item , 'lang'=>Yii::$app->controller->language, 'redirectUrl'=> Yii::$app->request->url]) .
                    ' / ' . Html::a(Yii::t('blog', 'Delete'), ['/blog/translation/delete', 'blog_id'=>$model->id, 'language' => $item, 'lang'=>Yii::$app->controller->language, 'redirectUrl'=> Yii::$app->request->url])
                    : Html::a(Yii::t('blog', 'Create'), ['/blog/translation/create', 'blog_id'=>$model->id, 'language'=> $item, 'lang'=>Yii::$app->controller->language, 'redirectUrl'=> Yii::$app->request->url])
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
              'format' => 'raw',
              'label' => Yii::t('blog', 'Change Status'),
              'value' => function ($model) use($module)  {
                  if ( $model->status == Blog::$_STATUS_SUBMITTED)
                  {
                    return (Html::a($module::t('blog', 'Confirm'), ['/blog/admin/confirm', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]) .
                    '/' . Html::a($module::t('blog', 'Reject'), ['/blog/admin/reject', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]));
                  }
                  elseif ($model->status == Blog::$_STATUS_CONFIRMED)
                  {
                    return(Html::a($module::t('blog', 'Reject'), ['/blog/admin/reject', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]) .
                    '/' . Html::a($module::t('blog', 'Archive'), ['/blog/admin/archive', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]));
                  }
                  elseif ($model->status == Blog::$_STATUS_REJECTED)
                  {
                    return (Html::a($module::t('blog', 'Confirm'), ['/blog/admin/confirm', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]) .
                    '/' . Html::a($module::t('blog', 'Archive'), ['/blog/admin/archive', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]));
                  }
                  else
                  {
                    return (Html::a($module::t('blog', 'Confirm'), ['/blog/admin/confirm', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]) .
                    '/' . Html::a($module::t('blog', 'Reject'), ['/blog/admin/reject', 'id'=>$model->id, 'redirectUrl'=> Yii::$app->request->url]));
                  }
              },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
          				'view' => function ($url, $model)
          				{
          					$label = '<span class="glyphicon glyphicon-eye-open"></span>';
          					$url = ['/blog/admin/view', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
          					return Html::a($label, $url);
          				},
          				'update' => function ($url, $model)
          				{
          					$label = '<span class="glyphicon glyphicon-pencil"></span>';
          					$url = ['/blog/admin/update', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language];
          					return Html::a($label, $url);
          				},
          				'delete' => function ($url, $model)
          				{
          					$label = '<span class="glyphicon glyphicon-trash"></span>';
          					$url = ['/blog/admin/delete', 'id'=>$model->id, 'lang'=>Yii::$app->controller->language, 'redirectUrl'=> Yii::$app->request->url];
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

    array_splice($columns, 7,0 ,$columns_d);

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
