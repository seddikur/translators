Vue.use(VeeValidate, {
    locale: 'ru',
});

var vm = new Vue({
    el: '#app',
    data: {
        tasks: [],
        createTaskForm: {
            show: false,        // Признак активности модального окна "Создание заказа"
            descr: 'заказ',
        },
        editTaskForm: {
            show: false,        // Признак активности модального окна "Редактирование заказа"
            id: -1,
            descr: '',
        },
        dateStart: '',          // Начальная дата фильтра
        dateStop: '',           // Конечная дата фильтра
    },

    mounted() {
        this.reloadTasks();
    },

    methods: {

        // Фильтр по дате размещения

        applyFilter: function () {
            console.log('applyFilter');
            // Меняем даты местами, если это необходимо

            if ((this.dateStop != '' && this.dateStart != '') && this.dateStop < this.dateStart) {
                var tmp = this.dateStart;
                this.dateStart = this.dateStop;
                this.dateStop = tmp;
            }

            var self = this;

            this.tasks.forEach(function (item, i) {
                if (self.dateStart == '' && self.dateStop == '') {
                    self.tasks[i].isFiltered = false;

                } else if (self.dateStart == '' || self.dateStop == '') {
                    if (self.dateStart == '' && self.dateStop != '' && item.task_date > self.dateStop) {
                        self.tasks[i].isFiltered = true;
                    } else if (self.dateStart != '' && self.dateStop == '' && item.task_date < self.dateStart) {
                        self.tasks[i].isFiltered = true;
                    } else {
                        self.tasks[i].isFiltered = false;
                    }

                } else if (item.tasks_date < self.dateStart || item.task_date > self.dateStop) {
                    self.tasks[i].isFiltered = true;
                } else {
                    self.tasks[i].isFiltered = false;
                }
            });
        },

        // Вывод списка заказов

        reloadTasks: function () {
            var self = this;
            $.ajax({
                dataType: 'json',
                url: '/tasks',
            })
                .done(function (data) {
                    data.forEach(function (item, i) {
                        data[i].isFiltered = false;
                    });
                    self.tasks = data;
                    self.applyFilter();
                })
                .fail(function (data) {
                    console.log(data);
                });
        },


        // Создание заказа

        createTask: function () {
            console.log('createTask');

            var self = this;

            $.ajax({
                method: 'POST',
                url: '/tasks',
                data: {descr: this.createTaskForm.descr}
            })
                .done(function (data) {

                    self.createTaskForm.show = false;
                    self.reloadTask();
                    self.notifySuccess('Создание завершено!');

                })
                .fail(function (data) {
                    self.notifyWarning('Ошибка создания');
                });

        },

        // Обновление заказа

        updateTask: function (id) {

            var self = this;

            $.ajax({
                method: 'PUT',
                url: '/tasks/' + id,
                data: {descr: this.editTaskForm.descr}
            })
                .done(function (data) {

                    self.editTaskForm.show = false;
                    self.reloadTask();
                    self.notifySuccess('Обновление завершено!');

                })
                .fail(function (data) {
                    self.notifyWarning('Ошибка обновления');
                });
        },

        // Редактирование заказа

        editTask: function (id) {

            var self = this;

            // На UI не ориентируемся, берем из базы свежие данные, если их нет - сообщаем об этом

            $.ajax({
                url: '/tasks/' + id,
            })
                .done(function (data) {

                    self.editTaskForm.descr = data.descr;
                    self.editTaskForm.id = data.id;
                    self.editTaskForm.show = true;

                })
                .fail(function (data) {
                    self.notifyWarning('Ошибка чтения заказа');
                });


        },

        // Удаление заказа

        deleteTask: function (id) {

            var self = this;

            this.$confirm({
                content: 'Удалить заказ № [' + id + ']?'
            })
                .then(function () {

                    $.ajax({
                        method: 'DELETE',
                        url: '/tasks/' + id,
                    })
                        .done(function (data) {

                            self.reloadTask();
                            self.notifySuccess('Удаление завершено!');

                        })
                        .fail(function (data) {
                            self.notifyWarning('Ошибка удаления');
                        });
                });
        },

        // Нотификаторы

        notifySuccess: function (content) {
            this.notify('success', content);
        },
        notifyWarning: function (content) {
            this.notify('warning', content);
        },
        notify: function (type, content) {
            this.$notify({type: type, content: content, placement: 'bottom-right', duration: 3000});
        },

    }
})
