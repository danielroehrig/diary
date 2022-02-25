<template>
	<Content app-name="diary">
		<AppNavigation>
			<div style="display:flex;">
				<button class="icon icon-view-previous" @click="goPrevDay" />
				<DatetimePicker
					v-model="selectedDate"
					class="diary-datetimepicker"
					type="date"
					:open="calendarOpen"
					@change="onDateChange" />
				<button style="flex-grow: 3" @click="openCalendar">
					{{ formattedDate }}
				</button>
				<button v-if="showNextDayButton" class="icon icon-view-next" @click="goNextDay" />
			</div>
		</AppNavigation>
		<AppContent>
			<Editor :date="date" />
		</AppContent>
	</content>
</template>

<script>
import { AppContent, AppNavigation, Content, DatetimePicker } from '@nextcloud/vue'
import Editor from './Editor'
import moment from 'nextcloud-moment'

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
			calendarOpen: false,
		}
	},
	computed: {
		formattedDate() {
			return this.date
		},
		showNextDayButton() {
			const nextDay = moment(this.date).add(1, 'day')
			const today = moment()
			return nextDay.isBefore(today)
		},
	},
	methods: {
		onDateChange(date) {
			this.$router.push({ name: 'date', params: { date: moment(date).format('YYYY-MM-DD') } })
			this.calendarOpen = false
		},
		openCalendar() {
			this.calendarOpen = !this.calendarOpen
		},
		goPrevDay() {
			const yesterday = moment(this.date).subtract(1, 'day')
			this.$router.push({ name: 'date', params: { date: yesterday.format('YYYY-MM-DD') } })
		},
		goNextDay() {
			const tomorrow = moment(this.date).add(1, 'day')
			this.$router.push({ name: 'date', params: { date: tomorrow.format('YYYY-MM-DD') } })
		},
	},
}
</script>
