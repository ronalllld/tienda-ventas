<script setup>
import { useCarritoStore } from '../../almacenes/carrito';
import { formatearPrecio } from '../../servicios/whatsappServicio';

const props = defineProps({
    item: { type: Object, required: true },
});

const carrito = useCarritoStore();
</script>

<template>
    <div class="flex gap-3 py-3">
        <div class="h-16 w-14 shrink-0 overflow-hidden rounded-lg bg-neutral-100">
            <img v-if="item.imagen" :src="item.imagen" class="h-full w-full object-cover">
        </div>

        <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-medium text-neutral-900">{{ item.nombre }}</p>
            <p class="text-xs text-neutral-500">
                <template v-if="item.talla">{{ item.talla }} &middot; {{ item.color }}</template>
                <template v-else>{{ item.color }}</template>
            </p>

            <div class="mt-1.5 flex items-center justify-between">
                <p class="text-sm font-semibold text-neutral-900">{{ formatearPrecio(item.precio) }}</p>
            </div>
        </div>

        <button
            type="button"
            class="h-5 w-5 shrink-0 self-start text-neutral-300 hover:text-red-500"
            aria-label="Quitar"
            @click="carrito.quitarItem(item.varianteId)"
        >
            &times;
        </button>
    </div>
</template>
