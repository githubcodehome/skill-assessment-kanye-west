<script setup>

import {useDataStore} from "@/stores/data";
import {ref} from 'vue'
import html2canvas from 'html2canvas';
import {OnClickOutside} from '@vueuse/components'

const download_image = ref(null);
const output = ref(Promise);
const imageStored = useDataStore();
const props = defineProps({
    show: Boolean,
    favorite: String
})
const emit = defineEmits(['close'])

function close() {
    emit('close')
    console.log('closeModal')
}

function closeModal() {
    console.log('closeModal')
}

async function download() {
    const el = download_image.value;
    const options = {
        type: 'dataURL',
        allowTaint: false,
        useCORS: true,
    };
    let output = await html2canvas(el, options);
    console.log('this.output ', output)
    window.location.href = output.toDataURL("image/png").replace("image/png", "image/octet-stream");
}
</script>

<template>
    <Transition name="modal">

        <div v-if="show" class="modal-mask">

            <div class="modal-wrapper">
                <div>
                    <OnClickOutside class="modal-container" @trigger="close">
                        <div class="modal-header">
                            <slot name="header"></slot>
                        </div>

                        <div class="modal-body">
                            <slot name="body">
                                <div ref="download_image" class="item grid place-items-center">
                                    <div class="item__content rounded-md">
                                        "{{ favorite }}"
                                        <div>- Kanye West</div>
                                    </div>
                                    <img alt="background image" class="item__image" :src="imageStored.image">
                                </div>
                            </slot>
                        </div>

                        <div class="modal-footer grid">
                            <slot name="footer">
                                <button
                                    class="modal-download-button m-2 border cursor-pointer border-green-200
                                                bg-green-200 text-green-700 rounded-md
                                                px-2 py-1 m-2 transition duration-500
                                                ease select-none hover:bg-green-300 focus:outline-none
                                                focus:shadow-outline"
                                    @click="download(favorite, imageStored.image)"
                                >Download Quote
                                </button>
                            </slot>
                        </div>
                    </OnClickOutside>
                </div>
            </div>

        </div>

    </Transition>
</template>

<style>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 600px;
    margin: 0 auto;
    padding: 20px 30px 40px 30px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
}

.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.modal-body {
    margin: 0;
}

.modal-default-button {
    float: right;
}

.modal-download-button {
    float: left;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter-from {
    opacity: 0;
}

.modal-leave-to {
    opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}

.item {
    position: relative;
    height: 100%;
}

.item__content {
    position: absolute;
    top: 50px;
    width: 80%;
    text-align: left;
    font-weight: bold;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    padding: 15px;
    font-style: italic;
    z-index: 99;
}

.item__content div {
    justify-content: right;
    align-items: end;
    text-align: right;
}

.item__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.8;
    z-index: 9;
}
</style>
