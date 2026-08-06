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
        <select v-model="filtros.categoria" class="shrink-0 rounded-full border-neutral-200 bg-white py-2 pl-3.5 pr-8 text-sm text-neutral-700 shadow-sm focus:border-violet-400 focus:ring-violet-400">
            <option value="">Categoría</option>
            <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.slug">
                {{ categoria.nombre }}
            </option>
        </select>

        <select v-model="filtros.talla" class="shrink-0 rounded-full border-neutral-200 bg-white py-2 pl-3.5 pr-8 text-sm text-neutral-700 shadow-sm focus:border-violet-400 focus:ring-violet-400">
            <option value="">Talla</option>
            <option v-for="talla in tallas" :key="talla" :value="talla">{{ talla }}</option>
        </select>

        <select v-model="filtros.color" class="shrink-0 rounded-full border-neutral-200 bg-white py-2 pl-3.5 pr-8 text-sm text-neutral-700 shadow-sm focus:border-violet-400 focus:ring-violet-400">
            <option value="">Color</option>
            <option v-for="color in colores" :key="color" :value="color">{{ color }}</option>
        </select>

        <input
            v-model="filtros.precio_min" type="number" min="0" placeholder="Precio mín."
            class="w-28 shrink-0 rounded-full border-neutral-200 bg-white py-2 text-sm text-neutral-700 shadow-sm placeholder:text-neutral-400 focus:border-violet-400 focus:ring-violet-400"
        >
        <input
            v-model="filtros.precio_max" type="number" min="0" placeholder="Precio máx."
            class="w-28 shrink-0 rounded-full border-neutral-200 bg-white py-2 text-sm text-neutral-700 shadow-sm placeholder:text-neutral-400 focus:border-violet-400 focus:ring-violet-400"
        >

        <button type="button" class="shrink-0 text-sm text-neutral-400 underline-offset-2 hover:text-violet-600 hover:underline" @click="limpiar">
            Limpiar
        </button>
    </div>
</template>
