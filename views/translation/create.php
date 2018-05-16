<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogTranslation */

$this->title = Yii::t('app', 'Create Blog Translation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('blog', 'Blog Translations')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-translation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
