<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
	invite_link_is_valid: Boolean,
	invite_link: String,
	invitedUser_email: String,
	invitedUser_invite_already_processed: String,
});

const form = useForm({
	name: "",
	email: "",
	password: "",
	password_confirmation: "",
	terms: false,
	invite_link: props?.invite_link ?? "",
});

const submit = () => {
	form.post(route("register"), {
		onFinish: () => form.reset("password", "password_confirmation"),
	});
};
</script>

<template>
	<GuestLayout>
		<Head title="Register" />

		<div
			v-if="!invite_link_is_valid"
			class="my-6 flex flex-col items-center space-y-3"
		>
			<div class="text-base font-semibold leading-6 text-gray-800">
				<p v-if="invitedUser_invite_already_processed !== ''">
					{{ invitedUser_invite_already_processed }}
				</p>
				<p v-else>
					It seems you were not invited. Only invited users can complete their
					registration here. Please use the invite link sent to your mailbox
				</p>
			</div>
		</div>

		<div class="my-4">
			<div>
				<p class="font-semibold text-red-900 text-sm">
					{{ form.errors.general }}
				</p>
			</div>
			<form @submit.prevent="submit">
				<div>
					<InputLabel for="name" value="Name" />

					<TextInput
						id="name"
						type="text"
						class="mt-1 block w-full"
						v-model="form.name"
						required
						autofocus
						autocomplete="name"
					/>

					<InputError class="mt-2" :message="form.errors.name" />
				</div>

				<div class="mt-4">
					<InputLabel for="email" value="Email" />

					<TextInput
						id="email"
						type="email"
						class="mt-1 block w-full"
						v-model="form.email"
						:placeholder="invitedUser_email"
						required
						autocomplete="username"
					/>

					<InputError class="mt-2" :message="form.errors.email" />
				</div>

				<div class="mt-4">
					<InputLabel for="password" value="Password" />

					<TextInput
						id="password"
						type="password"
						class="mt-1 block w-full"
						v-model="form.password"
						required
						autocomplete="new-password"
					/>

					<InputError class="mt-2" :message="form.errors.password" />
				</div>

				<div class="mt-4">
					<InputLabel for="password_confirmation" value="Confirm Password" />

					<TextInput
						id="password_confirmation"
						type="password"
						class="mt-1 block w-full"
						v-model="form.password_confirmation"
						required
						autocomplete="new-password"
					/>

					<InputError
						class="mt-2"
						:message="form.errors.password_confirmation"
					/>
				</div>

				<div class="flex items-center justify-center my-2">
					<div
						v-if="form?.errors?.invite_link ?? false"
						class="text-red-900 font-semibold uppercase"
					>
						invalid invite link
					</div>
				</div>
				<div class="flex items-center justify-end mt-4">
					<Link
						:href="route('login')"
						class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
					>
						Already registered?
					</Link>

					<PrimaryButton
						class="ml-4"
						:class="{ 'opacity-25': form.processing }"
						:disabled="form.processing"
					>
						Register
					</PrimaryButton>
				</div>
			</form>
		</div>
	</GuestLayout>
</template>
