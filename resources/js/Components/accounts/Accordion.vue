<script setup>
import { ref } from "vue";
const props = defineProps({
	title: { type: String, required: true },
	accordion_id: { type: String, required: true },
});
const showPanel = ref(false);
const togglePanel = (event) => {
	showPanel.value = !showPanel.value;
};
</script>

<template>
	<div class="panel container mb-4 border-2 rounded-lg shadow-sm">
		<button
			:arial-controls="'accordion-content-' + accordion_id"
			:id="'accordion-control-' + accordion_id"
			@click.prevent="togglePanel"
			class="p-2 w-full border-b-2 font-semibold flex flex-row items-center justify-between"
		>
			{{ title }}
			<span class="material-icons" v-if="showPanel">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="24"
					viewBox="0 0 24 24"
					width="24"
				>
					<path d="M0 0h24v24H0z" fill="none" />
					<path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z" />
				</svg>
			</span>
			<span class="material-icons" v-else>
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="24"
					viewBox="0 0 24 24"
					width="24"
				>
					<path d="M0 0h24v24H0z" fill="none" />
					<path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
				</svg>
			</span>
		</button>
		<div
			:aria-hidden="!showPanel"
			:id="'content-' + accordion_id"
			class="p-4"
			v-if="showPanel"
		>
			<slot></slot>
		</div>
	</div>
</template>
