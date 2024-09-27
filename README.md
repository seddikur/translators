# Тестовое задание (переводчики)

## Порядок установки проекта

* Клонирование проекта `` git clone https://github.com/seddikur/translators.git``
* Запуск Docker `` docker-compose up -d ``
* Переход в контейнер  `` docker-compose exec -it php bash ``
* Запуск установки расширений yii2 `` composer install ``
* Инициализация yii2 `` php init ``

настройка соединения bd
common/config  main-local.php
````
 'db' => [
            'class' => \yii\db\Connection::class,
              'dsn' => 'mysql:host=translators-db-1;dbname=yii',
            'username' => 'root',
            'password' => 'secret',
            'charset' => 'utf8',
        ],
````

* Запуск миграций `` php yii migrate ``
* Запуск скрипта для создания пользователей `` php yii user/create-users``

Скрипт создаст:
Пользователя с ролью админ (логин / пароль): admin / 12345
Пользователей с ролью менеджер (логин / пароль): manager1 / 12345 , manager2 / 12345
Пользователей с ролью юзер (логин / пароль): user1 / 12345 , user2 / 12345

