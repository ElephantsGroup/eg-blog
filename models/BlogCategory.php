<?php

namespace elephantsGroup\blog\models;

use Yii;

/**
 * This is the model class for table "{{%eg_blog_category}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property integer $status
 *
 * @property Blog[] $egBlogs
 * @property BlogCategoryTranslation[] $egBlogCategoryTranslations
 */
class BlogCategory extends \yii\db\ActiveRecord
{
    public static $_STATUS_INACTIVE = 0;
    public static $_STATUS_ACTIVE = 1;

    public static $upload_url;
    public static $upload_path;

    public $logo_file;


    public function init()
    {
        self::$upload_url = str_replace('/admin', '', Yii::getAlias('@web')) . '/uploads/eg-blog/blog-category/';
        self::$upload_path = str_replace('/admin', '', Yii::getAlias('@webroot')) . '/uploads/eg-blog/blog-category/';
        parent::init();
    }

    public static function getStatus()
    {
        $module = \Yii::$app->getModule('base');
        return [
            self::$_STATUS_INACTIVE => $module::t('Inactive'),
            self::$_STATUS_ACTIVE => $module::t('Active'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eg_blog_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'logo'], 'trim'],
            [['status'], 'integer'],
            [['name', 'logo'], 'string', 'max' => 32],
            [['status'], 'default', 'value' => self::$_STATUS_INACTIVE],
            [['status'], 'in', 'range' => array_keys(self::getStatus())],
            [['logo_file'], 'file', 'extensions' => 'png, jpg', 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $module = \Yii::$app->getModule('base');
        return [
            'id' => $module::t('ID'),
            'name' => $module::t('Name'),
            'logo' => $module::t('Logo'),
            'status' => $module::t('Status'),
            'title' => $module::t('Title'),
        ];
    }

    public function getTitle()
    {
        $module = \Yii::$app->getModule('base');
        $value = $module::t('Undefined');
        $translate = BlogCategoryTranslation::findOne(['cat_id'=>$this->id, 'language'=>Yii::$app->language]);
        if($translate)
            $value = $translate->title;
        return $value;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogs()
    {
        return $this->hasMany(Blog::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(BlogCategoryTranslation::className(), ['cat_id' => 'id']);
    }

    public function getTranslationByLang()
    {
        return $this->hasOne(BlogCategoryTranslation::className(), ['cat_id' => 'id'])->where('language = :language', [':language' => Yii::$app->controller->language]);
    }


    public function afterSave($insert, $changedAttributes)
    {
        if($this->logo_file)
        {
            $dir = self::$upload_path . $this->id . '/';
            if(!file_exists($dir))
            mkdir($dir, 0777, true);
            $file_name = 'Category' . $this->id . '-logo.' . $this->logo_file->extension;
            $this->logo_file->saveAs($dir . $file_name);
            $this->updateAttributes(['logo' => $file_name]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    public function beforeDelete()
    {
        foreach($this->translations as $translation)
            $translation->delete();

        if($this->logo != 'default.png')
        {
            $file_pah = self::$upload_url . $this->id . '/' . $this->logo;
            if(file_exists($file_pah))
                unlink($file_pah);
        }
        return parent::beforeDelete();
    }



    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    /*public static function find()
    {
        return new CategoryQuery(get_called_class());
    }*/
}
