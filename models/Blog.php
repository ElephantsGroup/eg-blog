<?php

namespace elephantsGroup\blog\models;

use Yii;
use yii\db\ActiveQuery;
use Grafika\Grafika;

/**
 * This is the model class for table "{{%eg_blog}}".
 *
 * @property integer $id
 * @property integer $version
 * @property integer $category_id
 * @property string $update_time
 * @property string $creation_time
 * @property string $archive_time
 * @property string $publish_time
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
    public $publish_time_time;

    public static $_STATUS_SUBMITTED = 0;
    public static $_STATUS_CONFIRMED = 1;
    public static $_STATUS_REJECTED = 2;
    public static $_STATUS_ARCHIVED = 3;
    public static $_STATUS_EDITED = 4;

    public static $upload_url;
    public static $upload_path;

    public $thumb_size = [];

    public function init()
    {
        $module = \Yii::$app->getModule('blog');
        self::$upload_url = str_replace('/admin', '', Yii::getAlias('@web')) . '/uploads/eg-blog/blog';
        self::$upload_path = str_replace('/admin', '', Yii::getAlias('@webroot')) . '/uploads/eg-blog/blog';
        if(!isset($module->thumbSize))
        {
            $this->thumb_size = [
                'icon' => [
                    'name' => $module->thumbIconName,
                    'width' => $module->thumbIconWidth,
                    'height' => $module->thumbIconHeight
                ],
                'larg' => [
                    'name' => $module->thumbLargName,
                    'width' => $module->thumbLargWidth,
                    'height' => $module->thumbLargHeight
                ],
                'medium' => [
                    'name' => $module->thumbMediumName,
                    'width' => $module->thumbMediumWidth,
                    'height' => $module->thumbMediumHeight
                ],
            ];
        }
        else
        {
            $this->thumb_size = $module->thumbSize;

            if(!isset($this->thumb_size['icon']))
            {
                $this->thumb_size['icon'] = [
                    'name' => $module->thumbIconName,
                    'width' => $module->thumbIconWidth,
                    'height' => $module->thumbIconHeight
                ];
            }
            else
            {
                if(!isset($this->thumb_size['icon']['name']))
                    $this->thumb_size['icon']['name'] = $module->thumbIconName;

                if(!isset($this->thumb_size['icon']['width']))
                    $this->thumb_size['icon']['width'] = $module->thumbIconWidth;

                if(!isset($this->thumb_size['icon']['height']))
                    $this->thumb_size['icon']['height'] = $module->thumbIconHeight;
            }

            if(!isset($this->thumb_size['larg']))
            {
                $this->thumb_size['larg'] = [
                    'name' => $module->thumbLargName,
                    'width' => $module->thumbLargWidth,
                    'height' => $module->thumbLargHeight
                ];
            }
            else
            {
                if(!isset($this->thumb_size['larg']['name']))
                    $this->thumb_size['larg']['name'] = $module->thumbLargName;

                if(!isset($this->thumb_size['larg']['width']))
                    $this->thumb_size['larg']['width'] = $module->thumbLargWidth;

                if(!isset($this->thumb_size['larg']['height']))
                    $this->thumb_size['larg']['height'] = $module->thumbLargHeight;
            }

            if(!isset($this->thumb_size['medium']))
            {
                $this->thumb_size['medium'] = [
                    'name' => $module->thumbMediumName,
                    'width' => $module->thumbMediumWidth,
                    'height' => $module->thumbMediumHeight
                ];
            }
            else
            {
                if(!isset($this->thumb_size['medium']['name']))
                    $this->thumb_size['medium']['name'] = $module->thumbMediumName;

                if(!isset($this->thumb_size['medium']['width']))
                    $this->thumb_size['medium']['width'] = $module->thumbMediumWidth;

                if(!isset($this->thumb_size['medium']['height']))
                    $this->thumb_size['medium']['height'] = $module->thumbMediumHeight;
            }
        }

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
            self::$_STATUS_EDITED => $module::t('Edited'),
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
            [['version', 'category_id', 'views', 'author_id', 'status'], 'integer'],
            [['update_time', 'creation_time', 'archive_time', 'publish_time'], 'date', 'format'=>'php:Y-m-d H:i:s'],
            [['thumb'], 'trim'],
            [['id', 'version', 'category_id'], 'required'],
            [['thumb'], 'string', 'max' => 15],
            [['update_time'], 'default', 'value' => (new \DateTime)->setTimestamp(time())->setTimezone(new \DateTimeZone('Iran'))->format('Y-m-d H:i:s')],
            [['creation_time'], 'default', 'value' => (new \DateTime)->setTimestamp(time())->setTimezone(new \DateTimeZone('Iran'))->format('Y-m-d H:i:s')],
            [['publish_time'], 'default', 'value' => (new \DateTime)->setTimestamp(time())->setTimezone(new \DateTimeZone('Iran'))->format('Y-m-d H:i:s')],
            [['views'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::$_STATUS_SUBMITTED],
            [['archive_time_time', 'publish_time_time'], 'string', 'max' => 11],
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
            'version' => $module::t('Version'),
            'category_id' => $module::t('Category ID'),
            'update_time' => $module::t('Update Time'),
            'creation_time' => $module::t('Creation Time'),
            'archive_time' => $module::t('Archive Time'),
            'publish_time' => $module::t('Publish Time'),
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
  		$module = \Yii::$app->getModule('blog');
  		$value = $module::t('blog', 'Undefined');
  		$max_version_translation = BlogTranslation::find()->where(['blog_id' => $this->id, 'language'=>Yii::$app->language])->max('version');
  		$translate = BlogTranslation::findOne(['blog_id'=>$this->id, 'language'=>Yii::$app->language, 'version' => $max_version_translation]);
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
        return $this->hasMany(BlogTranslation::className(), ['blog_id' => 'id', 'version' => 'version']);
    }

    public function getTranslationByLang()
    {
        return $this->hasOne(BlogTranslation::className(), ['blog_id' => 'id'])->where('language = :language', [':language' => Yii::$app->controller->language])->orderBy(['version'=>SORT_DESC]);
    }

    public static function find()
    {
      return new BlogQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        $date = new \DateTime();
        $date->setTimestamp(time());
        $date->setTimezone(new \DateTimezone('Iran'));
        $this->update_time = $date->format('Y-m-d H:i:s');
        if($this->isNewRecord)
            $this->creation_time = $date->format('Y-m-d H:i:s');
        if($this->publish_time == null && empty($this->publish_time))
            $this->publish_time = $date->format('Y-m-d H:i:s');
        if($this->archive_time == null && empty($this->archive_time))
            $this->archive_time = $date->format('Y-m-d H:i:s');

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

            $editor = Grafika::createEditor();
            $editor->open( $image, self::$upload_path . $this->id . '/' . $this->thumb);
            $backup = clone $image;
            $image_center = clone $image;

            $width = $image->getWidth();
            $height = $image->getHeight();

            $size = $width > $height ? $height : $width;

            $editor->crop( $image_center, $size, $size, 'center' );
            $editor->save( $image_center, self::$upload_path . $this->id . '/cropped-center.jpg' ); // Cropped version
            $image = [];

            foreach ($this->thumb_size as $key => $value)
            {
                $image[$key] = clone $image_center;
                $editor->resizeExact( $image[$key], $value['width'], $value['height'] );
                $editor->save( $image[$key], self::$upload_path . $this->id . '/' . $value['name']);

            }

            $editor->save( $backup, self::$upload_path . $this->id . '/original.jpg' ); // Unaffected by crop version
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

            $file_path_center = self::$upload_path . $this->id . '/cropped-center.jpg';
            if(file_exists($file_path_center))
                unlink($file_path_center);

            $file_path_original = self::$upload_path . $this->id . '/original.jpg';
            if(file_exists($file_path_original))
            unlink($file_path_original);

            foreach ($this->thumb_size as $key => $value)
            {
                $thumb_path = self::$upload_path . $this->id . '/'. $value['name'];
                if(file_exists($thumb_path))
                    unlink($thumb_path);
            }
        }
        return parent::beforeDelete();
    }

    public function getCanBeConfirmed()
    {
      return (($this->status == self::$_STATUS_SUBMITTED || $this->status == self::$_STATUS_ARCHIVED || $this->status == self::$_STATUS_REJECTED)
        && Yii::$app->user &&(Yii::$app->user->identity->isAdmin || Yii::$app->user->id == $this->author_id)
      );
    }

    public function Confirm()
    {
      if($this->getCanBeConfirmed())
      {
        $this->updateAttributes(['status' => self::$_STATUS_CONFIRMED]);
        return true;
      }
      return false;
    }

    public function getCanBeRejected()
    {
      return (($this->status == self::$_STATUS_SUBMITTED || $this->status == self::$_STATUS_CONFIRMED || $this->status == self::$_STATUS_ARCHIVED)
        && Yii::$app->user &&(Yii::$app->user->identity->isAdmin || Yii::$app->user->id == $this->author_id)
      );
    }

    public function Reject()
    {
      if($this->getCanBeRejected())
      {
        $this->updateAttributes(['status' => self::$_STATUS_REJECTED]);
        return true;
      }
      return false;
    }

    public function getCanBeArchived()
    {
      return (($this->status == self::$_STATUS_SUBMITTED || $this->status == self::$_STATUS_CONFIRMED || $this->status == self::$_STATUS_REJECTED)
        && Yii::$app->user &&(Yii::$app->user->identity->isAdmin || Yii::$app->user->id == $this->author_id)
      );
    }

    public function Archive()
    {
      if($this->getCanBeArchived())
      {
        $this->updateAttributes(['status' => self::$_STATUS_ARCHIVED]);
        return true;
      }
      return false;
    }
}
