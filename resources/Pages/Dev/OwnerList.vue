<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
</script>
<template>
    <Head title="Informações do Proprietário" />
    <AuthLayout>
        <v-btn color="green" variant="flat" @click="router.visit(route('developer.ownerCreateUpdateView'))">
            <v-icon start> mdi-plus </v-icon>
            Novo
        </v-btn>
        <h3>Owner list</h3>
    </AuthLayout>
</template>
