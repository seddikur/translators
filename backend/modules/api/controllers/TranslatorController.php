<?php

namespace app\modules\api\controllers;

use common\models\Translator;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * REST контроллер переводчиков
 *
 * Доступ к контроллеру только для аутентифицированных пользователей
 *
 * GET /translators - список всех переводчиков
 * GET /translators/1 - просмотр переводчика с ID = 1
 * POST /translators - добавление переводчика
 * PUT /translators/1 - обновление переводчика с ID = 1
 * DELETE /translators/1 - удаление переводчика с ID = 1
 */
class TranslatorController extends ActiveController
{
    public $modelClass = 'common\models\Translator';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => ['index', 'view', 'create', 'update', 'delete'],
                    'allow' => true,
                ],
            ],
        ];
        return $behaviors;
    }

    /**
     * Переопределяем actions по своему усмотрению
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = function () {
            return new ActiveDataProvider([
                'query' => Translator::find(),
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort' => [
                    'defaultOrder' => ['id' => SORT_DESC],
                ],
            ]);
        };

        return $actions;
    }

    /**
     * Находит модель Translator на основе значения первичного ключа.
     * Если модель не найдена, будет выброшено исключение 404 HTTP.
     * @param int $id ID
     * @return Translator загруженная модель
     * @throws NotFoundHttpException если модель не найдена
     */
    protected function findModel($id)
    {
        if (($model = Translator::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый переводчик не найден.');
    }
} 