<script setup lang="ts">
import { setLocale, type Locale } from '@/i18n';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();
const options: Locale[] = ['es', 'en'];
</script>

<template>
    <div class="glass flex rounded-full p-0.5 text-xs font-semibold uppercase">
        <button
            v-for="option in options"
            :key="option"
            type="button"
            class="rounded-full px-2.5 py-1 transition"
            :class="locale === option ? 'bg-gradient-to-r from-violet-500 to-cyan-500 text-white' : 'text-slate-400 hover:text-white'"
            :aria-pressed="locale === option"
            @click="setLocale(option)"
        >
            {{ option }}
        </button>
    </div>
</template>
