<script setup>
import { computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { route } from "ziggy-js";

const props = defineProps({
    company: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    id: "",
    name: "",
    corporate_name: "",
    cnpj: "",
    tag_url: "",
});

const is_update = computed(() => !(props.user_data == null));

function _load() {
    form.id = props.user_data.id;
    form.name = props.user_data.name;
    form.email = props.user_data.email;
    form.whatsapp = props.user_data.whatsapp;
    form.password = props.user_data.password;
    form.date_of_birth = props.user_data.date_of_birth;
}

function _save() {
    // form.post(route("developer.ownerCreateOrUpdate"), {
    //     onSuccess: () => {
    //         is_update.value ? null : form.reset();
    //     },
    // });
}

onMounted(() => {
    // if (props.user_data) {
    //     _load();
    // }
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
                    label="Razão Social"
                    variant="outlined"
                    name="corporate_name"
                    v-model="form.corporate_name"
                    :error-messages="form.errors.corporate_name"
                    :hide-details="!form.errors.corporate_name"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="6">
                <v-text-field
                    label="CNPJ"
                    variant="outlined"
                    type="text"
                    name="cnpj"
                    v-model="form.cnpj"
                    :error-messages="form.errors.cnpj"
                    :hide-details="!form.errors.cnpj"
                ></v-text-field>
            </v-col>
            <v-col cols="6">
                <v-text-field
                    label="TAG URL"
                    variant="outlined"
                    type="text"
                    name="tag_url"
                    v-model="form.tag_url"
                    :error-messages="form.errors.tag_url"
                    :hide-details="!form.errors.tag_url"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-select
                    label="Donos"
                    variant="outlined"
                    :items="[
                        'California',
                        'Colorado',
                        'Florida',
                        'Georgia',
                        'Texas',
                        'Wyoming',
                    ]"
                ></v-select>
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
