<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    producto: { type: Object, default: null },
    categorias: { type: Array, required: true },
    guardando: { type: Boolean, default: false },
});

const emit = defineEmits(['guardar']);

const form = reactive({
    nombre: '',
    slug: '',
    descripcion: '',
    precio: 0,
    categoria_id: '',
    activo: true,
});

watch(() => props.producto, (producto) => {
    form.nombre = producto?.nombre ?? '';
    form.slug = producto?.slug ?? '';
    form.descripcion = producto?.descripcion ?? '';
    form.precio = producto?.precio ?? 0;
    form.categoria_id = producto?.categoria?.id ?? '';
    form.activo = producto?.activo ?? true;
}, { immediate: true });

function enviar() {
    emit('guardar', { ...form });
}
</script>

<template>
    <form class="space-y-4 bg-white p-6 rounded-lg shadow-sm max-w-xl" @submit.prevent="enviar">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input v-model="form.nombre" required class="w-full rounded-md border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug (opcional)</label>
            <input v-model="form.slug" class="w-full rounded-md border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea v-model="form.descripcion" rows="3" class="w-full rounded-md border-gray-300"></textarea>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Precio (Bs)</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-400">Bs</span>
                    <input v-model.number="form.precio" type="number" min="0" step="0.01" required class="w-full rounded-md border-gray-300 pl-9">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                <select v-model="form.categoria_id" required class="w-full rounded-md border-gray-300">
                    <option value="" disabled>Selecciona una categoría</option>
                    <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                        {{ categoria.nombre }}
                    </option>
                </select>
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input v-model="form.activo" type="checkbox" class="rounded border-gray-300">
            Producto activo (visible en el catálogo)
        </label>

        <button type="submit" :disabled="guardando" class="rounded-md bg-gray-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-700 disabled:opacity-50">
            {{ guardando ? 'Guardando...' : 'Guardar producto' }}
        </button>
    </form>
</template>
