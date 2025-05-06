<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Translator;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Translator */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Переводчики';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="translator-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить переводчика', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return Translator::getTypeList()[$model->type] ?? $model->type;
                },
                'filter' => Translator::getTypeList(),
            ],
            [
                'attribute' => 'available_days',
                'value' => function ($model) {
                    return Translator::getAvailableDaysList()[$model->available_days] ?? $model->available_days;
                },
                'filter' => Translator::getAvailableDaysList(),
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->created_at);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->updated_at);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => 'Просмотр',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => 'Редактировать',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить этого переводчика?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div> 