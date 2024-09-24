<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Users;

/** @var yii\web\View $this */
/** @var \common\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <?= $form->field($model, 'busyness')->dropDownList(Users::getBusynessArray()) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
