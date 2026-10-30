
<script setup>
import { reactive } from "vue";
import { useMask } from "vuetify";
import { Head, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { useDialogAlert } from "@/composables/useDialogAlert";
import { formatDate, formatBooleanToText } from "@/utils/functions";

const maskes = reactive({
    cnpj: useMask({ mask: "##.###.###/####-##" }),
    cpf: useMask({ mask: "###.###.###-##" }),
    whatsapp: useMask({ mask: "(##) # ####-####" }),
});

const dialog_alert = useDialogAlert();

const modal_show_company = reactive({
    company: null,
    tab: "geral",
    loading: false,
    dialog: false,
});

const datatable = reactive({
    items_per_page: 10,
    headers: [
        {
            title: "Nome",
            align: "start",
            key: "name",
        },
        {
            title: "Razão Social",
            sortable: false,
            key: "corporate_name",
            align: "end",
        },
        {
            title: "CNPJ",
            sortable: false,
            key: "cnpj",
            align: "end",
        },
        {
            title: "CPF",
            sortable: false,
            key: "cpf",
            align: "end",
        },
        {
            title: "Ativo.",
            sortable: false,
            key: "active",
            align: "end",
        },
        {
            title: "Ações",
            key: "actions",
            sortable: false,
        },
    ],
    loading: false,
});

// VISUALIZAR EMPRESA
function _showCompany(item) {
    modal_show_company.company = item;
    modal_show_company.tab = "geral";
    modal_show_company.dialog = true;
}

// FECHAR MODAL
function _closeDialog() {
    modal_show_company.dialog = false;
    modal_show_company.company = null;
}
// EDITAR EMPRESA
function _edit(item) {
    router.get(route("company.createOrUpdateView", [item.id]));
}

// ATIVAR / DESATIVAR EMPRESA
function _toggleActive(item) {
    router.patch(
        route("company.toggleActive"),
        {
            id: item.id,
        },
        {
            only: ["companies"],
        },
    );
}

// DELETAR EMPRESA
function _delete(id) {
    router.delete(route("company.delete", [id]));
}

// CONFIRMAR EXCLUSÃO
function _deleteQuestion(item) {
    dialog_alert
        .setButtons({
            question_buttons: true,
        })
        .confirmFunction(() => {
            _delete(item.id);
        });

    dialog_alert.open(
        "Deletar empresa: " + item.name + "?",
        "question",
    );
}

// CARREGAR DADOS DA TABELA
function _loadData({ page, itemsPerPage, sortBy }) {
    datatable.loading = true;

    router.get(
        route("company.listView"),
        {
            page: page,
            per_page: itemsPerPage,
            sort_by: sortBy,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,

            onFinish: () => {
                datatable.loading = false;
            },
        },
    );
}
</script>

<template>
    <Head title="Empresas" />

    <AuthLayout>
        <v-btn
            color="green"
            variant="flat"
            @click="router.visit(route('company.createOrUpdateView'))"
        >
            <v-icon start>mdi-plus</v-icon>
            Novo
        </v-btn>

        <h3>Empresas</h3>

        <!-- #region tabela lista empresas -->
        <div>
            <v-data-table-server
                v-model:items-per-page="datatable.items_per_page"
                :headers="datatable.headers"
                :items="$page.props.companies.data"
                :items-length="$page.props.companies.total"
                :loading="datatable.loading"
                :items-per-page-options="[10, 25, 50]"
                gridlines="all"
                item-value="id"
                @update:options="_loadData"
            >
                <!-- CPF -->
                <template #item.cpf="{ item }">
                    <span>
                        {{ maskes.cpf.mask(item.cpf) || "N/A" }}
                    </span>
                </template>

                <!-- CNPJ -->
                <template #item.cnpj="{ item }">
                    <span>
                        {{ maskes.cnpj.mask(item.cnpj) || "N/A" }}
                    </span>
                </template>

                <!-- STATUS -->
                <template #item.active="{ item }">
                    <v-icon
                        :color="item.active ? 'success' : 'error'"
                        size="large"
                    >
                        {{
                            item.active
                                ? "mdi-check-circle"
                                : "mdi-close-circle"
                        }}
                    </v-icon>
                </template>

                <!-- AÇÕES -->
                <template #item.actions="{ item }">
                    <v-menu
                        offset-y
                        location="bottom end"
                        :close-on-content-click="false"
                    >
                        <template v-slot:activator="{ props }">
                            <v-btn
                                icon
                                size="small"
                                color="primary"
                                v-bind="props"
                            >
                                <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn>
                        </template>

                        <v-list density="compact" slim>
                            <!-- VISUALIZAR -->
                            <v-list-item @click="_showCompany(item)">
                                <template v-slot:prepend>
                                    <v-icon size="small">
                                        mdi-eye
                                    </v-icon>
                                </template>

                                <v-list-item-title>
                                    Visualizar
                                </v-list-item-title>
                            </v-list-item>

                            <!-- ATIVAR / DESATIVAR -->
                            <v-list-item @click="_toggleActive(item)">
                                <template v-slot:prepend>
                                    <v-icon size="small">
                                        {{
                                            item.active
                                                ? "mdi-close-circle"
                                                : "mdi-check-circle"
                                        }}
                                    </v-icon>
                                </template>

                                <v-list-item-title>
                                    {{
                                        item.active
                                            ? "Desativar"
                                            : "Ativar"
                                    }}
                                </v-list-item-title>
                            </v-list-item>

                            <!-- EDITAR -->
                            <v-list-item @click="_edit(item)">
                                <template v-slot:prepend>
                                    <v-icon size="small">
                                        mdi-pencil
                                    </v-icon>
                                </template>

                                <v-list-item-title>
                                    Editar
                                </v-list-item-title>
                            </v-list-item>

                            <!-- DELETAR -->
                            <v-list-item
                                @click="_deleteQuestion(item)"
                                color="error"
                            >
                                <template v-slot:prepend>
                                    <v-icon size="small" color="error">
                                        mdi-delete
                                    </v-icon>
                                </template>

                                <v-list-item-title>
                                    <span class="text-error">
                                        Deletar
                                    </span>
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </template>
            </v-data-table-server>
        </div>
        <!-- #endregion tabela lista empresas -->

        <!-- #region MODAL VISUALIZAR EMPRESA -->
        <v-dialog
            v-model="modal_show_company.dialog"
            max-width="650"
            scrollable
        >
            <v-card v-if="modal_show_company.company">
                <!-- TÍTULO -->
                <v-card-title
                    class="d-flex justify-space-between align-center pa-4"
                >
                    <div>
                        <div class="text-subtitle-1 font-weight-bold">
                            Dados da Empresa
                        </div>

                        <div class="text-caption text-medium-emphasis">
                            {{ modal_show_company.company.name }}
                        </div>
                    </div>

                    <v-btn
                        icon
                        variant="text"
                        size="small"
                        @click="_closeDialog"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-divider />

                <v-card-text class="pa-4">
                    <!-- ABAS -->
                    <v-tabs
                        v-model="modal_show_company.tab"
                        align-tabs="start"
                        class="mb-3"
                        show-arrows
                    >
                        <v-tab value="geral">Geral</v-tab>
                        <v-tab value="contato">Contato</v-tab>
                        <v-tab value="endereco">Endereço</v-tab>
                        <v-tab value="configuracoes">
                            Configurações
                        </v-tab>
                        <v-tab value="financeiro">Financeiro</v-tab>
                        <v-tab value="datas">Datas</v-tab>
                    </v-tabs>

                    <v-window v-model="modal_show_company.tab">
                        <!-- GERAL -->
                        <v-window-item value="geral">
                            <v-list density="compact">

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Nome:</strong>
                                        {{ modal_show_company.company.name || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Razão Social:</strong>
                                        {{ modal_show_company.company.corporate_name || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>CNPJ:</strong>
                                        {{ maskes.cnpj.mask(modal_show_company.company.cnpj) || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>CPF:</strong>
                                        {{ maskes.cpf.mask(modal_show_company.company.cpf) || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>URL:</strong>
                                        {{ modal_show_company.company.tag_url || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Ativo:</strong>

                                        <v-chip
                                            :color="modal_show_company.company.active ? 'success' : 'error'"
                                            size="small"
                                            class="ml-2"
                                        >
                                            {{ formatBooleanToText(modal_show_company.company.active) }}
                                        </v-chip>
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Logo:</strong>
                                        {{ modal_show_company.company.logo || "N/A" }}
                                    </p>
                                </v-list-item>
                            </v-list>
                        </v-window-item>

                        <!-- CONTATO -->
                        <v-window-item value="contato">
                            <v-list density="compact">
                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>WhatsApp:</strong>
                                        {{ maskes.whatsapp.mask(modal_show_company.company.whatsapp) || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Instagram:</strong>
                                        {{ modal_show_company.company.instagram || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Telegram:</strong>
                                        {{ modal_show_company.company.telegram || "N/A" }}
                                    </p>
                                </v-list-item>
                            </v-list>
                        </v-window-item>

                        <!-- ENDEREÇO -->
                        <v-window-item value="endereco">
                            <v-list density="compact">
                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>CEP:</strong>
                                        {{ modal_show_company.company.cep || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Rua:</strong>
                                        {{ modal_show_company.company.street || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Número:</strong>
                                        {{ modal_show_company.company.number || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Bairro:</strong>
                                        {{ modal_show_company.company.neighborhood || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Complemento:</strong>
                                        {{ modal_show_company.company.complement || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Cidade:</strong>
                                        {{ modal_show_company.company.city || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Estado:</strong>
                                        {{ modal_show_company.company.state || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Latitude:</strong>
                                        {{ modal_show_company.company.latitude ?? "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Longitude:</strong>
                                        {{ modal_show_company.company.longitude ?? "N/A" }}
                                    </p>
                                </v-list-item>
                            </v-list>
                        </v-window-item>

                        <!-- CONFIGURAÇÕES -->
                        <v-window-item value="configuracoes">
                            <v-list density="compact">
                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Permite avaliações:</strong>
                                        {{ formatBooleanToText(modal_show_company.company.is_allowed_reviews) }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Exibe funcionários:</strong>
                                        {{ formatBooleanToText(modal_show_company.company.is_display_employees) }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Permite cancelamento:</strong>
                                        {{ formatBooleanToText(modal_show_company.company.is_allowed_cancellation) }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Tempo permitido para cancelamento:</strong>
                                        {{ modal_show_company.company.cancellation_time_allowed ?? "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Unidade do tempo:</strong>
                                        {{ modal_show_company.company.cancellation_time_unit || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Dias máximos para agendamento:</strong>
                                        {{ modal_show_company.company.appointment_max_days_in_advance ?? "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-divider class="my-2" />

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Possui taxa de agendamento:</strong>
                                        {{ formatBooleanToText(modal_show_company.company.is_have_appointment_tax) }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Valor da taxa:</strong>
                                        {{ modal_show_company.company.appointment_tax_value }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Taxa percentual:</strong>
                                        {{ formatBooleanToText(modal_show_company.company.is_percentage_appointment_tax_value) }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Taxa por serviço:</strong>
                                        {{ formatBooleanToText(modal_show_company.company.is_fee_per_service) }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Desconta taxa:</strong>
                                        {{ formatBooleanToText(modal_show_company.company.is_discount_fee) }}
                                    </p>
                                </v-list-item>
                            </v-list>
                        </v-window-item>

                        <!-- FINANCEIRO -->
                        <v-window-item value="financeiro">
                            <v-list density="compact">
                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Chave PIX:</strong>
                                        {{ modal_show_company.company.pix_key || "N/A" }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Nome do PIX:</strong>
                                        {{ modal_show_company.company.pix_name || "N/A" }}
                                    </p>
                                </v-list-item>
                            </v-list>
                        </v-window-item>

                        <!-- DATAS -->
                        <v-window-item value="datas">
                            <v-list density="compact">
                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Criado em:</strong>
                                        {{ formatDate(modal_show_company.company.created_at) }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Atualizado em:</strong>
                                        {{ formatDate(modal_show_company.company.updated_at) }}
                                    </p>
                                </v-list-item>

                                <v-list-item>
                                    <p class="mb-0">
                                        <strong>Deletado em:</strong>
                                        {{ formatDate(modal_show_company.company.deleted_at) }}
                                    </p>
                                </v-list-item>
                            </v-list>
                        </v-window-item>
                    </v-window>
                </v-card-text>

                <v-divider />

                <!-- RODAPÉ -->
                <v-card-actions class="pa-3">
                    <v-spacer />

                    <v-btn
                        color="primary"
                        variant="flat"
                        @click="_closeDialog"
                    >
                        Fechar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
         <!-- #endregion MODAL VISUALIZAR EMPRESA -->
    </AuthLayout>
</template>
