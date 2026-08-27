<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    width: {
        type: String,
        default: '800px'
    }
});
</script>
<template>
    <div class="container" v-bind="$attrs" :style="{ '--container-width': props.width }"">
        <div class="title">
            <span>{{ props.title }}</span> 
        </div>
        <slot></slot>
    </div>
</template>
<style scoped lang="scss">
.container {
    position: relative;
    width: var(--container-width);
    border: 1px solid black;
    border-radius: 10px;
    padding: 20px 15px;
    .title {
        position: absolute;
        top: -15px;
        left: 30px;
        display: inline-block;
        padding: 2px 7px;
        background-color: white;
        z-index: 100;
        span {
            font-size: 17px;
            font-weight: bold;
        }
    }
}
@media (max-width: 600px) {
    .container {
        width: 100%;
    }
}
</style>
