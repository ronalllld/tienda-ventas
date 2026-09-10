<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useSesionAdminStore } from '../../almacenes/sesionAdmin';

const route = useRoute();
const router = useRouter();
const sesion = useSesionAdminStore();

const correo = ref('');
const contrasena = ref('');
const mostrarContrasena = ref(false);
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
    <div class="flex min-h-screen items-center justify-center bg-neutral-50 px-4 py-10 sm:px-6">
        <div class="w-full max-w-sm">
            <div class="mb-8 flex flex-col items-center text-center">
                <span class="mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-sm shadow-violet-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </span>
                <h1 class="text-xl font-bold tracking-tight text-neutral-900">Panel administrador</h1>
                <p class="mt-1 text-sm text-neutral-500">Tienda de Ropa &mdash; acceso interno</p>
            </div>

            <form
                class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm shadow-neutral-200/60 sm:p-8"
                @submit.prevent="enviar"
            >
                <div class="mb-4">
                    <label class="mb-1.5 block text-sm font-medium text-neutral-700">Correo</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0-.414.336-.75.75-.75h18c.414 0 .75.336.75.75v10.5a.75.75 0 01-.75.75H3a.75.75 0 01-.75-.75V6.75z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.4 7.05l9.037 6.478a1 1 0 001.126 0L21.6 7.05" />
                            </svg>
                        </span>
                        <input
                            v-model="correo"
                            type="email"
                            required
                            autocomplete="username"
                            placeholder="tucorreo@ejemplo.com"
                            class="w-full rounded-lg border border-neutral-300 py-2.5 pl-10 pr-3 text-sm text-neutral-900 placeholder:text-neutral-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100"
                        >
                    </div>
                </div>

                <div class="mb-5">
                    <label class="mb-1.5 block text-sm font-medium text-neutral-700">Contraseña</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </span>
                        <input
                            v-model="contrasena"
                            :type="mostrarContrasena ? 'text' : 'password'"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-lg border border-neutral-300 py-2.5 pl-10 pr-10 text-sm text-neutral-900 placeholder:text-neutral-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-100"
                        >
                        <button
                            type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-neutral-400 hover:text-neutral-600"
                            @click="mostrarContrasena = !mostrarContrasena"
                        >
                            <svg v-if="mostrarContrasena" xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <p v-if="error" class="mb-4 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600">{{ error }}</p>

                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="enviando"
                >
                    <svg v-if="enviando" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    {{ enviando ? 'Ingresando...' : 'Ingresar' }}
                </button>
            </form>
        </div>
    </div>
</template>
