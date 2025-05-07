<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Translator;
use bestyii\bootstrap\icons\assets\BootstrapIconAsset;

/** @var yii\web\View $this */
/** @var backend\models\TasksSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

BootstrapIconAsset::register($this);

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать задачу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => 'yii\bootstrap4\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'task_date',
            'descr',
            'date_completion',
//            'time_completion',
            [
                'attribute' => 'user_id',
                'value' => function($model) {
                    $translator = Translator::findOne($model->user_id);
                    return $translator ? $translator->name : '';
                },
                'label' => 'Переводчик'
            ],
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Tasks $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'headerOptions' => ['style' => 'width:15%'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '<i class="bi bi-person-check-fill"></i>',
                            ['tasks/view', 'id' => $model->id],
                            [
                                'style' => 'color: #0056b3 !important;',
                            ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<i class="bi bi-pencil-fill"></i>',
                            ['tasks/update', 'id' => $model->id],
                            [
                                'style' => 'color: #28a745 !important;',
                                'title' => 'Редактировать',
                            ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            '<i class="bi bi-trash-fill"></i>',
                            ['tasks/delete', 'id' => $model->id],
                            [
                                'style' => 'color: #dc3545 !important;',
                                'title' => 'Удалить',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                    'method' => 'post',
                                ],
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>
