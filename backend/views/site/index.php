<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Панель управления';

// Подключаем скрипты в правильном порядке
$this->registerJsFile('/js/vue.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('/js/vee-validate/vee-validate.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('/js/vee-validate/locale/ru.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('/js/uiv.min.js', ['position' => $this::POS_HEAD]);
$this->registerJsFile('/js/vue-app/admin.js', [
    'position' => $this::POS_END,
    'depends' => [yii\web\JqueryAsset::className()]
]);
?>

<div class="site-index">
    <div id="admin-app">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Статистика</div>
                    <div class="panel-body">
                        <!-- Здесь будет статистика -->
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Последние действия</div>
                    <div class="panel-body">
                        <!-- Здесь будет список последних действий -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .panel {
        margin-bottom: 20px;
    }
    .panel-heading {
        font-weight: bold;
    }
</style>

<script>
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
    
    const appElement = document.getElementById('admin-app');
    if (!appElement) {
        console.error('Element #admin-app not found!');
    } else {
        console.log('Element #admin-app found');
    }
});
</script> 