import Vue from 'vue'
import VueRouter from 'vue-router'
import { generateUrl } from '@nextcloud/router'
import moment from '@nextcloud/moment'
import Diary from './Diary'

Vue.use(VueRouter)

export default new VueRouter({
	mode: 'history',
	base: generateUrl('apps/diary'),
	routes: [
		{
			path: '/date/:date',
			name: 'date',
			props: true,
			component: Diary,
		},
		{
			path: '/',
			redirect: {
				name: 'date',
				params: {
					date: moment().format('YYYY-MM-DD'),
				},
			},
		},
	],
})
