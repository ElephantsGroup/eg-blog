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
                        'roles'   => ['news_publisher', 'admin'],
                    ],
                    [
                        'actions' => ['index', 'view', 'update'],
                        'allow'   => true,
                        'roles'   => ['news_editor', 'admin'],
                    ],
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow'   => true,
                        'roles'   => ['news_admin', 'admin'],
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

    public function actionIndex()
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
    public function actionCreate($blog_id, $language, $lang = 'fa-IR')
    {
        $_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = Blog::$upload_url .'images/';
        $_SESSION['KCFINDER']['uploadDir'] = Blog::$upload_path . 'images/';

        $model = new BlogTranslation();
        $model->blog_id = $blog_id;
        $model->language = $language;

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $model->blog_id = $blog_id;
            $model->language = $language;
            return $this->redirect(['admin/index', 'lang' => $lang]);
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
    public function actionUpdate($blog_id, $language, $lang = 'fa-IR')
    {
        $_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = Blog::$upload_url .'images/';
        $_SESSION['KCFINDER']['uploadDir'] = Blog::$upload_path . 'images/';

        $model = $this->findModel($blog_id, $language);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['admin/index', 'lang' => $lang]);
        } else {
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
    public function actionDelete($blog_id, $language, $lang = 'fa-IR')
    {
        $model = $this->findModel($blog_id, $language);

        if ($model->delete())
            return $this->redirect(['admin/index', 'lang' => $lang]);
        else
            var_dump($model->errors);
    }

    /**
     * Finds the BlogTranslation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $blog_id
     * @param string $language
     * @return BlogTranslation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($blog_id, $language)
    {
        if (($model = BlogTranslation::findOne(['blog_id' => $blog_id, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
