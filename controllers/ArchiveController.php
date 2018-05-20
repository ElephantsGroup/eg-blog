<?php

namespace elephantsGroup\blog\controllers;

//use yii\web\Controller;
use elephantsGroup\base\EGController;
use elephantsGroup\stat\models\Stat;

class ArchiveController extends EGController
{
    public function actionIndex($lang = 'fa-IR')
    {
		Stat::setView('blog', 'archive', 'index');

		//$this->layout = '//creative-item';
        return $this->render('index');
    }
}
