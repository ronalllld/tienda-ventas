<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import TablaProductos from '../../componentes/admin/TablaProductos.vue';
import { obtenerProductosAdmin, eliminarProducto } from '../../servicios/adminServicio';

const productos = ref([]);
const cargando = ref(true);

async function cargar() {
    cargando.value = true;
    productos.value = await obtenerProductosAdmin();
    cargando.value = false;
}

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
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Productos</h1>
            <RouterLink :to="{ name: 'admin.productos.nuevo' }" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Nuevo producto
            </RouterLink>
        </div>

        <p v-if="cargando" class="text-gray-500">Cargando...</p>
        <TablaProductos v-else :productos="productos" @eliminar="eliminar" />
    </div>
</template>
