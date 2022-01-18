import Vue from 'vue'
import App from './App'
import router from './router'

export default new Vue({
	el: '#vue-content',
	router,
	render: h => h(App),
})
