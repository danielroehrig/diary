<template>
	<Content app-name="diary">
		<AppNavigation />
		<AppContent>
			<Editor :date="date" />
		</AppContent>
	</content>
</template>

<script>
import { AppContent, AppNavigation, Content } from '@nextcloud/vue'
import Editor from './Editor'
export default {
	name: 'Diary',
	components: { AppNavigation, Content, Editor, AppContent },
	props: {
		date: {
			type: String,
			required: true,
		},
	},
}
</script>

<style scoped>

</style>
