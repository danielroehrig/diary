<template>
	<Content :class="{'icon-loading': loading}" app-name="diary">
		<AppNavigation>
			<template #default>
				<AppNavigationNew
					:text="Whatever" />
			</template>
		</AppNavigation>
		<AppContent>
			<div class="editor">
				<textarea />
			</div>
		</AppContent>
	</Content>
</template>

<script>
import { Content, AppNavigation, AppNavigationNew, AppContent } from '@nextcloud/vue'
import EasyMDE from 'easymde'
export default {
	name: 'App',
	components: {
		Content,
		AppNavigation,
		AppNavigationNew,
		AppContent,
	},
	data() {
		return {
			loading: false,
			editor: null,
		}
	},
	mounted() {
		this.editor = new EasyMDE()
	},
}
</script>
