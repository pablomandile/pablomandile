<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Project } from '@/types/portfolio';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';

defineProps<{ projects: Project[] }>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Proyectos', href: '/admin/projects' }];

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
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Proyectos</h1>
                <Button as-child>
                    <Link :href="route('admin.projects.create')">
                        <Plus class="h-4 w-4" />
                        Nuevo proyecto
                    </Link>
                </Button>
            </div>

            <p v-if="!projects.length" class="text-muted-foreground">Todavía no hay proyectos cargados.</p>

            <div v-else class="overflow-x-auto rounded-xl border border-border">
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
                            <td class="max-w-48 p-3 text-muted-foreground">
                                {{ project.technologies.map((technology) => technology.name).join(', ') }}
                            </td>
                            <td class="p-3">
                                <span
                                    class="rounded-full px-2 py-0.5 text-xs"
                                    :class="project.is_published ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400' : 'bg-muted text-muted-foreground'"
                                >
                                    {{ project.is_published ? 'Publicado' : 'Borrador' }}
                                </span>
                                <span
                                    v-if="project.is_featured"
                                    class="ml-1 rounded-full bg-violet-500/15 px-2 py-0.5 text-xs text-violet-600 dark:text-violet-400"
                                >
                                    Destacado
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
        </div>
    </AppLayout>
</template>
