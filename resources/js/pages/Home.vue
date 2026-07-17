<script setup lang="ts">
import AboutSection from '@/components/portfolio/AboutSection.vue';
import ContactSection from '@/components/portfolio/ContactSection.vue';
import GradientBlobs from '@/components/portfolio/GradientBlobs.vue';
import HeroSection from '@/components/portfolio/HeroSection.vue';
import LogoDivider from '@/components/portfolio/LogoDivider.vue';
import ProjectsSection from '@/components/portfolio/ProjectsSection.vue';
import SiteNav from '@/components/portfolio/SiteNav.vue';
import TechStackSection from '@/components/portfolio/TechStackSection.vue';
import type { Profile, Project, TechCategoryKey, Technology } from '@/types/portfolio';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { useI18n } from 'vue-i18n';

defineProps<{
    profile: Profile;
    technologies: Partial<Record<TechCategoryKey, Technology[]>>;
    projects: Project[];
}>();

const { t } = useI18n();
const year = new Date().getFullYear();

// La portada siempre se muestra en oscuro (independientemente de la preferencia
// del sistema o del panel admin). Forzamos el color-scheme para que el navegador
// no la trate como página "clara" y le aplique su modo oscuro automático encima,
// que apaga el neón (gradientes, glow y texto con degradado).
onMounted(() => {
    document.documentElement.style.colorScheme = 'dark';
});
</script>

<template>
    <Head />

    <div class="relative min-h-screen overflow-x-clip bg-night text-slate-300 selection:bg-violet-500/40">
        <GradientBlobs />

        <SiteNav :name="profile.name" />

        <main class="relative z-10 mx-auto max-w-6xl px-6">
            <HeroSection :profile="profile" />
            <AboutSection :profile="profile" />
            <TechStackSection :technologies="technologies" />
            <ProjectsSection :projects="projects" />
            <LogoDivider />
            <ContactSection :profile="profile" />
        </main>

        <footer class="relative z-10 border-t border-white/5 py-8 text-center text-sm text-slate-500">
            © {{ year }} {{ profile.name }} · {{ t('footer.built') }}
        </footer>
    </div>
</template>
