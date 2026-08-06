<script setup>
import { onMounted, reactive, ref, watch } from 'vue';
import TarjetaProducto from '../../componentes/tienda/TarjetaProducto.vue';
import FiltrosCatalogo from '../../componentes/tienda/FiltrosCatalogo.vue';
import { obtenerCategorias, obtenerProductos } from '../../servicios/catalogoServicio';

const productos = ref([]);
const categorias = ref([]);
const cargando = ref(true);
const filtros = reactive({ categoria: '', talla: '', color: '', precio_min: '', precio_max: '' });

async function cargarProductos() {
    cargando.value = true;
    const filtrosActivos = Object.fromEntries(
        Object.entries(filtros).filter(([, valor]) => valor !== '')
    );
    productos.value = await obtenerProductos(filtrosActivos);
    cargando.value = false;
}

onMounted(async () => {
    categorias.value = await obtenerCategorias();
    await cargarProductos();
});

watch(filtros, cargarProductos);
</script>

<template>
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl">Descubre la colección</h1>
            <p class="mt-1 text-sm text-neutral-500">Elige tus prendas favoritas y arma tu pedido en minutos.</p>
        </div>

        <FiltrosCatalogo v-model="filtros" :categorias="categorias" />

        <div v-if="cargando" class="grid grid-cols-2 gap-5 sm:grid-cols-3 sm:gap-6 xl:grid-cols-4">
            <div v-for="n in 8" :key="n" class="animate-pulse">
                <div class="aspect-[3/4] rounded-2xl bg-neutral-200"></div>
                <div class="mt-3 h-3 w-2/3 rounded bg-neutral-200"></div>
                <div class="mt-2 h-3 w-1/3 rounded bg-neutral-200"></div>
            </div>
        </div>
        <p v-else-if="productos.length === 0" class="py-16 text-center text-sm text-neutral-400">
            No se encontraron productos con esos filtros.
        </p>
        <div v-else class="grid grid-cols-2 gap-5 sm:grid-cols-3 sm:gap-6 xl:grid-cols-4">
            <TarjetaProducto v-for="producto in productos" :key="producto.id" :producto="producto" />
        </div>
    </div>
</template>
