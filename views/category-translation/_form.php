<?php

use elephantsGroup\blog\models\BlogCategory;
use elephantsGroup\blog\models\BlogTranslation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogCategoryTranslation */
/* @var $form yii\widgets\ActiveForm */
$module_base = \Yii::$app->getModule('base');
?>

<div class="blog-category-translation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    if(!$model->isNewRecord)
        echo $form->field($model, 'language')->dropDownList($module_base->languages, ['prompt' => Yii::t('blog', 'Select Languages ...')]);
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('blog_cat', 'Create') : Yii::t('blog_cat', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
