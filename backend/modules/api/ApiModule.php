<?php

namespace app\modules\api;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class ApiModule extends \yii\base\Module
{
    /**
     * Api модуль
     * http://localhost/admin/api/
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {

        parent::init();

        // custom initialization code goes here
    }



}
