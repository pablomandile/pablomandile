<script setup lang="ts">
import GithubIcon from '@/components/portfolio/icons/GithubIcon.vue';
import ImageCarousel from '@/components/portfolio/ImageCarousel.vue';
import { Dialog, DialogContent, DialogDescription, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { useLocalized } from '@/composables/useLocalized';
import type { Project } from '@/types/portfolio';
import { ExternalLink, Images, Maximize2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{ project: Project }>();

const { t } = useI18n();
const { l } = useLocalized();

const images = computed<string[]>(() => props.project.image_urls ?? []);
const hasImages = computed<boolean>(() => images.value.length > 0);

// Descripción: se muestra recortada a un máximo de caracteres con un enlace
// "más" para desplegar el texto completo (y "menos" para volver a plegarlo).
const DESCRIPTION_LIMIT = 331;
const expanded = ref(false);
const fullDescription = computed<string>(() => l(props.project.description));
const isLong = computed<boolean>(() => fullDescription.value.length > DESCRIPTION_LIMIT);
const shownDescription = computed<string>(() =>
    isLong.value && !expanded.value ? fullDescription.value.slice(0, DESCRIPTION_LIMIT).trimEnd() : fullDescription.value,
);
</script>

<template>
    <article
        class="glass group relative flex flex-col overflow-hidden rounded-2xl transition duration-300 hover:-translate-y-1 hover:border-violet-400/40 hover:shadow-[0_0_35px_-8px_rgba(139,92,246,0.55)]"
    >
        <!-- Portada: al hacer click abre el carrusel en un dialog más grande. -->
        <Dialog v-if="hasImages">
            <DialogTrigger as-child>
                <button
                    type="button"
                    class="relative block aspect-video w-full overflow-hidden bg-night-soft"
                    :aria-label="t('projects.expand')"
                >
                    <img
                        :src="project.cover_url!"
                        :alt="project.title"
                        loading="lazy"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    />

                    <!-- Scrim + ícono de ampliar al pasar el mouse. -->
                    <span
                        class="absolute inset-0 flex items-center justify-center bg-black/45 opacity-0 transition duration-300 group-hover:opacity-100"
                    >
                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-full border border-white/20 bg-black/50 text-white shadow-lg backdrop-blur-md"
                        >
                            <Maximize2 class="h-6 w-6 drop-shadow" />
                        </span>
                    </span>

                    <!-- Contador de imágenes cuando hay más de una. -->
                    <span
                        v-if="images.length > 1"
                        class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full border border-white/20 bg-black/50 px-2.5 py-1 text-xs font-medium text-white shadow-lg backdrop-blur-md"
                    >
                        <Images class="h-3.5 w-3.5" />
                        {{ images.length }}
                    </span>
                </button>
            </DialogTrigger>

            <DialogContent class="max-w-4xl gap-4 border-white/10 bg-night p-4 text-white sm:p-6">
                <DialogTitle class="pr-8 text-lg font-semibold text-white">{{ project.title }}</DialogTitle>
                <DialogDescription class="sr-only">{{ l(project.description) }}</DialogDescription>
                <ImageCarousel :images="images" :alt="project.title" />
            </DialogContent>
        </Dialog>

        <!-- Sin imágenes: inicial del título como placeholder. -->
        <div v-else class="aspect-video overflow-hidden bg-night-soft">
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-violet-900/40 to-cyan-900/40">
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

            <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-400">
                {{ shownDescription }}<template v-if="isLong">{{ expanded ? ' ' : '… ' }}<button
                        type="button"
                        class="cursor-pointer font-medium text-cyan-400 transition hover:text-cyan-300"
                        :aria-expanded="expanded"
                        @click="expanded = !expanded"
                    >{{ expanded ? t('projects.less') : t('projects.more') }}</button></template>
            </p>

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
