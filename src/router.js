import Vue from 'vue'
import VueRouter from 'vue-router'
import { generateUrl } from '@nextcloud/router'
import Editor from './Editor'
import moment from 'nextcloud-moment'

Vue.use(VueRouter)

export default new VueRouter({
	mode: 'history',
	base: generateUrl('apps/diary'),
	routes: [
		{
			path: '/',
			redirect: to => {
				return '/date/' + moment().format('YYYY-MM-DD')
			},
		},
		{
			path: '/date/:date',
			name: 'date',
			component: Editor,
		},
	],
})
