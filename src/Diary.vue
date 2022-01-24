<template>
	<Content app-name="diary">
		<AppNavigation>
			<button>Left</button>
			<DatetimePicker
				v-model="selectedDate"
				class="diary-datetimepicker"
				type="date"
				open="open"
				@change="onDateChange" />
			<button>Right</button>
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
