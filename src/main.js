import '../css/diary.scss'

import Vue from 'vue'
import App from './App'
import router from './router'

Vue.config.devtools = (process.env.NODE_ENV === 'development')

Vue.mixin({ methods: { t, n } })

export default new Vue({
	el: '#vue-content',
	router,
	render: h => h(App),
})
