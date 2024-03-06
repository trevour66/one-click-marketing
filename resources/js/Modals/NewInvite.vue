<script setup>
import { initFlowbite } from "flowbite";
import { onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import NoticeIcon from "@/Components/icons/notice_icon.vue";

import { useModalStore } from "@/Store/modal";

const user = usePage().props.auth.user;

const modalStore = useModalStore();

const form = useForm({
	invite_email: "",
	invite_status: "pending",
	invite_sent_by: user.id,
});

const newInviteForm_submit = () => {
	if (form.processing) {
		return;
	}
	const newInviteForm = document.querySelector("#newInviteForm") ?? false;

	if (newInviteForm) {
		form.post(route("invite.store"), {
			onSuccess: () => form.reset(),
		});
	}
};

// initialize components based on data attribute selectors
onMounted(() => {
	initFlowbite();
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
							<NoticeIcon />

							<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
								<h3
									class="text-base font-semibold leading-6 text-gray-900"
									id="modal-title"
								>
									Invite a User to the platform
								</h3>
								<div class="mt-2">
									<p class="text-sm text-gray-500">
										Please review the details below before sending the
										invitation.
									</p>
								</div>
							</div>
						</div>
						<div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
							<form
								id="newInviteForm"
								class="space-y-6"
								action="#"
								method="POST"
							>
								<div>
									<InputLabel
										class="block text-sm font-medium leading-6 text-gray-900"
										for="email_User"
										value="User Email address"
									/>
									<div class="mt-2">
										<TextInput
											id="email_User"
											type="text"
											class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
											v-model="form.invite_email"
											required
										/>
									</div>
									<InputError
										class="mt-2"
										:message="form.errors.invite_email"
									/>
								</div>

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
											Invite Sent
										</p>
									</Transition>
								</div>
							</form>
						</div>
					</div>
					<div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
						<button
							@click.prevent="newInviteForm_submit"
							:disabled="form.processing"
							type="button"
							class="inline-flex w-full justify-center rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 sm:ml-3 sm:w-auto"
						>
							Send Invite
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
