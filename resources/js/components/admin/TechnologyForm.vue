<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { availableTechIcons, techIconUrlBySlug } from '@/lib/techIcons';
import type { Technology } from '@/types/portfolio';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface CategoryOption {
    value: string;
    label: string;
}

const props = defineProps<{
    technology?: Technology | null;
    categories: CategoryOption[];
}>();

const isEdit = Boolean(props.technology);

const form = useForm({
    name: props.technology?.name ?? '',
    category: props.technology?.category ?? props.categories[0]?.value ?? '',
    icon: props.technology?.icon ?? '',
    sort_order: props.technology?.sort_order ?? 0,
});

// Los logos disponibles son los SVG que están en el repo; el campo acepta
// tanto el slug (`laravel-original`) como la clase de Devicon (`devicon-…`).
const iconOptions = availableTechIcons.map((slug) => ({ slug, url: techIconUrlBySlug(slug)! }));
const selectedIcon = computed(() => form.icon.trim().toLowerCase().replace(/^devicon-/, ''));
const iconPreview = computed(() => techIconUrlBySlug(form.icon));

function submit(): void {
    if (isEdit) {
        form.put(route('admin.technologies.update', props.technology!.id));
    } else {
        form.post(route('admin.technologies.store'));
    }
}
</script>

<template>
    <form class="max-w-xl space-y-6" @submit.prevent="submit">
        <div class="space-y-2">
            <Label for="name">Nombre</Label>
            <Input id="name" v-model="form.name" required placeholder="Ej. Laravel" />
            <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
        </div>

        <div class="space-y-2">
            <Label for="category">Categoría</Label>
            <select
                id="category"
                v-model="form.category"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
            >
                <option v-for="option in categories" :key="option.value" :value="option.value">{{ option.label }}</option>
            </select>
            <p v-if="form.errors.category" class="text-sm text-destructive">{{ form.errors.category }}</p>
        </div>

        <div class="space-y-2">
            <Label for="icon">Logo (opcional)</Label>

            <div class="flex items-center gap-3">
                <!-- Chip oscuro: es el fondo que tienen los logos en el sitio. -->
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-slate-900">
                    <img v-if="iconPreview" :src="iconPreview" alt="" class="h-8 w-8 object-contain" />
                    <span v-else class="text-xs text-slate-500">—</span>
                </span>
                <Input id="icon" v-model="form.icon" placeholder="Ej. laravel-original" />
            </div>

            <p class="text-xs text-muted-foreground">
                Elegí uno de los logos disponibles. Si queda vacío, en el sitio se muestra la inicial del nombre.
            </p>

            <div class="flex flex-wrap gap-2 rounded-lg border border-border p-3">
                <button
                    type="button"
                    class="flex h-[62px] w-16 flex-col items-center justify-center gap-1 rounded-lg border text-[10px] transition"
                    :class="selectedIcon === '' ? 'border-violet-500 bg-violet-500/10' : 'border-border hover:border-violet-400'"
                    title="Sin logo"
                    @click="form.icon = ''"
                >
                    <span class="text-lg leading-none text-muted-foreground">—</span>
                    <span class="text-muted-foreground">sin logo</span>
                </button>

                <button
                    v-for="option in iconOptions"
                    :key="option.slug"
                    type="button"
                    class="flex h-[62px] w-16 flex-col items-center justify-center gap-1 rounded-lg border transition"
                    :class="selectedIcon === option.slug ? 'border-violet-500 bg-violet-500/10' : 'border-border hover:border-violet-400'"
                    :title="option.slug"
                    @click="form.icon = option.slug"
                >
                    <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-900">
                        <img :src="option.url" alt="" class="h-6 w-6 object-contain" />
                    </span>
                    <span class="w-full truncate px-1 text-center text-[10px] text-muted-foreground">{{ option.slug.split('-')[0] }}</span>
                </button>
            </div>

            <p v-if="form.errors.icon" class="text-sm text-destructive">{{ form.errors.icon }}</p>
        </div>

        <div class="space-y-2">
            <Label for="sort_order">Orden</Label>
            <Input id="sort_order" v-model="form.sort_order" type="number" min="0" class="w-28" />
            <p v-if="form.errors.sort_order" class="text-sm text-destructive">{{ form.errors.sort_order }}</p>
        </div>

        <div class="flex flex-col gap-3 border-t border-border pt-6 sm:flex-row">
            <Button type="submit" :disabled="form.processing" class="w-full sm:w-auto">
                {{ isEdit ? 'Guardar cambios' : 'Crear tecnología' }}
            </Button>
            <Button variant="outline" as-child class="w-full sm:w-auto">
                <Link :href="route('admin.technologies.index')">Cancelar</Link>
            </Button>
        </div>
    </form>
</template>
