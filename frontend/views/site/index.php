<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Главная';
?>

<div class="site-index">
    <div class="jumbotron bg-light">
        <h1 class="display-4">Добро пожаловать!</h1>
        <p class="lead">Система управления заказами и переводчиками</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title h4">Заказы</h2>
                        <p class="card-text">Управление заказами через REST API:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><code>GET /tasks</code> - список всех заказов</li>
                            <li class="mb-2"><code>GET /tasks/1</code> - просмотр заказа с ID = 1</li>
                            <li class="mb-2"><code>POST /tasks</code> - добавление заказа</li>
                            <li class="mb-2"><code>PUT /tasks/1</code> - обновление заказа</li>
                            <li class="mb-2"><code>DELETE /tasks/1</code> - удаление заказа</li>
                        </ul>
                        <a class="btn btn-primary" href="http://localhost/index.html#/">Перейти к заказам &raquo;</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title h4">Переводчики</h2>
                        <p class="card-text">Управление переводчиками через REST API:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><code>GET /translators</code> - список всех переводчиков</li>
                            <li class="mb-2"><code>GET /translators/1</code> - просмотр переводчика с ID = 1</li>
                            <li class="mb-2"><code>POST /translators</code> - добавление переводчика</li>
                            <li class="mb-2"><code>PUT /translators/1</code> - обновление переводчика</li>
                            <li class="mb-2"><code>DELETE /translators/1</code> - удаление переводчика</li>
                        </ul>
                        <a class="btn btn-primary" href="http://localhost/index.html#/translators">Перейти к переводчикам &raquo;</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title h4">Управление через админ-панель</h2>
                        <p class="card-text">Для управления заказами и переводчиками через веб-интерфейс используйте админ-панель:</p>
                        <div class="alert alert-info">
                            <strong>Адрес админ-панели:</strong> <a href="http://localhost/admin" class="alert-link">http://localhost/admin</a><br>
                            <strong>Логин:</strong> admin<br>
                            <strong>Пароль:</strong> 12345
                        </div>
                        <p class="card-text">В админ-панели доступны следующие функции:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Управление заказами:</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">✓ Просмотр списка всех заказов</li>
                                    <li class="mb-2">✓ Создание новых заказов</li>
                                    <li class="mb-2">✓ Редактирование существующих заказов</li>
                                    <li class="mb-2">✓ Удаление заказов</li>
                                    <li class="mb-2">✓ Назначение переводчиков на заказы</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5>Управление переводчиками:</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">✓ Просмотр списка всех переводчиков</li>
                                    <li class="mb-2">✓ Добавление новых переводчиков</li>
                                    <li class="mb-2">✓ Редактирование информации о переводчиках</li>
                                    <li class="mb-2">✓ Удаление переводчиков</li>
                                    <li class="mb-2">✓ Просмотр заказов переводчика</li>
                                </ul>
                            </div>
                        </div>
                        <a href="http://localhost/admin" class="btn btn-success">Перейти в админ-панель &raquo;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



