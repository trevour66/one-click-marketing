import { defineStore } from "pinia";
import { ref } from "vue";

export const useModalStore = defineStore("modal", () => {
	const currentModal = ref("");

	const openNewLinkModal = () => {
		currentModal.value = "NEW_LINK";
	};

	const openEditLinkModal = () => {
		currentModal.value = "EDIT_LINK";
	};

	const openNewInviteModal = () => {
		currentModal.value = "NEW_INVITE";
	};

	const closeModal = () => {
		currentModal.value = "";
	};

	return {
		currentModal,
		openNewLinkModal,
		openEditLinkModal,
		openNewInviteModal,
		closeModal,
	};
});
