<script setup>
import { computed } from 'vue';

const archivos = defineModel({ default: () => [] });

const previsualizaciones = computed(() =>
    archivos.value.map((archivo) => ({ archivo, url: URL.createObjectURL(archivo) }))
);

function agregar(evento) {
    archivos.value = [...archivos.value, ...Array.from(evento.target.files)];
    evento.target.value = '';
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

        <label class="inline-block cursor-pointer rounded-md border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50">
            Agregar imágenes
            <input type="file" accept="image/*" multiple class="hidden" @change="agregar">
        </label>
        <p class="mt-1 text-xs text-gray-400">Se subirán al guardar el producto.</p>
    </div>
</template>
