<script setup>
import { onMounted, reactive, ref } from "vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useDialogAlert } from "@/composables/useDialogAlert";
const page = usePage();
const dialog_alert = useDialogAlert();
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
    </AuthLayout>
</template>
