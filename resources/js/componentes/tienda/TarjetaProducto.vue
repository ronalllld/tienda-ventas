<script setup>
import { RouterLink } from 'vue-router';
import { formatearPrecio } from '../../servicios/whatsappServicio';

defineProps({
    producto: { type: Object, required: true },
});
</script>

<template>
    <RouterLink :to="{ name: 'producto', params: { slug: producto.slug } }" class="group block">
        <div class="relative aspect-[3/4] overflow-hidden rounded-2xl bg-neutral-100">
            <img
                v-if="producto.imagen_principal"
                :src="producto.imagen_principal"
                :alt="producto.nombre"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
            >
            <span class="absolute bottom-2.5 left-2.5 rounded-full bg-violet-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                {{ formatearPrecio(producto.precio) }}
            </span>
        </div>
        <div class="mt-3">
            <p class="text-[11px] font-medium uppercase tracking-wide text-neutral-400">{{ producto.categoria?.nombre }}</p>
            <h3 class="mt-0.5 truncate text-sm font-medium text-neutral-900 group-hover:text-violet-600">{{ producto.nombre }}</h3>
        </div>
    </RouterLink>
</template>
