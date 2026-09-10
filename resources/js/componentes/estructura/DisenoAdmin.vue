<script setup>
import { ref, watch } from 'vue';
import { RouterLink, RouterView, useRoute, useRouter } from 'vue-router';
import { useSesionAdminStore } from '../../almacenes/sesionAdmin';

const sesion = useSesionAdminStore();
const router = useRouter();
const route = useRoute();

const menuAbierto = ref(false);

watch(() => route.fullPath, () => {
    menuAbierto.value = false;
});

async function salir() {
    await sesion.cerrarSesion();
    router.push({ name: 'admin.login' });
}

const enlaces = [
    { nombre: 'admin.productos', etiqueta: 'Productos' },
    { nombre: 'admin.categorias', etiqueta: 'Categorías' },
    { nombre: 'admin.ventas', etiqueta: 'Ventas' },
];
</script>

<template>
    <div class="min-h-screen bg-neutral-50 lg:flex">
        <header class="sticky top-0 z-30 flex items-center justify-between border-b border-neutral-200 bg-white px-4 py-3.5 lg:hidden">
            <span class="flex items-center gap-2 text-base font-bold tracking-tight text-neutral-900">
                <span class="h-2 w-2 rounded-full bg-violet-600"></span>
                Panel Admin
            </span>
            <button
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-neutral-600 hover:bg-neutral-100"
                aria-label="Abrir menú"
                @click="menuAbierto = true"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </button>
        </header>

        <div
            v-if="menuAbierto"
            class="fixed inset-0 z-40 bg-neutral-900/40 lg:hidden"
            @click="menuAbierto = false"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-neutral-900 text-neutral-100 transition-transform duration-200 ease-out lg:sticky lg:top-0 lg:h-screen lg:w-56 lg:translate-x-0"
            :class="{ 'translate-x-0': menuAbierto }"
        >
            <div class="flex items-center justify-between px-4 py-5 text-lg font-bold border-b border-neutral-800">
                <span class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                    Panel Admin
                </span>
                <button
                    type="button"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-neutral-400 hover:bg-neutral-800 lg:hidden"
                    aria-label="Cerrar menú"
                    @click="menuAbierto = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex-1 px-2 py-4 space-y-1">
                <RouterLink
                    v-for="enlace in enlaces"
                    :key="enlace.nombre"
                    :to="{ name: enlace.nombre }"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white"
                    active-class="bg-violet-600 text-white hover:bg-violet-600"
                >
                    {{ enlace.etiqueta }}
                </RouterLink>
            </nav>
            <div class="px-4 py-4 border-t border-neutral-800 text-sm">
                <p class="mb-2 truncate text-neutral-400">{{ sesion.administrador?.nombre }}</p>
                <button type="button" class="text-red-400 hover:text-red-300" @click="salir">Cerrar sesión</button>
            </div>
        </aside>

        <main class="flex-1 min-w-0 bg-neutral-50 px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
            <RouterView />
        </main>
    </div>
</template>
