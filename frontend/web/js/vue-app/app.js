// Инициализация Vue приложения
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем наличие элемента #app
    const appElement = document.getElementById('app');
    if (!appElement) {
        console.error('Element #app not found!');
        return;
    }

    // Проверяем, что Vue загружен
    if (typeof Vue === 'undefined') {
        console.error('Vue is not loaded!');
        return;
    }

    // Инициализируем VeeValidate
    if (typeof VeeValidate !== 'undefined') {
        Vue.use(VeeValidate, {
            locale: 'ru',
        });
    } else {
        console.error('VeeValidate is not loaded!');
        return;
    }

    // Создаем компонент
    Vue.component('task-table', {
        template: `
            <div>
                <h1>Заказы <span v-if="tasks.length">[{{ tasks.length }}]</span></h1>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th @click="sortBy('id')" :class="{ 'sort-asc': sortKey === 'id' && sortOrder === 'asc', 'sort-desc': sortKey === 'id' && sortOrder === 'desc' }">
                                ID <i class="glyphicon" :class="sortIcon('id')"></i>
                            </th>
                            <th @click="sortBy('task_date')" :class="{ 'sort-asc': sortKey === 'task_date' && sortOrder === 'asc', 'sort-desc': sortKey === 'task_date' && sortOrder === 'desc' }">
                                Date <i class="glyphicon" :class="sortIcon('task_date')"></i>
                            </th>
                            <th @click="sortBy('descr')" :class="{ 'sort-asc': sortKey === 'descr' && sortOrder === 'asc', 'sort-desc': sortKey === 'descr' && sortOrder === 'desc' }">
                                Description <i class="glyphicon" :class="sortIcon('descr')"></i>
                            </th>
                            <th @click="sortBy('user_id')" :class="{ 'sort-asc': sortKey === 'user_id' && sortOrder === 'asc', 'sort-desc': sortKey === 'user_id' && sortOrder === 'desc' }">
                                User <i class="glyphicon" :class="sortIcon('user_id')"></i>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="task in sortedTasks" :key="task.id">
                            <td>{{ task.id }}</td>
                            <td>{{ task.task_date }}</td>
                            <td>{{ task.descr }}</td>
                            <td>{{ task.user_id }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        `,
        data() {
            return {
                tasks: [],
                sortKey: 'id',
                sortOrder: 'asc'
            }
        },
        computed: {
            sortedTasks() {
                if (!this.tasks || !this.tasks.length) {
                    return [];
                }
                return [...this.tasks].sort((a, b) => {
                    let aVal = a[this.sortKey];
                    let bVal = b[this.sortKey];
                    
                    if (this.sortKey === 'task_date') {
                        aVal = new Date(aVal);
                        bVal = new Date(bVal);
                    }
                    
                    if (aVal < bVal) {
                        return this.sortOrder === 'asc' ? -1 : 1;
                    }
                    if (aVal > bVal) {
                        return this.sortOrder === 'asc' ? 1 : -1;
                    }
                    return 0;
                });
            }
        },
        mounted() {
            this.loadTasks();
        },
        methods: {
            loadTasks() {
                $.ajax({
                    dataType: 'json',
                    url: '/admin/api/tasks',
                    success: (data) => {
                        this.tasks = data;
                    },
                    error: (data) => {
                        console.error('Error loading tasks:', data);
                    }
                });
            },
            sortBy(key) {
                if (this.sortKey === key) {
                    this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortKey = key;
                    this.sortOrder = 'asc';
                }
            },
            sortIcon(key) {
                if (this.sortKey !== key) {
                    return 'glyphicon-sort';
                }
                return this.sortOrder === 'asc' ? 'glyphicon-sort-by-attributes' : 'glyphicon-sort-by-attributes-alt';
            }
        }
    });

    // Создаем экземпляр Vue
    window.app = new Vue({
        el: '#app'
    });
});
