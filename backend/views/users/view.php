<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var \common\models\Users $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id' => [
                'attribute' => 'id',
                'label' => 'ID',
            ],
            'username' => [
                'attribute' => 'username',
                'label' => 'Логин',
            ],
            'email:email' => [
                'attribute' => 'email',
                'label' => 'Email',
            ],
            'status' => [
                'attribute' => 'status',
                'label' => 'Статус',
            ],
            'created_at:date' => [
                'attribute' => 'created_at',
                'label' => 'Дата создания',
            ],
            'updated_at:date' => [
                'attribute' => 'updated_at',
                'label' => 'Дата обновления',
            ],
            'role' => [
                'attribute' => 'role',
                'label' => 'Роль',
                'value' => function ($model) {
                    $roles = [
                        \common\models\Users::Role_User => 'Пользователь',
                        \common\models\Users::Role_Manager => 'Менеджер',
                        \common\models\Users::Role_Admin => 'Администратор',
                    ];
                    return $roles[$model->role] ?? 'Неизвестная роль';
                },
            ],
        ],
    ]) ?>

</div>
