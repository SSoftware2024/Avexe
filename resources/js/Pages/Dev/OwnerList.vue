<script setup>
import { onMounted, reactive, ref } from "vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useDialogAlert } from "@/composables/useDialogAlert";
import { useMask } from "vuetify";
import { formatDate } from "@/utils/functions";
import userType from "@/enum/userType";

const page = usePage();
const whatsapp_mask = useMask({ mask: "(##) # ####-####" });
const dialog_alert = useDialogAlert();
const modal_show_user = reactive({
    user: "",
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

function _loadData({ page, itemsPerPage, sortBy }) {
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
function _deleteOwner(id) {
    router.delete(route("developer.ownerDelete", [id]));
}
function _toggleActiveOwner(item) {
    router.patch(route("developer.ownerToggleActive", [item.id]));
}
function _editOwner(item) {
    router.get(route("developer.ownerCreateUpdateView", [item.id]));
}
function _deleteQuestion(item) {
    dialog_alert
        .setButtons({
            question_buttons: true,
        })
        .confirmFunction(() => {
            _deleteOwner(item.id);
        });
    dialog_alert.open("Deletar usuario: " + item.name + "?", "question");
}
function _showOwner(item) {
    modal_show_user.user = item;
    modal_show_user.dialog = true;
}
function _closeDialog() {
    modal_show_user.dialog = false;
    modal_show_user.user = null;
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
                @update:options="_loadData"
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
                            <v-list-item @click="_showOwner(item)">
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

                                <v-list-item-title
                                    @click="_toggleActiveOwner(item)"
                                >
                                    {{ item.active ? "Desativar" : "Ativar" }}
                                </v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="_editOwner(item)">
                                <template v-slot:prepend>
                                    <v-icon size="small">mdi-pencil</v-icon>
                                </template>

                                <v-list-item-title>Editar</v-list-item-title>
                            </v-list-item>
                            <v-list-item
                                @click="_deleteQuestion(item)"
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

        <v-dialog v-model="modal_show_user.dialog" max-width="500" scrollable>
            <v-card v-if="modal_show_user.user">
                <v-card-title
                    class="d-flex justify-space-between align-center pa-4"
                >
                    <span class="text-subtitle-1 font-weight-bold"
                        >Dados do Proprietário</span
                    >
                    <v-btn
                        icon
                        variant="text"
                        size="small"
                        @click="_closeDialog"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text class="pa-4 pt-0">
                    <v-progress-circular
                        v-if="modal_show_user.loading"
                        indeterminate
                        color="primary"
                        class="my-6"
                    />
                    <div v-else>
                        <v-tabs
                            v-model="modal_show_user.tab"
                            align-tabs="auto"
                            class="mb-2"
                        >
                            <v-tab value="geral">Geral</v-tab>
                            <v-tab value="conta">Conta</v-tab>
                            <v-tab value="localizacao">Localização</v-tab>
                            <v-tab value="datas">Datas</v-tab>
                        </v-tabs>
                        <v-window v-model="modal_show_user.tab">
                            <v-window-item value="geral">
                                <v-list density="compact" nav>
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >ID:</span
                                            >
                                            {{ modal_show_user.user.id }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Nome:</span
                                            >
                                            {{ modal_show_user.user.name }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >WhatsApp:</span
                                            >
                                            {{
                                                whatsapp_mask.mask(
                                                    modal_show_user.user
                                                        .whatsapp,
                                                ) || "N/A"
                                            }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Tipo:</span
                                            >
                                            {{
                                                userType.getUserTypePTBR(
                                                    modal_show_user.user
                                                        .user_type
                                                )
                                            }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Ativo:</span
                                            >
                                            {{
                                               modal_show_user.user.active ? 'Sim':'Não'
                                            }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >ID Empresa:</span
                                            >
                                            {{
                                                modal_show_user.user
                                                    .company_id || "N/A"
                                            }}
                                        </p></v-list-item
                                    >
                                </v-list>
                            </v-window-item>
                            <v-window-item value="conta">
                                <v-list density="compact" nav>
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >E-mail:</span
                                            >
                                            {{
                                                modal_show_user.user.email ||
                                                "N/A"
                                            }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >E-mail Verif.:</span
                                            >
                                            {{
                                                formatDate(
                                                    modal_show_user.user
                                                        .email_verified_at,
                                                )
                                            }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >2FA:</span
                                            >
                                            Não implementado verificação
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Google ID:</span
                                            >
                                            {{
                                                modal_show_user.user
                                                    .google_id || "N/A"
                                            }}
                                        </p></v-list-item
                                    >
                                </v-list>
                            </v-window-item>
                            <v-window-item value="localizacao">
                                <v-list density="compact" nav>
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Latitude:</span
                                            >
                                            {{
                                                modal_show_user.user.latitude ||
                                                "N/A"
                                            }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Longitude:</span
                                            >
                                            {{
                                                modal_show_user.user
                                                    .longitude || "N/A"
                                            }}
                                        </p></v-list-item
                                    >
                                </v-list>
                            </v-window-item>
                            <v-window-item value="datas">
                                <v-list density="compact" nav>
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Data Nasc.:</span
                                            >
                                            {{
                                                formatDate(
                                                    modal_show_user.user
                                                        .date_of_birth,
                                                )
                                            }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Criado em:</span
                                            >
                                            {{
                                                formatDate(
                                                    modal_show_user.user
                                                        .created_at,
                                                )
                                            }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Atualizado em:</span
                                            >
                                            {{
                                                formatDate(
                                                    modal_show_user.user
                                                        .updated_at,
                                                )
                                            }}
                                        </p></v-list-item
                                    >
                                    <v-list-item
                                        ><p class="mb-0">
                                            <span class="font-weight-bold"
                                                >Deletado em:</span
                                            >
                                            {{
                                                formatDate(
                                                    modal_show_user.user
                                                        .deleted_at,
                                                )
                                            }}
                                        </p></v-list-item
                                    >
                                </v-list>
                            </v-window-item>
                        </v-window>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </AuthLayout>
</template>
