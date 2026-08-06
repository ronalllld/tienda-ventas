<script setup>
import { ref } from 'vue';
import { subirImagen, eliminarImagen } from '../../servicios/adminServicio';

const props = defineProps({
    productoId: { type: Number, required: true },
    imagenes: { type: Array, required: true },
});

const subiendo = ref(false);

async function subir(evento) {
    const archivo = evento.target.files[0];
    if (!archivo) {
        return;
    }

    subiendo.value = true;
    try {
        const imagen = await subirImagen(props.productoId, archivo, props.imagenes.length);
        props.imagenes.push(imagen);
    } finally {
        subiendo.value = false;
        evento.target.value = '';
    }
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

        <label class="inline-block rounded-md border border-gray-300 px-4 py-2 text-sm cursor-pointer hover:bg-gray-50">
            {{ subiendo ? 'Subiendo...' : 'Agregar imagen' }}
            <input type="file" accept="image/*" class="hidden" :disabled="subiendo" @change="subir">
        </label>
    </div>
</template>
