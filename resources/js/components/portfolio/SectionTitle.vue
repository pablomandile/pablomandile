<script setup lang="ts">
defineProps<{ title: string; centered?: boolean }>();
</script>

<template>
    <div class="mb-12" :class="{ 'text-center': centered }">
        <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ title }}</h2>
        <div class="mt-3 h-1 w-16 rounded-full bg-gradient-to-r from-violet-500 to-cyan-500" :class="{ 'mx-auto': centered }"></div>
    </div>
</template>
