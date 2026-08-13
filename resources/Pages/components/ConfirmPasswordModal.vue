<script setup>
import { ref } from "vue";
import { useForm } from '@inertiajs/vue3';
import { route } from "ziggy-js";

const props = defineProps({
    successFunction: {
        type: Function,
        required: true
    }
});
const emit = defineEmits(["update:modelValue", "confirm"]);
const form = useForm({
    password: ''
});

function _close() {
    emit("update:modelValue", false);
    form.reset();
}

function _submit() {

    form.post(route('password.confirm.store'),
        {
            onSuccess: (page) => {
                form.reset();
                props.successFunction();
                _close();
            },
        },
    );
}
</script>

<template>
    <v-dialog
        @update:model-value="emit('update:modelValue', $event)"
        width="auto"
        max-width="520"
    >
        <v-card class="pa-4">
            <v-card-title class="d-flex align-center">
                <v-icon icon="mdi-key" color="primary" class="me-2"></v-icon>
                Confirmar senha
                <v-spacer></v-spacer>
                <v-btn
                    icon="mdi-close"
                    color="red"
                    variant="text"
                    size="small"
                    @click="_close"
                ></v-btn>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="_submit">
                    <v-alert v-if="form.errors.password" type="error" variant="tonal" class="mb-3">
                        {{ form.errors.password }}
                    </v-alert>
                    <v-text-field
                        v-model="form.password"
                        label="Senha"
                        type="password"
                        prepend-inner-icon="mdi-lock"
                        variant="outlined"
                        required
                        autofocus
                    ></v-text-field>
                    <div class="d-flex justify-end gap-2">
                        <v-btn variant="text" color="grey" @click="_close"
                            >Cancelar</v-btn
                        >
                        <v-btn type="submit" variant="flat" color="primary" :loading="form.processing"
                            >Confirmar</v-btn
                        >
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
