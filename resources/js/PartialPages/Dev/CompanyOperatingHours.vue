<script setup>
import { computed, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const props = defineProps({
    company: {
        type: Object,
        default: null,
    },
});

const days = [
    {
        key: "monday",
        label: "Segunda-feira",
    },
    {
        key: "tuesday",
        label: "Terça-feira",
    },
    {
        key: "wednesday",
        label: "Quarta-feira",
    },
    {
        key: "thursday",
        label: "Quinta-feira",
    },
    {
        key: "friday",
        label: "Sexta-feira",
    },
    {
        key: "saturday",
        label: "Sábado",
    },
    {
        key: "sunday",
        label: "Domingo",
    },
];

const form = useForm({
    monday_is_active: true,
    monday_morning_start_time: null,
    monday_morning_end_time: null,
    monday_afternoon_start_time: null,
    monday_afternoon_end_time: null,
    monday_evening_start_time: null,
    monday_evening_end_time: null,

    tuesday_is_active: true,
    tuesday_morning_start_time: null,
    tuesday_morning_end_time: null,
    tuesday_afternoon_start_time: null,
    tuesday_afternoon_end_time: null,
    tuesday_evening_start_time: null,
    tuesday_evening_end_time: null,

    wednesday_is_active: true,
    wednesday_morning_start_time: null,
    wednesday_morning_end_time: null,
    wednesday_afternoon_start_time: null,
    wednesday_afternoon_end_time: null,
    wednesday_evening_start_time: null,
    wednesday_evening_end_time: null,

    thursday_is_active: true,
    thursday_morning_start_time: null,
    thursday_morning_end_time: null,
    thursday_afternoon_start_time: null,
    thursday_afternoon_end_time: null,
    thursday_evening_start_time: null,
    thursday_evening_end_time: null,

    friday_is_active: true,
    friday_morning_start_time: null,
    friday_morning_end_time: null,
    friday_afternoon_start_time: null,
    friday_afternoon_end_time: null,
    friday_evening_start_time: null,
    friday_evening_end_time: null,

    saturday_is_active: true,
    saturday_morning_start_time: null,
    saturday_morning_end_time: null,
    saturday_afternoon_start_time: null,
    saturday_afternoon_end_time: null,
    saturday_evening_start_time: null,
    saturday_evening_end_time: null,

    sunday_is_active: true,
    sunday_morning_start_time: null,
    sunday_morning_end_time: null,
    sunday_afternoon_start_time: null,
    sunday_afternoon_end_time: null,
    sunday_evening_start_time: null,
    sunday_evening_end_time: null,
});

const isUpdate = computed(() => props.company != null);

function _load() {
}

function _save() {
    // const url = route("companyOperatingHoursUpdate.create");
    const url = '';

    if (!isUpdate.value) {
        form.post(url, {
            onSuccess: () => {
                form.reset();
            },
        });

        return;
    }

    form.patch(url, {
        onSuccess: () => {
            form.reset();
        },
    });
}

onMounted(() => {
    if (props.company) {
        load();
    }
});
</script>

<template>
    <v-form class="operating-hours-form" @submit.prevent="_save">
        <div class="operating-hours-table-wrapper">
            <table class="operating-hours-table">
                <thead>
                    <tr>
                        <th class="day-column">
                            Dia
                        </th>

                        <th class="time-column">
                            Manhã Início
                        </th>

                        <th class="time-column">
                            Manhã Fim
                        </th>

                        <th class="time-column">
                            Tarde Início
                        </th>

                        <th class="time-column">
                            Tarde Fim
                        </th>

                        <th class="time-column">
                            Noite Início
                        </th>

                        <th class="time-column">
                            Noite Fim
                        </th>

                        <th class="active-column">
                            Ativo
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="day in days"
                        :key="day.key"
                    >
                        <td class="day-name">
                            {{ day.label }}
                        </td>

                        <!-- Manhã - Início -->
                        <td>
                            <v-text-field
                                v-model="
                                    form[
                                        `${day.key}_morning_start_time`
                                    ]
                                "
                                type="time"
                                density="compact"
                                variant="outlined"
                                hide-details
                            />
                        </td>

                        <!-- Manhã - Fim -->
                        <td>
                            <v-text-field
                                v-model="
                                    form[
                                        `${day.key}_morning_end_time`
                                    ]
                                "
                                type="time"
                                density="compact"
                                variant="outlined"
                                hide-details
                            />
                        </td>

                        <!-- Tarde - Início -->
                        <td>
                            <v-text-field
                                v-model="
                                    form[
                                        `${day.key}_afternoon_start_time`
                                    ]
                                "
                                type="time"
                                density="compact"
                                variant="outlined"
                                hide-details
                            />
                        </td>

                        <!-- Tarde - Fim -->
                        <td>
                            <v-text-field
                                v-model="
                                    form[
                                        `${day.key}_afternoon_end_time`
                                    ]
                                "
                                type="time"
                                density="compact"
                                variant="outlined"
                                hide-details
                            />
                        </td>

                        <!-- Noite - Início -->
                        <td>
                            <v-text-field
                                v-model="
                                    form[
                                        `${day.key}_evening_start_time`
                                    ]
                                "
                                type="time"
                                density="compact"
                                variant="outlined"
                                hide-details
                            />
                        </td>

                        <!-- Noite - Fim -->
                        <td>
                            <v-text-field
                                v-model="
                                    form[
                                        `${day.key}_evening_end_time`
                                    ]
                                "
                                type="time"
                                density="compact"
                                variant="outlined"
                                hide-details
                            />
                        </td>

                        <!-- Ativo -->
                        <td class="active-cell">
                            <v-switch
                                v-model="
                                    form[
                                        `${day.key}_is_active`
                                    ]
                                "
                                color="primary"
                                density="compact"
                                hide-details
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <v-row class="mt-4">
            <v-col
                cols="12"
                class="d-flex justify-end"
            >
                <v-btn
                    variant="flat"
                    color="primary"
                    type="submit"
                    append-icon="mdi-content-save"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    Salvar
                </v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>

<style scoped>
.operating-hours-form {
    width: 100%;
    min-width: 0;
}

.operating-hours-table-wrapper {
    width: 100%;
    max-width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
}

.operating-hours-table {
    width: 1200px;
    min-width: 1200px;
    border-collapse: collapse;
}

.operating-hours-table th {
    padding: 12px 10px;
    font-weight: 600;
    text-align: left;
    white-space: nowrap;
}

.operating-hours-table td {
    padding: 12px 10px;
    vertical-align: middle;
    white-space: nowrap;
}

.operating-hours-table tbody tr {
    border-top: 1px solid rgba(0, 0, 0, 0.08);
}

.day-column {
    width: 160px;
}

.time-column {
    width: 150px;
}

.active-column {
    width: 90px;
    text-align: center !important;
}

.day-name {
    font-weight: 500;
}

.active-cell {
    text-align: center;
}

.active-cell :deep(.v-input) {
    display: flex;
    justify-content: center;
}

.operating-hours-table :deep(.v-text-field) {
    min-width: 130px;
}
</style>