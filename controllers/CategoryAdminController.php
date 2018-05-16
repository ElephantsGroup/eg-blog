<?php

namespace elephantsGroup\blog\controllers;

use Yii;
use elephantsGroup\blog\models\BlogCategory;
use elephantsGroup\blog\models\BlogCategoryTranslation;
use elephantsGroup\blog\models\BlogCategorySearch;
use elephantsGroup\base\EGController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CategoryAdminController implements the CRUD actions for BlogCategory model.
 */
class CategoryAdminController extends EGController
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
     * Lists all BlogCategory models.
     * @return mixed
     */
    public function actionIndex($lang = 'fa-IR')
    {
        $searchModel = new BlogCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BlogCategory model.
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
     * Creates a new BlogCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($lang = 'fa-IR')
    {
        $model = new BlogCategory();
        $translation = new BlogCategoryTranslation();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->logo_file = UploadedFile::getInstance($model, 'logo_file');

            if($model->save())
            {
                if ($translation->load(Yii::$app->request->post()))
                {
                    if(!$translation->title)
                        return $this->redirect(['view', 'id' => $model->id]);
                    $translation->cat_id = $model->id;
                    $translation->language = $this->language;
                    if($translation->save())
                        return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        else
        {
            return $this->render('create', [
                'model' => $model,
                'translation' => $translation,
            ]);
        }
    }

    /**
     * Updates an existing BlogCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $lang = 'fa-IR')
    {
        $model = $this->findModel($id);
        $translation = BlogCategoryTranslation::findOne(array('cat_id' => $id, 'language' => $this->language));

        if ($model->load(Yii::$app->request->post()))
        {
            $model->logo_file = UploadedFile::getInstance($model, 'logo_file');

            if($model->save())
            {
                if ($translation && $translation->load(Yii::$app->request->post()))
                {
                    $translation->cat_id = $model->id;
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
     * Deletes an existing BlogCategory model.
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
     * Finds the BlogCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlogCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlogCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
