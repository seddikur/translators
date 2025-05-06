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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'task_date',
            'descr',
            'date_completion',
            'time_completion:datetime',
            [
                'attribute' => 'user_id',
                'value' => function($model) {
                    $translator = Translator::findOne($model->user_id);
                    return $translator ? $translator->name : '';
                },
                'label' => 'Переводчик'
            ],
        ],
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderTranslator,
        'pager' => [
            'class' => 'yii\bootstrap4\LinkPager'
        ],
        'rowOptions' => function ($translator) use ($model) {
            return [
                'class' => $model->user_id == $translator->id ? 'table-success' : '',
            ];
        },
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'value' => function ($data) use ($model) {
                    $name = $data->name;
                    if ($data->id == $model->user_id) {
                        return Html::tag('span', $name, ['class' => 'badge bg-success']);
                    }
                    return $name;
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'type',
                'value' => function ($data) {
                    return Translator::getTypeList()[$data->type] ?? '';
                }
            ],
            [
                'attribute' => 'available_days',
                'value' => function ($data) {
                    return Translator::getAvailableDaysList()[$data->available_days] ?? '';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{action}',
                'buttons' => [
                    'action' => function ($url, $data) use ($model){
                        return Html::a(
                            'Назначить',
                            ['appoint',
                                'id_user' => $data->id,
                                'id' =>$model->id
                            ], ['class' => 'btn btn-xs btn-success task-appoint']);
                    },
                ]
            ],
        ],
    ]); ?>

</div>
