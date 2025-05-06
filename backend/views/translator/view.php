<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Translator;

/* @var $this yii\web\View */
/* @var $model common\models\Translator */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Переводчики', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="translator-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого переводчика?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return Translator::getTypeList()[$model->type] ?? $model->type;
                },
            ],
            [
                'attribute' => 'available_days',
                'value' => function ($model) {
                    return Translator::getAvailableDaysList()[$model->available_days] ?? $model->available_days;
                },
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
        ],
    ]) ?>

</div> 