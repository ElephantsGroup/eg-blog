<?php

namespace elephantsGroup\blog\models;

use Yii;

/**
 * This is the model class for table "{{%eg_blog_category_translation}}".
 *
 * @property integer $cat_id
 * @property string $language
 * @property string $title
 *
 * @property BlogCategory $cat
 */
class BlogCategoryTranslation extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%eg_blog_category_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $module_base = \Yii::$app->getModule('base');
        return [
            [['cat_id', 'language'], 'required'],
            [['cat_id'], 'integer'],
            [['language'], 'default', 'value' => Yii::$app->language],
            [['language'], 'in', 'range' => array_keys($module_base->Languages)],
            [['language'], 'string', 'max' => 5],
            [['title'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $module = \Yii::$app->getModule('base');
        return [
            'cat_id' => $module::t('Category ID'),
            'language' => $module::t('Language'),
            'title' => $module::t('Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(BlogCategory::className(), ['id' => 'cat_id']);
    }
}
