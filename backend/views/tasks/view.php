<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Tasks $model */
/** @var yii\data\ActiveDataProvider $dataProviderUser */


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tasks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
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
            'user_id',
        ],
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderUser,
        'pager' => [
            'class' => 'yii\bootstrap4\LinkPager'
        ],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'role',
            [
                'attribute' => 'busyness',
                'value' => function ($data) {
                    switch ($data->busyness) {
                        case 1:
                            return '<span class="badge rounded-pill bg-danger">пн-пт</span>';
                        case 2:
                            return '<span class="badge rounded-pill bg-warning">пн-вс</span>';
                        default:
                            return 'не указано';
                    }
                },
                'format' => 'html'
            ],

            [
                'label' => 'Расчетное время выполнения (дни)',
                'value' => function ($data) use ($model) {
                    return \common\models\Tasks::leadTime($model->id, $data->id);
                },
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

//            [
//                'class' => \yii\grid\ActionColumn::class,
//                'urlCreator' => function ($action, Users $model, $key, $index, $column) {
//                    return \yii\helpers\Url::toRoute([$action, 'id' => $model->id]);
//                }
//            ],
        ],
    ]); ?>

</div>
