<script setup>
import CenterLayout from "@/layouts/CenterLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const page = usePage();

const form = useForm({
    email: page.props.email,
    password: "",
    password_confirmation: "",
    token: page.props.token
});

function _submit() {
    form.post(route("password.update"), {
        onSuccess: () => console.log('success')
    });
    
}
</script>

<template>
    <CenterLayout>
        <div class="d-flex align-center mb-4">
            <v-icon
                icon="mdi-lock-reset"
                size="28"
                color="primary"
                class="mr-3"
            ></v-icon>
            <h2 class="text-h5 font-weight-bold">Redefinir senha</h2>
        </div>
        <v-divider class="mb-4"></v-divider>

        <v-form @submit.prevent="_submit">
            <v-text-field
                label="Nova senha"
                variant="outlined"
                type="password"
                name="password"
                v-model="form.password"
                :error-messages="form.errors.password"
                :hide-details="!form.errors.password"
                class="mb-4"
            ></v-text-field>
            <v-text-field
                label="Confirmar senha"
                variant="outlined"
                type="password"
                name="password_confirmation"
                v-model="form.password_confirmation"
                :error-messages="form.errors.password_confirmation"
                :hide-details="!form.errors.password_confirmation"
                class="mb-4"
            ></v-text-field>
            <v-btn
                variant="flat"
                color="primary"
                block
                append-icon="mdi-content-save"
                type="submit"
                :loading="form.processing"
                :disabled="form.processing"
            >
                Redefinir senha
            </v-btn>
        </v-form>
    </CenterLayout>
</template>
