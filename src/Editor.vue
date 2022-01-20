<template>
	<div>
		<div id="title">
			{{ date }}hello
		</div>
		<div class="editor">
			<textarea id="diary-editor" />
		</div>
	</div>
</template>
<script>
import EasyMDE from 'easymde'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
	name: 'Editor',
	components: {
	},
	props: {
		date: {
			type: String,
			required: true,
		},
	},
	data() {
		return {
			editor: null,
		}
	},
	mounted() {
		this.editor = new EasyMDE({
			element: document.getElementById('diary-editor'),
			autoDownloadFontAwesome: false,
			autofocus: true,
			forceSync: true,
			spellChecker: false,
			nativeSpellcheck: false,
		})
		this.editor.codemirror.on('change', () => {
			const newContent = this.editor.value()
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
}
</script>
<style lang="css">
@import '@fortawesome/fontawesome-free/css/all.min.css';
@import '~easymde/dist/easymde.min.css';

.editor {
	padding-left: 3em;
	padding-top: 3em;
}
</style>
