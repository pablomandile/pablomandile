<script setup lang="ts">
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';

// Separador visual entre secciones: el logo centrado con dos líneas en
// degradado que se desvanecen hacia los costados y un glow neón detrás.
const { target, revealed } = useRevealOnScroll(0.3);
</script>

<template>
    <div
        ref="target"
        class="reveal flex items-center justify-center gap-5 py-10 sm:gap-8"
        :class="{ 'reveal-visible': revealed }"
        aria-hidden="true"
    >
        <span class="h-px max-w-[8rem] flex-1 bg-gradient-to-r from-transparent to-violet-500/40"></span>

        <div class="relative shrink-0">
            <div class="absolute inset-0 -z-10 rounded-full bg-gradient-to-r from-violet-500/30 to-cyan-500/30 blur-xl"></div>
            <img src="/img/logo.png" alt="" class="h-14 w-14 rounded-2xl ring-1 ring-white/10" />
        </div>

        <span class="h-px max-w-[8rem] flex-1 bg-gradient-to-l from-transparent to-cyan-500/40"></span>
    </div>
</template>
