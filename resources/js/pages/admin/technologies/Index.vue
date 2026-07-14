<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Technology } from '@/types/portfolio';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{ technologies: Technology[] }>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Tecnologías', href: '/admin/technologies' }];

const categoryLabels: Record<string, string> = {
    backend: 'Back-end',
    frontend: 'Front-end',
    database: 'Bases de datos',
    tools: 'Herramientas',
};

const grouped = computed(() => {
    const groups: Record<string, Technology[]> = {};
    for (const technology of props.technologies) {
        (groups[technology.category] ??= []).push(technology);
    }
    return groups;
});

function destroy(technology: Technology): void {
    if (confirm(`¿Eliminar "${technology.name}"?`)) {
        router.delete(route('admin.technologies.destroy', technology.id));
    }
}
</script>

<template>
    <Head title="Tecnologías" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Tecnologías</h1>
                <Button as-child>
                    <Link :href="route('admin.technologies.create')">
                        <Plus class="h-4 w-4" />
                        Nueva tecnología
                    </Link>
                </Button>
            </div>

            <p v-if="!technologies.length" class="text-muted-foreground">Todavía no hay tecnologías cargadas.</p>

            <div v-else class="space-y-8">
                <div v-for="(items, category) in grouped" :key="category">
                    <h2 class="mb-2 text-sm font-semibold uppercase tracking-widest text-muted-foreground">
                        {{ categoryLabels[category] ?? category }}
                    </h2>
                    <div class="overflow-x-auto rounded-xl border border-border">
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-border">
                                <tr v-for="technology in items" :key="technology.id">
                                    <td class="w-16 p-3 text-muted-foreground">{{ technology.sort_order }}</td>
                                    <td class="p-3 font-medium">{{ technology.name }}</td>
                                    <td class="p-3 text-muted-foreground">{{ technology.icon }}</td>
                                    <td class="p-3 text-right">
                                        <div class="flex justify-end gap-1">
                                            <Button variant="ghost" size="icon" as-child>
                                                <Link :href="route('admin.technologies.edit', technology.id)" aria-label="Editar">
                                                    <Pencil class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="icon" aria-label="Eliminar" @click="destroy(technology)">
                                                <Trash2 class="h-4 w-4 text-destructive" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
