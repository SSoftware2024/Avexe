<script setup>
import { reactive, ref, computed } from "vue";
const dialog = ref(false);
const data = reactive({
    title: "",
    message: "",
    type: "",
    color: "",
});
const get_icon = computed(() => {
    switch (data.type) {
        case "success":
            data.title = 'SUCESSO!';
            data.color = "green";
            return "mdi-check-circle";
            break;
        case "warning":
            data.title = 'AVISO!';
            data.color = "#FFB300";
            return "mdi-alert";
            break;
        case "error":
            data.title = 'ERRO!';
            data.color = "red";
            return "mdi-close-octagon";
            break;
        case "info":
        default:
            data.title = 'INFORMAÇÃO';
            data.color = "blue";
            return "mdi-information";
            break;
    }
    
});
function open(message, type) {
    data.message = message;
    data.type = type;
    dialog.value = true;
}
function close() {
    dialog.value = false;
}
defineExpose({
    open,
    close,
});
</script>
<template>
    <v-dialog v-model="dialog" width="auto">
        <v-card max-width="400" class="pa-4">
            <div class="d-flex align-center ga-3 mb-3">
                <v-icon :icon="get_icon" :color="data.color" size="x-large"></v-icon>
                <span class="text-h6 font-weight-bold">{{ data.title }}</span>
            </div>
            <p v-html="data.message" class="text-body-1 text-left"></p>
            <div class="d-flex justify-end mt-4">
                <v-btn
                    color="primary"
                    text="OK"
                    @click="dialog = false"
                ></v-btn>
            </div>
        </v-card>
    </v-dialog>
</template>
