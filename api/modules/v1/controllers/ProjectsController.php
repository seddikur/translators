<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\db\Expression;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Response;

use common\components\Recycling;
use common\models\Drivers;
use common\models\foProjects;
use common\models\ProductionSites;
use common\models\foProjectsHistory;
use common\models\foProjectsStates;

/**
 * Взаимодействие с проектами CRM Fresh Office.
 *
 * @author Roman Karkachev <post@romankarkachev.ru>
 */
class ProjectsController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\foProjects';

    /**
     * {@inheritDoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['view'], $actions['update'], $actions['delete']);
        return $actions;
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'update-state'  => ['POST'],
                    //'create' => ['GET', 'POST'],
                ],
            ],
            [
                'class' => ContentNegotiator::class,
                'only' => ['done', 'today', 'update-state'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * Дополняет массив проектов адресами производственных площадок.
     * @param $projects
     */
    private function complementWithSiteAddress(&$projects)
    {
        // делаем выборку производственных площадок
        $sites = ProductionSites::find()->where(['is not', 'address', null])->asArray()->all();

        foreach ($projects as $index => $project) {
            // идентифицируем площадку
            $key = array_search($project['address_destination'], array_column($sites, 'name'));
            if (false !== $key) {
                $projects[$index]['address_destination'] = $sites[$key]['address'];
                //$projects['destination_name'] = $sites[$key]['name'];
            }
            else {
                $projects[$index]['address_destination'] = null;
            }
            unset($key);
        }
    }

    /**
     * GET-запрос возвращает массив выполненных перевозок.
     * done
     * @return array
     */
    public function actionDone()
    {
        // идентификатор водителя
        $id = intval(Yii::$app->request->get('id'));
        // токен из мобильного приложения
        $token = Yii::$app->request->get('token');
        if (!empty($id) && !empty($token)) {
            $driverModel = Drivers::findOne(['id' => $id, 'mob_app_token' => $token]);
            if ($driverModel) {
                $projects = foProjects::find()->select([
                    'id' => 'ID_LIST_PROJECT_COMPANY',
                    'date_removal' => 'ADD_vivozdate',
                    'address_removal' => 'ADD_adres',
                    'address_destination' => 'ADD_proizodstvo',
                ])->where(['ADD_id_driver' => $id])->andWhere([
                    'or',
                    [foProjects::tableName() . '.TRASH' => null],
                    [foProjects::tableName() . '.TRASH' => 0],
                ])->asArray()->all();

                if (count($projects) > 0) {
                    // перебор только из-за присоединения адреса площадки
                    $this->complementWithSiteAddress($projects);
                }

                return [
                    'result' => true,
                    'projects' => $projects,
                ];
            }
        }

        return [
            'result' => false,
            'errorMessage' => 'Доступ запрещен.',
        ];
    }

    /**
     * Делает выборку заданий на сегодня.
     * today
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionToday()
    {
        // идентификатор водителя
        $id = intval(Yii::$app->request->get('id'));
        // токен из мобильного приложения
        $token = Yii::$app->request->get('token');
        if (!empty($id) && !empty($token)) {
            $driverModel = Drivers::findOne(['id' => $id, 'mob_app_token' => $token]);
            if ($driverModel) {
                // водитель обнаружен
                $today = Yii::$app->formatter->asDate(time(), 'php:Y-m-d');
                //дата, id проекта, адрес, примечание из проекта, контрагент
                $projects = foProjects::find()->select([
                    'id' => 'ID_LIST_PROJECT_COMPANY',
                    'date_removal' => 'ADD_vivozdate',
                    'address_removal' => 'ADD_adres',
                    'companyName' => 'COMPANY_NAME',
                    'comment' => 'PRIM_PROJECT_COMPANY',
                    'address_destination' => 'ADD_proizodstvo',
                ])->where([
                    'ADD_id_driver' => $id,
                ])->andWhere([
                    'or',
                    [foProjects::tableName() . '.TRASH' => null],
                    [foProjects::tableName() . '.TRASH' => 0],
                ])->andWhere([
                    'between', 'ADD_vivozdate', new Expression('CONVERT(datetime, \''. $today .'T00:00:00.000\', 126)'), new Expression('CONVERT(datetime, \''. $today .'T23:59:59.998\', 126)')
                ])->joinWith('company')->asArray()->all();
                if (count($projects) > 0) {
                    // перебор только из-за присоединения адреса площадки
                    $this->complementWithSiteAddress($projects);
                }

                return [
                    'result' => true,
                    'projects' => $projects,
                ];
            }
        }

        return [
            'result' => false,
            'errorMessage' => 'Доступ запрещен.',
        ];
    }

    /**
     * Назначает новый статус проекту, переданному в параметрах.
     * update-state
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdateState()
    {
        // идентификатор водителя
        $driver_id = intval(Yii::$app->request->post('driver_id'));
        // токен из мобильного приложения
        $token = Yii::$app->request->post('token');
        // идентификатор проекта
        $project_id = intval(Yii::$app->request->post('project_id'));
        // идентификатор нового статуса
        $state_id = intval(Yii::$app->request->post('state_id'));
        if (!empty($driver_id) && !empty($token) && !empty($project_id) && !empty($state_id)) {
            $driverModel = Drivers::findOne(['id' => $driver_id, 'mob_app_token' => $token]);
            $projectModel = foProjects::findOne(['ID_LIST_PROJECT_COMPANY' => $project_id]);
            $newStateModel = foProjectsStates::findOne($state_id);
            if (null !== $driverModel) {
                // водитель обнаружен
                if (null !== $projectModel) {
                    // проект обнаружен
                    if (null !== $newStateModel) {
                        if ($projectModel->ADD_id_driver == $driver_id) {
                            if ($projectModel->ID_PRIZNAK_PROJECT != $state_id) {
                                // меняем статус
                                $projectModel->updateAttributes(['ID_PRIZNAK_PROJECT' => $state_id]);

                                // делаем запись об изменении статуса в истории
                                if ((new foProjectsHistory([
                                    'ID_LIST_PROJECT_COMPANY' => $project_id,
                                    'ID_MANAGER' => 73, // freshoffice
                                    'DATE_CHENCH_PRIZNAK' => date('Y-m-d\TH:i:s.000'),
                                    'TIME_CHENCH_PRIZNAK' => Yii::$app->formatter->asDate(time(), 'php:H:i'),
                                    'ID_PRIZNAK_PROJECT' => $state_id,
                                    'RUN_NAME_CHANCH' => 'Водителем ' . $driverModel->representation . ' ID ' . $driver_id . ' из мобильного приложения изменен статус проeкта c "' . $projectModel->stateName . '" на "' . $newStateModel->PRIZNAK_PROJECT . '".',
                                ]))->save()) {
                                    return ['result' => true];
                                }
                                else {
                                    return [
                                        'result' => false,
                                        'errorMessage' => 'Статус изменен, но запись в историю сделать не удалось.',
                                    ];
                                }
                            }
                            else {
                                return ['result' => false, 'errorMessage' => 'Статус не нуждается в обновлении.'];
                            }
                        }
                        else {
                            return [
                                'result' => false,
                                'errorMessage' => 'Водителем по проекту назначено другое лицо.',
                            ];
                        }
                    }
                    else {
                        return [
                            'result' => false,
                            'errorMessage' => 'Недопустимый статус.',
                        ];
                    }
                }
                else {
                    return [
                        'result' => false,
                        'errorMessage' => 'Водитель не обнаружен.',
                    ];
                }
            }
            else {
                return [
                    'result' => false,
                    'errorMessage' => 'Проект не обнаружен.',
                ];
            }
        }
        else {
            return [
                'result' => false,
                'errorMessage' => Recycling::PROMPT_INVALID_PARAMS_PASSED,
            ];
        }

        return [
            'result' => false,
            'errorMessage' => 'Доступ запрещен.',
        ];
    }
}
