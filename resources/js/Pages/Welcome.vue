<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
	canLogin: Boolean,
	canRegister: Boolean,
	laravelVersion: String,
	phpVersion: String,
});
</script>

<template>
	<Head title="Welcome" />

	<div
		class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
	>
		<div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
			<Link
				v-if="$page.props.auth.user"
				:href="route('dashboard')"
				class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
				>Dashboard</Link
			>

			<template v-else>
				<Link
					:href="route('login')"
					class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
					>Log in</Link
				>

				<Link
					v-if="canRegister"
					:href="route('register')"
					class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
					>Register</Link
				>
			</template>
		</div>

		<div
			class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
			aria-hidden="true"
		>
			<div
				class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
				style="
					clip-path: polygon(
						74.1% 44.1%,
						100% 61.6%,
						97.5% 26.9%,
						85.5% 0.1%,
						80.7% 2%,
						72.5% 32.5%,
						60.2% 62.4%,
						52.4% 68.1%,
						47.5% 58.3%,
						45.2% 34.5%,
						27.5% 76.7%,
						0.1% 64.9%,
						17.9% 100%,
						27.6% 76.8%,
						76.1% 97.7%,
						74.1% 44.1%
					);
				"
			></div>
		</div>

		<div class="max-w-7xl mx-auto p-6">
			<div class="">
				<div class="relative isolate px-6 lg:px-8">
					<div class="mx-auto max-w-2xl py-28">
						<div class="hidden sm:mb-8 sm:flex sm:justify-center">
							<div
								class="relative rounded-full px-3 py-1 text-sm leading-6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20"
							>
								Announcing <b>addtolist.io</b>
								<span aria-hidden="true">&rarr;</span> Your Ultimate Link
								Generator for Effortless Email List Growth! 🔥
							</div>
						</div>
						<div class="text-center">
							<h1
								class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl"
							>
								Tired of the traditional opt-in process?
							</h1>
							<p class="mt-6 text-lg leading-8 text-gray-600">
								Say goodbye to cumbersome landing pages and hello to seamless
								list building with <b>addtolist.io</b>. With our innovative
								tool, you can effortlessly capture leads with just a single
								click, revolutionizing the way you grow your email list.
							</p>
							<div class="mt-10 flex items-center justify-center gap-x-6">
								<template v-if="$page.props.auth.user">
									<Link
										:href="route('dashboard')"
										class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
										>Dashboard</Link
									>
								</template>

								<template v-else>
									<Link
										:href="route('login')"
										class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
										>Log in</Link
									>

									<Link
										v-if="canRegister"
										:href="route('register')"
										class="text-sm font-semibold leading-6 text-gray-900"
										>Register</Link
									>
								</template>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div
			class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
			aria-hidden="true"
		>
			<div
				class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
				style="
					clip-path: polygon(
						74.1% 44.1%,
						100% 61.6%,
						97.5% 26.9%,
						85.5% 0.1%,
						80.7% 2%,
						72.5% 32.5%,
						60.2% 62.4%,
						52.4% 68.1%,
						47.5% 58.3%,
						45.2% 34.5%,
						27.5% 76.7%,
						0.1% 64.9%,
						17.9% 100%,
						27.6% 76.8%,
						76.1% 97.7%,
						74.1% 44.1%
					);
				"
			></div>
		</div>
	</div>
</template>

<style>
.bg-dots-darker {
	background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
	.dark\:bg-dots-lighter {
		background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
	}
}
</style>
