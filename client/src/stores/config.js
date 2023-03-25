import { defineStore } from "pinia";

export const useConfigStore = defineStore("config", () => {
    const state = {
        showNavbar: true,
        showSidebar: true,
        showMain: true,
        showFooter: true
    };
    return state;
});
