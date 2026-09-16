<script setup>
import { onMounted, reactive, ref } from "vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useDialogAlert } from "@/composables/useDialogAlert";
import { useMask } from 'vuetify'

const page = usePage();
const whatsapp_mask = useMask({ mask: '(##) # ####-####' })
const dialog_alert = useDialogAlert();
const dialog = ref(false);
const user = ref(null);
const loadingModal = ref(false);
const tab = ref("geral");
const datatable = reactive({
    items_per_page: 10,
    headers: [
        {
            title: "Nome",
            align: "start",
            key: "name",
        },
        { title: "Whatsapp", sortable: false, key: "whatsapp", align: "end" },
        { title: "E-mail", key: "email", align: "end" },
        {
            title: "Data Nasc.",
            sortable: false,
            key: "date_of_birth_formatted",
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

function loadData({ page, itemsPerPage, sortBy }) {
    datatable.loading = true;

    router.get(
        route("developer.ownerListView"),
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
function deleteOwner(id) {
    datatable.loading = true;
    router.delete(route("developer.ownerDelete", [id]));
}
function toggleActiveOwner(item) {
    router.patch(route("developer.ownerToggleActive", [item.id]));
}
function editOwner(item) {
    router.get(route("developer.ownerCreateUpdateView", [item.id]));
}
function deleteQuestion(item) {
    dialog_alert
        .setButtons({
            question_buttons: true,
        })
        .confirmFunction(() => {
            deleteOwner(item.id);
        });
    dialog_alert.open("Deletar usuario: " + item.name + "?", "question");
}
function showOwner(item) {
    loadingModal.value = true;
    user.value = item;
    dialog.value = true;
    setTimeout(() => {
        loadingModal.value = false;
    }, 500);
}
function closeDialog() {
    dialog.value = false;
    user.value = null;
    loadingModal.value = false;
}
function formatDate(date) {
    if (!date) return "N/A";
    return new Date(date).toLocaleDateString("pt-BR");
}
function formatUserType(type) {
    if (!type) return "N/A";
    const types = { customer: "Cliente", owner: "Proprietário", admin: "Administrador" };
    return types[type] || type;
}
function formatBoolean(val) {
    return val ? "Sim" : "Não";
}
</script>
<template>
    <Head title="Informações do Proprietário" />
    <AuthLayout>
        <v-btn
            color="green"
            variant="flat"
            @click="router.visit(route('developer.ownerCreateUpdateView'))"
        >
            <v-icon start> mdi-plus </v-icon>
            Novo
        </v-btn>
        <h3>Donos</h3>
        <div>
            <v-data-table-server
                v-model:items-per-page="datatable.items_per_page"
                :headers="datatable.headers"
                :items="$page.props.owners.data"
                :items-length="$page.props.owners.total"
                :loading="datatable.loading"
                :items-per-page-options="[1, 10, 25, 50]"
                gridlines="all"
                item-value="id"
                @update:options="loadData"
            >
                <template #item.whatsapp="{ item }">
                    <span>
                        {{ whatsapp_mask.mask(item.whatsapp) }}
                    </span>
                </template>
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
                            <v-list-item @click="showOwner(item)">
                                <template v-slot:prepend>
                                    <v-icon size="small">mdi-eye</v-icon>
                                </template>

                                <v-list-item-title
                                    >Visualizar</v-list-item-title
                                >
                            </v-list-item>
                            <v-list-item @click="">
                                <template v-slot:prepend>
                                    <v-icon size="small">
                                        {{
                                            item.active
                                                ? "mdi-close-circle"
                                                : "mdi-check-circle"
                                        }}
                                    </v-icon>
                                </template>

                                <v-list-item-title @click="toggleActiveOwner(item)">
                                    {{ item.active ? "Desativar" : "Ativar" }}
                                </v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="editOwner(item)">
                                <template v-slot:prepend>
                                    <v-icon size="small">mdi-pencil</v-icon>
                                </template>

                                <v-list-item-title>Editar</v-list-item-title>
                            </v-list-item>
                            <v-list-item
                                @click="deleteQuestion(item)"
                                color="error"
                            >
                                <template v-slot:prepend>
                                    <v-icon size="small" color="error"
                                        >mdi-delete</v-icon
                                    >
                                </template>

                                <v-list-item-title color="danger">
                                    <span class="text-error">Deletar</span>
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </template>
            </v-data-table-server>
        </div>

        <v-dialog v-model="dialog" max-width="500" scrollable>
            <v-card v-if="user">
                <v-card-title class="d-flex justify-space-between align-center pa-4">
                    <span class="text-subtitle-1 font-weight-bold">Dados do Proprietário</span>
                    <v-btn icon variant="text" size="small" @click="closeDialog">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text class="pa-4 pt-0">
                    <v-progress-circular v-if="loadingModal" indeterminate color="primary" class="my-6" />
                    <div v-else>
                        <v-tabs v-model="tab" align-tabs="auto" class="mb-2">
                            <v-tab value="geral">Geral</v-tab>
                            <v-tab value="conta">Conta</v-tab>
                            <v-tab value="localizacao">Localização</v-tab>
                            <v-tab value="datas">Datas</v-tab>
                            <v-tab value="empresa">Empresa</v-tab>
                        </v-tabs>
                        <v-window v-model="tab">
                            <v-window-item value="geral">
                                <v-list density="compact" nav>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">ID:</span> {{ user.id }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Nome:</span> {{ user.name }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">E-mail:</span> {{ user.email || "N/A" }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">WhatsApp:</span> {{ whatsapp_mask.mask(user.whatsapp) || "N/A" }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Tipo:</span> {{ formatUserType(user.user_type) }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Ativo:</span> {{ formatBoolean(user.active) }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Perfil:</span> {{ user.profile || "N/A" }}</p></v-list-item>
                                </v-list>
                            </v-window-item>
                            <v-window-item value="conta">
                                <v-list density="compact" nav>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Google ID:</span> {{ user.google_id || "N/A" }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Senha:</span> {{ user.password ? "********" : "N/A" }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">E-mail Verif.:</span> {{ formatDate(user.email_verified_at) }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Remember Token:</span> {{ user.remember_token ? "********" : "N/A" }}</p></v-list-item>
                                </v-list>
                            </v-window-item>
                            <v-window-item value="localizacao">
                                <v-list density="compact" nav>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Latitude:</span> {{ user.latitude || "N/A" }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Longitude:</span> {{ user.longitude || "N/A" }}</p></v-list-item>
                                </v-list>
                            </v-window-item>
                            <v-window-item value="datas">
                                <v-list density="compact" nav>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Data Nasc.:</span> {{ formatDate(user.date_of_birth) }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Criado em:</span> {{ formatDate(user.created_at) }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Atualizado em:</span> {{ formatDate(user.updated_at) }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">Deletado em:</span> {{ formatDate(user.deleted_at) }}</p></v-list-item>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">2FA:</span> {{ formatDate(user.two_factor_confirmed_at) }}</p></v-list-item>
                                </v-list>
                            </v-window-item>
                            <v-window-item value="empresa">
                                <v-list density="compact" nav>
                                    <v-list-item><p class="mb-0"><span class="font-weight-bold">ID Empresa:</span> {{ user.company_id || "N/A" }}</p></v-list-item>
                                </v-list>
                            </v-window-item>
                        </v-window>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </AuthLayout>
</template>
