<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Project } from '@/types/portfolio';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

defineProps<{ projects: Project[] }>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Proyectos', href: '/admin/projects' }];

interface Badge {
    label: string;
    class: string;
}

// Mismos estados en la tabla de escritorio y en las tarjetas de móvil.
function badges(project: Project): Badge[] {
    const status: Badge = project.is_published
        ? { label: 'Publicado', class: 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400' }
        : { label: 'Borrador', class: 'bg-muted text-muted-foreground' };

    return project.is_featured
        ? [status, { label: 'Destacado', class: 'bg-violet-500/15 text-violet-600 dark:text-violet-400' }]
        : [status];
}

function technologyNames(project: Project): string {
    return project.technologies.map((technology) => technology.name).join(', ');
}

function destroy(project: Project): void {
    if (confirm(`¿Eliminar el proyecto "${project.title}"?`)) {
        router.delete(route('admin.projects.destroy', project.id));
    }
}
</script>

<template>
    <Head title="Proyectos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h1 class="text-xl font-semibold">Proyectos</h1>
                <Button as-child class="w-full sm:w-auto">
                    <Link :href="route('admin.projects.create')">
                        <Plus class="h-4 w-4" />
                        Nuevo proyecto
                    </Link>
                </Button>
            </div>

            <p v-if="!projects.length" class="text-muted-foreground">Todavía no hay proyectos cargados.</p>

            <template v-else>
                <!-- Móvil: una tarjeta por proyecto, sin scroll horizontal -->
                <ul class="grid gap-3 md:hidden">
                    <li v-for="project in projects" :key="project.id" class="overflow-hidden rounded-xl border border-border">
                        <img
                            v-if="project.cover_url"
                            :src="project.cover_url"
                            :alt="project.title"
                            class="aspect-video w-full object-cover"
                        />

                        <div class="space-y-3 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <p class="font-medium leading-tight">{{ project.title }}</p>
                                <span class="shrink-0 text-xs text-muted-foreground">Orden {{ project.sort_order }}</span>
                            </div>

                            <div class="flex flex-wrap gap-1">
                                <span
                                    v-for="badge in badges(project)"
                                    :key="badge.label"
                                    class="rounded-full px-2 py-0.5 text-xs"
                                    :class="badge.class"
                                >
                                    {{ badge.label }}
                                </span>
                            </div>

                            <p v-if="project.technologies.length" class="text-sm text-muted-foreground">
                                {{ technologyNames(project) }}
                            </p>

                            <div class="flex gap-2 border-t border-border pt-3">
                                <Button variant="outline" class="h-10 flex-1" as-child>
                                    <Link :href="route('admin.projects.edit', project.id)">
                                        <Pencil class="h-4 w-4" />
                                        Editar
                                    </Link>
                                </Button>
                                <Button variant="outline" class="h-10 flex-1 text-destructive" @click="destroy(project)">
                                    <Trash2 class="h-4 w-4" />
                                    Eliminar
                                </Button>
                            </div>
                        </div>
                    </li>
                </ul>

                <!-- Escritorio: tabla -->
                <div class="hidden overflow-x-auto rounded-xl border border-border md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-border bg-muted/50 text-left text-muted-foreground">
                                <th class="p-3 font-medium">Vista previa</th>
                                <th class="p-3 font-medium">Título</th>
                                <th class="p-3 font-medium">Tecnologías</th>
                                <th class="p-3 font-medium">Estado</th>
                                <th class="p-3 font-medium">Orden</th>
                                <th class="p-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="project in projects" :key="project.id">
                                <td class="p-3">
                                    <img
                                        v-if="project.cover_url"
                                        :src="project.cover_url"
                                        :alt="project.title"
                                        class="aspect-video w-24 rounded-md object-cover"
                                    />
                                </td>
                                <td class="p-3 font-medium">{{ project.title }}</td>
                                <td class="max-w-48 p-3 text-muted-foreground">{{ technologyNames(project) }}</td>
                                <td class="p-3">
                                    <span
                                        v-for="badge in badges(project)"
                                        :key="badge.label"
                                        class="mr-1 rounded-full px-2 py-0.5 text-xs"
                                        :class="badge.class"
                                    >
                                        {{ badge.label }}
                                    </span>
                                </td>
                                <td class="p-3 text-muted-foreground">{{ project.sort_order }}</td>
                                <td class="p-3">
                                    <div class="flex gap-1">
                                        <Button variant="ghost" size="icon" as-child>
                                            <Link :href="route('admin.projects.edit', project.id)" aria-label="Editar">
                                                <Pencil class="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="icon" aria-label="Eliminar" @click="destroy(project)">
                                            <Trash2 class="h-4 w-4 text-destructive" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
    </AppLayout>
</template>
