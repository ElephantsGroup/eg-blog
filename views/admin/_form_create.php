<?php
use elephantsGroup\blog\models\BlogCategory;
use elephantsGroup\blog\models\BlogCategoryTranslation;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\jDate;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use mihaildev\ckeditor\CKEditor;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">

	<?php
		$base_module = Yii::$app->getModule('base');
		$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
	?>

    <?= $form->field($model, 'category_id')->dropDownList(
			ArrayHelper::map(
				BlogCategory::find()
					->select(['id', BlogCategoryTranslation::tableName() . '.title AS title'])
					->joinWith('translations')
					->where(['language' => Yii::$app->controller->language])
					->all(),
				'id',
				function($array, $key){ return BlogCategoryTranslation::findOne(['cat_id'=>$array->id, 'language'=>Yii::$app->controller->language])->title; }
			)
		, ['prompt' => $base_module::t('Select Category ...')]
		)
	?>

    <?= $form->field($model, 'archive_time')->widget(jDate\DatePicker::className()) ?>

		<?= $form->field($model, 'archive_time_time')->label('')->widget(TimePicker::className(), ['value' => '12:00 AM', 'pluginOptions' => ['showSeconds' => true]]) ?>

		<?= $form->field($model, 'publish_time')->widget(jDate\DatePicker::className()) ?>

		<?= $form->field($model, 'publish_time_time')->label('')->widget(TimePicker::className(), ['value' => '12:00 AM', 'pluginOptions' => ['showSeconds' => true]]) ?>

    <?= $form->field($model, 'thumb_file')->label('')->fileInput() ?>

    <?= $form->field($translation, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($translation, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($translation, 'intro')->textInput(); ?>

    <?= $form->field($translation, 'description')->widget(CKEditor::className(),[
		'editorOptions' => [
			'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
			'inline' => false, //по умолчанию false
			'filebrowserImageBrowseUrl' => Yii::getAlias('@web') . '/kcfinder/browse.php?type=images',
			'filebrowserImageUploadUrl' => Yii::getAlias('@web') . '/kcfinder/upload.php?type=images',
			'filebrowserBrowseUrl' => Yii::getAlias('@web') . '/kcfinder/browse.php?type=files',
			'filebrowserUploadUrl' => Yii::getAlias('@web') . '/kcfinder/upload.php?type=files',
		],
	]);
	?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('blog', 'Create') : Yii::t('blog', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
