<script setup lang="ts">
import TechnologyForm from '@/components/admin/TechnologyForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Technology } from '@/types/portfolio';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{ technology: Technology; categories: { value: string; label: string }[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Tecnologías', href: '/admin/technologies' },
    { title: props.technology.name, href: `/admin/technologies/${props.technology.id}/edit` },
];
</script>

<template>
    <Head :title="`Editar — ${technology.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4">
            <h1 class="text-xl font-semibold">Editar tecnología</h1>
            <TechnologyForm :technology="technology" :categories="categories" />
        </div>
    </AppLayout>
</template>
