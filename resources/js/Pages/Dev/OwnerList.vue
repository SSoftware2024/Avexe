<script setup>
import { onMounted, reactive, ref } from "vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
const page = usePage();
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
                <template #item.actions="{ item }">
                    <div class="d-flex ga-2">
                        <!-- Visualizar -->
                        <v-tooltip text="Visualizar">
                            <template #activator="{ props }">
                                <v-btn
                                    v-bind="props"
                                    icon="mdi-eye"
                                    size="small"
                                    color="info"
                                    @click="showOwner(item)"
                                />
                            </template>
                        </v-tooltip>

                        <!-- Editar -->
                        <v-tooltip text="Editar">
                            <template #activator="{ props }">
                                <v-btn
                                    v-bind="props"
                                    icon="mdi-pencil"
                                    size="small"
                                    color="#FFEE58"
                                    @click="() => router.get(route('developer.ownerCreateUpdateView', [item.id]))"
                                />
                            </template>
                        </v-tooltip>

                        <!-- Excluir -->
                        <v-tooltip text="Excluir">
                            <template #activator="{ props }">
                                <v-btn
                                    v-bind="props"
                                    icon="mdi-delete"
                                    size="small"
                                    color="error"
                                    @click="deleteOwner(item)"
                                />
                            </template>
                        </v-tooltip>
                    </div>
                </template>
            </v-data-table-server>
        </div>
    </AuthLayout>
</template>
