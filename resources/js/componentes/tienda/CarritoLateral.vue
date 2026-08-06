<script setup>
import { computed, ref, watch } from 'vue';
import { useCarritoStore } from '../../almacenes/carrito';
import ItemCarrito from './ItemCarrito.vue';
import { construirLinkWhatsApp, formatearPrecio } from '../../servicios/whatsappServicio';

const carrito = useCarritoStore();
const abierto = ref(false);

const linkWhatsApp = computed(() => construirLinkWhatsApp(carrito.items));

// Abre automáticamente el cajón en móvil cuando se agrega un producto nuevo.
watch(
    () => carrito.items.length,
    (nuevo, anterior) => {
        if (nuevo > anterior) {
            abierto.value = true;
        }
    }
);

function finalizarPedido() {
    window.open(linkWhatsApp.value, '_blank');
}

defineExpose({ abrir: () => (abierto.value = true) });
</script>

<template>
    <button
        v-if="!abierto"
        type="button"
        class="fixed bottom-5 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-violet-600 text-white shadow-lg shadow-violet-600/30 transition-transform hover:scale-105 lg:hidden"
        aria-label="Abrir selección"
        @click="abierto = true"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.914-4.457 2.35-6.834a1.107 1.107 0 00-.834-1.375c-3.848-.79-7.813-1.02-11.834-1.02H5.106M7.5 14.25L5.106 5.428M7.5 14.25l-1.5 3M14.25 17.25a.75.75 0 100 1.5.75.75 0 000-1.5zM7.5 17.25a.75.75 0 100 1.5.75.75 0 000-1.5z" />
        </svg>
        <span
            v-if="carrito.cantidadTotal > 0"
            class="absolute -top-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-white text-xs font-bold text-violet-600 ring-2 ring-white"
        >
            {{ carrito.cantidadTotal }}
        </span>
    </button>

    <div v-if="abierto" class="fixed inset-0 z-40 bg-neutral-900/40 lg:hidden" @click="abierto = false" />

    <aside
        class="fixed inset-x-0 bottom-0 z-50 flex max-h-[85vh] flex-col rounded-t-3xl bg-white shadow-2xl transition-transform duration-300 ease-out lg:sticky lg:inset-auto lg:top-24 lg:max-h-[calc(100vh-7rem)] lg:translate-y-0 lg:rounded-2xl lg:border lg:border-neutral-200 lg:shadow-sm"
        :class="abierto ? 'translate-y-0' : 'translate-y-full lg:translate-y-0'"
    >
        <div class="flex items-center justify-between border-b border-neutral-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-neutral-900">Tu selección</h2>
                <p class="text-xs text-neutral-400">{{ carrito.cantidadTotal }} {{ carrito.cantidadTotal === 1 ? 'prenda' : 'prendas' }}</p>
            </div>
            <button type="button" class="text-neutral-400 hover:text-violet-600 lg:hidden" aria-label="Cerrar" @click="abierto = false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-5">
            <div v-if="carrito.items.length === 0" class="flex flex-col items-center justify-center py-14 text-center">
                <p class="text-sm text-neutral-400">Aún no has agregado ninguna prenda.</p>
                <p class="mt-1 text-xs text-neutral-300">Elige talla y color en un producto para verlo aquí.</p>
            </div>
            <div v-else class="divide-y divide-neutral-100">
                <ItemCarrito v-for="item in carrito.items" :key="item.varianteId" :item="item" />
            </div>
        </div>

        <div v-if="carrito.items.length > 0" class="border-t border-neutral-100 px-5 py-4">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-sm text-neutral-500">Total estimado</span>
                <span class="text-lg font-bold text-neutral-900">{{ formatearPrecio(carrito.totalEstimado) }}</span>
            </div>
            <button
                type="button"
                class="flex w-full items-center justify-center gap-2 rounded-full bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-emerald-700"
                @click="finalizarPedido"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                    <path d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.77.46 3.45 1.27 4.9L2 22l5.25-1.28A9.96 9.96 0 0012.04 22c5.52 0 10-4.48 10-10s-4.48-10-10-10zm0 18.2c-1.6 0-3.15-.44-4.5-1.28l-.32-.19-3.12.76.77-3.04-.2-.32A8.18 8.18 0 013.84 12c0-4.53 3.68-8.2 8.2-8.2 4.53 0 8.2 3.67 8.2 8.2s-3.67 8.2-8.2 8.2z"/>
                </svg>
                Finalizar pedido por WhatsApp
            </button>
            <p class="mt-2 text-center text-[11px] text-neutral-400">Coordinamos el pago directamente por WhatsApp.</p>
        </div>
    </aside>
</template>
