<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { images } from "@js/utils/files.js";
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref } from "vue";

const appLayout = ref(null);
</script>

<template>
    <AppLayout ref="appLayout">
        <template #default="{ loggedStatus }">
            <v-container class="mt-4">
                <!-- NÃO LOGADO -->
                <div v-if="loggedStatus == 'no_logged'" class="text-center">
                    <v-card class="pa-8 mx-auto" max-width="500" elevation="4">
                        <v-icon
                            icon="mdi-calendar-lock"
                            size="64"
                            color="primary"
                            class="mb-4"
                        ></v-icon>
                        <h2 class="text-h5 font-weight-bold mb-2">
                            Acesso Restrito
                        </h2>
                        <p class="text-body-1 text-medium-emphasis mb-6">
                            Faça login ou cadastre-se para visualizar seus
                            agendamentos.
                        </p>
                        <v-row class="justify-center ga-2">
                            <v-btn
                                variant="flat"
                                color="primary"
                                size="large"
                                @click="router.visit(route('login'))"
                            >
                                Entrar
                            </v-btn>
                            <v-btn
                                variant="outlined"
                                color="primary"
                                size="large"
                                @click="appLayout.openModalRegister()"
                            >
                                Cadastrar
                            </v-btn>
                        </v-row>
                    </v-card>
                </div>

                <!-- LOGADO (PARCIAL OU TOTAL) -->
                <div v-else>
                    <!-- AVISO CADASTRO PARCIAL -->
                    <v-alert
                        v-if="loggedStatus == 'partial_registered'"
                        type="warning"
                        variant="tonal"
                        class="mb-4"
                        icon="mdi-alert-circle-outline"
                        closable
                    >
                        <template #title> Cadastro incompleto </template>
                        Complete seu cadastro com e-mail e senha em até
                        <strong>24 horas</strong>, caso contrário sua conta será
                        excluída.
                        <v-btn
                            variant="flat"
                            color="warning"
                            size="small"
                            class="mt-2"
                            @click="router.visit(route('register'))"
                        >
                            Completar cadastro
                        </v-btn>
                    </v-alert>

                    <!-- CONTEÚDO AGENDAMENTOS -->
                    <v-card class="pa-6" elevation="2">
                        <div class="d-flex align-center mb-4">
                            <v-icon
                                icon="mdi-calendar-text"
                                size="28"
                                color="primary"
                                class="mr-3"
                            ></v-icon>
                            <h2 class="text-h5 font-weight-bold">
                                Meus Agendamentos
                            </h2>
                        </div>
                        <v-divider class="mb-4"></v-divider>

                        <v-row>
                            <v-col cols="12" class="text-center pa-8">
                                <v-icon
                                    icon="mdi-calendar-blank"
                                    size="48"
                                    color="grey-lighten-1"
                                    class="mb-3"
                                ></v-icon>
                                <p class="text-body-1 text-medium-emphasis">
                                    Você ainda não tem agendamentos.
                                </p>
                                <v-btn
                                    variant="flat"
                                    color="primary"
                                    class="mt-3"
                                    prepend-icon="mdi-plus"
                                >
                                    Novo agendamento
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card>
                </div>
            </v-container>
        </template>
    </AppLayout>
</template>
<style scoped lang="scss"></style>
