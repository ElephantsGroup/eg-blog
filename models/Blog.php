<?php

namespace elephantsGroup\blog\models;

use Yii;

/**
 * This is the model class for table "{{%eg_blog}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $update_time
 * @property string $creation_time
 * @property string $archive_time
 * @property integer $views
 * @property string $thumb
 * @property integer $author_id
 * @property integer $status
 *
 * @property User $author
 * @property BlogCategory $category
 * @property BlogTranslation[] $egBlogTranslations
 */
class Blog extends \yii\db\ActiveRecord
{
    public $thumb_file;
    public $archive_time_time;

    public static $_STATUS_SUBMITTED = 0;
    public static $_STATUS_CONFIRMED = 1;
    public static $_STATUS_REJECTED = 2;
    public static $_STATUS_ARCHIVED = 3;

    public static $upload_url;
    public static $upload_path;


    public function init()
    {
        self::$upload_url = str_replace('/backend', '', Yii::getAlias('@web')) . '/uploads/eg-blog/blog';
        self::$upload_path = str_replace('/backend', '', Yii::getAlias('@webroot')) . '/uploads/eg-blog/blog';
        parent::init();
    }

    public static function getStatus()
    {
        $module = \Yii::$app->getModule('base');
        return [
            self::$_STATUS_SUBMITTED => $module::t('Submitted'),
            self::$_STATUS_CONFIRMED => $module::t('Confirmed'),
            self::$_STATUS_REJECTED => $module::t('Rejected'),
            self::$_STATUS_ARCHIVED => $module::t('Archived'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eg_blog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'views', 'author_id', 'status'], 'integer'],
            [['update_time', 'creation_time', 'archive_time'], 'date', 'format'=>'php:Y-m-d H:i:s'],
            [['thumb'], 'trim'],
            [['thumb'], 'string', 'max' => 15],
            [['update_time'], 'default', 'value' => (new \DateTime)->setTimestamp(time())->setTimezone(new \DateTimeZone('Iran'))->format('Y-m-d H:i:s')],
            [['creation_time'], 'default', 'value' => (new \DateTime)->setTimestamp(time())->setTimezone(new \DateTimeZone('Iran'))->format('Y-m-d H:i:s')],
            [['views'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::$_STATUS_SUBMITTED],
            [['archive_time_time'], 'string', 'max' => 11],
            [['thumb'], 'default', 'value' => 'default.png'],
            [['status'], 'in', 'range' => array_keys(self::getStatus())],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BlogCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['thumb_file'], 'file', 'extensions' => 'png, jpg', 'checkExtensionByMimeType' => false],
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
            'category_id' => $module::t('Category ID'),
            'update_time' => $module::t('Update Time'),
            'creation_time' => $module::t('Creation Time'),
            'archive_time' => $module::t('Archive Time'),
            'views' => $module::t('Views'),
            'thumb' => $module::t('Thumbnail'),
            'author_id' => $module::t('Author ID'),
            'status' => $module::t('Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTitle()
    {
        $module = \Yii::$app->getModule('news');
        $value = $module::t('news', 'Undefined');
        $translate = BlogTranslation::findOne(['blog_id'=>$this->id, 'language'=>Yii::$app->language]);
        if($translate)
            $value = $translate->title;
        return $value;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(BlogCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(BlogTranslation::className(), ['blog_id' => 'id']);
    }

    public function getTranslationByLang()
    {
        return $this->hasOne(BlogTranslation::className(), ['blog_id' => 'id'])->where('language = :language', [':language' => Yii::$app->controller->language]);
    }

    public function beforeSave($insert)
    {
        $date = new \DateTime();
        $date->setTimestamp(time());
        $date->setTimezone(new \DateTimezone('Iran'));
        $this->update_time = $date->format('Y-m-d H:i:s');
        if($this->isNewRecord)
            $this->creation_time = $date->format('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if($this->thumb_file)
        {
            $dir = self::$upload_path . $this->id . '/';
            if(!file_exists($dir))
                mkdir($dir, 0777, true);
            $file_name = 'blog-' . $this->id . '.' . $this->thumb_file->extension;
            $this->thumb_file->saveAs($dir . $file_name);
            $this->updateAttributes(['thumb' => $file_name]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete()
    {
        foreach($this->translations as $translations)
            $translations->delete();

        if($this->thumb != 'default.png')
        {
            $file_path = self::$upload_path . $this->id . '/' . $this->thumb;
            if(file_exists($file_path))
                unlink($file_path);
        }
        return parent::beforeDelete();
    }

}
