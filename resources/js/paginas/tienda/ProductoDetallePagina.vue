<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import GaleriaImagenes from '../../componentes/tienda/GaleriaImagenes.vue';
import SelectorVariante from '../../componentes/tienda/SelectorVariante.vue';
import { obtenerProducto } from '../../servicios/catalogoServicio';
import { formatearPrecio } from '../../servicios/whatsappServicio';
import { useCarritoStore } from '../../almacenes/carrito';

const route = useRoute();
const carrito = useCarritoStore();

const producto = ref(null);
const cargando = ref(true);
const varianteId = ref(null);
const cantidad = ref(1);
const mensaje = ref('');

const varianteElegida = computed(() =>
    producto.value?.variantes.find((v) => v.id === varianteId.value) ?? null
);

async function cargar() {
    cargando.value = true;
    producto.value = await obtenerProducto(route.params.slug);
    cargando.value = false;
}

onMounted(cargar);
watch(() => route.params.slug, cargar);

function agregarAlCarrito() {
    if (!varianteElegida.value || varianteElegida.value.stock === 0) {
        return;
    }

    carrito.agregarItem({
        varianteId: varianteElegida.value.id,
        productoId: producto.value.id,
        nombre: producto.value.nombre,
        slug: producto.value.slug,
        talla: varianteElegida.value.talla,
        color: varianteElegida.value.color,
        cantidad: cantidad.value,
        precio: producto.value.precio,
        stock: varianteElegida.value.stock,
        imagen: producto.value.imagenes[0]?.url ?? null,
    });

    mensaje.value = '¡Agregada a tu selección!';
    setTimeout(() => (mensaje.value = ''), 2500);
}
</script>

<template>
    <div>
        <RouterLink
            :to="{ name: 'catalogo' }"
            class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-neutral-500 hover:text-violet-600"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Volver al catálogo
        </RouterLink>

        <div v-if="cargando" class="text-neutral-400">Cargando...</div>
        <div v-else-if="producto" class="grid gap-8 sm:gap-10 md:grid-cols-2">
            <GaleriaImagenes :imagenes="producto.imagenes" />

            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-violet-500">{{ producto.categoria?.nombre }}</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-neutral-900">{{ producto.nombre }}</h1>
                <p class="mt-2 text-xl font-semibold text-neutral-900">{{ formatearPrecio(producto.precio) }}</p>
                <p class="mt-4 text-sm leading-relaxed text-neutral-500">{{ producto.descripcion }}</p>

                <div class="mt-6">
                    <SelectorVariante v-model="varianteId" :variantes="producto.variantes" />
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <div class="flex items-center rounded-full border border-neutral-200">
                        <button
                            type="button"
                            class="flex h-11 w-11 items-center justify-center text-neutral-500 hover:text-violet-600 disabled:opacity-30"
                            :disabled="cantidad <= 1"
                            @click="cantidad--"
                        >
                            &minus;
                        </button>
                        <span class="w-6 text-center text-sm font-medium text-neutral-900">{{ cantidad }}</span>
                        <button
                            type="button"
                            class="flex h-11 w-11 items-center justify-center text-neutral-500 hover:text-violet-600 disabled:opacity-30"
                            :disabled="cantidad >= (varianteElegida?.stock ?? 1)"
                            @click="cantidad++"
                        >
                            +
                        </button>
                    </div>
                    <button
                        type="button"
                        class="flex-1 rounded-full bg-violet-600 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-violet-700 disabled:cursor-not-allowed disabled:opacity-40"
                        :disabled="!varianteElegida || varianteElegida.stock === 0"
                        @click="agregarAlCarrito"
                    >
                        {{ varianteElegida?.stock === 0 ? 'Sin stock' : 'Agregar a mi selección' }}
                    </button>
                </div>

                <Transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-1"
                    leave-active-class="transition duration-150 ease-in"
                    leave-to-class="opacity-0"
                >
                    <p v-if="mensaje" class="mt-3 text-sm font-medium text-violet-600">{{ mensaje }}</p>
                </Transition>
            </div>
        </div>
    </div>
</template>
