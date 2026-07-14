<script setup lang="ts">
import SectionTitle from '@/components/portfolio/SectionTitle.vue';
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';
import type { TechCategoryKey, Technology } from '@/types/portfolio';
import { useI18n } from 'vue-i18n';

defineProps<{ technologies: Partial<Record<TechCategoryKey, Technology[]>> }>();

const { t } = useI18n();
const { target, revealed } = useRevealOnScroll();

const categories: TechCategoryKey[] = ['backend', 'frontend', 'database', 'tools'];
</script>

<template>
    <section id="tech" ref="target" class="reveal py-24" :class="{ 'reveal-visible': revealed }">
        <SectionTitle :title="t('tech.title')" />

        <div class="space-y-10">
            <div v-for="category in categories" :key="category">
                <template v-if="technologies[category]?.length">
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-slate-500">
                        {{ t(`tech.categories.${category}`) }}
                    </h3>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <span
                            v-for="technology in technologies[category]"
                            :key="technology.id"
                            class="glass rounded-full px-4 py-2 text-sm text-slate-300 transition hover:border-violet-400/50 hover:text-white hover:shadow-[0_0_20px_-4px_rgba(139,92,246,0.6)]"
                        >
                            {{ technology.name }}
                        </span>
                    </div>
                </template>
            </div>
        </div>
    </section>
</template>
