<script setup lang="ts">
import GithubIcon from '@/components/portfolio/icons/GithubIcon.vue';
import { useLocalized } from '@/composables/useLocalized';
import type { Project } from '@/types/portfolio';
import { ExternalLink } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

defineProps<{ project: Project }>();

const { t } = useI18n();
const { l } = useLocalized();
</script>

<template>
    <article
        class="glass group relative flex flex-col overflow-hidden rounded-2xl transition duration-300 hover:-translate-y-1 hover:border-violet-400/40 hover:shadow-[0_0_35px_-8px_rgba(139,92,246,0.55)]"
    >
        <div class="aspect-video overflow-hidden bg-night-soft">
            <img
                v-if="project.preview_image_url"
                :src="project.preview_image_url"
                :alt="project.title"
                loading="lazy"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
            />
            <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-violet-900/40 to-cyan-900/40">
                <span class="gradient-text text-3xl font-bold">{{ project.title.charAt(0) }}</span>
            </div>
        </div>

        <span
            v-if="project.is_featured"
            class="absolute right-3 top-3 rounded-full bg-gradient-to-r from-violet-500 to-cyan-500 px-3 py-1 text-xs font-semibold text-white shadow-lg"
        >
            {{ t('projects.featured') }}
        </span>

        <div class="flex flex-1 flex-col p-5">
            <h3 class="text-lg font-semibold text-white">{{ project.title }}</h3>

            <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-400">{{ l(project.description) }}</p>

            <div class="mt-4 flex flex-wrap gap-2">
                <span
                    v-for="technology in project.technologies"
                    :key="technology.id"
                    class="rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-xs text-cyan-300/90"
                >
                    {{ technology.name }}
                </span>
            </div>

            <div v-if="project.demo_url || project.repo_url" class="mt-5 flex gap-5 text-sm font-medium text-slate-300">
                <a
                    v-if="project.demo_url"
                    :href="project.demo_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 transition hover:text-white"
                >
                    <ExternalLink class="h-4 w-4" />
                    {{ t('projects.demo') }}
                </a>
                <a
                    v-if="project.repo_url"
                    :href="project.repo_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 transition hover:text-white"
                >
                    <GithubIcon />
                    {{ t('projects.code') }}
                </a>
            </div>
        </div>
    </article>
</template>
