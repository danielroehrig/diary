<template>
	<Content :class="{'icon-loading': loading}" app-name="diary">
		<router-view />
		<AppNavigation />
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
