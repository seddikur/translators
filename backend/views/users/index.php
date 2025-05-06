<?php

use common\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\widgets\grid\{StatusColumn};
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => 'yii\bootstrap4\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'username' => [
                'attribute' => 'username',
                'label' => 'Логин',
            ],
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
            'email:email' => [
                'attribute' => 'email',
                'label' => 'Email',
            ],
            [
                'class' => StatusColumn::class,
                'attribute' => 'status',
                'name' => 'statusName',
                'label' => 'Статус',
                'cssCLasses' => [
                    Users::STATUS_ACTIVE => 'success',
                    Users::STATUS_INACTIVE => 'warning',
                    Users::STATUS_DELETED => 'default',
                ],
            ],
            //'created_at',
            //'updated_at',
            //'verification_token',
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
         
            [
                'class' => ActionColumn::class,
                'header' => 'Действия',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, ['title' => 'Просмотр']);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-pencil-alt"></i>', $url, ['title' => 'Редактировать']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                'urlCreator' => function ($action, Users $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
