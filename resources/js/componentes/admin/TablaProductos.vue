<script setup>
import { RouterLink } from 'vue-router';
import { formatearPrecio } from '../../servicios/whatsappServicio';
import { actualizarVariante } from '../../servicios/adminServicio';

defineProps({
    productos: { type: Array, required: true },
});

defineEmits(['eliminar']);

async function alternarDisponible(variante) {
    const disponible = !variante.disponible;
    await actualizarVariante(variante.id, { talla: variante.talla, color: variante.color, disponible });
    variante.disponible = disponible;
}

function estadoProducto(producto) {
    if (producto.variantes.length === 0) {
        return { texto: 'Sin variantes', clase: 'text-amber-600' };
    }

    const hayDisponible = producto.variantes.some((v) => v.disponible);
    return hayDisponible
        ? { texto: 'A la venta', clase: 'text-green-600' }
        : { texto: 'Vendido', clase: 'text-gray-400' };
}
</script>

<template>
    <div class="overflow-x-auto rounded-lg bg-white shadow-sm">
        <table class="w-full min-w-[760px] text-left">
            <thead class="bg-gray-100 text-left text-xs font-medium text-gray-600">
                <tr>
                    <th class="px-4 py-3"></th>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Categoría</th>
                    <th class="px-4 py-3">Precio</th>
                    <th class="px-4 py-3">Variantes</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr v-for="producto in productos" :key="producto.id">
                    <td class="px-4 py-2">
                        <img v-if="producto.imagen_principal" :src="producto.imagen_principal" class="h-12 w-10 object-cover rounded bg-gray-100">
                        <span v-else class="inline-block rounded bg-amber-50 px-1.5 py-1 text-[10px] font-medium leading-tight text-amber-600">
                            Sin fotos
                        </span>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-900">{{ producto.nombre }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ producto.categoria?.nombre }}</td>
                    <td class="px-4 py-2 text-sm text-gray-900 whitespace-nowrap">{{ formatearPrecio(producto.precio) }}</td>
                    <td class="px-4 py-2 text-sm">
                        <p v-if="producto.variantes.length === 0" class="text-xs text-amber-600">Sin variantes</p>
                        <div v-else class="flex flex-wrap gap-1">
                            <button
                                v-for="variante in producto.variantes"
                                :key="variante.id"
                                type="button"
                                class="whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-medium"
                                :class="variante.disponible ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-200 text-gray-500 hover:bg-gray-300'"
                                :title="variante.disponible ? 'Disponible — clic para marcar vendido' : 'Vendido — clic para marcar disponible'"
                                @click="alternarDisponible(variante)"
                            >
                                {{ [variante.talla, variante.color].filter(Boolean).join(' ') }}
                            </button>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-sm">
                        <span :class="estadoProducto(producto).clase">{{ estadoProducto(producto).texto }}</span>
                    </td>
                    <td class="px-4 py-2 text-sm text-right space-x-3 whitespace-nowrap">
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
    </div>
</template>
