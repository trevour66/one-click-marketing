<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
	<div
		class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
	>
		<div>
			<Link href="/">
				<!-- <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" /> -->
				<h1
					class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white"
				>
					addtolist.io
				</h1>
			</Link>
		</div>

		<div
			class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
		>
			<slot />
		</div>
	</div>
</template>
