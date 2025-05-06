// Используем глобальные переменные
const axios = window.axios

// Компонент списка переводчиков
const TranslatorsList = {
    template: `
        <div class="translators-list">
            <h2>Список переводчиков</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Тип</th>
                        <th>Дни доступности</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="translator in translators" :key="translator.id">
                        <td>{{ translator.id }}</td>
                        <td>{{ translator.name }}</td>
                        <td>{{ getTypeLabel(translator.type) }}</td>
                        <td>{{ getAvailableDaysLabel(translator.available_days) }}</td>
                        <td>
                            <router-link :to="{ name: 'translator-view', params: { id: translator.id }}" class="btn btn-info btn-sm">Просмотр</router-link>
                            <button @click="deleteTranslator(translator.id)" class="btn btn-danger btn-sm">Удалить</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `,
    data() {
        return {
            translators: []
        }
    },
    methods: {
        async loadTranslators() {
            try {
                const response = await axios.get('/translator')
                this.translators = response.data
            } catch (error) {
                console.error('Ошибка при загрузке переводчиков:', error)
            }
        },
        async deleteTranslator(id) {
            if (confirm('Вы уверены, что хотите удалить этого переводчика?')) {
                try {
                    await axios.delete(`/translator/${id}`)
                    this.loadTranslators()
                } catch (error) {
                    console.error('Ошибка при удалении переводчика:', error)
                }
            }
        },
        getTypeLabel(type) {
            const types = {
                'full_time': 'Полный день',
                'part_time': 'Частичная занятость'
            }
            return types[type] || type
        },
        getAvailableDaysLabel(days) {
            const availableDays = {
                'weekdays': 'Будни',
                'weekends': 'Выходные'
            }
            return availableDays[days] || days
        }
    },
    mounted() {
        this.loadTranslators()
    }
}

// Компонент просмотра переводчика
const TranslatorView = {
    template: `
        <div class="translator-view">
            <h2>Просмотр переводчика</h2>
            <div v-if="translator" class="card">
                <div class="card-body">
                    <h5 class="card-title">Переводчик #{{ translator.id }}</h5>
                    <p class="card-text"><strong>ФИО:</strong> {{ translator.name }}</p>
                    <p class="card-text"><strong>Тип:</strong> {{ getTypeLabel(translator.type) }}</p>
                    <p class="card-text"><strong>Дни доступности:</strong> {{ getAvailableDaysLabel(translator.available_days) }}</p>
                    <router-link :to="{ name: 'translators-list' }" class="btn btn-secondary">Назад к списку</router-link>
                </div>
            </div>
        </div>
    `,
    data() {
        return {
            translator: null
        }
    },
    methods: {
        async loadTranslator() {
            try {
                const response = await axios.get(`/translator/${this.$route.params.id}`)
                this.translator = response.data
            } catch (error) {
                console.error('Ошибка при загрузке переводчика:', error)
            }
        },
        getTypeLabel(type) {
            const types = {
                'full_time': 'Полный день',
                'part_time': 'Частичная занятость'
            }
            return types[type] || type
        },
        getAvailableDaysLabel(days) {
            const availableDays = {
                'weekdays': 'Будни',
                'weekends': 'Выходные'
            }
            return availableDays[days] || days
        }
    },
    mounted() {
        this.loadTranslator()
    }
}

export { TranslatorsList, TranslatorView } 