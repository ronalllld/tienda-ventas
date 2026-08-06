<script setup>
import { ref } from 'vue';

const CLAVE_ALMACENAMIENTO = 'tienda-bienvenida-vista';

const visible = ref(localStorage.getItem(CLAVE_ALMACENAMIENTO) !== '1');

const pasos = [
    {
        titulo: 'Explora y filtra',
        texto: 'Recorre el catálogo y usa los filtros de categoría, talla, color o precio para encontrar lo que buscas.',
    },
    {
        titulo: 'Elige talla y color',
        texto: 'Entra a una prenda, selecciona la talla y el color disponibles, y agrégala a tu selección.',
    },
    {
        titulo: 'Revisa tu selección',
        texto: 'Tu selección aparece al lado (o tocando el botón flotante en el celular). Ahí puedes ajustar cantidades o quitar prendas.',
    },
    {
        titulo: 'Finaliza por WhatsApp',
        texto: 'Cuando estés listo, toca "Finalizar pedido" y se abrirá WhatsApp con tu pedido armado para coordinar el pago.',
    },
];

function cerrar() {
    visible.value = false;
    localStorage.setItem(CLAVE_ALMACENAMIENTO, '1');
}

defineExpose({ abrir: () => (visible.value = true) });
</script>

<template>
    <div v-if="visible" class="fixed inset-0 z-[60] flex items-end justify-center bg-neutral-900/50 p-0 sm:items-center sm:p-4">
        <div class="max-h-[90vh] w-full overflow-y-auto rounded-t-3xl bg-white p-6 shadow-2xl sm:max-w-md sm:rounded-3xl sm:p-8">
            <h2 class="text-xl font-bold text-neutral-900">¡Bienvenido/a! 👋</h2>
            <p class="mt-1 text-sm text-neutral-500">Así de fácil es comprar aquí:</p>

            <ol class="mt-6 space-y-5">
                <li v-for="(paso, indice) in pasos" :key="paso.titulo" class="flex gap-3">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-violet-600 text-xs font-bold text-white">
                        {{ indice + 1 }}
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-neutral-900">{{ paso.titulo }}</p>
                        <p class="text-sm text-neutral-500">{{ paso.texto }}</p>
                    </div>
                </li>
            </ol>

            <button
                type="button"
                class="mt-8 w-full rounded-full bg-violet-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-violet-700"
                @click="cerrar"
            >
                Empezar a comprar
            </button>
        </div>
    </div>
</template>
