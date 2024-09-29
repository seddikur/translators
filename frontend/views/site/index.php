<?php

use yii\helpers\Html;

$this->title = 'Tasks';

$this->registerJsFile('/js/vue.min.js', [ 'position' => $this::POS_HEAD ]);
$this->registerJsFile('/js/vee-validate/vee-validate.js', [ 'position' => $this::POS_HEAD ]);
$this->registerJsFile('/js/vee-validate/locale/ru.js', [ 'position' => $this::POS_HEAD ]);
$this->registerJsFile('/js/uiv.min.js', [ 'position' => $this::POS_HEAD ]);
$this->registerJsFile('/js/vue-app/app.js', [ 'depends' => [yii\web\JqueryAsset::className()] ]);

?>

<div class="task-index" id='app'>

    <h1><?= Html::encode($this->title) ?> [{{ tasks.length }}]</h1>

<!--    <div id="filter-bar">-->
<!--        <template>-->
<!--            Фильтр по дате размещения: с-->
<!--            <form class="form-inline">-->
<!--                <dropdown class="form-group">-->
<!--                    <div class="input-group">-->
<!--                        <input class="form-control" type="text" name="date-start"-->
<!--                               v-model="dateStart" v-validate="'date_format:YYYY-MM-DD'" data-vv-as="Дата с">-->
<!--                        <div class="input-group-btn">-->
<!--                            <btn class="dropdown-toggle"><i class="glyphicon glyphicon-calendar"></i></btn>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <template slot="dropdown">-->
<!--                        <li>-->
<!--                            <date-picker v-model="dateStart" :week-starts-with="1">-->
<!--                        </li>-->
<!--                    </template>-->
<!--                </dropdown>-->
<!--            </form>-->
<!--            по-->
<!--            <form class="form-inline">-->
<!--                <dropdown class="form-group">-->
<!--                    <div class="input-group">-->
<!--                        <input class="form-control" type="text" name="date-stop" v-model="dateStop"-->
<!--                               v-validate="'date_format:YYYY-MM-DD'" data-vv-as="Дата по">-->
<!--                        <div class="input-group-btn">-->
<!--                            <btn class="dropdown-toggle"><i class="glyphicon glyphicon-calendar"></i></btn>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <template slot="dropdown">-->
<!--                        <li>-->
<!--                            <date-picker v-model="dateStop" :week-starts-with="1">-->
<!--                        </li>-->
<!--                    </template>-->
<!--                </dropdown>-->
<!--            </form>-->
<!--            <btn type="primary" disabled v-if="errors.any()">'Apply'</btn>-->
<!--            <btn type="primary" @click="applyFilter()"-->
<!--                 v-if="!errors.any()">Apply</btn>-->
<!--        </template>-->
<!--    </div>-->
    <alert v-if="errors.has('date-start')" type="warning">{{ errors.first('date-start') }}</alert>
    <alert v-if="errors.has('date-stop')" type="warning">{{ errors.first('date-stop') }}</alert>

    <div>

        <table class="table table-btasked">

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
<!--                    <button class="btn btn-primary btn-sm" type="button" @click="editTask(task.id)"-->
<!--                            title="Edit">-->
<!--                        <i class="bi bi-pencil" aria-hidden="true"></i>-->
<!--                    </button>-->
<!--                    <button class="btn btn-primary btn-sm" type="button" @click="deleteTask(task.id)"-->
<!--                            title="Delete">-->
<!--                        <i class="bi bi-trash" aria-hidden="true"></i>-->
<!--                    </button>-->
                </td>
            </tr>
            </tbody>

        </table>






    </div>
</div>