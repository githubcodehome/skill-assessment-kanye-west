import { defineStore } from 'pinia';

export const useDataStore = defineStore('data', {
    state: () => {
        return {
            counter: 0,
            quotes: [],
            image: ''
        }
    },

    actions: {
        saveQuotes(quotes) {
            this.quotes = quotes;
        },
        saveCounter(counter) {
            this.counter = counter;
        },
        saveImage(image) {
            this.image = image;
        },
    },
})
