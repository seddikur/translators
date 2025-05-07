<?php

namespace backend\controllers;

use common\models\Tasks;
use backend\models\TasksSearch;
use common\models\Translator;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TasksController реализует CRUD действия для модели Tasks.
 */
class TasksController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Список всех моделей Tasks.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TasksSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображает одну модель Tasks.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException если модель не найдена
     */
    public function actionView($id)
    {
        $dataProviderTranslator = new ActiveDataProvider([
            'query' => Translator::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'id',
                    'name',
                    'type',
                    'available_days',
                    'leadTime' => [
                        'asc' => ['type' => SORT_ASC],
                        'desc' => ['type' => SORT_DESC],
                        'label' => 'Время выполнения',
                        'default' => SORT_ASC
                    ],
                ],
                'defaultOrder' => ['id' => SORT_DESC]
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProviderTranslator' => $dataProviderTranslator,
        ]);
    }

    /**
     * @param int $id_user
     * @param int $id
     * @return void|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionAppoint(int $id_user, int $id)
    {
        if ($id_user != null){
            $model= $this->findModel($id);
            $model->user_id = $id_user;
            $model->save();
            if ($model->save()){
                $translator = Translator::findOne($id_user);
                \Yii::$app->session->setFlash('info','Назначен ответственный -- '.$translator->name);
                return $this->redirect(['view', 'id' => $id]);
            }
        }
    }

    /**
     * Создает новую модель Tasks.
     * Если создание успешно, браузер будет перенаправлен на страницу 'view'.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Tasks();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (!$model->save()) {
                    \Yii::$app->session->setFlash('error', 'Ошибка при сохранении: ' . json_encode($model->errors, JSON_UNESCAPED_UNICODE));
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Обновляет существующую модель Tasks.
     * Если обновление успешно, браузер будет перенаправлен на страницу 'view'.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException если модель не найдена
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if (!$model->save()) {
                \Yii::$app->session->setFlash('error', 'Ошибка при сохранении: ' . json_encode($model->errors, JSON_UNESCAPED_UNICODE));
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаляет существующую модель Tasks.
     * Если удаление успешно, браузер будет перенаправлен на страницу 'index'.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException если модель не найдена
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Находит модель Tasks на основе значения первичного ключа.
     * Если модель не найдена, будет выброшено исключение 404 HTTP.
     * @param int $id ID
     * @return Tasks загруженная модель
     * @throws NotFoundHttpException если модель не найдена
     */
    protected function findModel($id)
    {
        if (($model = Tasks::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}
