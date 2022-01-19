<template>
	<Content :class="{'icon-loading': loading}" app-name="diary">
		<AppNavigation />
		<router-view />
	</Content>
</template>

<script>
import { AppNavigation, Content } from '@nextcloud/vue'

export default {
	name: 'App',
	components: {
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
