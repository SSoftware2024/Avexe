<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import SectionCard from "@/components/SectionCard.vue";
import CompanyCreateUpdate from "@/PartialPages/Dev/CompanyCreateUpdate.vue";
import CompanyOperatingHours from "@/PartialPages/Dev/CompanyOperatingHours.vue";
import OwnerCreateUpdate from "@/PartialPages/Dev/OwnerCreateUpdate.vue";
import { onMounted, ref } from "vue";

const page = usePage();
const new_owner_dialog = ref(false);
const tab = ref("company");
function _ownersByCompanyIds() {
    const owners = page.props?.ownersByCompany ?? [];
    return owners.map((owner) => owner.id);
}

onMounted(() => {});
</script>
<template>
    <Head title="Informações do Proprietário" />
    <AuthLayout>
        <v-sheet elevation="2">
            <v-tabs v-model="tab" color="primary">
                <v-tab value="company">Empresa</v-tab>
                <v-tab value="company_operating_hours">
                    Horário Funcionamento
                </v-tab>
            </v-tabs>

            <v-divider></v-divider>

            <v-tabs-window v-model="tab">
                <v-tabs-window-item value="company">
                    <div
                        class="d-flex flex-column justify-center align-center mt-3 mb-2"
                    >
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
                                <CompanyCreateUpdate
                                    :owners="$page.props.ownersAvalable"
                                    :owners_selected_ids="_ownersByCompanyIds()"
                                    :company="$page.props?.company"
                                ></CompanyCreateUpdate>
                            </div>
                        </SectionCard>
                    </div>
                </v-tabs-window-item>
                <v-tabs-window-item value="company_operating_hours">
                    <div
                        class="mt-3 mb-2 pa-2"
                    >
                         <CompanyOperatingHours></CompanyOperatingHours>
                    </div>
                </v-tabs-window-item>
            </v-tabs-window>
        </v-sheet>

        <v-dialog v-model="new_owner_dialog" width="auto">
            <v-card
                prepend-icon="mdi-account"
                title="Novo dono"
                class="dialog-auth-responsive-800 pa-3"
            >
                <OwnerCreateUpdate
                    @success="new_owner_dialog = false"
                ></OwnerCreateUpdate>

                <template #append>
                    <v-btn
                        icon
                        variant="text"
                        @click="new_owner_dialog = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </template>
            </v-card>
        </v-dialog>
    </AuthLayout>
</template>


