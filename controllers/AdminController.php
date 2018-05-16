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
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ];
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
            'model' => $this->findModel($id),
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
            $model->author_id = (int) Yii::$app->user->id;
            $model->thumb_file = UploadedFile::getInstance($model, 'thumb_file');

            if($model->save())
            {
                if ($translation->load(Yii::$app->request->post()))
                {
                    $translation->blog_id = $model->id;
                    $translation->language = $this->language;
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

        $model = $this->findModel($id);
        $translation = BlogTranslation::findOne(array('blog_id' => $id, 'language' => $this->language));

        date_default_timezone_set('Iran');

        $timestamp = (new \DateTime($model->archive_time))->getTimestamp();
        $hour = Jdf::jdate('h', $timestamp, '', 'Iran', 'en');
        $minute = Jdf::jdate('i', $timestamp, '', 'Iran', 'en');
        $second = Jdf::jdate('s', $timestamp, '', 'Iran', 'en');
        $type = 'AM';
        $model->archive_time_time = $hour . ':' . $minute . ':' . $second . ' ' . $type;
        $model->archive_time = Jdf::jdate('Y/m/d', $timestamp, '', 'Iran', 'en');

        if ($model->load(Yii::$app->request->post()))
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
            $model->author_id = (int) Yii::$app->user->id;

            $model->thumb_file = UploadedFile::getInstance($model, 'thumb_file');

            if($model->save())
            {
                if ($translation && $translation->load(Yii::$app->request->post()))
                {
                    if(!$translation->title && !$translation->subtitle && !$translation->intro && !$translation->description)
                        return $this->redirect(['view', 'id' => $model->id]);
                    $translation->blog_id = $model->id;
                    $translation->language = $this->language;
                    if($translation->save())
                        return $this->redirect(['view', 'id' => $model->id]);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        else
        {
            return $this->render('update', [
                'model' => $model,
                'translation' => $translation,
            ]);
        }
    }

    /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
}
