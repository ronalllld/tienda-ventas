<script setup>
import { computed, ref, watch } from 'vue';
import { RouterLink } from 'vue-router';
import { formatearPrecio } from '../../servicios/whatsappServicio';

const props = defineProps({
    producto: { type: Object, required: true },
});

const activa = ref(0);
let inicioX = 0;
let arrastrada = false;

const fotos = computed(() => (
    props.producto.imagenes?.length > 0 ? props.producto.imagenes : [props.producto.imagen_principal].filter(Boolean)
));

watch(() => props.producto.id, () => { activa.value = 0; });

function siguiente() {
    activa.value = (activa.value + 1) % fotos.value.length;
}

function anterior() {
    activa.value = (activa.value - 1 + fotos.value.length) % fotos.value.length;
}

function alTocarInicio(evento) {
    inicioX = evento.touches[0].clientX;
    arrastrada = false;
}

function alTocarMover(evento) {
    if (Math.abs(evento.touches[0].clientX - inicioX) > 10) {
        arrastrada = true;
    }
}

function alTocarFin(evento) {
    if (fotos.value.length <= 1) {
        return;
    }
    const deltaX = evento.changedTouches[0].clientX - inicioX;
    if (Math.abs(deltaX) < 30) {
        return;
    }
    deltaX < 0 ? siguiente() : anterior();
}

function alClicEnlace(evento) {
    if (arrastrada) {
        evento.preventDefault();
    }
}
</script>

<template>
    <RouterLink :to="{ name: 'producto', params: { slug: producto.slug } }" class="group block" @click="alClicEnlace">
        <div
            class="relative aspect-[3/4] touch-pan-y select-none overflow-hidden rounded-2xl bg-neutral-100"
            @touchstart="alTocarInicio"
            @touchmove="alTocarMover"
            @touchend="alTocarFin"
        >
            <img
                v-if="fotos[activa]"
                :src="fotos[activa]"
                :alt="producto.nombre"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                draggable="false"
            >
            <span class="absolute bottom-2.5 left-2.5 rounded-full bg-violet-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                {{ formatearPrecio(producto.precio) }}
            </span>

            <template v-if="fotos.length > 1">
                <button
                    type="button"
                    class="absolute inset-y-0 left-0 flex w-1/3 items-center justify-start pl-1.5 opacity-0 transition-opacity hover:opacity-100 sm:pl-2"
                    aria-label="Foto anterior"
                    @click.prevent.stop="anterior"
                >
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/90 text-neutral-700 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                </button>
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex w-1/3 items-center justify-end pr-1.5 opacity-0 transition-opacity hover:opacity-100 sm:pr-2"
                    aria-label="Foto siguiente"
                    @click.prevent.stop="siguiente"
                >
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/90 text-neutral-700 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </button>

                <div class="absolute right-2.5 top-2.5 flex gap-1">
                    <span
                        v-for="(foto, indice) in fotos"
                        :key="indice"
                        class="h-1.5 w-1.5 rounded-full transition-colors"
                        :class="indice === activa ? 'bg-white' : 'bg-white/50'"
                    ></span>
                </div>
            </template>
        </div>
        <div class="mt-3">
            <p class="text-[11px] font-medium uppercase tracking-wide text-neutral-400">{{ producto.categoria?.nombre }}</p>
            <h3 class="mt-0.5 truncate text-sm font-medium text-neutral-900 group-hover:text-violet-600">{{ producto.nombre }}</h3>
        </div>
    </RouterLink>
</template>
