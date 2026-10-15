<script setup>
import { computed, onMounted, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { unformat } from "v-money3";
import { money3Config } from "@/utils/functions.js";

const page = usePage();

const props = defineProps({
    company_id: {
        type: Number,
        default: null,
    },
    systemSettings: {
        type: Object,
    },
});

const form = useForm({
    simultaneous_access: "",
    max_owners: "",
    max_employees: "",
    date_payment: "",
    interval_return_message: "",
    value_monthly_fee: null,
    company_id: null,
});

function _load() {
    form.simultaneous_access = props.systemSettings?.simultaneous_access;
    form.max_owners = props.systemSettings?.max_owners;
    form.max_employees = props.systemSettings?.max_employees;
    form.date_payment = props.systemSettings?.date_payment;
    form.interval_return_message = props.systemSettings?.interval_return_message;
    form.value_monthly_fee = props.systemSettings?.value_monthly_fee_formated;
    form.company_id = props.company_id;
}
function _save() {
    form.transform((data) => ({
        ...data,
        value_monthly_fee: unformat(data.value_monthly_fee, money3Config),
    })).post(route("company.settings.save"), {
        only: ["response_data",'systemSettings'],
    });
}
onMounted(() => {
    _load();
});
</script>

<template>
    <v-form @submit.prevent="_save">
        <v-row>
            <v-col cols="6">
                <v-text-field
                    label="Acessos simultâneos *"
                    variant="outlined"
                    name="simultaneous_access"
                    type="number"
                    step="1"
                    v-model="form.simultaneous_access"
                    :error-messages="form.errors.simultaneous_access"
                    :hide-details="!form.errors.simultaneous_access"
                ></v-text-field>
            </v-col>
            <v-col cols="6">
                <v-text-field
                    label="Mensagem de retorno (intervalo) *"
                    variant="outlined"
                    name="interval_return_message"
                    type="number"
                    step="1"
                    v-model="form.interval_return_message"
                    :error-messages="form.errors.interval_return_message"
                    :hide-details="!form.errors.interval_return_message"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="6">
                <v-text-field
                    label="Máximo donos *"
                    variant="outlined"
                    type="number"
                    step="1"
                    name="max_owners"
                    v-model="form.max_owners"
                    :error-messages="form.errors.max_owners"
                    :hide-details="!form.errors.max_owners"
                ></v-text-field>
            </v-col>
            <v-col cols="6">
                <v-text-field
                    label="Máximo empregados *"
                    type="number"
                    step="1"
                    variant="outlined"
                    name="max_employees"
                    v-model="form.max_employees"
                    :error-messages="form.errors.max_employees"
                    :hide-details="!form.errors.max_employees"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="6">
                <v-date-input
                    variant="outlined"
                    label="Data pagamento"
                    autocomplete="false"
                    v-model="form.date_payment"
                    :error-messages="form.errors.date_payment"
                    :hide-details="!form.errors.date_payment"
                ></v-date-input>
            </v-col>
            <v-col cols="6">
                <v-text-field
                    label="Mensalidade (R$)"
                    type="text"
                    variant="outlined"
                    name="value_monthly_fee"
                    v-model="form.value_monthly_fee"
                    :error-messages="form.errors.value_monthly_fee"
                    :hide-details="!form.errors.value_monthly_fee"
                    v-money3="money3Config"
                ></v-text-field>
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
