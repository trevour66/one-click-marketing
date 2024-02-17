<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NewLink from "@/Components/NewLink.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, usePage } from "@inertiajs/vue3";

import { useClipboard, usePermission } from "@vueuse/core";

const user = usePage().props.auth.user;

const props = defineProps({
	email_providers: Array,
	embedded: Boolean,
});

const { text, isSupported, copy } = useClipboard();
const permissionRead = usePermission("clipboard-read");
const permissionWrite = usePermission("clipboard-write");

const user_unique_public_id = user.user_unique_public_id;

const copyEmbedLink = async () => {
	const user_unique_public_id = user.user_unique_public_id ?? false;

	if (!user_unique_public_id) {
		window.alert("Can not copy link please contact support");
	}

	const embed =
		route("embed.index", {
			user_unique_public_id: user_unique_public_id,
		}) ?? false;

	if (!embed) {
		window.alert("Can not copy link please contact support");
	}

	const full_link = `<iframe
			width="100%"
			height="100%"
			src="${embed}"
			title="One Click Marketing"
			style="border: 1px solid black; min-height: 300px"
		></iframe>`;

	await copy(full_link).then(() => {
		window.alert("Embed link copied");
	});
};
</script>

<template>
	<Head title="Dashboard" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Dashboard
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="mb-4 sticky top-2">
					<PrimaryButton
						@click.prevent="copyEmbedLink"
						type="button"
						class="mx-2 float-right"
						>Copy Embed link</PrimaryButton
					>
					<div class="clear-both"></div>
				</div>
				<div class="overflow-hidden sm:rounded-lg">
					<NewLink
						:user_unique_public_id="user_unique_public_id"
						:embedded="embedded"
						:email_providers="email_providers"
					/>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
