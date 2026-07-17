<template>
    <h1 class="bg-red-400">Olá inertia</h1>
    <Link :href="route('index2')"class="text-blue-400">To index2</Link>
    <v-btn prepend-icon="$vuetify" color="purple-darken-2"> Button </v-btn>
</template>
<script setup>
import { route } from 'ziggy-js'; //route ziggy
</script>