<?php

use common\models\Tasks;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
Use common\models\Users;
use bestyii\bootstrap\icons\assets\BootstrapIconAsset;

/** @var yii\web\View $this */
/** @var backend\models\TasksSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

BootstrapIconAsset::register($this);

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tasks', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'task_date',
            'descr',
            'date_completion',
//            'time_completion',
            'user_id',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Tasks $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} ',
                'headerOptions' => ['style' => 'width:5%'],
                'buttons' => [

                    'view' => function ($url, $model) {
                        return Html::a(
                            '<i class="bi bi-person-check-fill"></i>',
                            ['tasks/view', 'id' => $model->id],
                            [
                                'style' => 'color: #0056b3 !important;',
                            ]);
                    },

                ],
            ],
        ],
    ]); ?>



</div>
