<script setup>
import { computed, ref } from 'vue';
import { comprimirImagen } from '../../servicios/imagenServicio';

const archivos = defineModel({ default: () => [] });

const procesando = ref(false);
const arrastrando = ref(false);

const previsualizaciones = computed(() =>
    archivos.value.map((archivo) => ({ archivo, url: URL.createObjectURL(archivo) }))
);

async function agregarArchivos(nuevos) {
    if (nuevos.length === 0) {
        return;
    }

    procesando.value = true;
    try {
        const comprimidos = await Promise.all(nuevos.map(comprimirImagen));
        archivos.value = [...archivos.value, ...comprimidos];
    } finally {
        procesando.value = false;
    }
}

function alSeleccionar(evento) {
    agregarArchivos(Array.from(evento.target.files));
    evento.target.value = '';
}

function alSoltar(evento) {
    arrastrando.value = false;
    const nuevos = Array.from(evento.dataTransfer.files).filter((a) => a.type.startsWith('image/'));
    agregarArchivos(nuevos);
}

function quitar(indice) {
    archivos.value = archivos.value.filter((_, i) => i !== indice);
}
</script>

<template>
    <div>
        <div v-if="previsualizaciones.length > 0" class="mb-4 flex flex-wrap gap-3">
            <div v-for="(item, indice) in previsualizaciones" :key="indice" class="relative h-24 w-20">
                <img :src="item.url" class="h-full w-full rounded-md bg-gray-100 object-cover">
                <button
                    type="button"
                    class="absolute -top-2 -right-2 h-5 w-5 rounded-full bg-red-500 text-xs text-white"
                    @click="quitar(indice)"
                >
                    &times;
                </button>
            </div>
        </div>

        <label
            class="flex flex-col items-center justify-center rounded-md border-2 border-dashed px-4 py-6 text-center text-sm cursor-pointer transition-colors"
            :class="arrastrando ? 'border-violet-400 bg-violet-50 text-violet-600' : 'border-gray-300 text-gray-500 hover:bg-gray-50'"
            @dragover.prevent="arrastrando = true"
            @dragleave.prevent="arrastrando = false"
            @drop.prevent="alSoltar"
        >
            <span v-if="procesando">Procesando...</span>
            <span v-else>Arrastrá imágenes acá o hacé clic para elegirlas (podés seleccionar varias)</span>
            <input type="file" accept="image/*" multiple class="hidden" @change="alSeleccionar">
        </label>
        <p class="mt-1 text-xs text-gray-400">Se subirán al guardar el producto.</p>
    </div>
</template>
