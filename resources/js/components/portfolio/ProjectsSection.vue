<script setup lang="ts">
import ProjectCard from '@/components/portfolio/ProjectCard.vue';
import SectionTitle from '@/components/portfolio/SectionTitle.vue';
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';
import type { Project } from '@/types/portfolio';
import { useI18n } from 'vue-i18n';

defineProps<{ projects: Project[] }>();

const { t } = useI18n();
const { target, revealed } = useRevealOnScroll(0.05);
</script>

<template>
    <section id="projects" ref="target" class="py-24">
        <div class="reveal" :class="{ 'reveal-visible': revealed }">
            <SectionTitle :title="t('projects.title')" />
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="(project, index) in projects"
                :key="project.id"
                class="reveal"
                :class="{ 'reveal-visible': revealed }"
                :style="{ transitionDelay: `${index * 90}ms` }"
            >
                <ProjectCard :project="project" class="h-full" />
            </div>
        </div>
    </section>
</template>
