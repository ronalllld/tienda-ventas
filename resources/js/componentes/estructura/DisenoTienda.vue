<script setup>
import { ref } from 'vue';
import { RouterLink, RouterView } from 'vue-router';
import CarritoLateral from '../tienda/CarritoLateral.vue';
import ModalBienvenida from './ModalBienvenida.vue';

const modal = ref(null);
</script>

<template>
    <div class="flex min-h-screen flex-col bg-neutral-50">
        <header class="sticky top-0 z-30 border-b border-neutral-200 bg-white/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
                <RouterLink :to="{ name: 'catalogo' }" class="flex items-center gap-2 text-lg font-bold tracking-tight text-neutral-900">
                    <span class="h-2 w-2 rounded-full bg-violet-600"></span>
                    Tienda de Ropa
                </RouterLink>
                <button
                    type="button"
                    class="hidden items-center gap-1.5 text-sm text-neutral-500 hover:text-violet-600 sm:flex"
                    @click="modal?.abrir()"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                    </svg>
                    ¿Cómo funciona?
                </button>
            </div>
        </header>

        <div class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 lg:grid lg:grid-cols-[1fr_360px] lg:items-start lg:gap-8">
            <main class="min-w-0">
                <RouterView />
            </main>

            <CarritoLateral />
        </div>

        <footer class="border-t border-neutral-200 bg-white py-6 text-center text-sm text-neutral-400">
            Tienda de Ropa &mdash; pedidos coordinados por WhatsApp
        </footer>

        <ModalBienvenida ref="modal" />
    </div>
</template>
