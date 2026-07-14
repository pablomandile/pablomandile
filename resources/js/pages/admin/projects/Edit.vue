<script setup lang="ts">
import ProjectForm from '@/components/admin/ProjectForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Project, Technology } from '@/types/portfolio';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{ project: Project; technologies: Technology[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Proyectos', href: '/admin/projects' },
    { title: props.project.title, href: `/admin/projects/${props.project.id}/edit` },
];
</script>

<template>
    <Head :title="`Editar — ${project.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4">
            <h1 class="text-xl font-semibold">Editar proyecto</h1>
            <ProjectForm :project="project" :technologies="technologies" />
        </div>
    </AppLayout>
</template>
