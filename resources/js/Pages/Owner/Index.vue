<script setup>
import { ref, reactive, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { formatDateBrString, formatMoneyToBrString } from "@/utils/functions";
import { useMask } from "vuetify";
import AuthLayout from "@/layouts/AuthLayout.vue";

const page = usePage();
const props = page.props;
const dashboard_tabs = ref("data");
const maskes = reactive({
    cnpj: useMask({ mask: "##.###.###/####-##" }),
    cpf: useMask({ mask: "###.###.###-##" }),
    whatsapp: useMask({ mask: "(##) # ####-####" }),
});
// Informações recebidas do backend
const systemSettings = computed(() => props.company.systemSettings ?? null);
const employeesCount = computed(() => props.employeesCount ?? null);
const clientsCount = computed(() => props.clientsCount ?? null);
// Identifica a situação atual da assinatura
const systemSettingsStatus = computed(() => {
    let status_data = {
        label: "Não encontrada",
        color: "info",
        icon: "mdi-close-circle-outline",
    };
    const status_active = props.company.data.active;
    const expiration = props.company?.days_to_expire_payment;

    if (!status_active) {
        status_data = {
            label: "Inativa",
            color: "error",
            icon: "mdi-close-circle-outline",
        };
    }

    if (expiration <= 7) {
        return {
            label: "Vencimento próximo",
            color: "warning",
            icon: "mdi-clock-alert-outline",
        };
    }

    if (status_active) {
        status_data = {
            label: "Ativa",
            color: "success",
            icon: "mdi-check-circle-outline",
        };
    }
    return status_data;
});

// Mensagem complementar sobre o vencimento
const expirationMessage = computed(() => {
    const days = props.company?.days_to_expire_payment;

    if (days < 0)
        return `A assinatura está vencida, ${days * -1} dias de atraso`;
    if (days === 0) return "A assinatura vence hoje";
    if (days <= 7) return `A assinatura vence em ${days} dias`;

    return "Assinatura dentro do prazo de validade";
});
</script>

<template>
    <Head title="Painel de controle"></Head>
    <AuthLayout>
        <v-tabs
            v-model="dashboard_tabs"
            align-tabs="start"
            class="mb-3"
            show-arrows
        >
            <v-tab value="appointments">Agendamentos</v-tab>
            <v-tab value="data">Informações</v-tab>
        </v-tabs>
        <v-window v-model="dashboard_tabs">
            <v-window-item value="appointments">
                <v-container fluid class="pa-4 pa-md-6">
                    Agendamentos
                </v-container>
            </v-window-item>
            <v-window-item value="data">
                <v-container fluid class="pa-4 pa-md-6">
                    <!-- #region Dashboard Header -->
                    <v-row align="center" class="mb-6">
                        <v-col cols="12" class="d-flex justify-md-end">
                            <v-chip
                                color="primary"
                                variant="tonal"
                                prepend-icon="mdi-view-dashboard-outline"
                            >
                                Painel do proprietário
                            </v-chip>
                        </v-col>
                    </v-row>
                    <!-- #endregion -->

                    <!-- #region Informações da Empresa -->
                    <v-row class="mb-4">
                        <v-col cols="12">
                            <div class="mb-4">
                                <h2 class="text-h6 font-weight-bold">
                                    Minha empresa
                                </h2>

                                <p class="text-body-2 text-medium-emphasis">
                                    Informações gerais e situação da assinatura.
                                </p>
                            </div>

                            <v-card
                                rounded="lg"
                                border
                                elevation="0"
                                class="pa-4 pa-md-6"
                            >
                                <v-row align="center">
                                    <!-- Informações da empresa -->
                                    <v-col cols="12" md="5">
                                        <div class="d-flex align-center ga-4">
                                            <v-avatar
                                                color="primary"
                                                variant="tonal"
                                                rounded="lg"
                                                size="56"
                                            >
                                                <v-icon
                                                    icon="mdi-domain"
                                                    size="28"
                                                />
                                            </v-avatar>

                                            <div
                                                class="flex-grow-1 overflow-hidden"
                                            >
                                                <div
                                                    class="text-caption text-medium-emphasis font-weight-bold mb-1"
                                                >
                                                    EMPRESA
                                                </div>

                                                <div
                                                    class="text-h6 font-weight-bold text-truncate"
                                                >
                                                    {{
                                                        props.company.data
                                                            ?.name ??
                                                        "Empresa não informada"
                                                    }}
                                                </div>

                                                <div
                                                    class="text-caption text-medium-emphasis"
                                                >
                                                    <span
                                                        v-if="
                                                            props.company.data
                                                                ?.cnpj
                                                        "
                                                    >
                                                        CNPJ: {{
                                                            maskes.cnpj.mask(
                                                                props.company
                                                                    .data?.cnpj,
                                                            )
                                                        }}
                                                    </span>
                                                    <br />
                                                    <span
                                                        v-if="
                                                            props.company.data
                                                                ?.cpf
                                                        "
                                                    >
                                                        CPF: {{
                                                            maskes.cpf.mask(
                                                                props.company
                                                                    .data?.cpf,
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </v-col>

                                    <!-- Separador -->
                                    <v-col
                                        cols="12"
                                        md="1"
                                        class="d-flex justify-center"
                                    >
                                        <v-divider class="d-md-none" />
                                        <v-divider
                                            vertical
                                            class="d-none d-md-flex"
                                        />
                                    </v-col>

                                    <!-- Informações da assinatura -->
                                    <v-col cols="12" md="6">
                                        <div
                                            class="d-flex align-center justify-space-between flex-wrap ga-3 mb-4"
                                        >
                                            <span
                                                class="text-caption text-medium-emphasis font-weight-bold"
                                            >
                                                ASSINATURA
                                            </span>

                                            <v-chip
                                                :color="
                                                    systemSettingsStatus.color
                                                "
                                                size="small"
                                                variant="tonal"
                                                :prepend-icon="
                                                    systemSettingsStatus.icon
                                                "
                                            >
                                                {{ systemSettingsStatus.label }}
                                            </v-chip>
                                        </div>

                                        <div class="d-flex align-center ga-3">
                                            <v-avatar
                                                color="primary"
                                                variant="tonal"
                                                rounded="lg"
                                                size="42"
                                            >
                                                <v-icon
                                                    icon="mdi-calendar-month-outline"
                                                />
                                            </v-avatar>

                                            <div class="flex-grow-1">
                                                <div
                                                    class="text-caption text-medium-emphasis"
                                                >
                                                    Data de validade
                                                </div>

                                                <div
                                                    class="text-subtitle-1 font-weight-bold text-capitalize"
                                                >
                                                    {{
                                                        formatDateBrString(
                                                            systemSettings?.date_payment,
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="text-caption mt-3"
                                            :class="`text-${systemSettingsStatus.color}`"
                                        >
                                            {{ expirationMessage }}
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- #endregion -->

                    <!-- #region Indicadores KPI -->
                    <v-row class="mb-4">
                        <v-col cols="12">
                            <div class="mb-4">
                                <h2 class="text-h6 font-weight-bold">
                                    Indicadores gerais
                                </h2>

                                <p class="text-body-2 text-medium-emphasis">
                                    Resumo dos dados da sua empresa.
                                </p>
                            </div>
                        </v-col>

                        <!-- Funcionários -->
                        <v-col cols="12" sm="6">
                            <v-card
                                rounded="lg"
                                border
                                elevation="0"
                                class="pa-5 h-100"
                            >
                                <div
                                    class="d-flex align-center justify-space-between mb-5"
                                >
                                    <v-avatar
                                        color="primary"
                                        variant="tonal"
                                        rounded="lg"
                                        size="46"
                                    >
                                        <v-icon
                                            icon="mdi-account-group-outline"
                                            size="25"
                                        />
                                    </v-avatar>

                                    <span
                                        class="text-caption text-medium-emphasis font-weight-bold"
                                    >
                                        EQUIPE
                                    </span>
                                </div>

                                <div class="text-body-2 text-medium-emphasis">
                                    Funcionários
                                </div>

                                <div class="text-h3 font-weight-bold my-1">
                                    {{ employeesCount ?? "—" }}
                                </div>

                                <div class="text-caption text-medium-emphasis">
                                    Total de funcionários cadastrados
                                </div>
                            </v-card>
                        </v-col>

                        <!-- Clientes -->
                        <v-col cols="12" sm="6">
                            <v-card
                                rounded="lg"
                                border
                                elevation="0"
                                class="pa-5 h-100"
                            >
                                <div
                                    class="d-flex align-center justify-space-between mb-5"
                                >
                                    <v-avatar
                                        color="success"
                                        variant="tonal"
                                        rounded="lg"
                                        size="46"
                                    >
                                        <v-icon
                                            icon="mdi-account-multiple-plus-outline"
                                            size="25"
                                        />
                                    </v-avatar>

                                    <span
                                        class="text-caption text-medium-emphasis font-weight-bold"
                                    >
                                        RELACIONAMENTO
                                    </span>
                                </div>

                                <div class="text-body-2 text-medium-emphasis">
                                    Clientes
                                </div>

                                <div class="text-h3 font-weight-bold my-1">
                                    {{ clientsCount ?? "—" }}
                                </div>

                                <div class="text-caption text-medium-emphasis">
                                    Total de clientes cadastrados
                                </div>
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- #endregion -->

                    <!-- #region Área Reservada para Gráficos -->
                    <v-row>
                        <v-col cols="12">
                            <div class="mb-4">
                                <h2 class="text-h6 font-weight-bold">
                                    Visão analítica
                                </h2>

                                <p class="text-body-2 text-medium-emphasis">
                                    Área destinada aos indicadores e gráficos da
                                    empresa.
                                </p>
                            </div>

                            <v-card
                                rounded="lg"
                                border
                                elevation="0"
                                class="chart-placeholder d-flex align-center justify-center pa-6"
                            >
                                <div class="text-center" v-if="false">
                                    <v-avatar
                                        color="primary"
                                        variant="tonal"
                                        size="64"
                                        class="mb-4"
                                    >
                                        <v-icon
                                            icon="mdi-chart-box-outline"
                                            size="32"
                                        />
                                    </v-avatar>

                                    <h3
                                        class="text-subtitle-1 font-weight-bold mb-2"
                                    >
                                        Espaço para análises e indicadores
                                    </h3>

                                    <p
                                        class="text-body-2 text-medium-emphasis mx-auto chart-description"
                                    >
                                        Futuramente, você poderá acompanhar a
                                        evolução e os indicadores da sua empresa
                                        nesta área.
                                    </p>

                                    <v-chip
                                        color="secondary"
                                        variant="tonal"
                                        size="small"
                                        class="mt-4"
                                    >
                                        ÁREA RESERVADA
                                    </v-chip>
                                </div>
                                <div v-else>
                                    <v-pie
                                        title="Agendamentos (HOJE)"
                                        animation
                                        :palette="[
                                            '#048BA8',
                                            '#99C24D',
                                            '#F18F01',
                                        ]"
                                        tooltip
                                        legend
                                        :items="[
                                            { key: 1, title: 'Yes', value: 45 },
                                            { key: 2, title: 'No', value: 40 },
                                            {
                                                key: 3,
                                                title: 'Maybe',
                                                value: 15,
                                            },
                                        ]"
                                    />
                                </div>
                            </v-card>
                        </v-col>
                    </v-row>
                    <!-- #endregion -->
                </v-container>
            </v-window-item>
        </v-window>
    </AuthLayout>
</template>

<style scoped lang="scss">
.chart-placeholder {
    min-height: 280px;
    border-style: dashed !important;
}

.chart-description {
    max-width: 380px;
}
</style>
