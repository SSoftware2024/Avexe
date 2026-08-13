<!-- PARTIAL PAGE -->
<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { getCurrentLocation } from "@js/utils/functions.js";
import { useForm, router, usePage } from "@inertiajs/vue3";
import ConfirmPasswordModal from "@/components/ConfirmPasswordModal.vue";

const page = usePage();
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const confirm_password_dialog = ref(false);

const form = useForm({
    name: props.user?.name || "",
    email: props.user?.email || "",
    whatsapp: props.user?.whatsapp || "",
    photo: null,
    latitude: props.user?.latitude || null,
    longitude: props.user?.longitude || null,
});

const form_password = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});
const twofa = reactive({
    setupKey: "",
    qrcode: {
        url: "",
        svg: "",
    },
    recovery_codes: [],
});
const twofa_test_code = ref("");
const twofa_test_code_processing = ref(false);

//campos opcionais
const show_recovery_codes = ref(false);
const two_fa_active = ref(false);
const two_fa_confirmed = ref(false);

const photo_preview = ref(props.user?.profile || null);
//localização
const loading_location = ref(false);
const location_error = ref("");

const snackbar = reactive({
    show: false,
    message: "",
    color: "success",
});

function _enable2fa() {
    axios.post(route("two-factor.enable")).then(function (response) {
        if (response.status == 200) {
            _loadDataTwoFa();
            two_fa_active.value = true;
        }
    });
}
function _disable2fa() {
    console.log("try disable");
    router.delete(route("two-factor.disable"), {
        onSuccess: (page) => {
            two_fa_active.value = false;
        },
    });
}

function _loadDataTwoFa() {
    _getRecoveryCodes();
    _getQrcode();
    _getSetupKey();
}
function _getRecoveryCodes() {
    //false, muda para true, depois de ter os codigos para exibir
    if (!show_recovery_codes.value) {
        axios.get(route("two-factor.recovery-codes")).then(function (response) {
            twofa.recovery_codes = response.data;
            show_recovery_codes.value = true;
        });
    } else {
        show_recovery_codes.value = false;
    }
}

function _getQrcode() {
    axios.get(route("two-factor.qr-code")).then(function (response) {
        twofa.qrcode.svg = response.data.svg;
        twofa.qrcode.url = response.data.url;
    });
}

function _getSetupKey() {
    return axios.get(route("two-factor.secret-key")).then((response) => {
        twofa.setupKey = response.data.secretKey;
    });
}

function _newRecoveryCodes() {
    router.post(
        route("two-factor.regenerate-recovery-codes"),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                show_recovery_codes.value = false;
                _getRecoveryCodes();
            },
        },
    );
}

function _downloadRecoveryCodes() {
    try {
        axios.get(route("two-factor.recovery-codes")).then((response) => {
            const codes = response.data;

            const blob = new Blob([codes.join("\n")], {
                type: "text/plain",
            });

            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "2fa-recovery-codes.txt";
            document.body.appendChild(a);
            a.click();
            a.remove();

            window.URL.revokeObjectURL(url);
        });
    } catch (error) {
        console.error("Erro ao baixar códigos", error);
    }
}

function _test2fa() {
    twofa_test_code_processing.value = true;
    router.post(
        route("two-factor.confirm"),
        {
            code: twofa_test_code.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                twofa_test_code_processing.value = false;
                two_fa_confirmed.value = true;
            },
            onError: (error) => {
                twofa_test_code_processing.value = false;
                snackbar.message =
                    "Código inválido. Digite o código de 6 dígitos do autenticador.";
                snackbar.color = "error";
            },
        },
    );
}

function _toggle2fa() {
    two_fa_active.value ? _enable2fa() : _disable2fa();
}

function _onPhotoChange(event) {
    const file = event.target.files[0];
    if (!file) return;
    form.photo = file;
    photo_preview.value = URL.createObjectURL(file);
}

function _getCurrentLocation() {
    loading_location.value = true;
    location_error.value = "";
    getCurrentLocation().then((location) => {
        loading_location.value = false;
        if (location.error_message) {
            location_error.value = location.error_message;
        } else {
            form.latitude = location.latitude;
            form.longitude = location.longitude;
        }
    });
}

function _save() {
    form.put(route("user-profile-information.update"), {
        onSuccess: () => {
            snackbar.message = "Perfil salvo";
            snackbar.show = true;
            form_password.reset();
        },
    });
}

function _savePassword() {
    form_password.put(route("user-password.update"), {
        onSuccess: () => {
            snackbar.message = "Senha atualizada";
            snackbar.show = true;
            form_password.reset();
        },
    });
}

onMounted(() => {
    two_fa_active.value = page.props.twofa.is_enabled;
});
</script>
<template>
    <ConfirmPasswordModal
        v-model="confirm_password_dialog"
        :successFunction="_toggle2fa"
        :cancelFunction="() => (two_fa_active = !two_fa_active)"
    ></ConfirmPasswordModal>
    <v-container>
        <v-row>
            <!-- DADOS PESSOAIS -->
            <v-col cols="12" md="7">
                <v-card class="pa-6" elevation="2">
                    <div class="d-flex align-center mb-4">
                        <v-icon
                            icon="mdi-account-circle"
                            size="28"
                            color="primary"
                            class="mr-3"
                        ></v-icon>
                        <h2 class="text-h5 font-weight-bold">Dados pessoais</h2>
                    </div>
                    <v-divider class="mb-4"></v-divider>

                    <v-form @submit.prevent="_save">
                        <v-row>
                            <v-col cols="12" class="d-flex align-center">
                                <v-avatar size="72" class="mr-4">
                                    <v-img
                                        v-if="photo_preview"
                                        :src="photo_preview"
                                        cover
                                    ></v-img>
                                    <v-icon
                                        v-else
                                        icon="mdi-account"
                                        size="40"
                                    ></v-icon>
                                </v-avatar>
                                <div>
                                    <v-btn
                                        variant="outlined"
                                        color="primary"
                                        @click="$refs.photo_input.click()"
                                    >
                                        <v-icon start>mdi-camera</v-icon>
                                        Alterar foto
                                    </v-btn>
                                    <input
                                        ref="photo_input"
                                        type="file"
                                        accept="image/*"
                                        class="d-none"
                                        @change="_onPhotoChange"
                                    />
                                </div>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Nome *"
                                    variant="outlined"
                                    v-model="form.name"
                                    :error-messages="
                                        form.errors?.updateProfileInformation
                                            ?.name
                                    "
                                    :hide-details="
                                        !form.errors?.updateProfileInformation
                                            ?.name
                                    "
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="E-mail *"
                                    variant="outlined"
                                    type="email"
                                    v-model="form.email"
                                    :error-messages="
                                        form.errors?.updateProfileInformation
                                            ?.email
                                    "
                                    :hide-details="
                                        !form.errors?.updateProfileInformation
                                            ?.email
                                    "
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-mask-input
                                    mask="(##) # ####-####"
                                    label="Whatsapp *"
                                    variant="outlined"
                                    v-model="form.whatsapp"
                                    :error-messages="
                                        form.errors?.updateProfileInformation
                                            ?.whatsapp
                                    "
                                    :hide-details="
                                        !form.errors?.updateProfileInformation
                                            ?.whatsapp
                                    "
                                ></v-mask-input>
                            </v-col>
                        </v-row>

                        <v-divider class="my-4"></v-divider>

                        <p class="text-subtitle-1 font-weight-bold mb-2">
                            Localização
                        </p>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Latitude"
                                    variant="outlined"
                                    v-model="form.latitude"
                                    :error-messages="
                                        form.errors?.updateProfileInformation
                                            ?.latitude
                                    "
                                    :hide-details="
                                        !form.errors?.updateProfileInformation
                                            ?.latitude
                                    "
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Longitude"
                                    variant="outlined"
                                    v-model="form.longitude"
                                    :error-messages="
                                        form.errors?.updateProfileInformation
                                            ?.longitude
                                    "
                                    :hide-details="
                                        !form.errors?.updateProfileInformation
                                            ?.longitude
                                    "
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-alert
                                    v-if="location_error"
                                    type="error"
                                    variant="tonal"
                                    class="mb-2"
                                    icon="mdi-map-marker-alert"
                                >
                                    {{ location_error }}
                                </v-alert>
                                <v-btn
                                    variant="tonal"
                                    color="primary"
                                    prepend-icon="mdi-crosshairs-gps"
                                    :loading="loading_location"
                                    :disabled="loading_location"
                                    @click="_getCurrentLocation"
                                >
                                    Pegar localização atual
                                </v-btn>
                            </v-col>
                        </v-row>

                        <div class="d-flex justify-end mt-4">
                            <v-btn
                                variant="flat"
                                color="primary"
                                type="submit"
                                prepend-icon="mdi-content-save"
                                :loading="form.processing"
                                :disabled="form.processing"
                            >
                                Salvar alterações
                            </v-btn>
                        </div>
                    </v-form>
                </v-card>
            </v-col>

            <!-- SENHA -->
            <v-col cols="12" md="5">
                <v-card class="pa-6" elevation="2">
                    <div class="d-flex align-center mb-4">
                        <v-icon
                            icon="mdi-lock-reset"
                            size="28"
                            color="primary"
                            class="mr-3"
                        ></v-icon>
                        <h2 class="text-h5 font-weight-bold">Alterar senha</h2>
                    </div>
                    <v-divider class="mb-4"></v-divider>

                    <v-form @submit.prevent="_savePassword">
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    label="Senha atual"
                                    variant="outlined"
                                    type="password"
                                    v-model="form_password.current_password"
                                    :error-messages="
                                        form_password.errors?.updatePassword
                                            ?.current_password
                                    "
                                    :hide-details="
                                        !form_password.errors?.updatePassword
                                            ?.current_password
                                    "
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    label="Nova senha"
                                    variant="outlined"
                                    type="password"
                                    v-model="form_password.password"
                                    :error-messages="
                                        form_password.errors?.updatePassword
                                            ?.password
                                    "
                                    :hide-details="
                                        !form_password.errors?.updatePassword
                                            ?.password
                                    "
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    label="Confirmar nova senha"
                                    variant="outlined"
                                    type="password"
                                    v-model="
                                        form_password.password_confirmation
                                    "
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <div class="d-flex justify-end mt-4">
                            <v-btn
                                variant="flat"
                                color="primary"
                                type="submit"
                                prepend-icon="mdi-content-save"
                                :loading="form_password.processing"
                                :disabled="form_password.processing"
                            >
                                Atualizar senha
                            </v-btn>
                        </div>
                    </v-form>
                </v-card>
            </v-col>

            <!-- AUTENTICAÇÃO 2 FATORES -->
            <v-col cols="12">
                <v-card class="pa-6" elevation="2">
                    <div class="d-flex align-center mb-4">
                        <v-icon
                            icon="mdi-two-factor-authentication"
                            size="28"
                            color="primary"
                            class="mr-3"
                        ></v-icon>
                        <h2 class="text-h5 font-weight-bold">
                            Autenticação de dois fatores
                        </h2>
                        <v-spacer></v-spacer>
                        <v-tooltip
                            v-if="$page.props.twofa.is_enabled"
                            text="Gerar novos códigos de recuperação"
                            location="bottom"
                        >
                            <template v-slot:activator="{ props }">
                                <v-btn
                                    icon="mdi-key-chain-variant"
                                    size="small"
                                    variant="tonal"
                                    color="primary"
                                    v-bind="props"
                                    @click="_newRecoveryCodes"
                                ></v-btn>
                            </template>
                        </v-tooltip>
                    </div>
                    <v-divider class="mb-4"></v-divider>

                    <p class="text-body-1 text-medium-emphasis mb-4">
                        Adicione uma camada extra de segurança à sua conta
                        habilitando a autenticação de dois fatores.
                    </p>
                    <v-switch
                        :label="
                            two_fa_active ? '2FA ativado' : '2FA desativado'
                        "
                        :color="two_fa_active ? 'success' : 'grey'"
                        v-model="two_fa_active"
                        inset
                        hide-details
                        @click="
                            () => {
                                confirm_password_dialog = true;
                            }
                        "
                    ></v-switch>

                    <!-- ÁREA ATIVAÇÃO -->
                    <template v-if="two_fa_active">
                        <v-divider class="my-4"></v-divider>

                        <div v-if="twofa.qrcode.svg">
                            <!-- QR CODE -->
                            <p class="text-subtitle-1 font-weight-bold mb-2">
                                Escaneie o QR Code
                                <span v-if="twofa.setupKey"
                                    >Setup key: {{ twofa.setupKey }}</span
                                >
                            </p>
                            <p class="text-body-2 text-medium-emphasis mb-3">
                                Use um aplicativo autenticador (Google
                                Authenticator, etc.) para escanear o código
                                abaixo.
                            </p>
                            <div
                                class="d-flex justify-center mb-4"
                                v-if="twofa.qrcode.svg"
                                v-html="twofa.qrcode.svg"
                            ></div>

                            <!-- TESTE FINAL -->
                            <v-divider class="my-4"></v-divider>
                            <p class="text-subtitle-1 font-weight-bold mb-2">
                                Teste final de ativação
                            </p>
                            <p class="text-body-2 text-medium-emphasis mb-3">
                                Digite o código de 6 dígitos gerado no
                                aplicativo autenticador para confirmar que o 2FA
                                está funcionando.
                            </p>
                            <div class="d-flex ga-2 align-center">
                                <v-text-field
                                    v-model="twofa_test_code"
                                    label="Código do autenticador"
                                    variant="outlined"
                                    dense
                                    hide-details
                                    maxlength="6"
                                ></v-text-field>
                                <v-btn
                                    variant="flat"
                                    color="primary"
                                    prepend-icon="mdi-send"
                                    :loading="twofa_test_code_processing"
                                    :disabled="twofa_test_code_processing"
                                    class="flex-shrink-0"
                                    @click.prevent="_test2fa"
                                >
                                    Enviar
                                </v-btn>
                            </div>
                            <span
                                v-if="
                                    $page.props.errors
                                        ?.confirmTwoFactorAuthentication?.code
                                "
                            >
                                {{
                                    $page.props.errors
                                        ?.confirmTwoFactorAuthentication?.code
                                }}
                            </span>
                        </div>
                        <v-alert
                            v-if="two_fa_confirmed"
                            type="success"
                            variant="tonal"
                            class="mt-3"
                            icon="mdi-shield-check"
                        >
                            2FA confirmado com sucesso! Sua conta está
                            protegida.
                        </v-alert>
                    </template>
                    <!-- CÓDIGOS DE RECUPERAÇÃO -->
                    <div
                        v-if="twofa.qrcode.svg || $page.props.twofa.is_enabled"
                    >
                        <v-btn
                            variant="tonal"
                            color="primary"
                            block
                            prepend-icon="mdi-key-chain"
                            @click="_getRecoveryCodes"
                        >
                            {{
                                show_recovery_codes
                                    ? "Ocultar códigos de recuperação"
                                    : "Mostrar códigos de recuperação"
                            }}
                        </v-btn>

                        <v-alert
                            v-if="show_recovery_codes"
                            type="warning"
                            variant="tonal"
                            class="mt-3"
                        >
                            <p class="text-subtitle-2 font-weight-bold mb-2">
                                Códigos de recuperação
                            </p>
                            <p class="text-body-2 mb-3">
                                Guarde esses códigos em um local seguro. Eles
                                podem ser usados caso você perca o acesso ao
                                autenticador.
                            </p>
                            <div class="d-flex flex-wrap ga-2">
                                <v-chip
                                    v-for="(
                                        code, index
                                    ) in twofa.recovery_codes"
                                    :key="index"
                                    variant="outlined"
                                    class="font-weight-bold"
                                >
                                    {{ code }}
                                </v-chip>
                            </div>
                            <v-btn
                                variant="tonal"
                                color="primary"
                                size="small"
                                class="mt-3"
                                prepend-icon="mdi-download"
                                @click="_downloadRecoveryCodes"
                            >
                                Baixar
                            </v-btn>
                        </v-alert>
                    </div>
                </v-card>
            </v-col>
        </v-row>

        <v-snackbar
            v-model="snackbar.show"
            :color="snackbar.color"
            location="top"
            :timeout="4000"
        >
            {{ snackbar.message }}
            <template #actions>
                <v-btn
                    color="white"
                    variant="text"
                    @click="snackbar.show = false"
                >
                    FECHAR
                </v-btn>
            </template>
        </v-snackbar>
    </v-container>
</template>
