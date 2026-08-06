<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useSesionAdminStore } from '../../almacenes/sesionAdmin';

const route = useRoute();
const router = useRouter();
const sesion = useSesionAdminStore();

const correo = ref('');
const contrasena = ref('');
const error = ref('');
const enviando = ref(false);

async function enviar() {
    error.value = '';
    enviando.value = true;
    try {
        await sesion.iniciarSesion(correo.value, contrasena.value);
        router.push(route.query.redirigir || { name: 'admin.productos' });
    } catch (e) {
        error.value = e.response?.data?.errors?.correo?.[0] ?? 'No se pudo iniciar sesión.';
    } finally {
        enviando.value = false;
    }
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <form class="w-full max-w-sm bg-white p-8 rounded-lg shadow" @submit.prevent="enviar">
            <h1 class="text-xl font-bold text-gray-900 mb-6">Acceso administradores</h1>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
                <input v-model="correo" type="email" required class="w-full rounded-md border-gray-300">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input v-model="contrasena" type="password" required class="w-full rounded-md border-gray-300">
            </div>

            <p v-if="error" class="text-sm text-red-600 mb-4">{{ error }}</p>

            <button
                type="submit"
                class="w-full rounded-md bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-700 disabled:opacity-50"
                :disabled="enviando"
            >
                Ingresar
            </button>
        </form>
    </div>
</template>
