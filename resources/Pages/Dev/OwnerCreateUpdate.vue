<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SectionCard from "@/components/SectionCard.vue";
import OwnerCreateUpdate from "../PartialPages/OwnerCreateUpdate.vue";
const props = defineProps({
    user_data: Object
});
const form = useForm({
    name: "",
    email: "",
    whatsapp: "",
    password: "",
});
</script>
<template>
    <Head title="Informações do Proprietário" />
    <AuthLayout>
        <div class="d-flex justify-center">
            <SectionCard :title="props.user_data ? 'Atualizar Dono':'Cadastrar Dono'">
                <div>
                    <OwnerCreateUpdate :user_data="props.user_data"></OwnerCreateUpdate>
                </div>
            </SectionCard>
        </div>
    </AuthLayout>
</template>
