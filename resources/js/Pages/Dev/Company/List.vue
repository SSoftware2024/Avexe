<script setup>
import { reactive } from "vue";
import { useMask } from "vuetify";
import { Head, useForm, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import AuthLayout from "@/layouts/AuthLayout.vue";
import SectionCard from "@/components/SectionCard.vue";


const maskes = reactive({ 
    cnpj: useMask({ mask: "##.###.###/####-##" }),
    cpf: useMask({ mask: "###.###.###-##" })
})
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

function _edit(item) {
    router.get(route("developer.companyCreateOrUpdateView", [item.id]));
}


function _loadData({ page, itemsPerPage, sortBy }) {
    datatable.loading = true;

    router.get(
        route("developer.companyListView"),
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
            @click="router.visit(route('developer.companyCreateOrUpdateView'))"
        >
            <v-icon start> mdi-plus </v-icon>
            Novo
        </v-btn>
        <h3>Empresas</h3>
        <div>
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
                    <template #item.cpf="{ item }">
                        <span v-html="maskes.cpf.mask(item.cpf)"></span>
                    </template>
                    <template #item.cnpj="{ item }">
                        <span v-html="maskes.cnpj.mask(item.cnpj)"></span>
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
                                <v-list-item @click="">
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

                                    <v-list-item-title @click="">
                                        {{
                                            item.active ? "Desativar" : "Ativar"
                                        }}
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="_edit(item)">
                                    <template v-slot:prepend>
                                        <v-icon size="small">mdi-pencil</v-icon>
                                    </template>

                                    <v-list-item-title
                                        >Editar</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item @click="" color="error">
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
        </div>
    </AuthLayout>
</template>
