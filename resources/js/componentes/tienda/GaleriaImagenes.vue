<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    imagenes: { type: Array, default: () => [] },
});

const activa = ref(0);

watch(() => props.imagenes, () => { activa.value = 0; });
</script>

<template>
    <div>
        <div class="aspect-[3/4] overflow-hidden rounded-2xl bg-neutral-100">
            <img
                v-if="imagenes[activa]"
                :src="imagenes[activa].url"
                class="h-full w-full object-cover"
            >
        </div>
        <div v-if="imagenes.length > 1" class="mt-3 flex gap-2">
            <button
                v-for="(imagen, indice) in imagenes"
                :key="imagen.id"
                type="button"
                class="h-16 w-16 overflow-hidden rounded-xl border-2 transition-colors"
                :class="indice === activa ? 'border-neutral-900' : 'border-transparent opacity-70 hover:opacity-100'"
                @click="activa = indice"
            >
                <img :src="imagen.url" class="h-full w-full object-cover">
            </button>
        </div>
    </div>
</template>
