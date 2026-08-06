<script setup>
import { computed, ref, watch } from 'vue';

const props = defineProps({
    variantes: { type: Array, required: true },
});

const varianteId = defineModel();

const tallas = computed(() => [...new Set(props.variantes.map((v) => v.talla))]);
const tallaElegida = ref(tallas.value[0] ?? null);

const coloresParaTalla = computed(() =>
    props.variantes.filter((v) => v.talla === tallaElegida.value)
);
const colorElegido = ref(coloresParaTalla.value[0]?.color ?? null);

const varianteActual = computed(() =>
    props.variantes.find((v) => v.talla === tallaElegida.value && v.color === colorElegido.value) ?? null
);

watch(tallaElegida, () => {
    colorElegido.value = coloresParaTalla.value[0]?.color ?? null;
});

watch(varianteActual, () => {
    varianteId.value = varianteActual.value?.id ?? null;
}, { immediate: true });
</script>

<template>
    <div class="space-y-4">
        <div>
            <p class="mb-2 text-xs font-medium text-neutral-500">Talla</p>
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="talla in tallas"
                    :key="talla"
                    type="button"
                    class="rounded-full border px-4 py-1.5 text-sm transition-colors"
                    :class="talla === tallaElegida ? 'border-violet-600 bg-violet-600 text-white' : 'border-neutral-200 text-neutral-700 hover:border-violet-300'"
                    @click="tallaElegida = talla"
                >
                    {{ talla }}
                </button>
            </div>
        </div>

        <div>
            <p class="mb-2 text-xs font-medium text-neutral-500">Color</p>
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="variante in coloresParaTalla"
                    :key="variante.color"
                    type="button"
                    class="rounded-full border px-4 py-1.5 text-sm transition-colors disabled:cursor-not-allowed disabled:opacity-40"
                    :class="variante.color === colorElegido ? 'border-violet-600 bg-violet-600 text-white' : 'border-neutral-200 text-neutral-700 hover:border-violet-300'"
                    :disabled="variante.stock === 0"
                    @click="colorElegido = variante.color"
                >
                    {{ variante.color }}
                </button>
            </div>
        </div>

        <p v-if="varianteActual" class="text-xs text-neutral-400">
            <span v-if="varianteActual.stock > 0">{{ varianteActual.stock }} disponibles</span>
            <span v-else class="text-red-500">Sin stock</span>
        </p>
    </div>
</template>
