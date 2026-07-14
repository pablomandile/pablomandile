<script setup lang="ts">
import GithubIcon from '@/components/portfolio/icons/GithubIcon.vue';
import LinkedinIcon from '@/components/portfolio/icons/LinkedinIcon.vue';
import { useLocalized } from '@/composables/useLocalized';
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';
import type { Profile } from '@/types/portfolio';
import { MapPin } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

defineProps<{ profile: Profile }>();

const { t } = useI18n();
const { l } = useLocalized();
const { target, revealed } = useRevealOnScroll();
</script>

<template>
    <section id="contact" ref="target" class="reveal py-24 text-center" :class="{ 'reveal-visible': revealed }">
        <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ t('contact.title') }}</h2>
        <div class="mx-auto mt-3 h-1 w-16 rounded-full bg-gradient-to-r from-violet-500 to-cyan-500"></div>

        <p class="mx-auto mt-8 max-w-md text-lg leading-relaxed text-slate-400">{{ t('contact.blurb') }}</p>

        <a
            :href="`mailto:${profile.email}`"
            class="mt-10 inline-block rounded-full bg-gradient-to-r from-violet-500 to-cyan-500 px-8 py-4 font-semibold text-white shadow-lg shadow-violet-500/25 transition hover:scale-[1.03] hover:shadow-violet-500/40"
        >
            {{ t('contact.cta') }}
        </a>

        <div class="mt-10 flex items-center justify-center gap-4 text-xl text-slate-400">
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
        </div>

        <p class="mt-8 inline-flex items-center gap-1.5 text-sm text-slate-500">
            <MapPin class="h-4 w-4" />
            {{ l(profile.location) }}
        </p>
    </section>
</template>
