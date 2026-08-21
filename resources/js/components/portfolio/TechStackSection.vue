<script setup lang="ts">
import SectionTitle from '@/components/portfolio/SectionTitle.vue';
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';
import { techIconUrl } from '@/lib/techIcons';
import type { TechCategoryKey, Technology } from '@/types/portfolio';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{ technologies: Partial<Record<TechCategoryKey, Technology[]>> }>();

const { t } = useI18n();
const { target, revealed } = useRevealOnScroll();

const categories: TechCategoryKey[] = ['backend', 'frontend', 'database', 'tools'];

// Categorías con contenido, ya resuelto el logo de cada tecnología.
const groups = computed(() =>
    categories
        .map((category) => ({
            category,
            items: (props.technologies[category] ?? []).map((technology) => ({
                ...technology,
                iconUrl: techIconUrl(technology),
            })),
        }))
        .filter((group) => group.items.length > 0),
);
</script>

<template>
    <section id="tech" ref="target" class="reveal py-24" :class="{ 'reveal-visible': revealed }">
        <SectionTitle :title="t('tech.title')" />

        <div class="space-y-10">
            <div v-for="group in groups" :key="group.category">
                <h3 class="text-sm font-semibold uppercase tracking-widest text-slate-500">
                    {{ t(`tech.categories.${group.category}`) }}
                </h3>

                <!-- Un tile por tecnología: el logo siempre en una caja del mismo
                     tamaño, así conviven logos anchos y altos sin desalinearse. -->
                <ul class="mt-4 grid grid-cols-3 gap-3 sm:grid-cols-4 lg:grid-cols-6">
                    <li
                        v-for="technology in group.items"
                        :key="technology.id"
                        class="glass group/tech flex flex-col items-center gap-2.5 rounded-xl px-3 py-4 text-center transition hover:border-violet-400/50 hover:shadow-[0_0_20px_-4px_rgba(139,92,246,0.6)]"
                    >
                        <img
                            v-if="technology.iconUrl"
                            :src="technology.iconUrl"
                            :alt="technology.name"
                            loading="lazy"
                            class="h-11 w-11 shrink-0 object-contain transition duration-300 group-hover/tech:scale-110"
                        />

                        <!-- Sin logo: la inicial, en una caja del mismo tamaño. -->
                        <span
                            v-else
                            class="gradient-text flex h-11 w-11 shrink-0 items-center justify-center text-2xl font-bold"
                            aria-hidden="true"
                        >
                            {{ technology.name.charAt(0) }}
                        </span>

                        <span class="text-xs leading-tight text-slate-400 transition group-hover/tech:text-white">
                            {{ technology.name }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>
