<script setup lang="ts">
import GithubIcon from '@/components/portfolio/icons/GithubIcon.vue';
import LinkedinIcon from '@/components/portfolio/icons/LinkedinIcon.vue';
import { useLocalized } from '@/composables/useLocalized';
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';
import type { Profile } from '@/types/portfolio';
import { FileDown } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

defineProps<{ profile: Profile }>();

const { t } = useI18n();
const { l } = useLocalized();
const { target, revealed } = useRevealOnScroll();
</script>

<template>
    <section
        ref="target"
        class="reveal flex min-h-[100svh] items-center pb-16 pt-24"
        :class="{ 'reveal-visible': revealed }"
    >
        <div class="flex w-full flex-col-reverse items-center gap-12 lg:flex-row lg:justify-between lg:gap-16">
            <div class="w-full">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-400">{{ t('hero.greeting') }}</p>

                <h1 class="mt-4 text-5xl font-extrabold tracking-tight text-white sm:text-6xl lg:text-7xl">
                    {{ profile.name }}
                </h1>

                <h2 class="gradient-text mt-4 text-2xl font-bold sm:text-3xl">
                    {{ l(profile.headline) }}
                </h2>

                <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-400">
                    {{ l(profile.tagline) }}
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-4">
                    <a
                        href="#projects"
                        class="rounded-full bg-gradient-to-r from-violet-500 to-cyan-500 px-7 py-3 font-semibold text-white shadow-lg shadow-violet-500/25 transition hover:scale-[1.03] hover:shadow-violet-500/40"
                    >
                        {{ t('hero.cta_projects') }}
                    </a>
                    <a
                        href="#contact"
                        class="glass rounded-full px-7 py-3 font-semibold text-slate-200 transition hover:border-violet-400/50 hover:text-white"
                    >
                        {{ t('hero.cta_contact') }}
                    </a>
                </div>

                <div class="mt-10 flex items-center gap-4 text-xl text-slate-400">
                    <a
                        :href="profile.socials.github"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="GitHub"
                        class="glass rounded-full p-3 transition hover:border-cyan-400/50 hover:text-white"
                    >
                        <GithubIcon />
                    </a>
                    <a
                        :href="profile.socials.linkedin"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="LinkedIn"
                        class="glass rounded-full p-3 transition hover:border-cyan-400/50 hover:text-white"
                    >
                        <LinkedinIcon />
                    </a>
                    <a
                        v-if="profile.cv_url"
                        :href="profile.cv_url"
                        download="Pablo-Mandile-CV.pdf"
                        :aria-label="t('download_cv')"
                        :title="t('download_cv')"
                        class="glass rounded-full p-3 transition hover:border-cyan-400/50 hover:text-white"
                    >
                        <FileDown class="h-[1em] w-[1em]" />
                    </a>
                </div>
            </div>

            <div v-if="profile.photo_url" class="relative shrink-0">
                <div class="blob-float absolute -inset-6 rounded-full bg-gradient-to-tr from-violet-600/40 to-cyan-400/40 blur-2xl"></div>
                <div class="relative rounded-full bg-gradient-to-tr from-violet-500 to-cyan-400 p-1.5 shadow-2xl">
                    <img
                        :src="profile.photo_url"
                        :alt="profile.name"
                        class="h-56 w-56 rounded-full border-4 border-night object-cover sm:h-64 sm:w-64 lg:h-80 lg:w-80"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
