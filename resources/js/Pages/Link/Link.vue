<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import NewLink from "@/Modals/NewLink.vue";
import EditLink from "@/Modals/EditLink.vue";

import { onMounted, ref, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import { useModalStore } from "@/Store/modal";
import { useClipboard, usePermission } from "@vueuse/core";

const { text, isSupported, copy } = useClipboard();
const permissionRead = usePermission("clipboard-read");
const permissionWrite = usePermission("clipboard-write");

const props = defineProps({
	email_providers: Array,
	links: Array,
});

const modalStore = useModalStore();
const linkToBeEdited = ref(null);

const copyLink = async (full_link) => {
	if (!full_link) {
		return;
	}

	await copy(full_link).then(() => {
		window.alert("Link copied");
	});
};

const editLink = (link) => {
	linkToBeEdited.value = link;
	modalStore.openEditLinkModal();
};
</script>

<template>
	<EditLink
		:linkData="linkToBeEdited"
		:email_providers="email_providers"
		v-if="modalStore.currentModal === 'EDIT_LINK'"
	/>
	<NewLink
		:email_providers="email_providers"
		v-if="modalStore.currentModal === 'NEW_LINK'"
	/>
	<Head title="Marketing Link" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Marketing Link
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-10">
				<div class="mb-4 sticky top-2">
					<PrimaryButton
						@click.prevent="modalStore.openNewLinkModal"
						type="button"
						class="mx-2 float-right"
						>Create New link</PrimaryButton
					>
					<div class="clear-both"></div>
				</div>

				<div class="rounded-lg">
					<div class="grid grid-cols-1 gap-4">
						<template
							v-for="link in links"
							:key="link.email_marketing_links_id"
							:value="link.email_marketing_links_id"
						>
							<div
								class="block p-4 bg-white border border-gray-200 rounded-lg shadow"
							>
								<p class="font-normal text-sm text-gray-800">
									<span class="grid grid-cols-3 gap-2 mb-2 w-full">
										<span class="block break-all">
											<b>Name: <br /></b> {{ link.name }}
										</span>
										<span class="block break-all">
											<b>Campaign: <br /></b> {{ link.campaign }}
										</span>
										<span class="block break-all"
											><b>Subscriber(s): <br /></b>
											{{ link.subscribers }}
										</span>
									</span>
									<span class="grid grid-cols-3 gap-2 mb-2 w-full">
										<span class="block break-all"
											><b>Success Page: <br /></b>
											{{ link.success_page_url }}</span
										>
										<span class="block break-all"
											><b>Failure Page: <br /></b>
											{{ link.failure_page_url }}</span
										>
									</span>
									<span class="block break-all mb-2">
										<b>Link: <br /></b> {{ link.full_link }}
										<span
											@click="copyLink(link.full_link)"
											v-if="isSupported"
											class="inline-block shadow hover:shadow-xl w-6 h-6 rounded mx-2"
											><svg
												class="w-5 h-5 text-indigo-700 hover:text-black hover:cursor-pointer"
												aria-hidden="true"
												xmlns="http://www.w3.org/2000/svg"
												fill="currentColor"
												viewBox="0 0 24 24"
											>
												<path
													fill-rule="evenodd"
													d="M18 3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1V9a4 4 0 0 0-4-4h-3a2 2 0 0 0-1 .3V5c0-1.1.9-2 2-2h7Z"
													clip-rule="evenodd"
												/>
												<path
													fill-rule="evenodd"
													d="M8 7v4H4.2c0-.2.2-.3.3-.4l2.4-2.9A2 2 0 0 1 8 7.1Zm2 0v4a2 2 0 0 1-2 2H4v6c0 1.1.9 2 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z"
													clip-rule="evenodd"
												/>
											</svg>
										</span>
									</span>
								</p>
								<div class="float-right">
									<SecondaryButton
										@click.prevent="editLink(link)"
										class="inline-flex items-center font-medium text-center"
										>Edit
										<span>
											<svg
												class="w-4 h-4 text-gray-800 dark:text-white mx-2"
												aria-hidden="true"
												xmlns="http://www.w3.org/2000/svg"
												fill="none"
												viewBox="0 0 24 24"
											>
												<path
													stroke="currentColor"
													stroke-linecap="round"
													stroke-linejoin="round"
													stroke-width="2"
													d="m14.3 4.8 2.9 2.9M7 7H4a1 1 0 0 0-1 1v10c0 .6.4 1 1 1h11c.6 0 1-.4 1-1v-4.5m2.4-10a2 2 0 0 1 0 3l-6.8 6.8L8 14l.7-3.6 6.9-6.8a2 2 0 0 1 2.8 0Z"
												/>
											</svg> </span
									></SecondaryButton>
								</div>
								<div class="clear-both"></div>
							</div>
						</template>
					</div>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
