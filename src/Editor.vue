<template>
	<AppContent>
		<div class="editor">
			<textarea id="diary-editor" />
		</div>
	</AppContent>
</template>
<script>
import { AppContent } from '@nextcloud/vue'
import EasyMDE from 'easymde'

export default {
	name: 'Editor',
	components: {
		AppContent,
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
