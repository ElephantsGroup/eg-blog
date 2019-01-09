<?php

namespace elephantsGroup\blog\controllers;

use Yii;
use elephantsGroup\blog\models\BlogTranslation;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\base\EGController;
use yii\web\NotFoundHttpException;


/**
 * TranslationController implements the CRUD actions for BlogTranslation model.
 */
class TranslationController extends EGController
{
    public function behaviors()
    {
        $behaviors = [];
        /*$behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ];
        $auth = Yii::$app->getAuthManager();
        if ($auth)
        {
            $behaviors['access'] = [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow'   => true,
                        'roles'   => ['blog_publisher', 'admin'],
                    ],
                    [
                        'actions' => ['index', 'view', 'update'],
                        'allow'   => true,
                        'roles'   => ['blog_editor', 'admin'],
                    ],
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow'   => true,
                        'roles'   => ['blog_admin', 'admin'],
                    ],
                ],
            ];
        }
        else
        {
            $behaviors['access'] = [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ];
        }*/
        return $behaviors;
    }

    /**
     * Lists all BlogTranslation models.
     * @return mixed
     */

    /*public function actionIndex()
    {
        $searchModel = new BlogTranslationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single BlogTranslation model.
     * @param integer $blog_id
     * @param string $language
     * @return mixed
     */
    public function actionView($blog_id, $language, $lang = 'fa-IR')
    {
        return $this->render('view', [
            'model' => $this->findModel($blog_id, $language),
        ]);
    }

    /**
     * Creates a new BlogTranslation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($blog_id, $language, $lang = 'fa-IR', $redirectUrl)
    {
        $_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = Blog::$upload_url .'images/';
        $_SESSION['KCFINDER']['uploadDir'] = Blog::$upload_path . 'images/';

        $max_version = BLog::find()->where(['id' => $blog_id])->max('version');
        $model = new BlogTranslation();
        $model->blog_id = $blog_id;
        $model->language = $language;
        $model->version = $max_version;

        if ($model->load(Yii::$app->request->post()))
        {
          $model->title = trim($model->title);
          $model->subtitle = trim($model->subtitle);
          $model->intro = trim($model->intro);
          $model->description = trim($model->description);

          if($model->save())
          	return $this->redirect($redirectUrl);
          }
        else
        {
              return $this->render('create', [
                  'model' => $model,
              ]);
          }
    }

    /**
     * Updates an existing BlogTranslation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $blog_id
     * @param string $language
     * @return mixed
     */
    public function actionUpdate($blog_id, $language, $lang = 'fa-IR', $redirectUrl)
    {
        $_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = Blog::$upload_url .'images/';
        $_SESSION['KCFINDER']['uploadDir'] = Blog::$upload_path . 'images/';

        $max_version_translation = BlogTranslation::find()->where(['blog_id' => $blog_id, 'language' => $language])->max('version');
        $previous = $this->findModel($blog_id, $max_version_translation, $language);
        $model = $this->findModel($blog_id, $max_version_translation, $language);

        $max_version = Blog::find()->where(['id' => $blog_id])->max('version');
        $blog_previous_version = Blog::findOne(array('id' => $blog_id, 'version' => $max_version));
        $blog = new Blog();
        $new_translation = new BlogTranslation();

    		if ($model->load(Yii::$app->request->post()) && $new_translation->load(Yii::$app->request->post()) && $model->validate())
    		{
    			$blog->attributes = $blog_previous_version->attributes;
    			$blog->version = $max_version + 1;

    			$new_translation->blog_id = $model->blog_id;
    			$new_translation->language = $model->language;
    			$new_translation->version = $max_version_translation;
    			$new_translation->title = trim($new_translation->title);
    			$new_translation->subtitle = trim($new_translation->subtitle);
    			$new_translation->intro = trim($new_translation->intro);
    			$new_translation->description = trim($new_translation->description);

    			$translation_changed = !($new_translation->attributes == $previous->attributes);

    			if(!$translation_changed)
    			{
    				return $this->redirect($redirectUrl);
    			}
    			else
          {
    				if( $blog->save())
    				{
    					$blog_previous_version->updateAttributes (['status' => Blog::$_STATUS_EDITED]) ;
    					$new_translation->version = $blog->version;
    					if ($new_translation->save())
    					{
    						return $this->redirect($redirectUrl);
    					}
    					else
    					{
    						return $this->render('update', [
    							'model' => $model,
    						]);
    					}
    				 }
    				 else
    					var_dump($blog->errors); die;
  		      }
      		}
      		else
      		{
      			return $this->render('update', [
      				'model' => $model,
      			]);
      		}
    }
    /**
     * Deletes an existing BlogTranslation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $blog_id
     * @param string $language
     * @return mixed
     */
    public function actionDelete($blog_id, $language, $lang = 'fa-IR', $redirectUrl)
    {
      $version = BlogTranslation::find()->where(['blog_id' => $blog_id, 'language' => $language])->max('version');
          $model = $this->findModel($blog_id, $version, $language);

  		if ($model->delete())
  			return $this->redirect($redirectUrl);
  		else
  			var_dump($model->errors);    }

    /**
     * Finds the BlogTranslation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $blog_id
     * @param string $language
     * @return BlogTranslation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($blog_id, $version, $language)
    {
        if (($model = BlogTranslation::findOne(['blog_id' => $blog_id, 'version' => $version, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
