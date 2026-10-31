<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { images } from "@/utils/files.js";
import { ref } from "vue";
// import { route } from 'ziggy-js'; //route ziggy

const toggle = ref("left");

// Dados de exemplo — troque pelos dados reais vindos do backend
const empresas = ref(
    Array.from({ length: 12 }, (_, i) => ({
        id: i + 1,
        nome: "Avexe salão",
        endereco: "Jijoca de Jericoacoara - CE",
        distancia: "32,8 km",
        nota: "5,0",
    }))
);
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
                <div
                    class="w-full mt-2 d-flex flex-column flex-md-row justify-md-space-between"
                >
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
                        <v-pagination
                            :length="4"
                            :total-visible="7"
                            variant="flat"
                        ></v-pagination>
                    </div>
                </div>
            </v-col>
        </v-row>
        <!-- FIM FILTRO DE BUSCA -->

        <!-- LISTA DE EMPRESAS -->
        <div class="scrollable-list">
            <v-row>
                <v-col
                    v-for="empresa in empresas"
                    :key="empresa.id"
                    cols="12"
                    md="6"
                    lg="4"
                >
                    <v-card class="company-card" rounded="xl" flat>
                        <div class="company-card__top">
                            <v-avatar
                                size="60"
                                rounded="lg"
                                class="company-card__logo"
                            >
                                <v-img
                                    :src="images.logo_no_background"
                                    cover
                                ></v-img>
                            </v-avatar>

                            <div class="company-card__info">
                                <h4 class="company-card__name">
                                    {{ empresa.nome }}
                                </h4>

                                <div class="company-card__meta">
                                    <span class="company-card__rating">
                                        <v-icon size="15" color="amber-darken-1">
                                            mdi-star
                                        </v-icon>
                                        {{ empresa.nota }}
                                    </span>
                                    <span class="company-card__dot"></span>
                                    <span>{{ empresa.distancia }}</span>
                                </div>

                                <p class="company-card__address">
                                    {{ empresa.endereco }}
                                </p>
                            </div>
                        </div>

                        <v-btn
                            block
                            variant="tonal"
                            color="primary"
                            rounded="lg"
                            class="text-none mt-4"
                            append-icon="mdi-arrow-right"
                        >
                            Acessar
                        </v-btn>
                    </v-card>
                </v-col>
            </v-row>
        </div>
        <!-- FIM LISTA DE EMPRESAS -->
    </AppLayout>
</template>

<style scoped lang="scss">
.company-card {
    padding: 18px;
    background: rgb(var(--v-theme-surface));
    border: 1px solid rgba(var(--v-border-color), 0.1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.2s ease, border-color 0.2s ease;

    &:hover {
        border-color: rgba(var(--v-theme-primary), 0.35);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    &__top {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    &__logo {
        flex-shrink: 0;
        background: rgba(var(--v-theme-on-surface), 0.05);
    }

    &__info {
        flex: 1;
        min-width: 0;
    }

    &__name {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 600;
        line-height: 1.3;
        color: rgb(var(--v-theme-on-surface));
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 2px;
        font-size: 0.85rem;
        color: rgba(var(--v-theme-on-surface), 0.7);
    }

    &__rating {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-weight: 600;
        color: rgb(var(--v-theme-on-surface));
    }

    &__dot {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: rgba(var(--v-theme-on-surface), 0.35);
    }

    &__address {
        margin: 2px 0 0;
        font-size: 0.8rem;
        color: rgba(var(--v-theme-on-surface), 0.55);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
}

.scrollable-list {
    overflow-y: auto;
    max-height: calc(100vh - 200px);
    padding: 10px 3px;
}
</style>