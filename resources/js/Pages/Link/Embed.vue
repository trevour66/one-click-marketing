<script setup>
import { Head, Link } from "@inertiajs/vue3";

import NewLink from "@/Components/NewLink.vue";

const props = defineProps({
	email_providers: Array,
	embedded: Boolean,
	user_unique_public_id: String,
});
</script>

<template>
	<Head title="Welcome" />

	<div class="py-6">
		<div class="max-w-7xl mx-auto px-6">
			<div>
				<NewLink
					:user_unique_public_id="user_unique_public_id"
					:embedded="embedded"
					:email_providers="email_providers"
					:redirect_to_links="false"
				/>
			</div>
		</div>
	</div>
</template>

<style></style>
