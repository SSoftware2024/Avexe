<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SectionCard from "@/components/SectionCard.vue";
import CompanyCreateUpdate from "@/PartialPages/Dev/CompanyCreateUpdate.vue";
import OwnerCreateUpdate from "@/PartialPages/Dev/OwnerCreateUpdate.vue";
import { ref } from "vue";

const new_owner_dialog = ref(false);
</script>
<template>
    <Head title="Informações do Proprietário" />
    <AuthLayout>
        <div class="d-flex flex-column justify-center align-center">
            <SectionCard title="Opções" class="mb-8">
                <div>
                    <v-btn
                        color="green"
                        variant="flat"
                        @click="new_owner_dialog = true"
                    >
                        <v-icon start> mdi-plus </v-icon>
                        Novo Dono
                    </v-btn>
                </div>
            </SectionCard>
            <SectionCard title="Nova empresa">
                <div>
                    <CompanyCreateUpdate :owners="$page.props.ownersNotSelected"></CompanyCreateUpdate>
                </div>
            </SectionCard>
        </div>

        <v-dialog v-model="new_owner_dialog" width="auto">
            <v-card
                prepend-icon="mdi-account"
                title="Novo dono"
                class="dialog-auth-responsive-800 pa-3"
            >
                <OwnerCreateUpdate @success="new_owner_dialog = false"></OwnerCreateUpdate>

                <template #append>
                    <v-btn icon variant="text" @click="new_owner_dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </template>
            </v-card>
        </v-dialog>
    </AuthLayout>
</template>
