<script setup lang="ts">
import TechnologyForm from '@/components/admin/TechnologyForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps<{ categories: { value: string; label: string }[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Tecnologías', href: '/admin/technologies' },
    { title: 'Nueva', href: '/admin/technologies/create' },
];
</script>

<template>
    <Head title="Nueva tecnología" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4">
            <h1 class="text-xl font-semibold">Nueva tecnología</h1>
            <TechnologyForm :categories="categories" />
        </div>
    </AppLayout>
</template>
