import { defineStore } from 'pinia';

function mismoItem(a, b) {
    return a.varianteId === b.varianteId;
}

export const useCarritoStore = defineStore('carrito', {
    state: () => ({
        items: [],
    }),
    getters: {
        cantidadTotal: (state) => state.items.reduce((acum, item) => acum + item.cantidad, 0),
        totalEstimado: (state) => state.items.reduce((acum, item) => acum + item.precio * item.cantidad, 0),
    },
    actions: {
        agregarItem(nuevoItem) {
            const existente = this.items.find((item) => mismoItem(item, nuevoItem));

            if (existente) {
                existente.cantidad = Math.min(existente.cantidad + nuevoItem.cantidad, nuevoItem.stock);
                return;
            }

            this.items.push(nuevoItem);
        },
        actualizarCantidad(varianteId, cantidad) {
            const item = this.items.find((item) => item.varianteId === varianteId);
            if (item) {
                item.cantidad = Math.max(1, Math.min(cantidad, item.stock));
            }
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
