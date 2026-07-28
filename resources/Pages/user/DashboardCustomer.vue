<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { images } from "@js/utils/files.js";
import { usePage } from "@inertiajs/vue3";


</script>

<template>
    <AppLayout>
        <div>logado como {{ $page.props.user?.name }}</div>
    </AppLayout>
</template>
<style scoped lang="scss">

</style>