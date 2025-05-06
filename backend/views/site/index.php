<?php
/* @var $this yii\web\View */
/* @var $stats array */

use yii\helpers\Html;

$this->title = 'Панель управления';
?>

<div class="site-index">
    <div id="admin-app">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Статистика</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="text-muted">Заказы</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Всего заказов:</span>
                                <span class="badge badge-primary"><?= $stats['tasks']['total'] ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="text-muted">Переводчики</h6>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Всего переводчиков:</span>
                                <span class="badge badge-info"><?= $stats['translators']['total'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Полный день:</span>
                                <span class="badge badge-success"><?= $stats['translators']['fullTime'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Частичная занятость:</span>
                                <span class="badge badge-warning"><?= $stats['translators']['partTime'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Работают в будни:</span>
                                <span class="badge badge-secondary"><?= $stats['translators']['weekdays'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Работают в выходные:</span>
                                <span class="badge badge-secondary"><?= $stats['translators']['weekends'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Последние заказы</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($stats['recentTasks']): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Дата</th>
                                            <th>Описание</th>
                                            <th>Ответственный</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stats['recentTasks'] as $task): ?>
                                            <tr>
                                                <td><?= $task->id ?></td>
                                                <td><?= Yii::$app->formatter->asDate($task->task_date) ?></td>
                                                <td><?= Html::encode($task->descr) ?></td>
                                                <td>
                                                    <?php if ($task->translator): ?>
                                                        <?= Html::encode($task->translator->name) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Не назначен</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Нет последних заказов</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-header {
        font-weight: 500;
    }
    .badge {
        font-size: 0.875rem;
        padding: 0.5em 0.75em;
    }
    .table th {
        font-weight: 500;
        background-color: #f8f9fa;
    }
</style>

