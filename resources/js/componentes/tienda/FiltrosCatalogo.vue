<script setup>
const filtros = defineModel({ required: true });

defineProps({
    categorias: { type: Array, default: () => [] },
});

const tallas = ['S', 'M', 'L', 'Única'];
const colores = ['Blanco', 'Negro', 'Azul', 'Verde', 'Gris', 'Rojo', 'Beige', 'Multicolor', 'Marrón'];

function limpiar() {
    filtros.value = { categoria: '', talla: '', color: '', precio_min: '', precio_max: '' };
}
</script>

<template>
    <div class="mb-8 flex items-center gap-2 overflow-x-auto pb-1">
        <select v-model="filtros.categoria" class="shrink-0 rounded-full border border-gris/25 bg-white py-2 pl-3.5 pr-8 text-sm text-negro shadow-sm focus:border-negro focus:ring-2 focus:ring-lima/40 focus:outline-none">
            <option value="">Categoría</option>
            <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.slug">
                {{ categoria.nombre }}
            </option>
        </select>

        <select v-model="filtros.talla" class="shrink-0 rounded-full border border-gris/25 bg-white py-2 pl-3.5 pr-8 text-sm text-negro shadow-sm focus:border-negro focus:ring-2 focus:ring-lima/40 focus:outline-none">
            <option value="">Talla</option>
            <option v-for="talla in tallas" :key="talla" :value="talla">{{ talla }}</option>
        </select>

        <select v-model="filtros.color" class="shrink-0 rounded-full border border-gris/25 bg-white py-2 pl-3.5 pr-8 text-sm text-negro shadow-sm focus:border-negro focus:ring-2 focus:ring-lima/40 focus:outline-none">
            <option value="">Color</option>
            <option v-for="color in colores" :key="color" :value="color">{{ color }}</option>
        </select>

        <input
            v-model="filtros.precio_min" type="number" min="0" placeholder="Precio mín."
            class="w-28 shrink-0 rounded-full border border-gris/25 bg-white py-2 px-3.5 text-sm text-negro shadow-sm placeholder:text-gris focus:border-negro focus:ring-2 focus:ring-lima/40 focus:outline-none"
        >
        <input
            v-model="filtros.precio_max" type="number" min="0" placeholder="Precio máx."
            class="w-28 shrink-0 rounded-full border border-gris/25 bg-white py-2 px-3.5 text-sm text-negro shadow-sm placeholder:text-gris focus:border-negro focus:ring-2 focus:ring-lima/40 focus:outline-none"
        >

        <button type="button" class="shrink-0 text-sm text-gris underline-offset-2 hover:text-negro hover:underline" @click="limpiar">
            Limpiar
        </button>
    </div>
</template>
