<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Project, Technology } from '@/types/portfolio';
import { Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    project?: Project | null;
    technologies: Technology[];
}>();

const isEdit = Boolean(props.project);

const form = useForm({
    ...(isEdit ? { _method: 'put' } : {}),
    title: props.project?.title ?? '',
    slug: props.project?.slug ?? '',
    description: {
        es: props.project?.description?.es ?? '',
        en: props.project?.description?.en ?? '',
    },
    demo_url: props.project?.demo_url ?? '',
    repo_url: props.project?.repo_url ?? '',
    preview_image: null as File | null,
    is_featured: props.project?.is_featured ?? false,
    is_published: props.project?.is_published ?? true,
    sort_order: props.project?.sort_order ?? 0,
    technologies: props.project?.technologies.map((technology) => technology.id) ?? [],
});

const slugTouched = ref(isEdit);
const previewUrl = ref<string | null>(props.project?.preview_image_url ?? null);

function slugify(value: string): string {
    return value
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

watch(
    () => form.title,
    (title) => {
        if (!slugTouched.value) {
            form.slug = slugify(title);
        }
    },
);

function onFileChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    form.preview_image = file;
    previewUrl.value = file ? URL.createObjectURL(file) : (props.project?.preview_image_url ?? null);
}

function submit(): void {
    const url = isEdit ? route('admin.projects.update', props.project!.id) : route('admin.projects.store');
    form.post(url, { forceFormData: true });
}

const inputLikeClass =
    'flex min-h-28 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2';
</script>

<template>
    <form class="max-w-3xl space-y-6" @submit.prevent="submit">
        <div class="grid gap-6 sm:grid-cols-2">
            <div class="space-y-2">
                <Label for="title">Título</Label>
                <Input id="title" v-model="form.title" required />
                <p v-if="form.errors.title" class="text-sm text-destructive">{{ form.errors.title }}</p>
            </div>

            <div class="space-y-2">
                <Label for="slug">Slug</Label>
                <Input id="slug" v-model="form.slug" @input="slugTouched = true" />
                <p v-if="form.errors.slug" class="text-sm text-destructive">{{ form.errors.slug }}</p>
            </div>
        </div>

        <div class="space-y-2">
            <Label for="description_es">Descripción (español)</Label>
            <textarea id="description_es" v-model="form.description.es" :class="inputLikeClass" required></textarea>
            <p v-if="form.errors['description.es']" class="text-sm text-destructive">{{ form.errors['description.es'] }}</p>
        </div>

        <div class="space-y-2">
            <Label for="description_en">Descripción (inglés)</Label>
            <textarea id="description_en" v-model="form.description.en" :class="inputLikeClass" required></textarea>
            <p v-if="form.errors['description.en']" class="text-sm text-destructive">{{ form.errors['description.en'] }}</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="space-y-2">
                <Label for="demo_url">URL de demo</Label>
                <Input id="demo_url" v-model="form.demo_url" type="url" placeholder="https://…" />
                <p v-if="form.errors.demo_url" class="text-sm text-destructive">{{ form.errors.demo_url }}</p>
            </div>

            <div class="space-y-2">
                <Label for="repo_url">URL de repositorio</Label>
                <Input id="repo_url" v-model="form.repo_url" type="url" placeholder="https://github.com/…" />
                <p v-if="form.errors.repo_url" class="text-sm text-destructive">{{ form.errors.repo_url }}</p>
            </div>
        </div>

        <div class="space-y-2">
            <Label for="preview_image">Imagen de vista previa</Label>
            <img v-if="previewUrl" :src="previewUrl" alt="Vista previa" class="aspect-video w-64 rounded-lg border border-border object-cover" />
            <Input id="preview_image" type="file" accept="image/*" @change="onFileChange" />
            <p class="text-xs text-muted-foreground">JPG, PNG o WebP · máx. 2 MB · ideal 800×500</p>
            <p v-if="form.errors.preview_image" class="text-sm text-destructive">{{ form.errors.preview_image }}</p>
        </div>

        <div class="space-y-2">
            <Label>Tecnologías</Label>
            <div class="flex flex-wrap gap-x-6 gap-y-2">
                <label
                    v-for="technology in technologies"
                    :key="technology.id"
                    class="flex cursor-pointer items-center gap-2 text-sm text-foreground"
                >
                    <input v-model="form.technologies" type="checkbox" :value="technology.id" class="h-4 w-4 rounded border-input accent-violet-500" />
                    {{ technology.name }}
                </label>
            </div>
            <p v-if="form.errors.technologies" class="text-sm text-destructive">{{ form.errors.technologies }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-8">
            <label class="flex cursor-pointer items-center gap-2 text-sm text-foreground">
                <input v-model="form.is_featured" type="checkbox" class="h-4 w-4 rounded border-input accent-violet-500" />
                Destacado
            </label>

            <label class="flex cursor-pointer items-center gap-2 text-sm text-foreground">
                <input v-model="form.is_published" type="checkbox" class="h-4 w-4 rounded border-input accent-violet-500" />
                Publicado
            </label>

            <div class="flex items-center gap-2">
                <Label for="sort_order">Orden</Label>
                <Input id="sort_order" v-model="form.sort_order" type="number" min="0" class="w-24" />
            </div>
        </div>

        <div class="flex gap-3 border-t border-border pt-6">
            <Button type="submit" :disabled="form.processing">
                {{ isEdit ? 'Guardar cambios' : 'Crear proyecto' }}
            </Button>
            <Button variant="outline" as-child>
                <Link :href="route('admin.projects.index')">Cancelar</Link>
            </Button>
        </div>
    </form>
</template>
