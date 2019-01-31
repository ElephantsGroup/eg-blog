<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogCategory;
use elephantsGroup\jdf\Jdf;
use elephantsGroup\user\models\User;
use Yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\Blog */

$this->title = Yii::t('blog', 'Blog id') . ' ' . $model->id;
$translation = $model->translationByLang;
if($translation && $translation->title)
    $this->title = $translation->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$base = Yii::$app->getModule('base');
?>
<div class="blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a($base::t('Update'), ['update', 'id' => $model->id, 'lang'=>Yii::$app->controller->language], ['class' => 'btn btn-primary']) ?>
        <?= Html::a($base::t('Delete'), ['delete', 'id' => $model->id, 'redirectUrl' => Url::to([ '/blog/admin']), 'lang'=>Yii::$app->controller->language], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
<?=
  //TODO: chech use Jdf for first time
  Jdf::jdate('Y/m/d H:i:s', (new \DateTime())->getTimestamp(), '', 'Iran', 'en');
?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'  => 'category_id',
                'value'  => BlogCategory::findOne($model->category_id)->name,
                //'filter' => Lookup::items('SubjectType'),
            ],
            [
                'attribute' => 'thumb',
                'value' => Blog::$upload_url . $model->id . '/' . $model->thumb,
                'format' => ['image'],
            ],
            [
                'attribute'  => 'creation_time',
                'value'  => Jdf::jdate('Y/m/d H:i:s', (new \DateTime($model->creation_time))->getTimestamp(), '', 'Iran', 'en'),
                //'filter' => Lookup::items('SubjectType'),
            ],
            [
                'attribute'  => 'update_time',
                'value'  => Jdf::jdate('Y/m/d H:i:s', (new \DateTime($model->update_time))->getTimestamp(), '', 'Iran', 'en'),
                //'filter' => Lookup::items('SubjectType'),
            ],
            [
                'attribute'  => 'archive_time',
                'value'  => Jdf::jdate('Y/m/d H:i:s', (new \DateTime($model->archive_time))->getTimestamp(), '', 'Iran', 'en'),
                //'filter' => Lookup::items('SubjectType'),
            ],
            [
                'attribute'  => 'publish_time',
                'value'  => Jdf::jdate('Y/m/d H:i:s', (new \DateTime($model->publish_time))->getTimestamp(), '', 'Iran', 'en'),
                //'filter' => Lookup::items('SubjectType'),
            ],
            'views',
            [
                'attribute'  => 'author_id',
                'value'  => User::findOne($model->author_id)->username,
                //'filter' => Lookup::items('SubjectType'),
            ],
            [
                'attribute'  => 'status',
                'value'  => Blog::getStatus()[$model->status],
                //'filter' => Lookup::items('SubjectType'),
            ],
            //'views',
        ],
    ]) ?>

</div>
