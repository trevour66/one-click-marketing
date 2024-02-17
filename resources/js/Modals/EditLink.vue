<script setup>
import axios from "axios";
import Swal from "sweetalert2";

import { initFlowbite } from "flowbite";
import { onMounted, ref, reactive } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

import { useModalStore } from "@/Store/modal";

const user = usePage().props.auth.user;

const modalStore = useModalStore();

const props = defineProps({
	email_providers: Array,
	linkData: Object,
});

const form = reactive({
	email_marketing_links_id: null,
	name: "",
	partner_email_service: "",
	failure_page_url: "",
	success_page_url: "",
	link_identifier: "",
});

const error = ref(false);
const error_message = ref("");
const success = ref(false);

const linkCreated = reactive({
	email_marketing_links_id: null,
	email_marketing_platforms_id: null,
	failure_page_url: "",
	full_link: "",
	link_identifier: "",
	name: "",
	success_page_url: "",
});

const resetResponseData = () => {
	error.value = false;
	error_message.value = "";
	success.value = false;

	linkCreated.email_marketing_platforms_id = null;
	linkCreated.failure_page_url = "";
	linkCreated.full_link = "";
	linkCreated.link_identifier = "";
	linkCreated.name = "";
	linkCreated.success_page_url = "";
};

const editLinkForm_submit = async () => {
	resetResponseData();
	let URL = route("marketing_link.update");

	await axios
		.post(URL, {
			name: form.name,
			partner_email_service: form.partner_email_service,
			failure_page_url: form.failure_page_url,
			success_page_url: form.success_page_url,
		})
		.then(function (response) {
			const data = response?.data?.data ?? false;

			linkCreated.email_marketing_platforms_id =
				data?.email_marketing_platforms_id ?? false;
			linkCreated.failure_page_url = data?.failure_page_url ?? false;
			linkCreated.full_link = data?.full_link ?? false;
			linkCreated.link_identifier = data?.link_identifier ?? false;
			linkCreated.name = data?.name ?? false;
			linkCreated.success_page_url = data?.success_page_url ?? false;

			Swal.fire({
				icon: "success",
				title: "Your link has been created!",
				html: `<div class="p-4 text-sm text-gray-800 rounded-lg bg-gray-50 text-center">
					<span class="font-medium">${linkCreated.full_link}</span>
					</div>`,
				footer:
					'<i class="text-sm">Save this link and give it to your partner to send from their email service. It will only work when sent from the email service provider selected during setup. Do not use it on your website.</i>',
			}).then((result) => {
				if (result.isConfirmed) {
				}
			});

			console.log(response);
		})
		.catch(function (error) {
			error_message.value =
				response?.data?.data?.message ??
				"Error preparing your link. Please contact support";

			console.log(error);
		});
};

// initialize components based on data attribute selectors
onMounted(() => {
	initFlowbite();

	form.email_marketing_links_id = props.linkData.email_marketing_links_id;
	form.partner_email_service = props.linkData.email_marketing_platforms_id;
	form.failure_page_url = props.linkData.failure_page_url;
	form.link_identifier = props.linkData.link_identifier;
	form.name = props.linkData.name;
	form.success_page_url = props.linkData.success_page_url;
});
</script>

<template>
	<!-- Main modal -->
	<div
		class="relative z-10"
		aria-labelledby="modal-title"
		role="dialog"
		aria-modal="true"
	>
		<div
			class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
		></div>

		<div class="fixed inset-0 z-10 w-screen overflow-y-auto">
			<div
				class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
			>
				<div
					class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
				>
					<div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
						<div class="sm:flex sm:items-start">
							<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
								<h3
									class="text-base font-semibold leading-6 text-gray-900"
									id="modal-title"
								>
									Marketing set up Wizard
								</h3>
								<div class="mt-2">
									<p class="text-sm text-gray-500">
										Begin your journey. Save the link generated here and also
										give it to your partner to send from their email service.
									</p>
								</div>
							</div>
						</div>
						<div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
							<form id="newLinkForm" class="space-y-6" action="#" method="POST">
								<fieldset>
									<div class="grid grid-cols-2 gap-2">
										<div>
											<InputLabel
												class="block text-sm font-medium leading-6 text-gray-900"
												for="name"
												value="Name"
											/>
											<div class="mt-2">
												<TextInput
													id="name"
													v-model="form.name"
													type="text"
													class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
													required
												/>
											</div>
										</div>
										<div>
											<InputLabel
												class="block text-sm font-medium leading-6 text-gray-900"
												for="partner_email_service"
												value="Partner's Email Service"
											/>
											<div class="mt-2">
												<select
													id="partner_email_service"
													v-model="form.partner_email_service"
													class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
													required
												>
													<option
														v-for="email_provider in email_providers"
														:key="email_provider.email_marketing_platforms_id"
														:value="email_provider.email_marketing_platforms_id"
													>
														{{ email_provider.name }}
													</option>
												</select>
											</div>
										</div>
									</div>
								</fieldset>

								<fieldset>
									<div class="grid grid-cols-1 gap-2">
										<div>
											<InputLabel
												class="block text-sm font-medium leading-6 text-gray-900"
												for="success_page_url"
												value="Success Page URL"
											/>
											<div class="mt-2">
												<TextInput
													id="success_page_url"
													v-model="form.success_page_url"
													type="text"
													class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
													required
												/>
											</div>
										</div>

										<div>
											<InputLabel
												class="block text-sm font-medium leading-6 text-gray-900"
												for="failure_page_url"
												value="Failure Page URL"
											/>
											<div class="mt-2">
												<TextInput
													id="failure_page_url"
													v-model="form.failure_page_url"
													type="text"
													class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
													required
												/>
											</div>
										</div>
									</div>
								</fieldset>

								<InputError class="mt-2" :message="error" />
								<div class="flex items-center gap-4">
									<Transition
										enter-from-class="opacity-0"
										leave-to-class="opacity-0"
										class="transition ease-in-out"
									>
										<p
											v-if="form.recentlySuccessful"
											class="text-sm text-gray-600"
										>
											Link Saved
										</p>
									</Transition>
								</div>
							</form>
						</div>
					</div>
					<div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
						<button
							@click.prevent="editLinkForm_submit"
							type="button"
							class="inline-flex w-full justify-center rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 sm:ml-3 sm:w-auto"
						>
							Save link
						</button>
						<button
							@click.prevent="modalStore.closeModal"
							type="button"
							class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
						>
							Cancel
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
