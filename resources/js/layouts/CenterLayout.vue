<template>
    <v-app>
        <v-main
            class="d-flex align-center justify-center px-4"
            style="min-height: 100vh"
        >
            <v-card
                class="pa-6 w-100"
                elevation="2"
                style="max-width: 480px"
            >
                <div class="d-flex justify-center mb-2">
                    <v-img
                        :src="images.avexe_name_logo"
                        max-width="150"
                        contain
                    />
                </div>
                <slot></slot>
            </v-card>
        </v-main>
    </v-app>
</template>
<script setup>
import { images } from "@/utils/files.js";
</script>
