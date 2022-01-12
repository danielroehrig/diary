<template>
	<Content :class="{'icon-loading': loading}" app-name="diary">
		<AppNavigation />
		<Editor />
	</Content>
</template>

<script>
import { AppNavigation, Content } from '@nextcloud/vue'
import Editor from './Editor'

export default {
	name: 'App',
	components: {
		Editor,
		Content,
		AppNavigation,
	},
	data() {
		return {
			loading: false,
		}
	},
}
</script>
