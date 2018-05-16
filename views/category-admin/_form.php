<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model elephantsGroup\event\models\EventCategory */
/* @var $form yii\widgets\ActiveForm */
$module = \Yii::$app->getModule('event_cat');
?>

<div class="event-category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logo_file')->label()->fileInput() ?>

    <?= $form->field($model, 'status')->dropDownList(elephantsGroup\event\models\EventCategory::getStatus(), ['prompt' => Yii::t('app', 'Select Status')]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? $module::t('event_cat', 'Create') : $module::t('event_cat', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
