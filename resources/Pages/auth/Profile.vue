<script setup>
import { reactive, ref, watch } from "vue";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const form = reactive({
    name: props.user?.name || "",
    email: props.user?.email || "",
    whatsapp: props.user?.whatsapp || "",
    photo: null,
    new_password: "",
    confirm_password: "",
    latitude: props.user?.latitude || null,
    longitude: props.user?.longitude || null,
});

const photo_preview = ref(props.user?.profile || null);
const two_fa_active = ref(false);
const two_fa_confirmed = ref(false);
const qr_code_url = ref("");
const recovery_codes = ref([]);
const show_recovery_codes = ref(false);
const test_code = ref("");
const test_processing = ref(false);
const loading_location = ref(false);
const location_error = ref("");

const snackbar = reactive({
    show: false,
    message: "",
    color: "success",
});

function _generateRecoveryCodes() {
    const codes = [];
    for (let i = 0; i < 8; i++) {
        const random = (length) => {
            let value = "";
            const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            for (let j = 0; j < length; j++) {
                value += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return value;
        };
        codes.push(`${random(4)}-${random(4)}`);
    }
    return codes;
}

function _generateSecret() {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
    let secret = "";
    for (let i = 0; i < 32; i++) {
        secret += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return secret;
}

function _buildQrCodeUrl() {
    const label = encodeURIComponent(props.user?.email || "avexe");
    const secret = _generateSecret();
    const otpauth = `otpauth://totp/Avexe:${label}?secret=${secret}&issuer=Avexe`;
    return `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(
        otpauth
    )}`;
}

function _toggle2fa() {
    if (two_fa_active.value) {
        qr_code_url.value = _buildQrCodeUrl();
        recovery_codes.value = _generateRecoveryCodes();
        two_fa_confirmed.value = false;
        show_recovery_codes.value = false;
        test_code.value = "";
    } else {
        qr_code_url.value = "";
        recovery_codes.value = [];
        two_fa_confirmed.value = false;
        show_recovery_codes.value = false;
        test_code.value = "";
    }
}

function _regenerateRecoveryCodes() {
    recovery_codes.value = _generateRecoveryCodes();
    show_recovery_codes.value = true;
    snackbar.message = "Novos códigos de recuperação gerados.";
    snackbar.color = "success";
    snackbar.show = true;
}

function _test2fa() {
    test_processing.value = true;
    setTimeout(() => {
        test_processing.value = false;
        if (test_code.value.trim().length === 6) {
            two_fa_confirmed.value = true;
            snackbar.message = "2FA validado com sucesso!";
            snackbar.color = "success";
        } else {
            snackbar.message = "Código inválido. Digite o código de 6 dígitos do autenticador.";
            snackbar.color = "error";
        }
        snackbar.show = true;
    }, 800);
}

function _onPhotoChange(event) {
    const file = event.target.files[0];
    if (!file) return;
    form.photo = file;
    photo_preview.value = URL.createObjectURL(file);
}

function _getCurrentLocation() {
    if (!navigator.geolocation) {
        location_error.value = "Geolocalização não suportada no seu navegador.";
        return;
    }
    loading_location.value = true;
    location_error.value = "";
    navigator.geolocation.getCurrentPosition(
        (position) => {
            form.latitude = Number(position.coords.latitude.toFixed(8));
            form.longitude = Number(position.coords.longitude.toFixed(8));
            loading_location.value = false;
        },
        (error) => {
            loading_location.value = false;
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    location_error.value =
                        "Permissão de localização negada. Habilite para usar esse recurso.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    location_error.value = "Localização indisponível no momento.";
                    break;
                case error.TIMEOUT:
                    location_error.value = "Tempo esgotado ao buscar localização.";
                    break;
                default:
                    location_error.value = "Erro ao obter localização.";
            }
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

function _save() {
    snackbar.message = "Dados salvos (front-end).";
    snackbar.show = true;
}
</script>
<template>
    <v-container class="mt-4">
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
                                    <v-icon v-else icon="mdi-account" size="40"></v-icon>
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
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="E-mail *"
                                    variant="outlined"
                                    type="email"
                                    v-model="form.email"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-mask-input
                                    mask="(##) # ####-####"
                                    label="Whatsapp *"
                                    variant="outlined"
                                    v-model="form.whatsapp"
                                ></v-mask-input>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Nova senha"
                                    variant="outlined"
                                    type="password"
                                    v-model="form.new_password"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Confirmar nova senha"
                                    variant="outlined"
                                    type="password"
                                    v-model="form.confirm_password"
                                ></v-text-field>
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
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    label="Longitude"
                                    variant="outlined"
                                    v-model="form.longitude"
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
                            >
                                Salvar alterações
                            </v-btn>
                        </div>
                    </v-form>
                </v-card>
            </v-col>

            <!-- AUTENTICAÇÃO 2 FATORES -->
            <v-col cols="12" md="5">
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
                            v-if="two_fa_active"
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
                                    @click="_regenerateRecoveryCodes"
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
                            two_fa_active
                                ? '2FA ativado'
                                : '2FA desativado'
                        "
                        :color="two_fa_active ? 'success' : 'grey'"
                        v-model="two_fa_active"
                        inset
                        hide-details
                        @change="_toggle2fa"
                    ></v-switch>

                    <!-- ÁREA ATIVAÇÃO -->
                    <template v-if="two_fa_active">
                        <v-divider class="my-4"></v-divider>

                        <!-- QR CODE -->
                        <p class="text-subtitle-1 font-weight-bold mb-2">
                            Escaneie o QR Code
                        </p>
                        <p class="text-body-2 text-medium-emphasis mb-3">
                            Use um aplicativo autenticador (Google
                            Authenticator, etc.) para escanear o código abaixo.
                        </p>
                        <div class="d-flex justify-center mb-4">
                            <v-img
                                :src="qr_code_url"
                                width="200"
                                height="200"
                                contain
                                class="rounded-lg border"
                            ></v-img>
                        </div>

                        <!-- CÓDIGOS DE RECUPERAÇÃO -->
                        <v-btn
                            variant="tonal"
                            color="primary"
                            block
                            prepend-icon="mdi-key-chain"
                            @click="show_recovery_codes = !show_recovery_codes"
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
                                    v-for="(code, index) in recovery_codes"
                                    :key="index"
                                    variant="outlined"
                                    class="font-weight-bold"
                                >
                                    {{ code }}
                                </v-chip>
                            </div>
                        </v-alert>

                        <!-- TESTE FINAL -->
                        <v-divider class="my-4"></v-divider>
                        <p class="text-subtitle-1 font-weight-bold mb-2">
                            Teste final de ativação
                        </p>
                        <p class="text-body-2 text-medium-emphasis mb-3">
                            Digite o código de 6 dígitos gerado no aplicativo
                            autenticador para confirmar que o 2FA está
                            funcionando.
                        </p>
                        <div class="d-flex ga-2 align-center">
                            <v-text-field
                                v-model="test_code"
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
                                :loading="test_processing"
                                :disabled="test_processing"
                                class="flex-shrink-0"
                                @click="_test2fa"
                            >
                                Enviar
                            </v-btn>
                        </div>
                        <v-alert
                            v-if="two_fa_confirmed"
                            type="success"
                            variant="tonal"
                            class="mt-3"
                            icon="mdi-shield-check"
                        >
                            2FA confirmado com sucesso! Sua conta está protegida.
                        </v-alert>
                    </template>
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
