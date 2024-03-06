<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import NewInvite from "@/Modals/NewInvite.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, usePage } from "@inertiajs/vue3";

import AccordionPanel from "@/Components/accounts/Accordion.vue";
import accordionContent from "@/Components/accounts/accordionContent.vue";

import { useModalStore } from "@/Store/modal";

const modalStore = useModalStore();

const user = usePage().props.auth.user;

const userRoles = user?.roles ?? [];

const userRolesName = userRoles.map((elem) => {
	return elem.name;
});

const props = defineProps({
	martketing_links_data: Object,
});
</script>

<template>
	<NewInvite v-if="modalStore.currentModal === 'NEW_INVITE'" />

	<Head title="Users Accounts" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Users Accounts
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="mb-4 sticky top-2">
					<PrimaryButton
						@click.prevent="modalStore.openNewInviteModal"
						type="button"
						class="mx-2 float-right"
						>Invite a user</PrimaryButton
					>
					<div class="clear-both"></div>
				</div>
				<div class="overflow-hidden sm:rounded-lg"></div>
			</div>

			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-8">
				<div class="grid grid-cols-3 gap-4">
					<template
						v-for="(link, index) in martketing_links_data.data"
						:key="index"
					>
						<div
							class="block p-4 bg-white border border-gray-200 rounded-lg shadow h-80 overflow-y-auto"
						>
							<h3 class="font-semibold mb-3">Account Details</h3>
							<p class="font-normal text-sm text-gray-800 mb-4">
								<span class="grid grid-cols-1 gap-1 mb-2 w-full">
									<span class="block break-all">
										<b>Name <br /></b> {{ link?.user_data?.name }}
									</span>
									<span class="block break-all">
										<b>Email: <br /></b> {{ link?.user_data?.email ?? "" }}
									</span>
								</span>
							</p>

							<div v-if="(link?.marketing_links ?? []).length > 0">
								<h3 class="font-semibold mb-3">Links</h3>

								<div v-for="(mk_link, index) in link.marketing_links">
									<AccordionPanel
										:accordion_id="`${link.user_data.id}-${mk_link.email_marketing_links_id}`"
										:title="`${mk_link.name} (Subscribers: ${mk_link.subscribers})`"
									>
										<accordionContent
											:success_page_url="mk_link.success_page_url"
											:failure_page_url="mk_link.failure_page_url"
											:platform="mk_link.platforms_name"
											:created_at="
												mk_link.created_at
													? new Date(mk_link.created_at).toLocaleString()
													: ''
											"
										/>
									</AccordionPanel>
								</div>
							</div>
						</div>
					</template>
				</div>
			</div>
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-8">
				<div class="float-right">
					<div class="flex float-right">
						<!-- Previous Button -->
						<a
							v-if="martketing_links_data?.link?.prev"
							:href="martketing_links_data?.link?.prev ?? '#'"
							class="flex items-center justify-center px-4 h-10 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
						>
							Previous
						</a>

						<!-- Next Button -->
						<a
							v-if="martketing_links_data?.link?.next"
							:href="martketing_links_data?.link?.next ?? '#'"
							class="flex items-center justify-center px-4 h-10 ms-3 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
						>
							Next
						</a>
					</div>
				</div>
				<div class="clear-both"></div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
