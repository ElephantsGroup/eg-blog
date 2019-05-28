<?php

use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogTranslation;
use backend\widgets\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$module_base = \Yii::$app->getModule('base');
/* @var $this yii\web\View */
/* @var $model elephantsGroup\blog\models\BlogTranslation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-translation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    if(!$model->isNewRecord)
        echo $form->field($model, 'language')->dropDownList($module_base->languages, ['prompt' => Yii::t('app', 'Select Languages ...')]);
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'intro')->textInput(); ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(),[
        // 'editorOptions' => [
        //     'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
        //     'inline' => false, //по умолчанию false
        //     'filebrowserImageBrowseUrl' => Yii::getAlias('@web') . '/kcfinder/browse.php?type=images',
        //     'filebrowserImageUploadUrl' => Yii::getAlias('@web') . '/kcfinder/upload.php?type=images',
        //     'filebrowserBrowseUrl' => Yii::getAlias('@web') . '/kcfinder/browse.php?type=files',
        //     'filebrowserUploadUrl' => Yii::getAlias('@web') . '/kcfinder/upload.php?type=files',
        // ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('blog', 'Create') : Yii::t('blog', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
