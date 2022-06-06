<template>
	<Content id="diary-content" app-name="diary">
		<AppNavigation>
			<div style="display:flex;">
				<Button class="icon icon-view-previous" @click="goPrevDay" />
				<DatetimePicker
					v-model="selectedDate"
					class="diary-datetimepicker"
					type="date"
					:open="calendarOpen"
					@change="onDateChange" />
				<Button style="flex-grow: 3" @click="openCalendar">
					{{ formattedDate }}
				</Button>
				<Button v-if="showNextDayButton" class="icon icon-view-next" @click="goNextDay" />
			</div>
			<template #footer>
				<AppNavigationItem :title="t('diary', 'Export')" icon="icon-download">
					<template #actions>
						<ActionLink :href="pdfDownloadLink">
							<template #icon>
								<FilePdfBox :size="20" />
								{{ t('diary', 'As PDF') }}
							</template>
						</ActionLink>
						<ActionLink :href="markdownDownloadLink">
							<template #icon>
								<Markdown :size="20" />
								{{ t('diary', 'As Markdown') }}
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
		}
	},
	computed: {
		formattedDate() {
			return moment(this.date).format('LL')
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
