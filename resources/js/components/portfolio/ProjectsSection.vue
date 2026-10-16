<script setup lang="ts">
import ProjectCard from '@/components/portfolio/ProjectCard.vue';
import SectionTitle from '@/components/portfolio/SectionTitle.vue';
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';
import type { Project } from '@/types/portfolio';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

defineProps<{ projects: Project[] }>();

const { t } = useI18n();
const { target, revealed } = useRevealOnScroll(0.05);

// `auto-rows-fr` iguala todas las filas al alto de la más alta, así una ficha
// con título corto o pocas pastillas no queda más baja que las demás. Se
// desactiva mientras alguien tenga una descripción desplegada: si no, esa ficha
// estiraría a todas las otras y quedarían llenas de espacio vacío.
const expandedCards = ref(0);

function onExpandedChange(expanded: boolean): void {
    expandedCards.value = Math.max(0, expandedCards.value + (expanded ? 1 : -1));
}
</script>

<template>
    <section id="projects" ref="target" class="py-24">
        <div class="reveal" :class="{ 'reveal-visible': revealed }">
            <SectionTitle :title="`${t('projects.title')} (${projects.length})`" />
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" :class="{ 'auto-rows-fr': expandedCards === 0 }">
            <div
                v-for="(project, index) in projects"
                :key="project.id"
                class="reveal h-full"
                :class="{ 'reveal-visible': revealed }"
                :style="{ transitionDelay: `${index * 90}ms` }"
            >
                <ProjectCard :project="project" class="h-full" @expanded-change="onExpandedChange" />
            </div>
        </div>
    </section>
</template>
