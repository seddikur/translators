<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Translator;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var common\models\Tasks $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tasks-form">
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(Translator::find()->all(), 'id', 'name'),
        ['prompt' => 'Выберите переводчика']
    ) ?>

    <?= $form->field($model, 'date_completion')->widget(DatePicker::class, [
        'options' => ['placeholder' => 'Выберите дату выполнения ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ],
    ]) ?>

    <?= $form->field($model, 'time_completion')->textInput([
        'type' => 'number',
        'min' => 0,
        'max' => 1440,
        'placeholder' => 'Введите время в минутах (0-1440)',
        'title' => 'Введите время в минутах от 0 до 1440'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
