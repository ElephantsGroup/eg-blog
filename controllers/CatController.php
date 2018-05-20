<?php

namespace elephantsGroup\blog\controllers;

use elephantsGroup\blog\models\BlogCategory;
use elephantsGroup\blog\models\BlogCategoryTranslation;
use elephantsGroup\gallery\models\Category;
use elephantsGroup\gallery\models\CategoryTranslation;
use Yii;
//use yii\web\Controller;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogTranslation;
use elephantsGroup\stat\models\Stat;
use elephantsGroup\base\EGController;
use elephantsGroup\jdf\Jdf;

class CatController extends EGController
{
	private function getBeginDate($lang, $begin_time = null)
	{
		if( $begin_time == null)
		{
			if($lang == 'fa-IR')
			{
				$date = new \DateTime();
				$now_date = Jdf::jdate('Y/m/d', time(), '', 'Iran', 'en');
				$year = (int)(substr($now_date, 0, 4));
				$month = (int)(substr($now_date, 5, 2));
				$date->setTimestamp(Jdf::jmktime(0, 0, 0, $month, 1, $year));
				$from = $date->format('Y-m-d');
			}else
			{
				$date = new \DateTime('first day of this month');
				//$date->setTimestamp();
				$date->setTimezone(new \DateTimezone('Iran'));
				$from = $date->format('Y-m-d');
			}	
			
		}else
		{
			if($lang == 'fa-IR')
			{
				$date = new \DateTime();
				$year = (int)(substr($begin_time, 0, 4));
				$month = (int)(substr($begin_time, 5, 2));
				$day = (int)(substr($begin_time, 8, 2));
				$date->setTimestamp(Jdf::jmktime(0, 0, 0, $month, $day, $year));
				$from = $date->format('Y-m-d');
			}
			else
			{
				$date = new \DateTime();
				$date->setTimezone(new \DateTimezone('Iran'));
				$begin_date = strtotime($begin_time);
				$date->setTimestamp($begin_date);
				$from = $date->format('Y-m-d');
			}
		}
		
		return $from;
	}
	
	private function getEndDate($lang, $end_time = null)
	{		
		if( $end_time == null)
		{
			if($lang == 'fa-IR')
			{
				$date=new \DateTime();
				$now_date = Jdf::jdate('Y/m/d', time(), '', 'Iran', 'en');
				$year = (int)(substr($now_date, 0, 4));
				$month = (int)(substr($now_date, 5, 2));
				$day = (int)(substr($now_date, 8, 2));
				$date->setTimestamp(Jdf::jmktime(23, 59, 59, $month, $day, $year));
				$to = $date->format('Y-m-d');
			}else
			{
				$date = new \DateTime();
				$date->setTimestamp(time());
				$date->setTimezone(new \DateTimezone('Iran'));
				$to = $date->modify('+1 day')->format('Y-m-d');
			}
		}else
		{
			if($lang == 'fa-IR')
			{
				$date=new \DateTime();
				$date->setTimezone(new \DateTimezone('Iran'));
				$year = (int)(substr($end_time, 0, 4));
				$month = (int)(substr($end_time, 5, 2));
				$day = (int)(substr($end_time, 8, 2));
				$date->setTimestamp(Jdf::jmktime(20, 29, 59, $month, $day, $year)); // TODO: fix with iran timezone later, PHP 7 jdf conflict
				$to = $date->format('Y-m-d');
			}else
			{
				$date = new \DateTime();
				$date->setTimezone(new \DateTimezone('Iran'));
				$end_date = strtotime($end_time);
				$date->setTimestamp($end_date);
				$to = $date->format('Y-m-d');
			}
			
		}
		return $to;
	}

    public function actionIndex($lang = 'fa-IR', $begin_time = null, $end_time = null)
    {
		Stat::setView('blog', 'default', 'index');
		
		//$this->layout = '//creative-item';
		Yii::$app->controller->addLanguageUrl('fa-IR', Yii::$app->urlManager->createUrl(['blog', 'lang' => 'fa-IR']), (Yii::$app->controller->language !== 'fa-IR'));
		Yii::$app->controller->addLanguageUrl('en', Yii::$app->urlManager->createUrl(['blog', 'lang' => 'en']), (Yii::$app->controller->language !== 'en'));
        
		$begin = $this->getBeginDate($this->language, $begin_time);
		$end = $this->getEndDate($this->language, $end_time); 
		$cat_list = [];
//		$blog = Blog::find()->where(['between', 'creation_time', $begin, $end])->all();
		$cat = BlogCategory::find()->all();
		foreach($cat as $item)
		{
			$translation = BlogCategoryTranslation::findOne(array('cat_id' => $item->id, 'language' => $this->language));
			if($translation)
			{
				$cat_list[] = [
				    'id' => $item['id'],
                    'thumb' => BlogCategory::$upload_url . $item['id'] . '/' . $item['logo'],
                    'title' => $translation->title,
                ];
			}
		}
		return $this->render('index',[
			'category' => $cat_list,
			'from' => $begin,
			'to' => $end,
			'language' => $this->language
		]);

    }

    public function actionView($id, $lang = 'fa-IR')
    {
		Stat::setView('blog', 'default', 'view');

        //$this->layout = '//creative-item';
		Yii::$app->controller->addLanguageUrl('fa-IR', Yii::$app->urlManager->createUrl(['blog/cat/view', 'id'=>$id, 'lang' => 'fa-IR']), (Yii::$app->controller->language !== 'fa-IR'));
		Yii::$app->controller->addLanguageUrl('en', Yii::$app->urlManager->createUrl(['blog/cat/view', 'id'=>$id, 'lang' => 'en']), (Yii::$app->controller->language !== 'en'));

        $model = BlogCategory::findOne($id);
        $blog_list = [];
        $cat_blog = Blog::find()->where(['category_id' => $id])->all();
        foreach($cat_blog as $item)
        {
            $translation = BlogTranslation::findOne(array('blog_id' => $item->id, 'language' => $this->language));
            if($translation)
            {
                $blog_list[] = [
                    'id' => $item['id'],
                    'thumb' => BlogCategory::$upload_url . $item['id'] . '/' . $item['thumb'],
                    'title' => $translation->title,
                    'subtitle' => $translation->subtitle,
                    'intro' => $translation->intro
                ];
            }
        }
        return $this->render('view', [
            'model' => $model,
            'blog_list' => $blog_list,
        ]);
    }
}
