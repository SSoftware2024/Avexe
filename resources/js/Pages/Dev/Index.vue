<script setup>
import { computed, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import AuthLayout from "@/layouts/AuthLayout.vue";

const props = defineProps({
    companies: Object,
    owners: Object,
});

// Mês atual calculado automaticamente (ex.: "11/2026"), não precisa editar todo mês
const currentMonth = computed(() =>
    new Date().toLocaleDateString("pt-BR", {
        month: "2-digit",
        year: "numeric",
    }),
);

const currentDateToFilter = computed(() => {
    const date = new Date();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${year}-${month}`;
});

/**
 * Cada card é descrito por um objeto; o template só percorre a lista.
 * - color: cor de destaque (barra lateral + ícone)
 * - to: rota para onde o card leva ao clicar (ajuste para as suas rotas)
 * - values: lista de números exibidos (1 ou 2 por card)
 */
const cards = computed(() => [
    {
        key: "users",
        title: "Usuários(Donos)",
        icon: "mdi-account-group-outline",
        color: "#4b5563",
        to: route("owner.listView"),
        values: [
            { label: "Ativos", value: props.owners.active },
            { label: "Inativos", value: props.owners.inactive, muted: true },
        ],
    },
    {
        key: "companies",
        title: "Empresas",
        icon: "mdi-office-building-outline",
        color: "#f26a21",
        to: route("company.listView"),
        values: [
            { label: "Ativas", value: props.companies.active },
            {
                label: "Inativas(vencidas)",
                value: props.companies.inactive,
                muted: true,
            },
        ],
    },
    {
        key: "expected",
        title: `Expectativa mensal · ${currentMonth.value}`,
        icon: "mdi-cash-multiple",
        color: "#2e7d32",
        to: route("company.listView", {
            date_payment_type: "month",
            date_payment: currentDateToFilter.value,
        }),
        values: [
            {
                label: "Valor previsto",
                value: "R$ " + props.companies.monthlyAmount.value_total_month,
                big: true,
            },
        ],
    },
    {
        key: "paying",
        title: "Empresas que pagam este mês",
        icon: "mdi-check-decagram-outline",
        color: "#1976d2",
        to: route("company.listView", {
            date_payment_type: "month",
            date_payment: currentDateToFilter.value,
        }),
        values: [
            {
                label: "Empresas",
                value: props.companies.monthlyAmount.count,
                big: true,
            },
        ],
    },
]);
</script>

<template>
    <AuthLayout>
        <Head title="Dashboard Developer" />
        <div class="dashboard">
            <header class="dashboard__header">
                <h3 class="dashboard__title">Dashboard</h3>
                <span class="dashboard__subtitle"
                    >Visão geral · {{ currentMonth }}</span
                >
            </header>

            <v-row>
                <v-col
                    v-for="card in cards"
                    :key="card.key"
                    cols="12"
                    sm="6"
                    lg="4"
                >
                    <!-- :to transforma o v-card em link (clicável); hover dá o efeito de elevação -->
                    <v-card
                        class="stat-card"
                        hover
                        elevation="1"
                        :style="{ '--accent': card.color }"
                        @click="router.get(card.to)"
                    >
                        <div class="stat-card__header">
                            <span class="stat-card__icon">
                                <v-icon :icon="card.icon" size="22" />
                            </span>
                            <span class="stat-card__title">{{
                                card.title
                            }}</span>
                            <v-icon
                                icon="mdi-chevron-right"
                                class="stat-card__arrow"
                            />
                        </div>

                        <div class="stat-card__values">
                            <div
                                v-for="item in card.values"
                                :key="item.label"
                                class="stat-card__value-block"
                            >
                                <span
                                    class="stat-card__number"
                                    :class="{
                                        'stat-card__number--big': item.big,
                                        'stat-card__number--muted': item.muted,
                                    }"
                                >
                                    {{ item.value }}
                                </span>
                                <span class="stat-card__label">{{
                                    item.label
                                }}</span>
                            </div>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </AuthLayout>
</template>

<style scoped>
/* Cabeçalho da página: título + subtítulo discreto, no mesmo tom cinza-escuro do restante do sistema */
.dashboard__header {
    display: flex;
    align-items: baseline;
    gap: 12px;
    margin-bottom: 16px;
}
.dashboard__title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
}
.dashboard__subtitle {
    font-size: 0.85rem;
    color: #6b7280;
}

/* Card: fundo branco, cantos arredondados e uma barra lateral colorida (usa a variável --accent
   definida inline em cada card). Isso dá identidade visual a cada métrica sem poluir. */
.stat-card {
    position: relative;
    height: 100%;
    padding: 20px 20px 20px 24px;
    border-radius: 10px;
    background: #fff;
    overflow: hidden;
    cursor: pointer;
    /* transição suave para o efeito de hover */
    transition:
        transform 0.15s ease,
        box-shadow 0.15s ease;
}
.stat-card::before {
    /* barra lateral colorida */
    content: "";
    position: absolute;
    inset: 0 auto 0 0;
    width: 5px;
    background: var(--accent);
}
.stat-card:hover {
    /* ao passar o mouse: sobe 2px e ganha sombra maior, indicando que é clicável */
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12) !important;
}

/* Linha superior: ícone + título + seta. A seta fica à direita (margin-left: auto) */
.stat-card__header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}
.stat-card__icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    color: var(--accent);
    /* fundo do ícone = cor de destaque com 12% de opacidade */
    background: color-mix(in srgb, var(--accent) 12%, transparent);
}
.stat-card__title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
}
.stat-card__arrow {
    margin-left: auto;
    color: #9ca3af;
    transition: transform 0.15s ease;
}
.stat-card:hover .stat-card__arrow {
    /* seta anda um pouco para a direita no hover */
    transform: translateX(3px);
    color: var(--accent);
}

/* Área dos números: blocos lado a lado (ativos | inativos) com espaçamento generoso */
.stat-card__values {
    display: flex;
    gap: 32px;
}
.stat-card__value-block {
    display: flex;
    flex-direction: column;
}
.stat-card__number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1.1;
    color: #1f2937;
}
/* Cards com um único dado ganham número maior e na cor de destaque */
.stat-card__number--big {
    font-size: 2.4rem;
    color: var(--accent);
}
/* Número secundário (inativos): cinza para não competir com o principal */
.stat-card__number--muted {
    color: #9ca3af;
}
.stat-card__label {
    margin-top: 4px;
    font-size: 0.8rem;
    color: #6b7280;
}
</style>
