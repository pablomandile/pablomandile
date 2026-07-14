<script setup lang="ts">
import SectionTitle from '@/components/portfolio/SectionTitle.vue';
import { useLocalized } from '@/composables/useLocalized';
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';
import type { Profile } from '@/types/portfolio';
import { useI18n } from 'vue-i18n';

defineProps<{ profile: Profile }>();

const { t } = useI18n();
const { l } = useLocalized();
const { target, revealed } = useRevealOnScroll();
</script>

<template>
    <section id="about" ref="target" class="reveal py-24" :class="{ 'reveal-visible': revealed }">
        <SectionTitle :title="t('about.title')" />

        <div class="grid gap-12 md:grid-cols-[3fr_2fr]">
            <div class="space-y-5 leading-relaxed text-slate-400">
                <p v-for="(paragraph, index) in l(profile.about)" :key="index">{{ paragraph }}</p>
            </div>

            <aside class="glass h-fit rounded-2xl p-6">
                <h3 class="text-sm font-semibold uppercase tracking-widest text-slate-500">{{ t('about.experience') }}</h3>
                <ul class="mt-5 space-y-6">
                    <li v-for="job in profile.experience" :key="job.company" class="border-l-2 border-violet-500/40 pl-4">
                        <p class="font-medium text-white">{{ l(job.role) }}</p>
                        <p class="mt-0.5 text-sm text-slate-400">{{ job.company }}</p>
                        <p class="mt-1 text-xs uppercase tracking-wide text-cyan-400/80">{{ l(job.period) }}</p>
                    </li>
                </ul>
            </aside>
        </div>
    </section>
</template>
