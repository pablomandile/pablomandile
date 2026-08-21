<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { techIconUrl } from '@/lib/techIcons';
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
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h1 class="text-xl font-semibold">Tecnologías</h1>
                <Button as-child class="w-full sm:w-auto">
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

                    <!-- Móvil: una tarjeta por tecnología, sin scroll horizontal -->
                    <ul class="grid gap-3 md:hidden">
                        <li v-for="technology in items" :key="technology.id" class="space-y-3 rounded-xl border border-border p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-slate-900">
                                        <img
                                            v-if="techIconUrl(technology)"
                                            :src="techIconUrl(technology)!"
                                            alt=""
                                            class="h-6 w-6 object-contain"
                                        />
                                        <span v-else class="text-xs text-slate-500">—</span>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-medium leading-tight">{{ technology.name }}</p>
                                        <p v-if="technology.icon" class="mt-0.5 truncate text-xs text-muted-foreground">
                                            {{ technology.icon }}
                                        </p>
                                    </div>
                                </div>
                                <span class="shrink-0 text-xs text-muted-foreground">Orden {{ technology.sort_order }}</span>
                            </div>

                            <div class="flex gap-2 border-t border-border pt-3">
                                <Button variant="outline" class="h-10 flex-1" as-child>
                                    <Link :href="route('admin.technologies.edit', technology.id)">
                                        <Pencil class="h-4 w-4" />
                                        Editar
                                    </Link>
                                </Button>
                                <Button variant="outline" class="h-10 flex-1 text-destructive" @click="destroy(technology)">
                                    <Trash2 class="h-4 w-4" />
                                    Eliminar
                                </Button>
                            </div>
                        </li>
                    </ul>

                    <!-- Escritorio: tabla -->
                    <div class="hidden overflow-x-auto rounded-xl border border-border md:block">
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-border">
                                <tr v-for="technology in items" :key="technology.id">
                                    <td class="w-16 p-3 text-muted-foreground">{{ technology.sort_order }}</td>
                                    <td class="w-14 p-3">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-md bg-slate-900">
                                            <img
                                                v-if="techIconUrl(technology)"
                                                :src="techIconUrl(technology)!"
                                                alt=""
                                                class="h-6 w-6 object-contain"
                                            />
                                            <span v-else class="text-xs text-slate-500">—</span>
                                        </span>
                                    </td>
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
