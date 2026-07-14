<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
});
</script>

<template>
    <img src="/img/logo.png" alt="Panel de Admin" class="rounded-md" v-bind="$attrs" />
</template>
