<script setup>
import axios from 'axios';
import {ref} from 'vue'
import {onMounted} from 'vue'
import RefreshButton from "@/Pages/Buttons/RefreshButton.vue";
import {useDataStore} from "@/stores/data";

const COUNT_QUOTES = 5;
const prefix = '/api';
const quotes = ref([]);
const error = ref(null);
const counter = ref(COUNT_QUOTES);
const isValid = ref(true)
const isFavorite = ref(true)
const favorites = ref([]);
const quotesStored = useDataStore();

onMounted(() => {
    if (quotesStored.quotes.length === 0) {
        getQuotes();
    } else {
        quotes.value = quotesStored.quotes;
    }

    if (quotesStored.counter === 0) {
        quotesStored.saveCounter(counter.value);
    } else {
        counter.value = quotesStored.counter;
    }
});

async function getQuotes() {
    counter.value = counter.value ? counter.value : COUNT_QUOTES;
    error.value = null
    isValid.value = false
    quotes.value = [];
    try {
        const q = new URLSearchParams({
            'count': counter.value ? counter.value : COUNT_QUOTES
        }).toString();

        const url = route('api.get') + `?${q}`;
        const response = await axios.get(url);
        isValid.value = true
        quotes.value = response.data.data;
        quotesStored.saveQuotes(quotes.value)
        quotesStored.saveCounter(counter.value)
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

async function addFavorite(quote) {
    isFavorite.value = false
    try {
        const url = route('api.add_favorite', {text: quote});
        await axios.post(url);
        favorites.value.push(quote);
        const i = quotes.value.indexOf(quote)
        if (i > -1) {
            quotes.value.splice(i, 1)
            quotesStored.saveQuotes(quotes.value)
        }
        isFavorite.value = true
    } catch (err) {
        isFavorite.value = true
        if (err.response) {
            error.value = err.response.data.message;
        } else if (err.request) {
            error.value = err.response.data.message;
        } else {
            error.value = err.message;
        }
    }
}
</script>

<template>
    <div>
        <div class="inline-block">
            <div class="inline-block">
                <button @click="getQuotes" :disabled="!isValid" class="relative rounded px-5 py-2.5 overflow-hidden
            group bg-green-500 relative hover:bg-gradient-to-r hover:from-green-500 hover:to-green-400 text-white
            hover:ring-2 hover:ring-offset-2 hover:ring-green-400 transition-all ease-out duration-300">
                <span class="absolute right-0 w-8 h-32 -mt-12 transition-all duration-1000 transform translate-x-12
                bg-white opacity-10 rotate-12 group-hover:-translate-x-40 ease"></span>
                    <span class="relative">Refresh Quotes</span>
                </button>
            </div>
            <div class="inline-block m-3 bg-blue-100 px-2 rounded-md">
                Random quotes count:<input type="number" v-model="counter" min="1" max="150" class="p-1 m-2 border-solid
            border-2 border-gray-300 w-20 rounded-sm">
            </div>
        </div>
        <div v-if="quotes && quotes.length === 0 && !isValid" class="py-2">
            <RefreshButton/>
        </div>
        <div v-if="error">{{ error }}</div>
        <div class="p-5 text-gray-400 text-2xl" v-if="!quotes || quotes.length === 0 && isValid">Not found</div>
        <transition-group name="fade" tag="div">
            <div v-for="(quote, key) in quotes" :key="key">
                <div>
                    <span class="bg-slate-100 rounded-sm p-1 inline">{{ quote }}</span>
                    <span>
                <button
                    :disabled="!isFavorite"
                    @click="addFavorite(quote)"
                    type="button"
                    class="border border-yellow-200 bg-yellow-200 text-yellow-700 rounded-md
                    px-2 py-1 m-2 transition duration-500
                    ease select-none hover:bg-yellow-300 focus:outline-none focus:shadow-outline">
                    Add to Favorites
                </button>
            </span>
                </div>
            </div>
        </transition-group>
    </div>
</template>
<style>

/* 1. declare transition */
.fade-move,
.fade-enter-active,
.fade-leave-active {
    transition: all 0.5s cubic-bezier(0.55, 0, 0.1, 1);
}

/* 2. declare enter from and leave to state */
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scaleY(0.01) translate(30px, 0);
}

/* 3. ensure leaving items are taken out of layout flow so that moving
      animations can be calculated correctly. */
.fade-leave-active {
    position: absolute;
}
</style>


