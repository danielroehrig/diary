<template>
	<Content id="diary-content" app-name="diary">
		<AppNavigation>
			<div style="display:flex;">
				<Button class="icon icon-view-previous"
					style="height: 34px; width: 34px; min-height: 0!important; min-width: 0!important; margin: 5px 5px"
					@click="goPrevDay" />
				<DatetimePicker
					v-model="selectedDate"
					class="diary-datetimepicker"
					type="date"
					:open="calendarOpen"
					@change="onDateChange" />
				<Button
					style="flex-grow: 3; height: 34px; min-height: 0!important; min-width: 0!important; margin: 5px 5px; font-size: 13px"
					@click="openCalendar">
					{{ formattedDate }}
				</Button>
				<Button v-if="showNextDayButton"
					class="icon icon-view-next"
					style="height: 34px; width: 34px; min-height: 0!important; min-width: 0!important; margin: 5px 5px"
					@click="goNextDay" />
			</div>
			<ul>
				<ListItem
					v-for="entry in lastEntries"
					:key="entry.date"
					:title="formatDate(entry.date)"
					:bold="false"
					:compact="true"
					counter-type="highlighted"
					@click="onDateChange(entry.date)">
					<template #subtitle>
						{{ entry.excerp }}
					</template>
				</ListItem>
			</ul>
			<template #footer>
				<AppNavigationItem :title="t('diary', 'Export')" icon="icon-download">
					<template #actions>
						<ActionLink :href="pdfDownloadLink">
							<template #icon>
								<FilePdfBox :size="20" />
								{{ t('diary', 'as PDF') }}
							</template>
						</ActionLink>
						<ActionLink :href="markdownDownloadLink">
							<template #icon>
								<Markdown :size="20" />
								{{ t('diary', 'as Markdown') }}
							</template>
						</ActionLink>
					</template>
				</AppNavigationItem>
			</template>
		</AppNavigation>
		<AppContent>
			<Editor id="diary-editor" :date="date" />
		</AppContent>
	</content>
</template>

<script>
import {
	AppContent,
	AppNavigation,
	Content,
	AppNavigationItem,
	DatetimePicker,
	Button,
	ActionLink,
	ListItem,
} from '@nextcloud/vue'
import Editor from './Editor'
import moment from 'nextcloud-moment'
import FilePdfBox from 'vue-material-design-icons/FilePdfBox'
import Markdown from 'vue-material-design-icons/LanguageMarkdown'
import { generateUrl } from '@nextcloud/router'

export default {
	name: 'Diary',
	components: {
		AppNavigation,
		Content,
		Editor,
		AppContent,
		AppNavigationItem,
		DatetimePicker,
		Button,
		FilePdfBox,
		Markdown,
		ActionLink,
		ListItem,
	},
	props: {
		date: {
			type: String,
			required: true,
		},
	},
	data() {
		const baseUrl = generateUrl('apps/diary')
		return {
			selectedDate: null,
			calendarOpen: false,
			baseUrl,
			lastEntries: [
				{
					date: '2022-08-16',
					excerp: 'In this slot you can put both text and other components such as icons',
				},
				{
					date: '2022-08-13',
					excerp: 'I am really losing my patience',
				},
			],
		}
	},
	computed: {
		formattedDate() {
			return this.formatDate(this.date)
		},
		showNextDayButton() {
			const nextDay = moment(this.date).add(1, 'day')
			const today = moment()
			return nextDay.isBefore(today)
		},
		markdownDownloadLink() {
			return this.baseUrl + '/export/markdown'
		},
		pdfDownloadLink() {
			return this.baseUrl + '/export/pdf'
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
		formatDate(date) {
			return moment(date).format('LL')
		},
	},
}
</script>
<style>
#diary-content {
	width: 100%;
	padding-top: 0;
}

#diary-editor {
	width: 100%;
	max-width: 800px;
	padding-left: 20px;
}

.editor-toolbar {
	border: none;
}

</style>
