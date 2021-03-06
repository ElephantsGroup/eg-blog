<?php

namespace elephantsGroup\blog\components;

use Yii;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogTranslation;
use yii\base\Widget;
use yii\helpers\Html;
use yii\db\Expression;
use elephantsGroup\jdf\Jdf;


class DateList extends Widget
{
	public $language;
	public $title;
	public $view_file = 'date_list';
	protected $_list = [];

	public function init()
	{
		if(!isset($this->language) || !$this->language)
			$this->language = Yii::$app->language;
		if(!isset($this->title) || !$this->title)
			$this->title = Yii::t('blog_params', 'Blog Title');
        if(!isset($this->view_file) || !$this->view_file)
            $this->view_file = Yii::t('blog_params', 'View File');
	}


    public function run()
	{
		$expression_count = new Expression('COUNT(id) AS num');
		$expression_year = new Expression('YEAR(creation_time) AS year');
		$expression_max_time = new Expression('MAX(creation_time) AS max_time');
		$expression_month = new Expression('MONTH(creation_time) AS month');
		$expression_month_num = new Expression('DATEDIFF(MAX(creation_time), MIN(creation_time)) / 30 AS months, ');

		if (Yii::$app->controller->language =='fa-IR')
		{
			$blog_date = Blog::find()->select($expression_max_time, $expression_month_num)->asArray()->one();
			if($blog_date)
			{
				$max_date_month = Jdf::jdate('n',(new \DateTime($blog_date['max_time']))->getTimestamp(), '', 'Iran', 'en');
				$max_date_month_fa = Jdf::jdate('F',(new \DateTime($blog_date['max_time']))->getTimestamp(), '', 'Iran', '');
				$max_date_year = Jdf::jdate('Y',(new \DateTime($blog_date['max_time']))->getTimestamp(), '', 'Iran', 'en');
				$months = $blog_date['months']+1;
				for($i = $months; $i >= 0; $i--)
				{
					$begin_time = Jdf::jmktime(0, 0, 0, intVal($max_date_month),1, intVal($max_date_year), 'Iran');
					$begin_date = Jdf::jdate('Y/m/d', $begin_time, '', 'Iran', 'en');
					$date = new \DateTime();
					$date->setTimestamp($begin_time);
					$begin_time = $date->format('Y-m-d');

					$end_time = Jdf::jmktime(23, 59, 59, intVal($max_date_month),30, intVal($max_date_year), 'Iran');
					$end_date = Jdf::jdate('Y/m/d', $end_time, '', 'Iran', 'en');
					$date = new \DateTime();
					$date->setTimestamp($end_time);
					$end_time = $date->format('Y-m-d');

					$blog_count = Blog::find()->where(['between', 'creation_time', $begin_time, $end_time])->count();

					if($blog_count)
					{
						$this->_list[] = [
							'year' => $max_date_year,
							'month' => $max_date_month_fa,
							'count' => $blog_count,
							'from' => $begin_date,
							'to' =>  $end_date,
						];
					}
					if($max_date_month == 1)
					{
						$max_date_month = 12;
						$max_date_year--;
					}
					else
					{
						$max_date_month--;
					}
				}
			}
		}
		else
		{
			$blog_date = Blog::find()->select([$expression_count, $expression_year, $expression_month])->groupBy(['year','month'])->notEdited()->asArray()->all();
			foreach ($blog_date as $date)
			{
				$begin_date = new \DateTime();
				$begin_time = mktime(0, 0, 0, $date['month'], 1, $date['year']);
				$begin_date->setTimestamp($begin_time);
				$from = $begin_date->format('Y-m-d');

				$end_date = new \DateTime();
				$end_time = mktime(23, 59, 59, $date['month'], 30, $date['year']);
				$end_date->setTimestamp($end_time);
				$to = $end_date->format('Y-m-d');

				$this->_list[]=[
					'year' => $date['year'],
					'month' => $date['month'],
					'count' => $date['num'],
					'from' => $from,
					'to' => $to,
				];
			}
		}
		return $this->render($this->view_file, [
			'date' => $this->_list,
			'language' => $this->language,
		]);
	}
}
