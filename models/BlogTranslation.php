<?php

namespace elephantsGroup\blog\models;

use Yii;

/**
 * This is the model class for table "{{%eg_blog_translation}}".
 *
 * @property integer $blog_id
 * @property integer $version
 * @property string $language
 * @property string $title
 * @property string $subtitle
 * @property string $intro
 * @property string $description
 *
 * @property Blog $blog
 */
class BlogTranslation extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%eg_blog_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $module_base = \Yii::$app->getModule('base');
        return [
            [['blog_id', 'version', 'language'], 'required'],
            [['blog_id', 'version'], 'integer'],
            [['language', 'title', 'subtitle', 'intro', 'description'], 'trim'],
            [['intro', 'description'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['language'], 'default', 'value' => Yii::$app->language],
            [['language'], 'in', 'range' => array_keys($module_base->languages)],
            [['title', 'subtitle'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $module_base = \Yii::$app->getModule('base');
        $module_blog = \Yii::$app->getModule('blog');
        return [
            'blog_id' => $module_blog::t('blog', 'Blog ID'),
            'version' => $module_blog::t('blog', 'Version'),
            'language' => $module_base::t('Language'),
            'title' => $module_base::t('Title'),
            'subtitle' => $module_base::t('Subtitle'),
            'intro' => $module_base::t('Intro'),
            'description' => $module_base::t('Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlog()
    {
        return $this->hasOne(Blog::className(), ['id' => 'blog_id', 'version' => 'version']);
    }
}
