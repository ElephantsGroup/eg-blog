<?php

namespace elephantsGroup\blog\components;

use Yii;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogTranslation;
use yii\base\Widget;
use yii\helpers\Html;

class ArchivedBlog extends Widget
{
	public $number = 100;
	public $language;
	public $title;
	public $subtitle;
    public $view_file = 'archived_blog' ;

	protected $_blog = [];

	public function init()
	{
		if(!isset($this->language) || !$this->language)
			$this->language = Yii::$app->language;
		if(!isset($this->title) || !$this->title)
			$this->title = Yii::t('blog_params', 'Archived blog Title');
		if(!isset($this->subtitle) || !$this->subtitle)
			$this->subtitle = Yii::t('blog_params', 'Archived blog Subtitle');
        if(!isset($this->view_file) || !$this->view_file)
            $this->view_file = Yii::t('blog_params', 'View File');
	}

    public function run()
	{
		$blog = Blog::find()->where(['status' => Blog::$_STATUS_ARCHIVED])->orderBy(['creation_time'=>SORT_DESC])->all();
		$i = 0;
		foreach($blog as $blog_item)
		{
			if($i == $this->number) break;
			$translation = BlogTranslation::findOne(array('blog_id'=>$blog_item->id, 'language'=>$this->language));
			if($translation)
			{
				$this->_blog[] = [
				    'id' => $blog_item['id'],
                    'thumb' => Blog::$upload_url . $blog_item['id'] . '/' . $blog_item['thumb'],
                    'title' => $translation->title,
                    'subtitle' => $translation->subtitle,
                    'intro' => $translation->intro,
                ];
				$i++;
			}
		}
		return $this->render($this->view_file, [
		    'blog'=>$this->_blog,
            'last_blog_title'=>$this->title,
            'last_blog_subtitle'=>$this->subtitle
        ]);
	}
}