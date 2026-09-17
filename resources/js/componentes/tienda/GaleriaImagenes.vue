<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    imagenes: { type: Array, default: () => [] },
});

const activa = ref(0);
let inicioX = 0;

watch(() => props.imagenes, () => { activa.value = 0; });

function siguiente() {
    activa.value = (activa.value + 1) % props.imagenes.length;
}

function anterior() {
    activa.value = (activa.value - 1 + props.imagenes.length) % props.imagenes.length;
}

function alTocarInicio(evento) {
    inicioX = evento.touches[0].clientX;
}

function alTocarFin(evento) {
    if (props.imagenes.length <= 1) {
        return;
    }
    const deltaX = evento.changedTouches[0].clientX - inicioX;
    if (Math.abs(deltaX) < 40) {
        return;
    }
    deltaX < 0 ? siguiente() : anterior();
}
</script>

<template>
    <div>
        <div
            class="relative aspect-[3/4] touch-pan-y select-none overflow-hidden rounded-2xl bg-neutral-100"
            @touchstart="alTocarInicio"
            @touchend="alTocarFin"
        >
            <img
                v-if="imagenes[activa]"
                :src="imagenes[activa].url"
                class="h-full w-full object-cover"
                draggable="false"
            >

            <template v-if="imagenes.length > 1">
                <button
                    type="button"
                    class="absolute left-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-neutral-700 shadow-sm hover:bg-white"
                    aria-label="Foto anterior"
                    @click="anterior"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    type="button"
                    class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-neutral-700 shadow-sm hover:bg-white"
                    aria-label="Foto siguiente"
                    @click="siguiente"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="absolute bottom-3 left-1/2 flex -translate-x-1/2 gap-1.5">
                    <span
                        v-for="(imagen, indice) in imagenes"
                        :key="imagen.id"
                        class="h-1.5 w-1.5 rounded-full transition-colors"
                        :class="indice === activa ? 'bg-white' : 'bg-white/50'"
                    ></span>
                </div>
            </template>
        </div>

        <div v-if="imagenes.length > 1" class="mt-3 flex gap-2">
            <button
                v-for="(imagen, indice) in imagenes"
                :key="imagen.id"
                type="button"
                class="h-16 w-16 overflow-hidden rounded-xl border-2 transition-colors"
                :class="indice === activa ? 'border-neutral-900' : 'border-transparent opacity-70 hover:opacity-100'"
                @click="activa = indice"
            >
                <img :src="imagen.url" class="h-full w-full object-cover">
            </button>
        </div>
    </div>
</template>
