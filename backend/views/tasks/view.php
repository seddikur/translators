<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Translator;

/** @var yii\web\View $this */
/** @var common\models\Tasks $model */
/** @var yii\data\ActiveDataProvider $dataProviderTranslator */


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tasks-view">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <p class="mb-4">
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mr-2']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Детали задачи</h5>
        </div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'task_date',
                    'descr',
                    'date_completion',
                    [
                        'attribute' => 'leadTime',
                        'value' => function($model) {
                            return \common\models\Tasks::leadTime($model->id, $model->user_id) . ' дней';
                        },
                        'label' => 'Время выполнения'
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function($model) {
                            $translator = Translator::findOne($model->user_id);
                            return $translator ? $translator->name : '';
                        },
                        'label' => 'Переводчик'
                    ],
                ],
                'options' => ['class' => 'table table-striped table-bordered detail-view'],
            ]) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Список переводчиков</h5>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProviderTranslator,
                'pager' => [
                    'class' => 'yii\bootstrap4\LinkPager',
                    'options' => ['class' => 'pagination justify-content-center'],
                ],
                'tableOptions' => ['class' => 'table table-striped table-bordered'],
                'rowOptions' => function ($translator) use ($model) {
                    return [
                        'class' => $model->user_id == $translator->id ? 'table-success' : '',
                    ];
                },
                'columns' => [
                    [
                        'attribute' => 'id',
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'name',
                        'value' => function ($data) use ($model) {
                            $name = $data->name;
                            if ($data->id == $model->user_id) {
                                return Html::tag('span', $name, ['class' => 'badge badge-success']);
                            }
                            return $name;
                        },
                        'format' => 'html'
                    ],
                    [
                        'attribute' => 'type',
                        'value' => function ($data) {
                            return Translator::getTypeList()[$data->type] ?? '';
                        },
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'available_days',
                        'value' => function ($data) {
                            return Translator::getAvailableDaysList()[$data->available_days] ?? '';
                        },
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'leadTime',
                        'value' => function ($data) use ($model) {
                            return \common\models\Tasks::leadTime($model->id, $data->id) . ' дней';
                        },
                        'label' => 'Время выполнения',
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['class' => 'text-center'],
                        'enableSorting' => true,
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{action}',
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['class' => 'text-center'],
                        'buttons' => [
                            'action' => function ($url, $data) use ($model){
                                return Html::a(
                                    'Назначить',
                                    ['appoint',
                                        'id_user' => $data->id,
                                        'id' =>$model->id
                                    ], ['class' => 'btn btn-sm btn-success task-appoint']);
                            },
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>
