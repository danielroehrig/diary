<template>
	<Content :class="{'icon-loading': loading}" app-name="diary">
		<AppNavigation>
			<template #default>
				<AppNavigationNew
					:text="Whatever" />
			</template>
		</AppNavigation>
		<AppContent>
			Well well well
		</AppContent>
	</Content>
</template>

<script>
import { Content, AppNavigation, AppNavigationNew, AppContent } from '@nextcloud/vue'
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
		}
	},
}
</script>
