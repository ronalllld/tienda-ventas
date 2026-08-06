<script setup>
import { RouterLink } from 'vue-router';
import { formatearPrecio } from '../../servicios/whatsappServicio';

defineProps({
    productos: { type: Array, required: true },
});

defineEmits(['eliminar']);
</script>

<template>
    <table class="w-full bg-white rounded-lg overflow-hidden shadow-sm">
        <thead class="bg-gray-100 text-left text-xs font-medium text-gray-600">
            <tr>
                <th class="px-4 py-3"></th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Categoría</th>
                <th class="px-4 py-3">Precio</th>
                <th class="px-4 py-3">Estado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <tr v-for="producto in productos" :key="producto.id">
                <td class="px-4 py-2">
                    <img v-if="producto.imagen_principal" :src="producto.imagen_principal" class="h-12 w-10 object-cover rounded bg-gray-100">
                </td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ producto.nombre }}</td>
                <td class="px-4 py-2 text-sm text-gray-500">{{ producto.categoria?.nombre }}</td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ formatearPrecio(producto.precio) }}</td>
                <td class="px-4 py-2 text-sm">
                    <span :class="producto.activo ? 'text-green-600' : 'text-gray-400'">
                        {{ producto.activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="px-4 py-2 text-sm text-right space-x-3">
                    <RouterLink :to="{ name: 'admin.productos.editar', params: { id: producto.id } }" class="text-gray-600 hover:underline">
                        Editar
                    </RouterLink>
                    <button type="button" class="text-red-500 hover:underline" @click="$emit('eliminar', producto)">
                        Eliminar
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</template>
