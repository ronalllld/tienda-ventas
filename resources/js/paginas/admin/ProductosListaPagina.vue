<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { RouterLink } from 'vue-router';
import TablaProductos from '../../componentes/admin/TablaProductos.vue';
import { obtenerProductosAdmin, obtenerCategoriasAdmin, eliminarProducto } from '../../servicios/adminServicio';

const productos = ref([]);
const categorias = ref([]);
const cargando = ref(true);

const filtros = reactive({
    texto: '',
    categoriaId: '',
    estado: '',
    soloPendientes: false,
});

async function cargar() {
    cargando.value = true;
    [productos.value, categorias.value] = await Promise.all([
        obtenerProductosAdmin(),
        obtenerCategoriasAdmin(),
    ]);
    cargando.value = false;
}

function esPendiente(producto) {
    return !producto.imagen_principal || producto.variantes.length === 0;
}

const productosFiltrados = computed(() => productos.value.filter((producto) => {
    if (filtros.texto && !producto.nombre.toLowerCase().includes(filtros.texto.toLowerCase())) {
        return false;
    }
    if (filtros.categoriaId && producto.categoria?.id !== filtros.categoriaId) {
        return false;
    }
    if (filtros.estado === 'activo' && !producto.activo) {
        return false;
    }
    if (filtros.estado === 'inactivo' && producto.activo) {
        return false;
    }
    if (filtros.soloPendientes && !esPendiente(producto)) {
        return false;
    }
    return true;
}));

async function eliminar(producto) {
    if (!confirm(`¿Eliminar el producto "${producto.nombre}"?`)) {
        return;
    }
    await eliminarProducto(producto.id);
    await cargar();
}

onMounted(cargar);
</script>

<template>
    <div>
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Productos</h1>
            <RouterLink :to="{ name: 'admin.productos.nuevo' }" class="inline-block rounded-md bg-gray-900 px-4 py-2 text-center text-sm font-medium text-white hover:bg-gray-700">
                Nuevo producto
            </RouterLink>
        </div>

        <div v-if="!cargando" class="mb-4 flex flex-wrap items-center gap-2">
            <input
                v-model="filtros.texto"
                type="search"
                placeholder="Buscar por nombre..."
                class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100"
            >
            <select
                v-model="filtros.categoriaId"
                class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100"
            >
                <option value="">Todas las categorías</option>
                <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                    {{ categoria.nombre }}
                </option>
            </select>
            <select
                v-model="filtros.estado"
                class="rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100"
            >
                <option value="">Activos e inactivos</option>
                <option value="activo">Solo activos</option>
                <option value="inactivo">Solo inactivos</option>
            </select>
            <label class="flex items-center gap-1.5 text-sm text-gray-600">
                <input v-model="filtros.soloPendientes" type="checkbox" class="h-4 w-4 rounded border border-gray-300">
                Solo pendientes (sin fotos o sin variantes)
            </label>
        </div>

        <p v-if="cargando" class="text-gray-500">Cargando...</p>
        <template v-else>
            <p v-if="productosFiltrados.length === 0" class="text-sm text-gray-500">
                Ningún producto coincide con el filtro.
            </p>
            <TablaProductos v-else :productos="productosFiltrados" @eliminar="eliminar" />
        </template>
    </div>
</template>
