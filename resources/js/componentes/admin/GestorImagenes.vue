<script setup>
import { ref } from 'vue';
import { subirImagen, eliminarImagen } from '../../servicios/adminServicio';
import { comprimirImagen } from '../../servicios/imagenServicio';

const props = defineProps({
    productoId: { type: Number, required: true },
    imagenes: { type: Array, required: true },
});

const subiendo = ref(false);
const arrastrando = ref(false);

async function subirArchivos(archivos) {
    if (archivos.length === 0) {
        return;
    }

    subiendo.value = true;
    try {
        for (const archivo of archivos) {
            const comprimida = await comprimirImagen(archivo);
            const imagen = await subirImagen(props.productoId, comprimida, props.imagenes.length);
            props.imagenes.push(imagen);
        }
    } finally {
        subiendo.value = false;
    }
}

function alSeleccionar(evento) {
    subirArchivos(Array.from(evento.target.files));
    evento.target.value = '';
}

function alSoltar(evento) {
    arrastrando.value = false;
    const archivos = Array.from(evento.dataTransfer.files).filter((a) => a.type.startsWith('image/'));
    subirArchivos(archivos);
}

async function eliminar(imagen) {
    await eliminarImagen(imagen.id);
    const indice = props.imagenes.findIndex((i) => i.id === imagen.id);
    if (indice !== -1) {
        props.imagenes.splice(indice, 1);
    }
}
</script>

<template>
    <div>
        <div class="flex flex-wrap gap-3 mb-4">
            <div v-for="imagen in imagenes" :key="imagen.id" class="relative h-24 w-20">
                <img :src="imagen.url" class="h-full w-full object-cover rounded-md bg-gray-100">
                <button
                    type="button"
                    class="absolute -top-2 -right-2 h-5 w-5 rounded-full bg-red-500 text-white text-xs"
                    @click="eliminar(imagen)"
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
            <span v-if="subiendo">Subiendo...</span>
            <span v-else>Arrastrá imágenes acá o hacé clic para elegirlas (podés seleccionar varias)</span>
            <input type="file" accept="image/*" multiple class="hidden" :disabled="subiendo" @change="alSeleccionar">
        </label>
    </div>
</template>
