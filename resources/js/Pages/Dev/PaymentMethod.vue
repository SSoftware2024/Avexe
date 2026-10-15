<script setup>
import { onMounted, ref, watch } from "vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useDialogAlert } from "@/composables/useDialogAlert";
import AuthLayout from "@/layouts/AuthLayout.vue";
import SectionCard from "@/components/SectionCard.vue";
import JsonEditorVue from "json-editor-vue";

const dialog_alert = useDialogAlert(); 
const page = usePage();
const dialog_json_base = ref(false);
const json_content = ref({});
function createJson(overwrite = false) {
    router.post(
        route("payment_method.createJson"),
        {
            json_content: json_content.value,
            overwrite: overwrite
        },
        {
            onSuccess: () => {
                if (page.props.response_data?.json_overwrite) {
                    dialog_alert
                        .setButtons({
                            question_buttons: true,
                        })
                        .confirmFunction(() => {
                            createJson(true);
                        });
                    dialog_alert.open(
                        "Deseja sobreescrever arquivo json atual?",
                        "question",
                    );
                }
            },
        },
    );
}
watch(
    () => page.props.json_running,
    (value) => {
        json_content.value = page.props.json_running;
    },
    { immediate: true },
);
</script>
<template>
    <Head title="Informações do Proprietário" />
    <AuthLayout>
        <h3>Métodos de pagamento (BASE)</h3>
        <ul>
            <li>Conta a receber → receivable</li>
            <li>Conta a pagar → payable</li>
        </ul>
        <div class="mb-3">
            <SectionCard title="Ações" width="100%">
                <div class="d-flex flex-row ga-2">
                    <v-tooltip
                        text="Mostrar arquivo json que serve de base"
                        location="top"
                    >
                        <template v-slot:activator="{ props }">
                            <v-btn
                                color="#F9A825"
                                variant="flat"
                                v-bind="props"
                                @click="dialog_json_base = true"
                            >
                                <v-icon start> mdi-code-json </v-icon>
                                JSON Base
                            </v-btn>
                        </template>
                    </v-tooltip>
                    <v-btn color="green" variant="flat" @click="createJson(false)">
                        <v-icon start> mdi-content-save </v-icon>
                        Salvar
                    </v-btn>
                </div>
            </SectionCard>
        </div>
        <div>
            <JsonEditorVue
                v-model="json_content"
                class="jse-theme-dark"
                language="pt-BR"
                v-bind="{
                    /* local props & attrs */
                }"
            />
        </div>

        <v-dialog v-model="dialog_json_base" width="auto">
            <v-card
                prepend-icon="mdi-code-json"
                title="Json de exemplo, base."
                class="dialog-auth-responsive-800 pa-3"
            >
                <JsonEditorVue
                    v-model="$page.props.json_database_base"
                    language="pt-BR"
                    mode="tree"
                    :main-menu-bar="false"
                    readOnly
                />
                <template #append>
                    <v-btn
                        icon
                        variant="text"
                        @click="dialog_json_base = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </template>
            </v-card>
        </v-dialog>
    </AuthLayout>
</template>
