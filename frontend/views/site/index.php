<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Заказы';

// Подключаем скрипты в правильном порядке
$this->registerJsFile('/js/vue.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('/js/vee-validate/vee-validate.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('/js/vee-validate/locale/ru.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('/js/uiv.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('/js/vue-app/app.js', [
    'position' => $this::POS_END,
    'depends' => [yii\web\JqueryAsset::className()]
]);
?>

<div class="site-index">
    <div id="app">
        <task-table></task-table>
    </div>
</div>

<style>
    .sort-asc, .sort-desc {
        background-color: #f5f5f5;
    }
    .sort-asc .glyphicon, .sort-desc .glyphicon {
        margin-left: 5px;
    }
    th {
        cursor: pointer;
    }
    th:hover {
        background-color: #e9e9e9;
    }
</style>

<script>
// Проверка загрузки скриптов
window.addEventListener('load', function() {
    console.log('Window loaded');
    if (typeof Vue === 'undefined') {
        console.error('Vue is not loaded!');
    } else {
        console.log('Vue is loaded');
    }
    if (typeof VeeValidate === 'undefined') {
        console.error('VeeValidate is not loaded!');
    } else {
        console.log('VeeValidate is loaded');
    }
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
    } else {
        console.log('jQuery is loaded');
    }
    
    // Проверяем наличие элемента #app
    const appElement = document.getElementById('app');
    if (!appElement) {
        console.error('Element #app not found!');
    } else {
        console.log('Element #app found');
    }
});
</script>