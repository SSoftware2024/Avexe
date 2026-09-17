<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SectionCard from "@/components/SectionCard.vue";
const props = defineProps({
    user_data: Object
});
</script>
<template>
    <Head title="Informações do Proprietário" />
    <AuthLayout>
        <div class="d-flex justify-center">
            <SectionCard :title="props.user_data ? 'Atualizar Dono':'Cadastrar Dono'">
                <div>
                    
                </div>
            </SectionCard>
        </div>
    </AuthLayout>
</template>