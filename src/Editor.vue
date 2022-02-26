<template>
	<div>
		<div v-if="loading">
			Loading ...
		</div>
		<div id="entry-title">
			{{ title }}
		</div>
		<VueSimplemde ref="markdownEditor"
			v-model="content"
			:configs="configs"
			@input="updateEntry" />
	</div>
</template>
<script>
import VueSimplemde from 'vue-simplemde'

import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import moment from 'nextcloud-moment'

export default {
	name: 'Editor',
	components: { VueSimplemde },
	props: {
		date: {
			type: String,
			required: true,
		},
	},
	data() {
		return {
			loading: false,
			editor: null,
			content: '',
			configs: {
				toolbar: ['bold', 'italic', 'strikethrough', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', '|', 'preview', '|', 'guide'],
				autoDownloadFontAwesome: false,
				placeholder: 'Write here (translate that)',
				spellChecker: false,
			},
		}
	},
	computed: {
		simplemde() {
			return this.$refs.markdownEditor.simplemde
		},
		title() {
			return moment(this.date).format('LL')
		},
	},
	created() {
		this.$watch(() => this.$route.params, () => this.fetchEntry(), { immediate: true })
	},
	mounted() {
		this.simplemde.codemirror.on('change', () => {
			const newContent = this.simplemde.value()
			const payload = {
				content: newContent,
			}
			// eslint-disable-next-line no-console
			console.log(newContent)
			// Send content to backend
			axios.put(generateUrl('apps/diary/entry/' + this.date), payload)
				.then(response => {
					// eslint-disable-next-line no-console
					console.log(response)
				})
				.catch(error => {
					// eslint-disable-next-line no-console
					console.log(error)
				})
		})
	},
	methods: {
		fetchEntry() {
			axios.get(generateUrl('apps/diary/entry/' + this.$route.params.date))
				.then(response => {
					// eslint-disable-next-line no-console
					console.log(response)
					if (response.data.entryContent) {
						this.content = response.data.entryContent
					} else {
						this.content = ''
					}
				})
				.catch(error => {
					// eslint-disable-next-line no-console
					console.log(error)
				})
		},
		updateEntry(input) {
			const payload = {
				content: input,
			}
			// eslint-disable-next-line no-console
			console.log(input)
			// Send content to backend
			axios.put(generateUrl('apps/diary/entry/' + this.date), payload)
				.then(response => {
					// eslint-disable-next-line no-console
					console.log(response)
				})
				.catch(error => {
					// eslint-disable-next-line no-console
					console.log(error)
				})
		},
	},
}
</script>
<style lang="css">
@import '@fortawesome/fontawesome-free/css/all.min.css';
@import '~simplemde/dist/simplemde.min.css';

.editor {
	padding-left: 3em;
	padding-top: 3em;
}

#entry-title {
	padding-left: 2.5em;
	padding-top: 0.5em;
	font-weight: bold;
	font-size: larger;
}
</style>
