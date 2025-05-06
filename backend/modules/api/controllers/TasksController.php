<?php

namespace app\modules\api\controllers;

use common\models\Tasks;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

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
                    'actions' => ['index', 'error', 'view', 'update', 'create', 'delete'],
                    'allow' => true,
                ],
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

        $actions['index']['prepareDataProvider'] = function () {
            $query = Tasks::find()
                ->with('translator');
            
            // Получаем параметры фильтрации
            $dateStart = \Yii::$app->request->get('dateStart');
            $dateStop = \Yii::$app->request->get('dateStop');
            
            if ($dateStart) {
                $query->andWhere(['>=', 'task_date', $dateStart]);
            }
            
            if ($dateStop) {
                $query->andWhere(['<=', 'task_date', $dateStop]);
            }
            
            return new ActiveDataProvider([
                'query' => $query,
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
