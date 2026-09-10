import { defineStore } from 'pinia';

function mismoItem(a, b) {
    return a.varianteId === b.varianteId;
}

export const useCarritoStore = defineStore('carrito', {
    state: () => ({
        items: [],
    }),
    getters: {
        cantidadTotal: (state) => state.items.length,
        totalEstimado: (state) => state.items.reduce((acum, item) => acum + item.precio, 0),
    },
    actions: {
        agregarItem(nuevoItem) {
            if (this.items.some((item) => mismoItem(item, nuevoItem))) {
                return;
            }

            this.items.push(nuevoItem);
        },
        quitarItem(varianteId) {
            this.items = this.items.filter((item) => item.varianteId !== varianteId);
        },
        vaciar() {
            this.items = [];
        },
    },
    persist: {
        key: 'carrito-tienda',
        storage: localStorage,
    },
});
