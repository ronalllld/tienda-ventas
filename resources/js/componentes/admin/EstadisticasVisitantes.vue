<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { obtenerEstadisticasVisitantes } from '../../servicios/adminServicio';

const estadisticas = ref(null);
const cargando = ref(true);
let temporizador = null;

async function cargar() {
    estadisticas.value = await obtenerEstadisticasVisitantes();
    cargando.value = false;
}

function formatearFecha(fecha) {
    return new Date(`${fecha}T00:00:00`).toLocaleDateString('es-BO', { day: '2-digit', month: 'short', year: 'numeric' });
}

function formatearHora(hora) {
    return hora ? hora.slice(0, 5) : '—';
}

onMounted(() => {
    cargar();
    temporizador = setInterval(cargar, 30000);
});

onUnmounted(() => {
    clearInterval(temporizador);
});
</script>

<template>
    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Visitantes</h2>

        <p v-if="cargando" class="text-gray-500">Cargando...</p>

        <div v-else class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-3">
            <div class="rounded-lg bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500">Conectados ahora</p>
                <p class="mt-1 text-2xl font-bold text-emerald-600">{{ estadisticas.conectados }}</p>
                <p class="mt-1 text-xs text-gray-400">Actividad en los últimos 5 minutos</p>
            </div>
            <div class="rounded-lg bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500">Visitantes de hoy</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ estadisticas.visitantes_hoy }}</p>
                <p class="mt-1 text-xs text-gray-400">Visitantes únicos del día</p>
            </div>
            <div class="rounded-lg bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500">Pico del día</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">
                    {{ estadisticas.pico_hoy?.maximo ?? 0 }}
                    <span v-if="estadisticas.pico_hoy" class="text-sm font-medium text-gray-400">a las {{ formatearHora(estadisticas.pico_hoy.hora) }}</span>
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    <template v-if="estadisticas.record">Récord: {{ estadisticas.record.maximo }} el {{ formatearFecha(estadisticas.record.fecha) }}</template>
                    <template v-else>Todavía no hay récord histórico</template>
                </p>
            </div>
        </div>
    </div>
</template>
