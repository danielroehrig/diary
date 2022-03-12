<template>
	<div style="position: relative">
		<div id="entry-title">
			<i v-if="isLoading" class="fa fa-spinner fa-spin" />
			{{ unSavedMarker }}{{ title }}
		</div>
		<VueSimplemde ref="markdownEditor"
			v-model="content"
			:configs="configs" />
		<div v-if="isLoading" id="overlay">
			<div style="margin: auto">
				<i class="fa fa-spinner fa-spin fa-10x" />
			</div>
		</div>
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
			status: null,
			unSavedChanges: false,
			editor: null,
			content: '',
			configs: {
				toolbar: ['bold', 'italic', 'strikethrough', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', '|', 'preview', '|', 'guide'],
				autoDownloadFontAwesome: false,
				placeholder: 'Write here (translate that)',
				spellChecker: false,
				status: false,
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
		unSavedMarker() {
			return this.unSavedChanges ? '*' : ''
		},
		isLoading() {
			return this.status === 'loading'
		},
	},
	created() {
		this.$watch(() => this.$route.params, () => this.fetchEntry(), { immediate: true })
	},
	mounted() {
		this.simplemde.codemirror.on('change', () => {
			// A load is a change, so we need to catch this
			if (this.status === 'loaded') {
				this.status = 'writing'
				return
			}
			this.unSavedChanges = true
			clearTimeout(this.timeout)
			const saveFunction = () => {
				const newContent = this.simplemde.value()
				const payload = {
					content: newContent,
				}
				// Send content to backend
				axios.put(generateUrl('apps/diary/entry/' + this.date), payload)
					.then(response => {
						this.unSavedChanges = false
					})
					.catch(error => {
						// TODO Show alert box with error
						// eslint-disable-next-line no-console
						console.log(error)
					})
			}
			this.timeout = setTimeout(saveFunction, 500)
		})
	},
	methods: {
		fetchEntry() {
			this.status = 'loading'
			axios.get(generateUrl('apps/diary/entry/' + this.$route.params.date))
				.then(response => {
					if (response.data.entryContent) {
						this.content = response.data.entryContent
					} else {
						this.content = ''
					}
					this.status = 'loaded'
					// This ensures, that the right content is immediately shown. Actually, the model should force the editor to
					// update, but that doesn't seem to happen when moving between dates
					this.simplemde.value(this.content)
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

#overlay {
	z-index: 99;
	width: 100%;
	height: 100%;
	position: absolute;
	top: 0;
	left: 0;
	background: rgba(255, 255, 255, 0.4);
	display: flex;
}
</style>
