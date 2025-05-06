<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Translator;

/* @var $this yii\web\View */
/* @var $model common\models\Translator */

$this->title = 'Редактировать переводчика: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Переводчики', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="translator-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="translator-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->dropDownList(
            Translator::getTypeList(),
            ['prompt' => 'Выберите тип']
        ) ?>

        <?= $form->field($model, 'available_days')->dropDownList(
            Translator::getAvailableDaysList(),
            ['prompt' => 'Выберите дни доступности']
        ) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div> 