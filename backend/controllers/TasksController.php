<?php

namespace backend\controllers;

use common\models\Tasks;
use backend\models\TasksSearch;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\filters\AccessControl;

/**
 * REST контроллер заказов
 *
 * Доступ к контроллеру только для аутентифицированных пользователей
 *
 * GET /tasks - список всех заказов пользователя (администратор видит все заказы)
 * GET /tasks/1 - просмотр заказа с ID = 1, если он был создан текущим пользователем (администратор видит все заказы)
 * POST /tasks - добавление заказа
 * PUT /tasks/1 - обновление заказа с ID = 1, если он был создан текущим пользователем (либо это администратор)
 * DELETE /tasks/1 - удаление заказа с ID = 1, если он был создан текущим пользователем (либо это администратор)
 *
 */
class TasksController extends ActiveController
{
    public $modelClass = 'common\models\Tasks';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['index'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => ['create'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
//                [
//                    'actions' => ['view'],
//                    'allow' => true,
//                    'matchCallback' =>
//                        function () {
//                            return Yii::$app->user->can('orderRead', ['order' =>
//                                $this->findModel(Yii::$app->request->get('id'))]);
//                        },
//                ],
//                [
//                    'actions' => ['update'],
//                    'allow' => true,
//                    'matchCallback' =>
//                        function () {
//                            return Yii::$app->user->can('orderUpdate', ['order' =>
//                                $this->findModel(Yii::$app->request->get('id'))]);
//                        },
//                ],
//                [
//                    'actions' => ['delete'],
//                    'allow' => true,
//                    'matchCallback' =>
//                        function () {
//                            return Yii::$app->user->can('orderDelete', ['order' =>
//                                $this->findModel(Yii::$app->request->get('id'))]);
//                        },
//                ],
            ],
        ];
        return $behaviors;
    }

    /**
     * Переопределяем actions по своему осмотрению
     */
    public function actions()
    {

        $actions = parent::actions();

        // index (каждому пользователю показываем свое, если только он не админ)

        $actions['index']['prepareDataProvider'] =  function () {
            return new ActiveDataProvider([
//                'query' => (\Yii::$app->user->can('admin') ? Tasks::find()->with('users') :
//                    Tasks::find()->with('users')->where(['user_id' => \Yii::$app->user->id])),
                'query' =>  Tasks::find(),
            ]);
        };

        return $actions;
    }



    /**
     * Finds the Tasks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Tasks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tasks::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
