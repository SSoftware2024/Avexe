<script setup>
import { reactive, ref } from "vue";
import { useDisplay } from "vuetify";
import { images } from "../../js/utils/files.js";
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js"; //route ziggy
const drawer = ref(false);
const { smAndDown } = useDisplay(); //responsividade -> small ou menor (xs, sm)

const links_customer = [
    {
        text: "Início",
        to: "/url",
    },
    {
        text: "Meus agendamentos",
        to: "/url",
    },
    {
        text: "Sou empresário",
        to: "/url",
    },
];
const logged_status = ref("no_logged"); //logged - no_logged - partial_registered

const dialog = reactive({
    register: false,
    login: false,
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
                    :variant="route().current('index') ? 'flat':'text'"
                    :color="route().current('index') ? 'orange':'black'"
                    v-if="!smAndDown"
                    @click="router.visit(route('index'))"
                    :class="['cursor-pointer', route().current('index') ? 'text-white' : '']"
                    >Início
                </v-btn>
                <v-btn
                    v-if="!smAndDown"
                    :variant="route().current('appoint') ? 'flat':'text'"
                    :color="route().current('appoint') ? 'orange':'black'"
                    @click="router.visit(route('appoint'))"
                    :class="['cursor-pointer', route().current('appoint') ? 'text-white' : '']"
                    >Meus agendamentos</v-btn
                >
                <v-btn
                    v-if="!smAndDown"
                    @click="router.visit('/')"
                    variant="outlined"
                    color="primary"
                    class="cursor-pointer"
                >
                    <span class="font-weight-bold">Sou empresário</span></v-btn
                >
                <v-spacer />
                <div v-if="logged_status == 'no_logged'" class="mr-2">
                    <v-btn
                        v-if="!smAndDown"
                        variant="flat"
                        color="primary"
                        class="cursor-pointer mr-1"
                        @click="dialog.login = true"
                    >
                        <span class="font-weight-bold">Entrar</span></v-btn
                    >
                    <v-btn
                        v-if="!smAndDown"
                        variant="flat"
                        color="primary"
                        class="cursor-pointer"
                        @click="dialog.register = true"
                    >
                        <span class="font-weight-bold">Registrar</span></v-btn
                    >
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
                        @click="dialog.register = true"
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
                            <v-list-item
                                v-for="(i, index) in 4"
                                :key="index"
                                :value="index"
                            >
                                <v-list-item-title
                                    >teste opções</v-list-item-title
                                >
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
                        @click="router.visit(link.to)"
                    ></v-list-item>
                </v-list>
            </v-navigation-drawer>

            <v-main>
                <v-container>
                    <slot></slot>
                </v-container>
            </v-main>
        </v-layout>
        <!-- DIALOG REGISTRO -->

        <v-dialog v-model="dialog.register" width="auto" location="top center">
            <v-card
                title="Registrar-se"
                class="pa-3 position-relative dialog-auth-responsive"
            >
                <v-btn
                    icon="mdi-close"
                    color="red"
                    variant="text"
                    class="position-absolute"
                    style="top: 8px; right: 8px"
                    @click="dialog.register = false"
                ></v-btn>
                <div>
                    <v-form class="d-flex flex-column ga-2">
                        <v-text-field
                            label="Nome *"
                            variant="outlined"
                            hide-details
                        ></v-text-field>
                        <v-text-field
                            label="E-mail"
                            variant="outlined"
                            hide-details
                        ></v-text-field>
                        <v-text-field
                            label="Senha *"
                            variant="outlined"
                            type="password"
                            hide-details
                        ></v-text-field>
                        <v-text-field
                            label="Whatsapp *"
                            variant="outlined"
                            hide-details
                        ></v-text-field>
                        <div
                            class="w-full d-flex flex-row align-content-center"
                        >
                            <div class="align-self-end" style="flex-grow: 3">
                                <v-checkbox
                                    label="Aceito os termos de uso"
                                    color="primary"
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
                            @click="dialog.register = false"
                        ></v-btn>
                    </v-form>
                </div>
            </v-card>
        </v-dialog>
        <!-- FIM DIALOG REGISTRO -->
        <!-- DIALOG LOGIN -->
        <v-dialog v-model="dialog.login" width="auto" location="top center">
            <v-card
                title="Entrar"
                class="pa-3 position-relative dialog-auth-responsive"
            >
                <v-btn
                    icon="mdi-close"
                    color="red"
                    variant="text"
                    class="position-absolute"
                    style="top: 8px; right: 8px"
                    @click="dialog.login = false"
                ></v-btn>
                <div>
                    <v-form class="d-flex flex-column ga-2">
                        <v-text-field
                            label="E-mail *"
                            variant="outlined"
                            type="email"
                            hide-details
                        ></v-text-field>
                        <v-text-field
                            label="Senha *"
                            variant="outlined"
                            type="password"
                            hide-details
                        ></v-text-field>
                        <div
                            class="w-full d-flex flex-row align-content-center"
                        >
                            <div class="align-self-end" style="flex-grow: 3">
                                <v-checkbox
                                    label="Lembrar de mim"
                                    color="primary"
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
                            text="Salvar"
                            class="align-self-end"
                            append-icon="mdi-content-save"
                            @click="dialog.login = false"
                        ></v-btn>
                    </v-form>
                </div>
            </v-card>
        </v-dialog>
        <!-- FIM DIALOG LOGIN -->
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
