<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import { images } from "@js/utils/files.js";
import { usePage } from "@inertiajs/vue3";
import Profile from "@/Auth/Profile.vue";

const page = usePage();
</script>

<template>
    <AppLayout>
        <Profile :user="$page.props.user"></Profile>
    </AppLayout>
</template>
<style scoped lang="scss">

</style>