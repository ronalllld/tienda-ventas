<script setup>
import { computed, onMounted, ref } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import FormularioProducto from '../../componentes/admin/FormularioProducto.vue';
import GestorVariantes from '../../componentes/admin/GestorVariantes.vue';
import GestorImagenes from '../../componentes/admin/GestorImagenes.vue';
import SelectorImagenesNuevas from '../../componentes/admin/SelectorImagenesNuevas.vue';
import {
    obtenerCategoriasAdmin,
    obtenerProductoAdmin,
    crearProducto,
    actualizarProducto,
    subirImagen,
} from '../../servicios/adminServicio';

const route = useRoute();
const router = useRouter();

const categorias = ref([]);
const producto = ref(null);
const cargando = ref(true);
const guardando = ref(false);
const imagenesNuevas = ref([]);

const esEdicion = computed(() => !!route.params.id);

async function cargar() {
    cargando.value = true;
    categorias.value = await obtenerCategoriasAdmin();

    if (esEdicion.value) {
        producto.value = await obtenerProductoAdmin(route.params.id);
    }

    cargando.value = false;
}

onMounted(cargar);

async function guardar(datos) {
    guardando.value = true;
    try {
        if (esEdicion.value) {
            producto.value = await actualizarProducto(route.params.id, datos);
            return;
        }

        const nuevo = await crearProducto(datos);

        for (const [indice, archivo] of imagenesNuevas.value.entries()) {
            await subirImagen(nuevo.id, archivo, indice);
        }

        router.replace({ name: 'admin.productos.editar', params: { id: nuevo.id } });
    } finally {
        guardando.value = false;
    }
}
</script>

<template>
    <div>
        <RouterLink :to="{ name: 'admin.productos' }" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-violet-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Volver a productos
        </RouterLink>

        <h1 class="text-2xl font-bold text-gray-900 mb-6">
            {{ esEdicion ? 'Editar producto' : 'Nuevo producto' }}
        </h1>

        <p v-if="cargando" class="text-gray-500">Cargando...</p>

        <template v-else>
            <FormularioProducto :producto="producto" :categorias="categorias" :guardando="guardando" @guardar="guardar" />

            <div class="max-w-xl mt-8 space-y-8">
                <section v-if="!esEdicion">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Imágenes</h2>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <SelectorImagenesNuevas v-model="imagenesNuevas" />
                    </div>
                </section>

                <template v-if="esEdicion && producto">
                    <section>
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Variantes</h2>
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <GestorVariantes :producto-id="producto.id" :variantes="producto.variantes" />
                        </div>
                    </section>

                    <section>
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Imágenes</h2>
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <GestorImagenes :producto-id="producto.id" :imagenes="producto.imagenes" />
                        </div>
                    </section>
                </template>

                <p v-else-if="!esEdicion" class="text-sm text-gray-500">
                    Guarda el producto para poder agregar variantes de talla, color y stock.
                </p>
            </div>
        </template>
    </div>
</template>
