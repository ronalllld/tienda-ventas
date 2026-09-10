<script setup>
import { onMounted, ref } from 'vue';
import FormularioCategoria from '../../componentes/admin/FormularioCategoria.vue';
import {
    obtenerCategoriasAdmin,
    crearCategoria,
    actualizarCategoria,
    eliminarCategoria,
} from '../../servicios/adminServicio';

const categorias = ref([]);
const editando = ref(null);

async function cargar() {
    categorias.value = await obtenerCategoriasAdmin();
}

async function guardar(datos) {
    if (editando.value) {
        await actualizarCategoria(editando.value.id, datos);
        editando.value = null;
    } else {
        await crearCategoria(datos);
    }
    await cargar();
}

async function eliminar(categoria) {
    if (!confirm(`¿Eliminar la categoría "${categoria.nombre}"? Esto también elimina sus productos.`)) {
        return;
    }
    await eliminarCategoria(categoria.id);
    await cargar();
}

onMounted(cargar);
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Categorías</h1>

        <FormularioCategoria :categoria="editando" class="mb-6" @guardar="guardar" @cancelar="editando = null" />

        <div class="overflow-x-auto rounded-lg bg-white shadow-sm">
            <table class="w-full min-w-[480px] text-left">
                <thead class="bg-gray-100 text-left text-xs font-medium text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="categoria in categorias" :key="categoria.id">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ categoria.nombre }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ categoria.slug }}</td>
                        <td class="px-4 py-3 text-sm text-right space-x-3 whitespace-nowrap">
                            <button type="button" class="text-gray-600 hover:underline" @click="editando = categoria">Editar</button>
                            <button type="button" class="text-red-500 hover:underline" @click="eliminar(categoria)">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
