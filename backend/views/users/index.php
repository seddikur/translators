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

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
            'email:email',
            [
                'class' => StatusColumn::class,
                'attribute' => 'status',
                'name' => 'statusName',
                'cssCLasses' => [
                    Users::STATUS_ACTIVE => 'success',
                    Users::STATUS_INACTIVE => 'warning',
                    Users::STATUS_DELETED => 'default',
                ],
            ],
            //'created_at',
            //'updated_at',
            //'verification_token',
            'role',
            [
                'attribute' => 'busyness',
                'value' => function ($data) {
                    switch ($data->busyness) {
                        case 1: return '<span class="badge rounded-pill bg-danger">пн-пт</span>';
                        case 2: return '<span class="badge rounded-pill bg-warning">пн-вс</span>';
                        default: return 'не указано';
                    }
                },
                'format' => 'html'
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Users $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
