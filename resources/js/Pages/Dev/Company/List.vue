<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import SectionCard from "@/components/SectionCard.vue";

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
            tabela datable
        </div>
    </AuthLayout>
</template>