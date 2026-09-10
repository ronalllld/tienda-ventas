<script setup>
import { onMounted, ref } from 'vue';
import { obtenerVentas } from '../../servicios/adminServicio';
import { formatearPrecio } from '../../servicios/whatsappServicio';

const ventas = ref([]);
const resumen = ref(null);
const cargando = ref(true);

async function cargar() {
    cargando.value = true;
    const respuesta = await obtenerVentas();
    ventas.value = respuesta.ventas;
    resumen.value = respuesta.resumen;
    cargando.value = false;
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-BO', { day: '2-digit', month: 'short', year: 'numeric' });
}

onMounted(cargar);
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Ventas</h1>

        <p v-if="cargando" class="text-gray-500">Cargando...</p>

        <template v-else>
            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-xs font-medium text-gray-500">Vendidas este mes</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ resumen.cantidad_mes }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ resumen.cantidad_total }} en total</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-xs font-medium text-gray-500">Ingresos este mes</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ formatearPrecio(resumen.ingresos_mes) }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ formatearPrecio(resumen.ingresos_total) }} en total</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-xs font-medium text-gray-500">Ganancia este mes</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-600">{{ formatearPrecio(resumen.ganancia_mes) }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ formatearPrecio(resumen.ganancia_total) }} en total</p>
                </div>
            </div>

            <div v-if="ventas.length === 0" class="rounded-lg bg-white p-6 text-sm text-gray-500 shadow-sm">
                Todavía no registraste ninguna venta. Marcá una prenda como "Vendido" en el detalle del producto para que aparezca acá.
            </div>

            <div v-else class="overflow-x-auto rounded-lg bg-white shadow-sm">
                <table class="w-full min-w-[560px] text-left">
                    <thead class="bg-gray-100 text-left text-xs font-medium text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Producto</th>
                            <th class="px-4 py-3">Talla / Color</th>
                            <th class="px-4 py-3">Venta</th>
                            <th class="px-4 py-3">Ganancia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="venta in ventas" :key="venta.id">
                            <td class="px-4 py-2 text-sm text-gray-500 whitespace-nowrap">{{ formatearFecha(venta.fecha) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ venta.nombre_producto }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">
                                <template v-if="venta.talla">{{ venta.talla }} &middot; {{ venta.color }}</template>
                                <template v-else>{{ venta.color }}</template>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 whitespace-nowrap">{{ formatearPrecio(venta.precio_venta) }}</td>
                            <td class="px-4 py-2 text-sm text-emerald-600 whitespace-nowrap">{{ formatearPrecio(venta.ganancia) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </div>
</template>
