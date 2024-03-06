<script setup>
defineProps({
	success_page_url: String,
	failure_page_url: String,
	platform: String,
	created_at: String,
});
</script>

<template>
	<div class="w-full h-full bg-orange-300 rounded-lg p-2">
		<p class="font-normal text-sm text-gray-800">
			<span class="grid grid-cols-2 gap-2 my-2 w-full">
				<span class="block break-all"
					><b>Success Page: <br /></b> {{ success_page_url }}</span
				>
				<span class="block break-all"
					><b>Failure Page: <br /></b> {{ failure_page_url }}</span
				>
				<span class="block break-all"
					><b>Platform: <br /></b> {{ platform }}</span
				>
				<span class="block break-all"
					><b>Date Created: <br /></b> {{ created_at }}</span
				>
			</span>
		</p>
	</div>
</template>
