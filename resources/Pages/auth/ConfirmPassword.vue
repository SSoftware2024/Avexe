<script setup>
import CenterLayout from "@/layouts/CenterLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const form = useForm({
    password: ""
});

function _submit() {
    form.post(route("password.confirm.store"), {
        onSuccess: () => {
            window.location.reload();
        },
    });
}
</script>

<template>
    <CenterLayout>
        <div class="d-flex align-center mb-4">
            <v-icon
                icon="mdi-lock-check"
                size="28"
                color="primary"
                class="mr-3"
            ></v-icon>
            <h2 class="text-h5 font-weight-bold">Confirmar senha</h2>
        </div>
        <v-divider class="mb-4"></v-divider>

        <p class="text-body-2 text-medium-emphasis mb-4">
            Por segurança, confirme sua senha para continuar.
        </p>

        <v-form @submit.prevent="_submit">
            <v-text-field
                label="Senha"
                variant="outlined"
                type="password"
                name="password"
                v-model="form.password"
                prepend-inner-icon="mdi-lock"
                :error-messages="form.errors.password"
                :hide-details="!form.errors.password"
                autofocus
                class="mb-4"
            ></v-text-field>
            <v-btn
                variant="flat"
                color="primary"
                block
                append-icon="mdi-check"
                type="submit"
                :loading="form.processing"
                :disabled="form.processing"
            >
                Confirmar
            </v-btn>
        </v-form>
    </CenterLayout>
</template>