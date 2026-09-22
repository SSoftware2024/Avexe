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
    owners: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    id: "",
    name: "",
    corporate_name: "",
    cnpj: "",
    cpf: "",
    tag_url: "",
    owners_ids: [],
});

const is_update = computed(() => !(props.company == null));
const sortedOwnersSelected = computed(() => {
    const items = props.owners ?? [];
    const selected = form.owners_ids ?? [];

    return [...items].sort((a, b) => {
        const aSelected = selected.includes(a.id);
        const bSelected = selected.includes(b.id);

        return Number(bSelected) - Number(aSelected);
    });
});

function _load() {
    form.id = props.user_data.id;
    form.name = props.user_data.name;
    form.email = props.user_data.email;
    form.whatsapp = props.user_data.whatsapp;
    form.password = props.user_data.password;
    form.date_of_birth = props.user_data.date_of_birth;
}

function _save() {
    form.post(route("developer.companyCreateOrUpdate"), {
        onSuccess: () => {
            is_update.value ? null : form.reset();
        },
    });
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
                <v-mask-input
                    mask="##.###.###/####-##"
                    label="CNPJ *"
                    variant="outlined"
                    name="cnpj"
                    v-model="form.cnpj"
                    :error-messages="form.errors.cnpj"
                    :hide-details="!form.errors.cnpj"
                ></v-mask-input>
            </v-col>
            <v-col cols="6">
                <v-mask-input
                    mask="###.###.###-##"
                    label="CPF *"
                    variant="outlined"
                    name="cpf"
                    v-model="form.cpf"
                    :error-messages="form.errors.cpf"
                    :hide-details="!form.errors.cpf"
                ></v-mask-input>
            </v-col>
        </v-row>
        <v-row>
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
            <v-col cols="6">
                <v-autocomplete
                    v-model="form.owners_ids"
                    label="Donos"
                    variant="outlined"
                    :items="sortedOwnersSelected"
                    item-title="name"
                    item-value="id"
                    multiple
                    chips
                    :error-messages="form.errors.owners_ids"
                    :hide-details="!form.errors.owners_ids"
                ></v-autocomplete>
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
