<template>
    <h1>Olá página 2</h1>
    <Link :href="route('index')"class="text-blue-400">To index</Link>
</template>
<script setup>
import { route } from 'ziggy-js'; //route ziggy
</script>