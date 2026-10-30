<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SectionCard from "@/components/SectionCard.vue";
import DeveloperCreateUpdate from "@/PartialPages/Dev/DeveloperCreateUpdate.vue";
const props = defineProps({
    user_data: Object
});
</script>
<template>
    <Head title="Informações do Proprietário" />
    <AuthLayout>
        <div class="d-flex justify-center">
            <SectionCard :title="props.user_data ? 'Atualizar Desenvolvedor':'Cadastrar Desenvolvedor'">
                <div>
                    <DeveloperCreateUpdate :user_data="props.user_data"></DeveloperCreateUpdate>
                </div>
            </SectionCard>
        </div>
    </AuthLayout>
</template>
