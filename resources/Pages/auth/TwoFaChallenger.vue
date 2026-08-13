<script setup>
import CenterLayout from "@/layouts/CenterLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { computed, ref, watch } from "vue";

const code_len = 6;

const form = useForm({
    code: "",
    recovery_code: "",
});

const using_recovery = ref(false);
const digits = ref(Array.from({ length: code_len }, () => ""));
const inputs = ref([]);

const code_complete = computed(() => digits.value.every((d) => d !== ""));
//WATCHS
watch(code_complete, (complete) => {
    if (complete && !using_recovery.value && !form.processing) {
        _submit();
    }
});
watch(using_recovery, () => {
    form.clearErrors();
});
//FUNCTIONS
function _onType(index, event) {
    digits.value[index] = event.target.value.slice(-1);

    if (digits.value[index] && index < code_len - 1) {
        inputs.value[index + 1]?.focus();
    }
}

function _onKeydown(index, event) {
    if (event.key === "Backspace" && digits.value[index] === "" && index > 0) {
        inputs.value[index - 1]?.focus();
    }

    if (event.key === "ArrowLeft" && index > 0) {
        inputs.value[index - 1]?.focus();
    }

    if (event.key === "ArrowRight" && index < code_len - 1) {
        inputs.value[index + 1]?.focus();
    }
}

function _onPaste(event) {
    event.preventDefault();

    const pasted = (event.clipboardData?.getData("text") || "").replace(
        /\D/g,
        "",
    );

    [...pasted].slice(0, code_len).forEach((char, i) => {
        digits.value[i] = char;
    });

    const last = Math.max(Math.min(pasted.length, code_len) - 1, 0);
    inputs.value[last]?.focus();
}

function _backToCode() {
    form.recovery_code = "";
    using_recovery.value = false;
    inputs.value[0]?.focus();
}
function _clearFields() {
    form.reset();
    digits.value = Array.from({ length: code_len }, () => "");
    inputs.value[0]?.focus();
}
function _submit() {
    if (form.processing) {
        return;
    }

    if (using_recovery.value) {
        form.code = "";
        form.post(route("two-factor.login.store"), {
            onFinish: () => {
                form.hasErrors ? _clearFields() : null;
            },
        });
        return;
    }

    form.code = digits.value.join("");
    form.post(route("two-factor.login.store"), {
        onFinish: () => {
            form.hasErrors ? _clearFields() : null;
        },
    });
}
</script>

<template>
    <CenterLayout>
        <div class="text-center">
            <v-avatar color="primary" size="64" class="mb-3 elevation-3">
                <v-icon
                    icon="mdi-shield-lock-outline"
                    size="36"
                    color="white"
                ></v-icon>
            </v-avatar>

            <h2 class="text-h5 font-weight-bold">Verificação em duas etapas</h2>

            <p
                v-if="!using_recovery"
                class="text-body-2 text-medium-emphasis mt-2"
            >
                Digite o código de <strong>6 dígitos</strong> gerado pelo seu
                aplicativo autenticador (Google Authenticator, Authy, entre
                outros) para concluir o login.
            </p>

            <p v-else class="text-body-2 text-medium-emphasis mt-2">
                Digite um dos <strong>códigos de recuperação</strong> salvos
                quando ativou a verificação em duas etapas. Cada código tem 16
                dígitos e pode ser usado uma única vez.
            </p>

            <v-alert
                v-if="form.hasErrors"
                type="error"
                variant="tonal"
                class="mt-4"
                icon="mdi-alert-circle-outline"
                density="compact"
            >
                {{
                    form.errors.code ||
                    form.errors.recovery_code ||
                    "Não foi possível validar o código. Verifique e tente novamente."
                }}
            </v-alert>
        </div>

        <v-divider class="my-6"></v-divider>

        <form @submit.prevent="_submit">
            <template v-if="!using_recovery">
                <div class="d-flex justify-space-between g-2" @paste="_onPaste">
                    <input
                        v-for="(_, i) in code_len"
                        :key="i"
                        :ref="(el) => (inputs[i] = el)"
                        :value="digits[i]"
                        inputmode="numeric"
                        maxlength="1"
                        autocomplete="one-time-code"
                        pattern="[0-9]*"
                        aria-label="Dígito"
                        class="otp-input"
                        :class="{ 'otp-input--filled': digits[i] }"
                        @input="_onType(i, $event)"
                        @keydown="_onKeydown(i, $event)"
                        @focus="(e) => e.target.select()"
                    />
                </div>

                <p class="text-caption text-medium-emphasis text-center mt-3">
                    O código costuma ser preenchido automaticamente no seu
                    celular.
                </p>

                <v-btn
                    variant="flat"
                    color="primary"
                    block
                    class="mt-4"
                    type="submit"
                    append-icon="mdi-login"
                    :loading="form.processing"
                    :disabled="form.processing || !code_complete"
                >
                    Verificar e entrar
                </v-btn>

                <div class="text-center mt-3">
                    <v-btn
                        variant="text"
                        color="primary"
                        size="small"
                        prepend-icon="mdi-key-chain"
                        @click="using_recovery = true"
                    >
                        Não tem o app? Use um código de recuperação
                    </v-btn>
                </div>
            </template>

            <template v-else>
                <v-text-field
                    v-model="form.recovery_code"
                    label="Código de recuperação"
                    variant="outlined"
                    hide-details="auto"
                    placeholder="xxxx-xxxx-xxxx-xxxx"
                    :error-messages="form.errors.recovery_code"
                    class="mb-4"
                    @keyup.enter="_submit"
                ></v-text-field>

                <p class="text-caption text-medium-emphasis mb-4">
                    Os códigos de recuperação foram gerados quando a verificação
                    em duas etapas foi ativada. Em caso de perda, a conta só
                    pode ser recuperada entrando em contato com o suporte.
                </p>

                <v-btn
                    variant="flat"
                    color="primary"
                    block
                    type="submit"
                    append-icon="mdi-login"
                    :loading="form.processing"
                    :disabled="form.processing || !form.recovery_code"
                >
                    Verificar e entrar
                </v-btn>

                <div class="text-center mt-3">
                    <v-btn
                        variant="text"
                        color="primary"
                        size="small"
                        prepend-icon="mdi-cellphone-lock"
                        @click="_backToCode"
                    >
                        Voltar para o código de 6 dígitos
                    </v-btn>
                </div>
            </template>
        </form>

        <v-divider class="my-6"></v-divider>

        <div class="d-flex justify-center ga-8">
            <div class="text-center">
                <v-icon icon="mdi-timer-sand" color="medium-emphasis"></v-icon>
                <p class="text-caption text-medium-emphasis mt-1">
                    5 tentativas por minuto
                </p>
            </div>
            <div class="text-center">
                <v-icon
                    icon="mdi-shield-check"
                    color="medium-emphasis"
                ></v-icon>
                <p class="text-caption text-medium-emphasis mt-1">
                    Sua conta está protegida
                </p>
            </div>
        </div>
    </CenterLayout>
</template>

<style scoped>
.otp-input {
    width: 48px;
    height: 56px;
    padding: 0;
    border: 1px solid rgba(128, 128, 128, 0.4);
    border-radius: 8px;
    background: transparent;
    color: inherit;
    font-size: 1.4rem;
    font-weight: 600;
    text-align: center;
    outline: none;
    transition:
        border-color 0.15s ease,
        box-shadow 0.15s ease;
}

.otp-input:focus {
    border-color: rgb(var(--v-theme-primary));
    box-shadow: 0 0 0 4px rgba(var(--v-theme-primary), 0.15);
}

.otp-input--filled {
    border-color: rgba(var(--v-theme-primary), 0.6);
}
</style>
