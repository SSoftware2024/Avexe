<script setup>
import { onMounted, reactive, ref, computed } from "vue";
import { useDisplay } from "vuetify";
import { images } from "../../js/utils/files.js";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js"; //route ziggy
const page = usePage();
const drawer = ref(false);
const { smAndDown } = useDisplay(); //responsividade -> small ou menor (xs, sm)
const links_customer = [
    {
        text: "Início",
        to: route('index'),
        action: '', //callback
    },
    {
        text: "Meus agendamentos",
        to: route('customer.appointmentsView'),
        action: '',
    },
    {
        text: "Sou empresário",
        to: "/url",
        action: '',
    },
    {
        text: "Cadastrar / Entrar",
        to: "#",
        action: openModalRegister
    },
];
const modal_type = ref('register'); //login
const dialog_register_login = ref(false);

const form_register = useForm({
    name: "",
    whatsapp: "",
    email: "",
    terms_of_use: false,
    password: "",
    password_confirmation: "",
});
const form_login = useForm({
    username: null,
    password: null,
    remember: false,
    toggle_visible_password: false,
});

const snackbar = reactive({
    show: false,
    message: "",
});
//COMPUTEDS
const logged_status = computed(() => {
    //logged - no_logged - partial_registered
    if (!page.props?.user) {
        return "no_logged";
    } else if (!page.props.user.whatsapp) {
        return "partial_registered";
    } else if (page.props.user.email) {
        return "logged";
    }
});
//PRIVADOS
function _register() {
    form_register.post(route("register.store"));
}

function _login() {
    form_login.post(route("login.store"));
}

function _google_auth(operation) {
    if (operation == "register" && !form_register.terms_of_use) {
        snackbar.message =
            "É necessário aceitar os termos de uso para continuar com Google.";
        snackbar.show = true;
        return;
    } else {
        window.location.href = route("customer.loginGoogle");
    }
}

function _sideLinkOrAction(link){
    if(link.to == '#'){
        link.action();
    }else{
        router.visit(link.to);
    }
}

//PÚBLICOS
function openModalRegister() {
    modal_type.value = 'register';
    dialog_register_login.value = true;
}

onMounted(() => {});

defineExpose({
    openModalRegister,
});
</script>
<template>
    <v-app>
        <v-layout>
            <v-app-bar>
                <template v-slot:prepend>
                    <v-app-bar-nav-icon
                        v-if="smAndDown"
                        @click.stop="drawer = !drawer"
                    ></v-app-bar-nav-icon>
                </template>
                <v-img
                    :src="images.avexe_name_logo"
                    max-width="100"
                    max-height="100"
                    class="mx-3"
                />

                <v-btn
                    :variant="route().current('index') ? 'flat' : 'text'"
                    :color="route().current('index') ? 'orange' : 'black'"
                    v-if="!smAndDown"
                    @click="router.visit(route('index'))"
                    :class="[
                        'cursor-pointer',
                        route().current('index') ? 'text-white' : '',
                    ]"
                    >Início
                </v-btn>
                <v-btn
                    v-if="!smAndDown"
                    :variant="
                        route().current('customer.appointmentsView')
                            ? 'flat'
                            : 'text'
                    "
                    :color="
                        route().current('customer.appointmentsView')
                            ? 'orange'
                            : 'black'
                    "
                    @click="router.visit(route('customer.appointmentsView'))"
                    :class="[
                        'cursor-pointer',
                        route().current('customer.appointmentsView')
                            ? 'text-white'
                            : '',
                    ]"
                >
                    Meus agendamentos
                </v-btn>
                <v-btn
                    v-if="!smAndDown"
                    @click="router.visit('/')"
                    variant="outlined"
                    color="primary"
                    class="cursor-pointer"
                >
                    <span class="font-weight-bold">Sou empresário</span>
                </v-btn>
                <v-spacer />
                <div v-if="logged_status == 'no_logged'" class="mr-2">
                    <v-btn
                        v-if="!smAndDown"
                        variant="flat"
                        color="primary"
                        class="cursor-pointer"
                        @click="dialog_register_login = true"
                    >
                        <span class="font-weight-bold">Cadastro / Login</span>
                    </v-btn>
                </div>
                <div
                    v-else-if="logged_status == 'partial_registered'"
                    class="mr-2"
                >
                    <v-btn
                        v-if="!smAndDown"
                        variant="flat"
                        color="primary"
                        class="cursor-pointer"
                        @click="router.visit(route('customer.profileView'))"
                    >
                        <span class="font-weight-bold"
                            >Completar cadastro</span
                        ></v-btn
                    >
                </div>
                <div v-else class="mr-2">
                    <v-menu offset-y>
                        <template v-slot:activator="{ props }">
                            <v-icon-btn
                                icon="mdi-account-badge "
                                color="primary"
                                v-bind="props"
                            ></v-icon-btn>
                        </template>

                        <v-list>

                            <v-list-item @click="router.visit(route('customer.profileView'))">
                                <v-list-item-title>PERFIL</v-list-item-title>
                            </v-list-item>
                            <v-list-item @click="router.post(route('logout'))">
                                <v-list-item-title>SAIR</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </v-app-bar>

            <v-navigation-drawer v-model="drawer" temporary>
                <v-list>
                    <v-list-item
                        v-for="link in links_customer"
                        :key="link.text"
                        :title="link.text"
                        @click="_sideLinkOrAction(link)"
                    ></v-list-item>
                </v-list>
            </v-navigation-drawer>

            <v-main>
                <v-container>
                    <v-alert
                        v-if="logged_status == 'partial_registered'"
                        type="warning"
                        variant="tonal"
                        class="mb-4"
                        icon="mdi-alert-circle-outline"
                        closable
                    >
                        <template #title> Cadastro incompleto </template>
                        Complete seu cadastro com whatsapp afim de receber
                        mensagens para melhor atendimento.
                        <v-btn
                            variant="flat"
                            color="warning"
                            size="small"
                            class="mt-2"
                            @click="router.visit(route('customer.profileView'))"
                        >
                            Completar cadastro
                        </v-btn>
                    </v-alert>
                    <slot :logged-status="logged_status"></slot>
                </v-container>
            </v-main>
        </v-layout>
        <!-- DIALOG REGISTRO - LOGIN -->

        <v-dialog
            v-model="dialog_register_login"
            width="auto"
            location="top center"
        >
            <v-card
                :title="modal_type == 'login' ? 'Login' : 'Cadastro cliente'"
                class="pa-3 position-relative dialog-auth-responsive"
            >
                <v-btn
                    icon="mdi-close"
                    color="red"
                    variant="text"
                    class="position-absolute"
                    style="top: 8px; right: 8px"
                    @click="dialog_register_login = false"
                ></v-btn>
                <div class="mb-2">
                    <v-btn-toggle
                        v-model="modal_type"
                        divided
                        variant="outlined"
                        color="primary"
                    >
                        <v-btn value="register">
                            <span>Cadastrar</span>

                            <v-icon end> mdi-account-plus </v-icon>
                        </v-btn>

                        <v-btn value="login">
                            <span>Entrar</span>

                            <v-icon end> mdi-login </v-icon>
                        </v-btn>
                    </v-btn-toggle>
                </div>
                <div v-if="modal_type == 'register'">
                    <v-form
                        class="d-flex flex-column ga-2"
                        @submit.prevent="_register"
                    >
                        <v-text-field
                            label="Nome *"
                            variant="outlined"
                            name="name"
                            v-model="form_register.name"
                            :error-messages="form_register.errors.name"
                            :hide-details="!form_register.errors.name"
                        ></v-text-field>
                        <v-text-field
                            label="E-mail"
                            variant="outlined"
                            name="email"
                            v-model="form_register.email"
                            :error-messages="form_register.errors.email"
                            :hide-details="!form_register.errors.email"
                        ></v-text-field>
                        <v-mask-input
                            mask="(##) # ####-####"
                            label="Whatsapp *"
                            variant="outlined"
                            name="whatsapp"
                            v-model="form_register.whatsapp"
                            :error-messages="form_register.errors.whatsapp"
                            :hide-details="!form_register.errors.whatsapp"
                        ></v-mask-input>
                        <v-text-field
                            label="Senha"
                            variant="outlined"
                            type="password"
                            name="password"
                            v-model="form_register.password"
                            :error-messages="form_register.errors.password"
                            :hide-details="!form_register.errors.password"
                        ></v-text-field>
                        <v-text-field
                            label="Confirmar senha"
                            variant="outlined"
                            type="password"
                            name="password_confirmation"
                            v-model="form_register.password_confirmation"
                            hide-details
                        ></v-text-field>

                        <div
                            class="w-full d-flex flex-row align-content-center"
                        >
                            <div class="align-self-end" style="flex-grow: 3">
                                <v-checkbox
                                    label="Aceito os termos de uso"
                                    color="primary"
                                    v-model="form_register.terms_of_use"
                                    :error-messages="
                                        form_register.errors.terms_of_use
                                    "
                                    :hide-details="
                                        !form_register.errors.terms_of_use
                                    "
                                ></v-checkbox>
                            </div>
                            <div
                                class="align-self-center position-relative"
                                style="flex-grow: 0; top: -10px"
                            >
                                <a href="#">Termos de uso</a>
                            </div>
                        </div>
                        <v-btn
                            variant="flat"
                            color="primary"
                            text="Salvar"
                            class="align-self-end"
                            append-icon="mdi-content-save "
                            type="submit"
                            :loading="form_register.processing"
                            :disabled="form_register.processing"
                        ></v-btn>
                        <v-divider class="my-2"></v-divider>
                        <v-btn
                            variant="flat"
                            color="#4285F4"
                            class="align-self-center text-white"
                            block
                            prepend-icon="mdi-google"
                            @click="_google_auth('register')"
                        >
                            Continuar com Google
                        </v-btn>
                    </v-form>
                </div>
                <div v-else>
                    <v-form
                        class="d-flex flex-column ga-2"
                        @submit.prevent="_login"
                    >
                        <v-text-field
                            label="E-mail / Whatsapp *"
                            variant="outlined"
                            type="text"
                            v-model="form_login.username"
                            :error-messages="form_login.errors.username"
                            persistent-hint
                            hint="Exemplo caso whatsapp: 86994567898"
                        ></v-text-field>
                        <v-text-field
                            label="Senha *"
                            variant="outlined"
                            v-model="form_login.password"
                            :type="form_login.toggle_visible_password ? 'text' : 'password'"
                            :append-inner-icon="form_login.toggle_visible_password ? 'mdi-eye-off' : 'mdi-eye'"
                            @click:append-inner="form_login.toggle_visible_password = !form_login.toggle_visible_password"
                            :error-messages="form_login.errors.password"
                            :hide-details="!form_login.errors.password"
                        ></v-text-field>
                        <div
                            class="w-full d-flex flex-row align-content-center"
                        >
                            <div class="align-self-end" style="flex-grow: 3">
                                <v-checkbox
                                    label="Lembrar de mim"
                                    color="primary"
                                    v-model="form_login.remember"
                                ></v-checkbox>
                            </div>
                            <div
                                class="align-self-center position-relative"
                                style="flex-grow: 0; top: -10px"
                            >
                                <a href="#">Esqueceu sua senha!?</a>
                            </div>
                        </div>
                        <v-btn
                            variant="flat"
                            color="primary"
                            class="align-self-end"
                            append-icon="mdi-content-save"
                            type="submit"
                            :loading="form_login.processing"
                            :disabled="form_login.processing"
                            >Salvar
                        </v-btn>
                        <v-divider class="my-2"></v-divider>
                        <v-btn
                            variant="flat"
                            color="#4285F4"
                            class="text-white"
                            block
                            prepend-icon="mdi-google"
                            @click="_google_auth('login')"
                        >
                            Continuar com Google
                        </v-btn>
                    </v-form>
                </div>
            </v-card>
        </v-dialog>
        <!-- FIM DIALOG REGISTRO - LOGIN -->

        <v-snackbar
            v-model="snackbar.show"
            color="error"
            location="top"
            :timeout="5000"
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
    </v-app>
</template>
<style scoped lang="scss">
.v-container {
    @media (max-width: 1144px) {
        max-width: 100%;
    }
}

.dialog-auth-responsive {
    width: 500px;

    @media (max-width: 532px) {
        width: 450px;
    }

    @media (max-width: 476px) {
        width: 380px;
    }

    @media (max-width: 406px) {
        width: 320px;
    }

    @media (max-width: 344px) {
        width: 290px;
    }
}
</style>
