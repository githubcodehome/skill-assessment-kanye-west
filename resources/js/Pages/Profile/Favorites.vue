<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import {onBeforeMount, ref} from "vue";
import {useDataStore} from "@/stores/data";
import Modal from "@/Pages/Profile/Modal.vue";

const imageStored = useDataStore();
const favorites = ref([]);

const showModal = ref([]);
const isValid = ref(false);
const error = ref('');
function close(i) {
    showModal[i].value = false;
}
function confirm(i) {
    showModal[i].value = false;
}
const images = ["/images/leaves-g817ea558c_1920.jpg", "/images/icicles-g993950891_1920.jpg"];
onBeforeMount(() => {
    favorites.value = getFavorites();
});

async function getFavorites() {

    favorites.value = [];
    try {
        const url = route('api.get_favorites');
        const response = await axios.get(url);
        isValid.value = true
        favorites.value = response.data.data;
    } catch (err) {
        isValid.value = true
        if (err.response) {
            error.value = err.response.data.message;
        } else if (err.request) {
            error.value = err.response.data.message;
        } else {
            error.value = err.message;
        }
    }
}

async function deleteFavorite(id) {
    try {
        const url = route('api.delete_favorite', id);
        const response = await axios.delete(url);

        if (response.data.status === 'OK') {
            favorites.value = favorites.value.filter(e => e.id !== id)
        }
    } catch (err) {
        if (err.response) {
            error.value = err.response.data.message;
        } else if (err.request) {
            error.value = err.response.data.message;
        } else {
            error.value = err.message;
        }
    }
}

function selectImage(src) {
    imageStored.saveImage(src)
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Favorites"/>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Favorites</h2>
            <div class="d-inline-block text-gray-500">
                Select one image for background and then click 'Show with image button'
            </div>
            <div class="flex">
                <div v-for="image in images" :key="image">
                    <img alt="image fro background" class="rounded-md border-4 border-gray-200 hover:border-blue-300 transition duration-500
                    hover:cursor-pointer h-20 m-2"
                         :class="{'border-blue-300': imageStored.image.includes(image)}"
                         @click="selectImage($event.target.src)" :src="image">
                </div>

            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900">
                        <div class="p-5 text-gray-400 text-2xl" v-if="!favorites || favorites.length === 0 &&
                        isValid">Not found
                        </div>
                        <transition-group name="fade" tag="div">
                            <div class="border-2 rounded-md  m-3 border-blue-100" v-for="(favorite, key) in favorites" :key="key">
                                <div class="p-1 inline-block ">
                                        <span class="">
                                        {{ favorite.text }}
                                        </span>
                                    <span>

                                                <button
                                                    v-if="imageStored.image"
                                                    @click="showModal[key] = true"
                                                    type="button"
                                                    class="border cursor-pointer border-green-200 bg-green-200
                                                    text-green-700 rounded-md
                                                px-2 py-1 m-2 transition duration-500
                                                ease select-none hover:bg-green-300 focus:outline-none
                                                focus:shadow-outline">
                                                Show with Image
                                            </button>
                                        <Teleport to="body">
                                            <modal :show="showModal[key]" :favorite="favorite.text" @close="showModal[key] = false">
                                            </modal>
                                        </Teleport>

                                        </span>
                                        <span>
                                            <button
                                                @click="deleteFavorite(favorite.id)"
                                                type="button"
                                                class="border cursor-pointer border-red-200 bg-red-200 text-red-700 rounded-md
                                                px-2 py-1 m-2 transition duration-500
                                                ease select-none hover:bg-red-300 focus:outline-none focus:shadow-outline">
                                                Delete from Favorites
                                            </button>
                                        </span>

                                </div>
                            </div>
                        </transition-group>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>


.fade-move,
.fade-enter-active,
.fade-leave-active {
    transition: all 0.5s cubic-bezier(0.55, 0, 0.1, 1);
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scaleY(0.01) translate(30px, 0);
}

.fade-leave-active {
    position: absolute;
}







</style>

