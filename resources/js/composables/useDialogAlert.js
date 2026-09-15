import { reactive, ref, computed } from "vue";

const dialog = ref(false);

const data = reactive({
    title: "",
    message: "",
    type: "info",
    color: "",

    question_buttons: false, //question_buttons
    cancel_button_text: "",
    cancel_function: null,

    confirm_button_text: "",
    confirm_function: null,
});

const get_icon = computed(() => {
    switch (data.type) {
        case "question":
            data.title = "Deseja continuar?";
            data.color = "#5C6BC0";
            return "mdi-help-circle-outline";

        case "success":
            data.title = "SUCESSO!";
            data.color = "green";
            return "mdi-check-circle";

        case "warning":
            data.title = "AVISO!";
            data.color = "#FFB300";
            return "mdi-alert";

        case "error":
            data.title = "ERRO!";
            data.color = "red";
            return "mdi-close-octagon";

        default:
            data.title = "INFORMAÇÃO";
            data.color = "blue";
            return "mdi-information";
    }
});

function open(message, type = "info") {
    data.message = message;
    data.type = type;
    dialog.value = true;
    data.question_buttons = type === 'question';
}

function close() {
    dialog.value = false;
}

function setButtons({
    question_buttons = false,
    cancel_button_text = "",
    confirm_button_text = "",
}) {
    data.question_buttons = question_buttons;
    data.cancel_button_text = cancel_button_text;
    data.confirm_button_text = confirm_button_text;

    return api;
}

function confirmFunction(callback) {
    data.confirm_function = callback;

    return api;
}

function cancelFunction(callback) {
    data.cancel_function = callback;

    return api;
}

function confirm() {
    data.confirm_function?.();
    close();
}

function cancel() {
    data.cancel_function?.();
    close();
}

const api = {
    open,
    close,
    setButtons,
    confirmFunction,
    cancelFunction,
};

function useDialogAlert() {
    return api;
}

export {
    dialog,
    data,
    get_icon,
    open,
    close,
    confirm,
    cancel,
    useDialogAlert
};