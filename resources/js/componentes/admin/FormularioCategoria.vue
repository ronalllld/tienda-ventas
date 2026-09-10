<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    categoria: { type: Object, default: null },
});

const emit = defineEmits(['guardar', 'cancelar']);

const form = reactive({ nombre: '', slug: '' });

watch(() => props.categoria, (categoria) => {
    form.nombre = categoria?.nombre ?? '';
    form.slug = categoria?.slug ?? '';
}, { immediate: true });

function enviar() {
    emit('guardar', { ...form });

    if (!props.categoria) {
        form.nombre = '';
        form.slug = '';
    }
}
</script>

<template>
    <form class="flex flex-wrap items-end gap-3 bg-white p-4 rounded-lg border border-gray-200" @submit.prevent="enviar">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Nombre</label>
            <input v-model="form.nombre" required class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Slug (opcional)</label>
            <input v-model="form.slug" class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100">
        </div>
        <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
            Guardar
        </button>
        <button v-if="categoria" type="button" class="text-sm text-gray-500 underline" @click="emit('cancelar')">
            Cancelar
        </button>
    </form>
</template>
