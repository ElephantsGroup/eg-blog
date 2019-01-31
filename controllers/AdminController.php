<?php

namespace elephantsGroup\blog\controllers;

use Yii;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogSearch;
use elephantsGroup\blog\models\BlogTranslation;
use elephantsGroup\base\EGController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use elephantsGroup\jdf\Jdf;
use yii\web\UploadedFile;

/**
 * AdminController implements the CRUD actions for Blog model.
 */
class AdminController extends EGController
{
    public function behaviors()
    {
        $behaviors = [];
        // $behaviors['verbs'] = [
        //     'class' => VerbFilter::className(),
        //     'actions' => [
        //         'delete' => ['post'],
        //     ],
        // ];
        return $behaviors;
    }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex($lang = 'fa-IR')
    {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blog model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $lang = 'fa-IR')
    {
        return $this->render('view', [
            'model' => $this->findModelMaxVersion($id),
        ]);
    }

    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($lang = 'fa-IR')
    {
        $_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = Blog::$upload_url .'images/';
        $_SESSION['KCFINDER']['uploadDir'] = Blog::$upload_path . 'images/';

        $model = new Blog();
        $translation = new BlogTranslation();

        date_default_timezone_set('Iran');

        if ($model->load(Yii::$app->request->post()))
        {
          if ($model->archive_time != null && !empty($model->archive_time))
          {
            $datetime = $model->archive_time;
            $time = $model->archive_time_time;
            $year = (int)(substr($datetime, 0, 4));
            $month = (int)(substr($datetime, 5, 2));
            $day = (int)(substr($datetime, 8, 2));
            $hour = (int)(substr($time, 0, 2));
            $minute = (int)(substr($time, 3, 2));
            $second = (int)(substr($time, 6, 2));
            if(substr($time, 9, 2) == 'PM')
                $hour += 12;
            $date = new \DateTime();
            $date->setTimestamp(Jdf::jmktime($hour, $minute, $second, $month, $day, $year));
            $model->archive_time = $date->format('Y-m-d H:i:s');
          }

          if ($model->publish_time != null && !empty($model->publish_time))
          {
              $datetime_publish = $model->publish_time;
              $time_publish = $model->publish_time_time;
              $year_publish = (int)(substr($datetime_publish, 0, 4));
              $month_publish = (int)(substr($datetime_publish, 5, 2));
              $day_publish = (int)(substr($datetime_publish, 8, 2));
              $hour_publish = (int)(substr($time_publish, 0, 2));
              $minute_publish = (int)(substr($time_publish, 3, 2));
              $second_publish = (int)(substr($time_publish, 6, 2));
              if(substr($time_publish, 9, 2) == 'PM')
                  $hour_publish += 12;
              $date_publish = new \DateTime();
              $date_publish->setTimestamp(Jdf::jmktime($hour_publish, $minute_publish, $second_publish, $month_publish, $day_publish, $year_publish));
        			$model->publish_time = $date_publish->format('Y-m-d H:i:s');
          }

            $max_ID = Blog::find()->max('id');
            if($max_ID == null && empty($max_ID))
              $model->id = 1;
            else
              $model->id = $max_ID+1;
      			$model->version = 1;
      			$model->author_id = (int) Yii::$app->user->id;
      			$model->thumb_file = UploadedFile::getInstance($model, 'thumb_file');

            if($model->save())
            {
                if ($translation->load(Yii::$app->request->post()))
                {
                  $translation->blog_id = $model->id;
                  $translation->version = $model->version;
                  $translation->language = $this->language;
                  $translation->title = trim($translation->title);
                  $translation->subtitle = trim($translation->subtitle);
                  $translation->intro = trim($translation->intro);
                  $translation->description = trim($translation->description);

                  if($translation->save())
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        else
        {
            return $this->render('create',[
                'model' => $model,
                'translation' => $translation,
            ]);
        }
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $lang = 'fa-IR')
    {
        $_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = Blog::$upload_url .'images/';
        $_SESSION['KCFINDER']['uploadDir'] = Blog::$upload_path . 'images/';

        $max_version = Blog::find()->where(['id' => $id])->max('version');
    		$blog_previous_version = Blog::findOne(array('id' => $id, 'version' => $max_version));

        $model = new Blog();
        $max_version_translation = BlogTranslation::find()->where(['blog_id' => $id, 'language' => $this->language])->max('version');
    		$translation_previous_version = BlogTranslation::findOne(array('blog_id' => $id, 'language' => $this->language, 'version' => $max_version_translation));
    		$translation = new BlogTranslation();

        if ($model->load(Yii::$app->request->post()) && $translation->load(Yii::$app->request->post()))
    		{
    			$model->id = $id;
    			$model->version = $max_version;
    			$model->thumb_file = UploadedFile::getInstance($model, 'thumb_file');

    			if($model->thumb_file == null || empty($model->thumb_file))
    				$model->thumb = $blog_previous_version->thumb;

          if($model->archive_time == $blog_previous_version->archive_time)
    			{
    				$model->archive_time = $blog_previous_version->archive_time;
    			}
          elseif($model->archive_time != null && !empty($model->archive_time))
    			 {
            $datetime = $model->archive_time;
      			$time = $model->archive_time_time;
      			$year = (int)(substr($datetime, 0, 4));
      			$month = (int)(substr($datetime, 5, 2));
      			$day = (int)(substr($datetime, 8, 2));
      			$hour = (int)(substr($time, 0, 2));
      			$minute = (int)(substr($time, 3, 2));
      			$second = (int)(substr($time, 6, 2));
      			if(substr($time, 9, 2) == 'PM')
      				$hour += 12;
      			$date = new \DateTime();
      			$date->setTimestamp(Jdf::jmktime($hour, $minute, $second, $month, $day, $year));
            $model->archive_time = $date->format('Y-m-d H:i:s');
          }

          if($model->publish_time == $blog_previous_version->publish_time)
    			{
    				$model->publish_time = $blog_previous_version->publish_time;
    			}
          elseif($model->publish_time != null && !empty($model->publish_time))
    			 {
            $datetime = $model->publish_time;
      			$time = $model->publish_time_time;
      			$year = (int)(substr($datetime, 0, 4));
      			$month = (int)(substr($datetime, 5, 2));
      			$day = (int)(substr($datetime, 8, 2));
      			$hour = (int)(substr($time, 0, 2));
      			$minute = (int)(substr($time, 3, 2));
      			$second = (int)(substr($time, 6, 2));
      			if(substr($time, 9, 2) == 'PM')
      				$hour += 12;
      			$date = new \DateTime();
      			$date->setTimestamp(Jdf::jmktime($hour, $minute, $second, $month, $day, $year));
            $model->publish_time = $date->format('Y-m-d H:i:s');
          }

    			$translation->blog_id = $model->id;
    			$translation->language = $this->language;
    			$translation->version = $max_version_translation;
    			$translation->title = trim($translation->title);
    			$translation->subtitle = trim($translation->subtitle);
    			$translation->intro = trim($translation->intro);
    			$translation->description = trim($translation->description);

    			$blog_changed = !($model->attributes == $blog_previous_version->attributes);
    			$translation_changed = !($translation->attributes == $translation_previous_version->attributes);

    			if(!$blog_changed && !$translation_changed)
    			{
    				return $this->redirect(['view', 'id' => $model->id]);
    			}
    			else
    			{
    				$model->version = $max_version + 1;
    				if($model->save())
    				{
    					$blog_previous_version->updateAttributes (['status' => Blog::$_STATUS_EDITED]) ;
    					if($translation_changed)
    					{
    						if(!$translation->title && !$translation->subtitle && !$translation->intro && !$translation->description)
    							return $this->redirect(['view', 'id' => $model->id]);
    						$translation->version = $model->version;
    						if($translation->save())
    							return $this->redirect(['view', 'id' => $model->id]);
    					}
    					else
    					{
    						return $this->redirect(['view', 'id' => $model->id]);
    					}
    				}
    				else {
    					var_dump($model->errors); die;
    				}
    			}
    		}
    		else
    		{
    			return $this->render('update', [
    				'model' => $blog_previous_version,
    				'translation' => $translation_previous_version,
    			]);
    		}
    }

    /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     public function actionDelete($id, $redirectUrl)
     {
       // $blog_version = BlogTranslation::find()->select('version')->where(['blog_id' => $id])->all();
   		$blog_version = Blog::find()->select('version')->where(['id' => $id])->all();
   		foreach($blog_version as $version)
   		{
   			foreach($this->findModels($id, $version) as $model)
   				$model->delete();
   		}
       return $this->redirect($redirectUrl);
     }

     public function actionConfirm($id, $redirectUrl)
 	  {
 	    $blog_module = Yii::$app->getModule('blog');
 	    $response = [
 				'status' => 500,
 				'message' => $blog_module::t('blog', 'Server problem')
 			];
 			try
 			{
 				$blog = $this->findModelMaxVersion($id);
 				if (!$blog)
 				{
 					$response = [
 						'status' => 500,
 						'message' => $blog_module::t('blog', 'Blog Not Found.')
 					];
 				}

 				if ($blog->confirm())
 				{
 					$response = [
 						'status' => 200,
 						'message' => $blog_module::t('blog', 'Successful')
 					];
 				}
 				else
 				{
 					$response = [
 						'status' => 500,
 						'message' => $blog_module::t('blog', 'cant set to confirm')
 					];
 				}
 			}
 			catch (Exception $exp)
 			{
 				$response = [
 					'status' => 500,
 					'message' => $blog_module::t('blog', $exp)
 				];
 			}
 		return $this->redirect($redirectUrl);
 	  }

 		public function actionReject($id, $redirectUrl)
 	  {
 	    $blog_module = Yii::$app->getModule('blog');
 	    $response = [
 				'status' => 500,
 				'message' => $blog_module::t('blog', 'Server problem')
 			];
 			try
 			{
 				$blog = $this->findModelMaxVersion($id);
 				if (!$blog)
 				{
 					$response = [
 						'status' => 500,
 						'message' => $blog_module::t('blog', 'Blog Not Found.')
 					];
 				}

 				if ($blog->reject())
 				{
 					//var_dump($id); die;
 					$response = [
 						'status' => 200,
 						'message' => $blog_module::t('blog', 'Successful')
 					];
 				}
 				else
 				{
 					$response = [
 						'status' => 500,
 						'message' => $blog_module::t('blog', 'cant set to reject')
 					];
 				}
 			}
 			catch (Exception $exp)
 			{
 				$response = [
 					'status' => 500,
 					'message' => $blog_module::t('blog', $exp)
 				];
 			}

 			//return json_encode($response);
 			return $this->redirect($redirectUrl);
 	  }

 		public function actionArchive($id, $redirectUrl)
 		{
 			$blog_module = Yii::$app->getModule('blog');
 			$response = [
 				'status' => 500,
 				'message' => $blog_module::t('blog', 'Server problem')
 			];
 			try
 			{
 				$blog = $this->findModelMaxVersion($id);
 				if (!$blog)
 				{
 					$response = [
 						'status' => 500,
 						'message' => $blog_module::t('blog', 'Blog Not Found.')
 					];
 				}

 				if ($blog->archive())
 				{
 					//var_dump($id); die;
 					$response = [
 						'status' => 200,
 						'message' => $blog_module::t('blog', 'Successful')
 					];
 				}
 				else
 				{
 					$response = [
 						'status' => 500,
 						'message' => $blog_module::t('blog', 'cant set to archive')
 					];
 				}
 			}
 			catch (Exception $exp)
 			{
 				$response = [
 					'status' => 500,
 					'message' => $blog_module::t('blog', $exp)
 				];
 			}

 			//return json_encode($response);
 			return $this->redirect($redirectUrl);
 		}


    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModels($id, $version)
  	{
  		if (($model = Blog::find()->where(['id' => $id, 'version' => $version])->all()) !== null)
      {
      	return $model;
      }
      else
      {
      	throw new NotFoundHttpException('The requested page does not exist.');
      }
  	}

  	protected function findModelMaxVersion($id)
  	{
  	  $max_version = Blog::find()->where(['id' => $id])->max('version');
  	  if (($model = Blog::findOne(['id' => $id, 'version' => $max_version])) !== null)
  	  {
  			return $model;
  	  }
  	  else
  	  {
  		  throw new NotFoundHttpException('The requested page does not exist.');
  	  }
  	}
}
