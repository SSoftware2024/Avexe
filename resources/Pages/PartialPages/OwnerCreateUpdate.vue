<script setup>
import { computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { route } from "ziggy-js";

const props = defineProps({
    user_data: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    id: "",
    name: "",
    email: "",
    whatsapp: "",
    password: "",
    date_of_birth: "",
});

const is_update = computed(() => !(props.user_data == null));

function _loadUser() {
    form.id = props.user_data.id;
    form.name = props.user_data.name;
    form.email = props.user_data.email;
    form.whatsapp = props.user_data.whatsapp;
    form.password = props.user_data.password;
    form.date_of_birth = props.user_data.date_of_birth;
}

function _save() {
    form.post(route("developer.ownerCreateOrUpdate"), {
        onSuccess: () => {
            is_update.value ? null : form.reset();
        },
    });
}

onMounted(() => {
    if (props.user_data) {
        _loadUser();
    }
});
</script>

<template>
    <v-form @submit.prevent="_save">
        <v-row>
            <v-col cols="6">
                <v-text-field
                    label="Nome *"
                    variant="outlined"
                    name="name"
                    v-model="form.name"
                    :error-messages="form.errors.name"
                    :hide-details="!form.errors.name"
                ></v-text-field>
            </v-col>
            <v-col cols="6">
                <v-text-field
                    label="E-mail *"
                    variant="outlined"
                    name="email"
                    v-model="form.email"
                    :error-messages="form.errors.email"
                    :hide-details="!form.errors.email"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="6">
                <v-mask-input
                    mask="(##) # ####-####"
                    label="Whatsapp *"
                    variant="outlined"
                    name="whatsapp"
                    v-model="form.whatsapp"
                    :error-messages="form.errors.whatsapp"
                    :hide-details="!form.errors.whatsapp"
                ></v-mask-input>
            </v-col>
            <v-col cols="6">
                <v-text-field
                    label="Senha"
                    variant="outlined"
                    type="password"
                    name="password"
                    v-model="form.password"
                    :error-messages="form.errors.password"
                    :hide-details="!form.errors.password"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-date-input
                    variant="outlined"
                    label="Data Nascimento"
                    autocomplete="false"
                    v-model="form.date_of_birth"
                    :error-messages="form.errors.date_of_birth"
                    :hide-details="!form.errors.date_of_birth"
                ></v-date-input>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <div class="d-flex justify-end">
                    <v-btn
                        variant="flat"
                        color="primary"
                        text="Salvar"
                        class="align-self-end"
                        append-icon="mdi-content-save "
                        type="submit"
                        :loading="form.processing"
                        :disabled="form.processing"
                    ></v-btn>
                </div>
            </v-col>
        </v-row>
    </v-form>
</template>
