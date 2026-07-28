<script setup>
import AppLayout from "./layouts/AppLayout.vue";
import { images } from "../js/utils/files.js";
import { ref } from "vue";
// import { route } from 'ziggy-js'; //route ziggy

const toggle = ref("left");
</script>

<template>
    <AppLayout>
        <!-- FILTRO DE BUSCA -->
        <v-row>
            <v-col cols="12">
                <div class="w-full">
                    <v-form
                        class="d-flex flex-column flex-md-row ga-4 align-md-center"
                    >
                        <v-text-field
                            class="flex-grow-1"
                            label="Pesquise pelo nome"
                            variant="outlined"
                            prepend-inner-icon="mdi-magnify"
                            clearable
                            hide-details
                        ></v-text-field>
                        <v-btn
                            variant="flat"
                            text="Buscar"
                            append-icon="mdi-magnify"
                            color="primary"
                            size="x-large"
                            class="flex-shrink-0"
                        >
                        </v-btn>
                    </v-form>
                </div>
                <div class="w-full mt-2 d-flex flex-row justify-space-between">
                    <div>
                        <v-btn-toggle
                            v-model="toggle"
                            divided
                            variant="outlined"
                            color="primary"
                        >
                            <v-btn value="left">
                                <span>Nome</span>

                                <v-icon end> mdi-domain </v-icon>
                            </v-btn>

                            <v-btn value="center">
                                <span>Próximas</span>

                                <v-icon end> mdi-map-marker </v-icon>
                            </v-btn>
                        </v-btn-toggle>
                    </div>
                    <div v-if="true">
                        <v-pagination :length="4" :total-visible="7" variant="flat"></v-pagination>
                    </div>
                </div>
            </v-col>
        </v-row>
        <!-- FIM FILTRO DE BUSCA -->
        <!-- LISTA DE EMPRESAS -->
        <div class="scrollable-list">
            <v-row>
                <v-col cols="12" md="6" lg="4" v-for="i in 12">
                    <v-card class="mx-auto pa-4" hover>
                        <div class="content">
                            <div class="image_content">
                                <v-img
                                    :src="images.logo_no_background"
                                    rounded="100%"
                                    width="70"
                                    height="70"
                                ></v-img>
                            </div>
                            <div class="text">
                                <h4>Avexe salão</h4>
                                <p>
                                    Jijoca de Jericoacoara - CE , 190 -
                                    62595-000
                                </p>
                            </div>
                        </div>
                        <div class="d-flex justify-end ma-0 pa-0">
                            <v-chip
                                prepend-icon="mdi-map-marker"
                                class="ma-2"
                                color="red"
                            >
                                32,8km
                            </v-chip>
                            <v-btn
                                variant="flat"
                                text="Acessar"
                                append-icon="mdi-arrow-right-box"
                                color="#388E3C"
                                class="text-white align-self-center"
                            >
                                <template v-slot:append>
                                    <v-icon color="white"></v-icon>
                                </template>
                            </v-btn>
                        </div>

                        <div class="float-rate">
                            <v-chip
                                append-icon="mdi-star"
                                class="ma-2"
                                color="orange"
                            >
                                5,0
                            </v-chip>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </div>
        <!-- FIM LISTA DE EMPRESAS -->
    </AppLayout>
</template>
<style scoped lang="scss">
.content {
    display: flex;
    // align-items: center;
}
.image {
    background-color: black;
}
.text {
    margin: 0 15px;
    min-width: 0;
    h4 {
        margin: 0;
    }
    p {
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
}
.float-rate {
    position: absolute;
    top: 2px;
    right: 5px;
}
.scrollable-list {
    overflow-y: auto;
    max-height: calc(100vh - 200px);
    padding: 10px 3px;
}
</style>
