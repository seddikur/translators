<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    public function init()
    {

        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;

        Yii::$app->response->on(
            Response::EVENT_BEFORE_SEND,
            [$this, 'beforeResponseSend']
        );
    }

    public function beforeResponseSend(\yii\base\Event $event)
    {
        /**
         * @var \yii\web\Response $response
         */
        $response = $event->sender;
    }

}
