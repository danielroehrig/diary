<template>
	<div style="position: relative">
		<div id="entry-title">
			<i v-if="isLoading" class="fa fa-spinner fa-spin" />
			{{ unSavedMarker }}{{ title }}
		</div>
		<VueSimplemde ref="markdownEditor"
			:model-value="content"
			:configs="configs"
			preview-class="markdown-body" />
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
				placeholder: t('diary', 'Write your entry here'),
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
			const day = moment(this.date)
			return day.format('dddd') + ' - ' + day.format('LL')
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
			// A load is a change, so we need to catch this.
			// We compare the content because switching to a page will trigger the change event TWICE! (Or not at all, if
			// we don't set the value of the editor to the content). So whenever these values are equal, we did not reach
			// the other workaround where we manually set the content to the value of the editor.
			if (this.status === 'loaded' || this.content === this.simplemde.value()) {
				this.status = 'writing'
				return
			}
			// This line here is SUPER important! Because although simplemde has the model set to this.content, changing
			// the content in the editor WON'T change the content of the content property. How long did it take me to find
			// this out? Like two days.
			this.content = this.simplemde.value()
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

						if (response.data.isEmpty || response.data.isNew) {
							this.$emit('reload-entries')
						}
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
@import '~github-markdown-css';

.vue-simplemde {
	padding-left: 0.5em;
}

.editor-toolbar a {
	color: var(--color-main-text) !important;
}

.editor-preview {
	background-color: var(--color-main-background);
	color: var(--color-main-text);
}

.CodeMirror {
	background-color: var(--color-main-background);
	color: var(--color-main-text);
	border: none;
}

.CodeMirror, .CodeMirror-scroll {
	padding-bottom: 50px;
}

.CodeMirror-cursor {
	border-color: var(--color-main-text);
}

.CodeMirror-code {
	width: unset !important;
	border: none !important;
}

.editor-toolbar a.active, .editor-toolbar a:hover {
	background-color: var(--color-background-hover) !important;
}

.editor-toolbar.disabled-for-preview a:not(.no-disable) {
	background-color: var(--color-background-darker) !important;
	color: var(--color-text-lighter) !important;
}

#entry-title {
	padding-left: 1.5em;
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
