<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Technology } from '@/types/portfolio';
import { Link, useForm } from '@inertiajs/vue3';

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
            <Label for="icon">Ícono (opcional)</Label>
            <Input id="icon" v-model="form.icon" placeholder="Ej. devicon-laravel-original" />
            <p class="text-xs text-muted-foreground">Clase de <a href="https://devicon.dev" target="_blank" rel="noopener" class="underline">Devicon</a>, opcional.</p>
            <p v-if="form.errors.icon" class="text-sm text-destructive">{{ form.errors.icon }}</p>
        </div>

        <div class="space-y-2">
            <Label for="sort_order">Orden</Label>
            <Input id="sort_order" v-model="form.sort_order" type="number" min="0" class="w-28" />
            <p v-if="form.errors.sort_order" class="text-sm text-destructive">{{ form.errors.sort_order }}</p>
        </div>

        <div class="flex gap-3 border-t border-border pt-6">
            <Button type="submit" :disabled="form.processing">
                {{ isEdit ? 'Guardar cambios' : 'Crear tecnología' }}
            </Button>
            <Button variant="outline" as-child>
                <Link :href="route('admin.technologies.index')">Cancelar</Link>
            </Button>
        </div>
    </form>
</template>
