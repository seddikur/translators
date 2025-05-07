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
        'pager' => [
            'class' => 'yii\bootstrap4\LinkPager'
        ],
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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'Просмотр',
                            'class' => 'btn btn-sm btn-info',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => 'Редактировать',
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => 'Удалить',
                            'class' => 'btn btn-sm btn-danger',
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