<?php

use common\models\Tasks;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\TasksSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/vue.min.js', [ 'position' => $this::POS_HEAD ]);
$this->registerJsFile('/js/vee-validate/vee-validate.js', [ 'position' => $this::POS_HEAD ]);
$this->registerJsFile('/js/vee-validate/locale/ru.js', [ 'position' => $this::POS_HEAD ]);
$this->registerJsFile('/js/uiv.min.js', [ 'position' => $this::POS_HEAD ]);
$this->registerJsFile('/js/vue-app/app.js', [ 'depends' => [yii\web\JqueryAsset::className()] ]);
?>

<div class="task-index" id='app'>

    <h1><?= Html::encode($this->title) ?> [{{ tasks.length }}]</h1>


    <div>

        <table class="table table-bordered">

            <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Description</th>
                <th>User</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody v-for='task in tasks' v-if="!task.isFiltered">
            <tr>
                <td>{{ task.id }}</td>
                <td>{{ task.task_date }}</td>
                <td>{{ task.descr }}</td>
<!--                <td>{{ task.username }}</td>-->
                <td>{{ task.user_id }}</td>
                <td>
                    <button class="btn btn-default btn-sm" type="button" @click="editTasks(task.id)"
                            title="Редактировать">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                    <button class="btn btn-default btn-sm" type="button" @click="deleteTasks(task.id)"
                            title="Удалить">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                </td>
            </tr>
            </tbody>

        </table>

        <p>
            <a class="btn btn-primary" href="/"
               @click.prevent="createTasksForm.show=true">Добавить</a>
        </p>






    </div>
</div>

