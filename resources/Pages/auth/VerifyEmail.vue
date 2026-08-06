<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { computed } from "vue";

const page = usePage();
const form = useForm({});

const status = computed(() => page.props.status);

function _resend() {
    form.post(route("verification.send"));
}
</script>
<template>
    <AppLayout>
        <v-row class="justify-center pa-4">
            <v-col cols="12" md="6" lg="4">
                <v-card class="pa-6 text-center" elevation="2">
                    <v-icon icon="mdi-email-outline" size="64" color="primary" class="mb-4"></v-icon>

                    <h2 class="text-h5 font-weight-bold mb-3">Validar e-mail</h2>

                    <p class="text-body-1 text-medium-emphasis mb-2">
                        Enviamos um link de validação para o seu e-mail. Aguarde até 5 minutos para recebê-lo.
                        E-mail válido durante 60 minutos.
                    </p>

                    <v-alert
                        v-if="status"
                        type="success"
                        variant="tonal"
                        class="mb-4"
                        icon="mdi-check-circle-outline"
                    >
                        {{ status }}
                    </v-alert>

                    <v-btn
                        variant="flat"
                        color="primary"
                        block
                        class="mb-3"
                        prepend-icon="mdi-email-send-outline"
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="_resend"
                    >
                        Reenviar e-mail
                    </v-btn>

                    <p class="text-caption text-medium-emphasis">
                        Se o e-mail não for validado, sua conta será excluída em 24h.
                    </p>
                </v-card>
            </v-col>
        </v-row>
    </AppLayout>
</template>
