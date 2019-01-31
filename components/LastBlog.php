<?php

namespace elephantsGroup\blog\components;

use elephantsGroup\blog\models\BlogQuery;
use Yii;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogTranslation;
use yii\base\Widget;
use yii\helpers\Html;

class LastBlog extends Widget
{
	public $number = 3;
	public $language;
	public $title;
	public $subtitle;
	public $title_is_link = true;
	public $blog_title_is_link = true;
	public $show_global_more = false;
	public $global_more_text = '';
	public $show_archive_button = false;
	public $archive_button_text = '';
    public $view_file = 'last_blog';

	protected $_blog = [];

	public function init()
	{
		if(!isset($this->language) || !$this->language)
			$this->language = Yii::$app->language;
		if(!isset($this->title) || !$this->title)
			$this->title = Yii::t('blog_params', 'Last Blog Title');
		if(!isset($this->subtitle))
			$this->subtitle = Yii::t('blog_params', 'Last Blog Subtitle');
		if(!isset($this->global_more_text))
			$this->global_more_text = Yii::t('blog_params', 'Global More Text');
		if(!isset($this->archive_button_text))
			$this->archive_button_text = Yii::t('blog_params', 'Archive Button Text');
        if(!isset($this->view_file) || !$this->view_file)
            $this->view_file = Yii::t('blog_params', 'View File');
	}

    public function run()
	{
		$date = new \DateTime('now');
		$date->setTimezone(new \DateTimezone('Iran'));
		$now = $date->format('Y-m-d H:m:s');
		
		$blog = Blog::find()->where(['<=', 'publish_time' , $now ])->where(['status' => Blog::$_STATUS_CONFIRMED])->orderBy(['creation_time'=>SORT_DESC])->all();
		$i = 0;
		foreach($blog as $blog_item)
		{
			if($i == $this->number) break;
			$max_version_translation = BlogTranslation::find()->where(['blog_id' => $blog_item->id, 'language' => $this->language])->max('version');
			$translation = BlogTranslation::findOne(array('blog_id'=>$blog_item->id, 'language'=>$this->language, 'version' => $max_version_translation));
			if($translation)
			{
				$this->_blog[] = [
				    'id' => $blog_item['id'],
                    'thumb' => Blog::$upload_url . $blog_item['id'] . '/' . $blog_item['thumb'],
                    'title' => $translation->title,
                    'subtitle' => $translation->subtitle,
                    'intro' => $translation->intro
                ];
				$i++;
			}
		}

		return $this->render($this->view_file, [
			'blog' => $this->_blog,
			'last_blog_title' => $this->title,
			'last_blog_subtitle' => $this->subtitle,
			'title_is_link' => $this->title_is_link,
			'blog_title_is_link' => $this->blog_title_is_link,
			'language' => $this->language,
			'show_global_more' => $this->show_global_more,
			'global_more_text' => $this->global_more_text,
			'show_archive_button' => $this->show_archive_button,
			'archive_button_text' => $this->archive_button_text,
		]);
	}
}
