<script setup lang="ts">
import ProjectForm from '@/components/admin/ProjectForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Technology } from '@/types/portfolio';
import { Head } from '@inertiajs/vue3';

defineProps<{ technologies: Technology[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Proyectos', href: '/admin/projects' },
    { title: 'Nuevo proyecto', href: '/admin/projects/create' },
];
</script>

<template>
    <Head title="Nuevo proyecto" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4">
            <h1 class="text-xl font-semibold">Nuevo proyecto</h1>
            <ProjectForm :technologies="technologies" />
        </div>
    </AppLayout>
</template>
