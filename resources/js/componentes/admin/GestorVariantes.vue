<script setup>
import { reactive, ref } from 'vue';
import { crearVariante, actualizarVariante, eliminarVariante } from '../../servicios/adminServicio';

const props = defineProps({
    productoId: { type: Number, required: true },
    variantes: { type: Array, required: true },
});

const nueva = reactive({ talla: '', color: '', stock: 0 });

async function agregar() {
    const variante = await crearVariante(props.productoId, { ...nueva });
    props.variantes.push(variante);
    nueva.talla = '';
    nueva.color = '';
    nueva.stock = 0;
}

async function actualizarStock(variante, stock) {
    await actualizarVariante(variante.id, { talla: variante.talla, color: variante.color, stock });
    variante.stock = stock;
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
        <table class="w-full text-sm mb-4">
            <thead class="text-left text-xs text-gray-500">
                <tr>
                    <th class="py-1">Talla</th>
                    <th class="py-1">Color</th>
                    <th class="py-1">Stock</th>
                    <th class="py-1"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="variante in variantes" :key="variante.id" class="border-t border-gray-100">
                    <td class="py-1.5">{{ variante.talla }}</td>
                    <td class="py-1.5">{{ variante.color }}</td>
                    <td class="py-1.5">
                        <input
                            type="number" min="0" :value="variante.stock" class="w-20 rounded-md border-gray-300 text-sm"
                            @change="actualizarStock(variante, Number($event.target.value))"
                        >
                    </td>
                    <td class="py-1.5 text-right">
                        <button type="button" class="text-red-500 hover:underline" @click="eliminar(variante)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <form class="flex items-end gap-2" @submit.prevent="agregar">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Talla</label>
                <input v-model="nueva.talla" required class="w-20 rounded-md border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Color</label>
                <input v-model="nueva.color" required class="w-28 rounded-md border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Stock</label>
                <input v-model.number="nueva.stock" type="number" min="0" required class="w-20 rounded-md border-gray-300 text-sm">
            </div>
            <button type="submit" class="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Agregar
            </button>
        </form>
    </div>
</template>
