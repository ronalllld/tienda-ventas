<script setup>
import { reactive } from 'vue';
import { crearVariante, actualizarVariante, eliminarVariante } from '../../servicios/adminServicio';

const props = defineProps({
    productoId: { type: Number, required: true },
    variantes: { type: Array, required: true },
});

const nueva = reactive({ talla: '', color: '' });

async function agregar() {
    const variante = await crearVariante(props.productoId, {
        talla: nueva.talla || null,
        color: nueva.color,
    });
    props.variantes.push(variante);
    nueva.talla = '';
    nueva.color = '';
}

async function alternarDisponible(variante) {
    const disponible = !variante.disponible;
    await actualizarVariante(variante.id, { talla: variante.talla, color: variante.color, disponible });
    variante.disponible = disponible;
}

async function eliminar(variante) {
    await eliminarVariante(variante.id);
    const indice = props.variantes.findIndex((v) => v.id === variante.id);
    if (indice !== -1) {
        props.variantes.splice(indice, 1);
    }
}
</script>

<template>
    <div>
        <p class="mb-3 text-xs text-gray-500">
            Cada fila es una prenda única. Dejá la talla vacía si el producto no usa tallas (ej. carteras).
        </p>

        <div class="mb-4 overflow-x-auto">
            <table class="w-full min-w-[420px] text-sm">
                <thead class="text-left text-xs text-gray-500">
                    <tr>
                        <th class="py-1">Talla</th>
                        <th class="py-1">Color</th>
                        <th class="py-1">Estado</th>
                        <th class="py-1"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="variante in variantes" :key="variante.id" class="border-t border-gray-100">
                        <td class="py-1.5">{{ variante.talla || '—' }}</td>
                        <td class="py-1.5">{{ variante.color }}</td>
                        <td class="py-1.5">
                            <button
                                type="button"
                                class="rounded-full px-2.5 py-1 text-xs font-medium"
                                :class="variante.disponible ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-200 text-gray-600 hover:bg-gray-300'"
                                @click="alternarDisponible(variante)"
                            >
                                {{ variante.disponible ? 'Disponible' : 'Vendido' }}
                            </button>
                        </td>
                        <td class="py-1.5 text-right">
                            <button type="button" class="text-red-500 hover:underline" @click="eliminar(variante)">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form class="flex flex-wrap items-end gap-2" @submit.prevent="agregar">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Talla (opcional)</label>
                <input v-model="nueva.talla" class="w-20 rounded-md border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Color</label>
                <input v-model="nueva.color" required class="w-28 rounded-md border-gray-300 text-sm">
            </div>
            <button type="submit" class="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Agregar
            </button>
        </form>
    </div>
</template>
