<script setup>
import { RouterLink, RouterView, useRouter } from 'vue-router';
import { useSesionAdminStore } from '../../almacenes/sesionAdmin';

const sesion = useSesionAdminStore();
const router = useRouter();

async function salir() {
    await sesion.cerrarSesion();
    router.push({ name: 'admin.login' });
}
</script>

<template>
    <div class="min-h-screen flex">
        <aside class="w-56 bg-gray-900 text-gray-100 flex flex-col">
            <div class="px-4 py-5 text-lg font-bold border-b border-gray-800">Panel Admin</div>
            <nav class="flex-1 px-2 py-4 space-y-1">
                <RouterLink :to="{ name: 'admin.productos' }" class="block rounded px-3 py-2 text-sm hover:bg-gray-800" active-class="bg-gray-800">
                    Productos
                </RouterLink>
                <RouterLink :to="{ name: 'admin.categorias' }" class="block rounded px-3 py-2 text-sm hover:bg-gray-800" active-class="bg-gray-800">
                    Categorías
                </RouterLink>
            </nav>
            <div class="px-4 py-4 border-t border-gray-800 text-sm">
                <p class="text-gray-400 mb-2">{{ sesion.administrador?.nombre }}</p>
                <button type="button" class="text-red-400 hover:text-red-300" @click="salir">Cerrar sesión</button>
            </div>
        </aside>

        <main class="flex-1 bg-gray-50 p-8">
            <RouterView />
        </main>
    </div>
</template>
