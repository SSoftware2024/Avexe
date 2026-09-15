<script setup>
import {
    dialog,
    data,
    get_icon,
    confirm,
    cancel,
    close,
} from "@/composables/useDialogAlert";
</script>

<template>
    <v-dialog v-model="dialog" width="auto">
        <v-card max-width="400" class="pa-4">

            <div class="d-flex align-center ga-3 mb-3">
                <v-icon
                    :icon="get_icon"
                    :color="data.color"
                    size="x-large"
                />

                <span class="text-h6 font-weight-bold">
                    {{ data.title }}
                </span>
            </div>

            <p
                v-html="data.message"
                class="text-body-1 text-left"
            />

            <div
                v-if="!data.question_buttons"
                class="d-flex justify-end mt-4"
            >
                <v-btn
                    color="primary"
                    text="OK"
                    @click="close"
                />
            </div>

            <div
                v-else
                class="d-flex justify-end ga-2 mt-4"
            >
                <v-btn
                    color="green"
                    :text="data.confirm_button_text || 'Confirmar'"
                    @click="confirm"
                />

                <v-btn
                    color="#B71C1C"
                    :text="data.cancel_button_text || 'Cancelar'"
                    @click="cancel"
                />
            </div>

        </v-card>
    </v-dialog>
</template>