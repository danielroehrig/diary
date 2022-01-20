<template>
	<Content app-name="diary">
		<AppNavigation>
			<DatetimePicker v-model="selectedDate" type="date" @change="onDateChange" />
		</AppNavigation>
		<AppContent>
			<Editor :date="date" />
		</AppContent>
	</content>
</template>

<script>
import { AppContent, AppNavigation, Content, DatetimePicker } from '@nextcloud/vue'
import Editor from './Editor'
export default {
	name: 'Diary',
	components: { AppNavigation, Content, Editor, AppContent, DatetimePicker },
	props: {
		date: {
			type: String,
			required: true,
		},
	},
	data() {
		return {
			selectedDate: null,
		}
	},
	methods: {
		onDateChange(date) {
			// eslint-disable-next-line no-console
			console.log(date)
		},
	},
}
</script>

<style scoped>

</style>
