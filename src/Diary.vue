<template>
	<NcContent id="diary-content" app-name="diary">
		<NcAppNavigation>
			<div class="navigation-wrapper">
				<NcButton class="icon icon-view-previous"
					@click="goPrevDay" />
				<NcDatetimePicker
					v-model="selectedDate"
					class="diary-datetimepicker"
					type="date"
					:open="calendarOpen"
					@change="onDateChange" />
				<NcButton
					class="open-calendar"
					@click="openCalendar">
					{{ formattedDate }}
				</NcButton>
				<NcButton v-if="showNextDayButton"
					class="icon icon-view-next"
					@click="goNextDay" />
			</div>
			<template #list>
				<ul>
					<NcListItem
						v-for="entry in lastEntries"
						:key="entry.date"
						:title="formatDate(entry.date)"
						:bold="false"
						:compact="true"
						counter-type="highlighted"
						@click="!isCurrentDate(entry.date) ? onDateChange(entry.date) : null">
						<template #icon>
							<NcAppNavigationIconBullet v-if="isCurrentDate(entry.date)" color="0082c9" />
							<NcAppNavigationIconBullet v-else color="FFFFFF" />
						</template>
						<template #subtitle>
							{{ entry.excerpt }}
						</template>
					</NcListItem>
				</ul>
			</template>
			<template #footer>
				<NcAppNavigationItem class="export" :title="t('diary', 'Export')" icon="icon-download">
					<template #actions>
						<NcActionLink :href="pdfDownloadLink">
							<template #icon>
								<FilePdfBox :size="20" />
								{{ t('diary', 'as PDF') }}
							</template>
						</NcActionLink>
						<NcActionLink :href="markdownDownloadLink">
							<template #icon>
								<Markdown :size="20" />
								{{ t('diary', 'as Markdown') }}
							</template>
						</NcActionLink>
					</template>
				</NcAppNavigationItem>
			</template>
		</NcAppNavigation>
		<NcAppContent>
			<Editor :date="date" @entry-edit="onEdit" />
		</NcAppContent>
	</NcContent>
</template>

<script>
import {
	NcAppContent,
	NcAppNavigation,
	NcContent,
	NcAppNavigationItem,
	NcDatetimePicker,
	NcButton,
	NcActionLink,
	NcAppNavigationIconBullet,
	NcListItem,
} from '@nextcloud/vue'
import Editor from './Editor'
import moment from '@nextcloud/moment'
import FilePdfBox from 'vue-material-design-icons/FilePdfBox'
import Markdown from 'vue-material-design-icons/LanguageMarkdown'
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'

export default {
	name: 'Diary',
	components: {
		NcAppNavigation,
		NcContent,
		Editor,
		NcAppContent,
		NcAppNavigationItem,
		NcDatetimePicker,
		NcButton,
		FilePdfBox,
		Markdown,
		NcActionLink,
		NcAppNavigationIconBullet,
		NcListItem,
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
			pastEntriesAmount: 10,
			lastEntries: [],
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
	mounted() {
		this.fetchPastEntries()
	},
	methods: {
		onDateChange(date) {
			this.$router.push({ name: 'date', params: { date: moment(date).format('YYYY-MM-DD') } })
			this.calendarOpen = false
			this.fetchPastEntries()
		},
		isCurrentDate(date) {
			return this.date === date
		},
		openCalendar() {
			this.calendarOpen = !this.calendarOpen
		},
		goPrevDay() {
			const yesterday = moment(this.date).subtract(1, 'day')
			this.$router.push({ name: 'date', params: { date: yesterday.format('YYYY-MM-DD') } })
			this.fetchPastEntries()
		},
		goNextDay() {
			const tomorrow = moment(this.date).add(1, 'day')
			this.$router.push({ name: 'date', params: { date: tomorrow.format('YYYY-MM-DD') } })
			this.fetchPastEntries()
		},
		onEdit(date, content) {
			const entryIndex = this.lastEntries.findIndex((e) => e.date === date)
			if (entryIndex === -1) {
				this.lastEntries.unshift({ date, excerpt: content })
			} else {
				if (content) {
					this.lastEntries[entryIndex].excerpt = content.substring(0, 40)
				} else {
					this.lastEntries.splice(entryIndex, 1)
				}
			}
		},
		formatDate(date) {
			return moment(date).format('LL')
		},
		fetchPastEntries() {
			axios.get(generateUrl('apps/diary/entries/' + this.pastEntriesAmount))
				.then(response => {
					if (response.data) {
						this.lastEntries = response.data
					} else {
						this.content = ''
					}
				})
				.catch(error => {
					// eslint-disable-next-line no-console
					console.log(error)
					this.status = 'error'
				})
		},
	},
}
</script>

<style lang="scss">
#diary-content {
  margin: 0;
  height: calc(100% - 50px);
  width: inherit;
  .navigation-wrapper {
    display: flex;
    justify-content: space-around;
    padding: 12px;
    .diary-datetimepicker {
      width: 0; // Hides drop-down
      .mx-input-wrapper {
        display: none;
      }
    }
    .open-calendar {
      flex-grow: 3;
      font-size: 14px;
    }
  }
  .export {
    padding: 12px;
  }
}
</style>
