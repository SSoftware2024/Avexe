<script setup>
import { computed, ref } from "vue";

defineOptions({ inheritAttrs: false });

// O valor do v-model é uma string "YYYY-MM" (ex.: "2026-11") ou null.
// Se chegar uma data completa (ex.: "2026-11-06" ou "2026-11-06T10:00:00Z"),
// só ano e mês são considerados.
const model = defineModel({ type: String, default: null });

const menu = ref(false);

// Normaliza qualquer string iniciada por YYYY-MM para exatamente "YYYY-MM".
// Devolve null se o valor não for reconhecido. O v-month-picker precisa receber esse formato exato: com qualquer sufixo ele monta uma data inválida e 
// lança "Invalid time value".
const normalized = computed(() => {
    const match = /^(\d{4})-(\d{1,2})/.exec(model.value ?? "");
    if (!match) return null;
    const month = Number(match[2]);
    if (month < 1 || month > 12) return null;
    return `${match[1]}-${String(month).padStart(2, "0")}`;
});

// Texto exibido no campo (ex.: "novembro de 2026")
const text = computed(() => {
    if (!normalized.value) return "";
    const [year, month] = normalized.value.split("-").map(Number);
    return new Date(year, month - 1, 1).toLocaleDateString("pt-BR", {
        month: "long",
        year: "numeric",
    });
});

// O picker devolve "YYYY-MM" (string), então grava direto.
function _select(value) {
    model.value = value || null;
    menu.value = false;
}
</script>

<template>
    <v-menu
        v-model="menu"
        :close-on-content-click="false"
        min-width="auto"
        transition="scale-transition"
    >
        <template #activator="{ props: activatorProps }">
            <v-text-field
                label="Mês"
                variant="outlined"
                density="compact"
                prepend-inner-icon="mdi-calendar-month"
                clearable
                v-bind="{ ...$attrs, ...activatorProps }"
                :model-value="text"
                readonly
                @click:clear="model = null"
            />
        </template>

        <v-month-picker
            :model-value="normalized ?? undefined"
            @update:model-value="_select"
        />
    </v-menu>
</template>