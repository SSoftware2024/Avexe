<script setup>
import { ref } from "vue";
import { useDisplay } from "vuetify";
import { images } from "../../js/utils/files.js";

const drawer = ref(false);
const { smAndDown } = useDisplay(); //responsividade -> small ou menor (xs, sm)

const links_customer = [
    {
        text: 'Início',
        to: '/url'
    },
    {
        text: 'Meus agendamentos',
        to: '/url'
    },
    {
        text: 'Sou empresário',
        to: '/url'
    },
]
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
               

                <v-btn v-if="!smAndDown" to="/" text class="cursor-pointer">Início</v-btn>
                <v-btn v-if="!smAndDown" to="/" text class="cursor-pointer">Meus agendamentos</v-btn>
                <v-btn v-if="!smAndDown" to="/" variant="flat" color="primary" class="cursor-pointer"> <span class="font-weight-bold">Sou empresário</span></v-btn>
                 <v-spacer />
                 <v-btn v-if="!smAndDown" to="/" variant="flat" color="primary" class="cursor-pointer mr-1"> <span class="font-weight-bold">Entrar</span></v-btn>
                 <v-btn v-if="!smAndDown" to="/" variant="flat" color="primary" class="cursor-pointer"> <span class="font-weight-bold">Registrar</span></v-btn>
            </v-app-bar>

            <v-navigation-drawer v-model="drawer" temporary>
                <v-list>
                    <v-list-item
                        v-for="link in links_customer"
                        :key="link.text"
                        :title="link.text"
                        :to="link.to"
                    ></v-list-item>
                </v-list>
            </v-navigation-drawer>

            <v-main>
                <v-container>
                    <slot></slot>
                </v-container>
            </v-main>
        </v-layout>
    </v-app>
</template>
<style scoped lang="scss">
.v-container {
    @media (max-width: 1144px) {
        max-width: 100%;
    }
}
</style>
