// Используем глобальные переменные вместо импортов
const Vue = window.Vue
const axios = window.axios
const VueRouter = window.VueRouter
import { TranslatorsList, TranslatorView } from './translators.js'

// Настройка axios
axios.defaults.baseURL = 'http://localhost/admin/api'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

Vue.use(VueRouter)

// Компонент списка задач
const TasksList = {
    template: `
        <div class="tasks-list">
            <h2>Список задач</h2>
            <div class="filters mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="date" v-model="dateStart" class="form-control" placeholder="Дата начала">
                    </div>
                    <div class="col-md-4">
                        <input type="date" v-model="dateStop" class="form-control" placeholder="Дата окончания">
                    </div>
                    <div class="col-md-4">
                        <button @click="loadTasks" class="btn btn-primary">Применить фильтр</button>
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Дата</th>
                        <th>Описание</th>
                        <th>Переводчик</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in tasks" :key="task.id">
                        <td>{{ task.id }}</td>
                        <td>{{ task.task_date }}</td>
                        <td>{{ task.descr }}</td>
                        <td>{{ task.translator ? task.translator.name : 'Не назначен' }}</td>
                        <td>
                            <router-link :to="{ name: 'task-view', params: { id: task.id }}" class="btn btn-info btn-sm">Просмотр</router-link>
                            <button @click="deleteTask(task.id)" class="btn btn-danger btn-sm">Удалить</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `,
    data() {
        return {
            tasks: [],
            dateStart: '',
            dateStop: ''
        }
    },
    methods: {
        async loadTasks() {
            try {
                const params = {}
                if (this.dateStart) params.dateStart = this.dateStart
                if (this.dateStop) params.dateStop = this.dateStop
                
                const response = await axios.get('/tasks', { params })
                this.tasks = response.data
            } catch (error) {
                console.error('Ошибка при загрузке задач:', error)
            }
        },
        async deleteTask(id) {
            if (confirm('Вы уверены, что хотите удалить эту задачу?')) {
                try {
                    await axios.delete(`/tasks/${id}`)
                    this.loadTasks()
                } catch (error) {
                    console.error('Ошибка при удалении задачи:', error)
                }
            }
        }
    },
    mounted() {
        this.loadTasks()
    }
}

// Компонент просмотра задачи
const TaskView = {
    template: `
        <div class="task-view">
            <h2>Просмотр задачи</h2>
            <div v-if="task" class="card">
                <div class="card-body">
                    <h5 class="card-title">Задача #{{ task.id }}</h5>
                    <p class="card-text"><strong>Дата:</strong> {{ task.task_date }}</p>
                    <p class="card-text"><strong>Описание:</strong> {{ task.descr }}</p>
                    <p class="card-text"><strong>Переводчик:</strong> {{ task.translator ? task.translator.name : 'Не назначен' }}</p>
                    <router-link :to="{ name: 'tasks-list' }" class="btn btn-secondary">Назад к списку</router-link>
                </div>
            </div>
        </div>
    `,
    data() {
        return {
            task: null
        }
    },
    methods: {
        async loadTask() {
            try {
                const response = await axios.get(`/tasks/${this.$route.params.id}`)
                this.task = response.data
            } catch (error) {
                console.error('Ошибка при загрузке задачи:', error)
            }
        }
    },
    mounted() {
        this.loadTask()
    }
}

// Настройка маршрутов
const routes = [
    { path: '/', name: 'tasks-list', component: TasksList },
    { path: '/task/:id', name: 'task-view', component: TaskView },
    { path: '/translators', name: 'translators-list', component: TranslatorsList },
    { path: '/translator/:id', name: 'translator-view', component: TranslatorView }
]

const router = new VueRouter({
    routes
})

// Создание и монтирование приложения
new Vue({
    router,
    el: '#app'
}) 