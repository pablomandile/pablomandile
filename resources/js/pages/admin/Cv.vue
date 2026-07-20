<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ExternalLink, FileText, Trash2, Upload } from 'lucide-vue-next';
import { ref } from 'vue';

interface Cv {
    url: string;
    name: string;
    uploaded_at: string | null;
}

defineProps<{ cv: Cv | null }>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Currículum', href: '/admin/cv' }];

const form = useForm<{ cv: File | null }>({ cv: null });
const fileName = ref<string | null>(null);

function onFileChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    form.cv = file;
    fileName.value = file?.name ?? null;
}

function submit(): void {
    form.post(route('admin.cv.update'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            fileName.value = null;
        },
    });
}

function remove(): void {
    if (confirm('¿Eliminar el CV actual? Dejará de mostrarse el botón de descarga en el sitio.')) {
        router.delete(route('admin.cv.destroy'), { preserveScroll: true });
    }
}
</script>

<template>
    <Head title="Currículum" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-8 p-4">
            <div>
                <h1 class="text-xl font-semibold">Currículum (CV)</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Subí tu CV en PDF. Aparece como botón de descarga en el sitio. Al subir uno nuevo se reemplaza el anterior.
                </p>
            </div>

            <!-- CV actual -->
            <section class="max-w-2xl space-y-3">
                <h2 class="text-sm font-semibold uppercase tracking-widest text-muted-foreground">CV actual</h2>

                <div v-if="cv" class="flex flex-wrap items-center gap-4 rounded-xl border border-border p-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-violet-500/10 text-violet-600 dark:text-violet-400">
                        <FileText class="h-6 w-6" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium">{{ cv.name }}</p>
                        <p v-if="cv.uploaded_at" class="text-xs text-muted-foreground">Subido el {{ cv.uploaded_at }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="outline" size="sm" as-child>
                            <a :href="cv.url" target="_blank" rel="noopener noreferrer">
                                <ExternalLink class="h-4 w-4" />
                                Ver
                            </a>
                        </Button>
                        <Button variant="ghost" size="icon" aria-label="Eliminar CV" @click="remove">
                            <Trash2 class="h-4 w-4 text-destructive" />
                        </Button>
                    </div>
                </div>

                <p v-else class="rounded-xl border border-dashed border-border p-4 text-sm text-muted-foreground">
                    Todavía no hay ningún CV cargado.
                </p>
            </section>

            <!-- Subir / reemplazar -->
            <section class="max-w-2xl space-y-4">
                <h2 class="text-sm font-semibold uppercase tracking-widest text-muted-foreground">
                    {{ cv ? 'Reemplazar CV' : 'Subir CV' }}
                </h2>

                <form class="space-y-4" @submit.prevent="submit">
                    <div class="space-y-2">
                        <Label for="cv">Archivo PDF</Label>
                        <Input id="cv" type="file" accept="application/pdf,.pdf" @change="onFileChange" />
                        <p class="text-xs text-muted-foreground">Solo PDF · máx. 5 MB.</p>
                        <p v-if="form.errors.cv" class="text-sm text-destructive">{{ form.errors.cv }}</p>
                    </div>

                    <div v-if="form.progress" class="h-1.5 w-full overflow-hidden rounded-full bg-muted">
                        <div class="h-full bg-violet-500 transition-all" :style="{ width: `${form.progress.percentage}%` }"></div>
                    </div>

                    <div class="flex items-center gap-3">
                        <Button type="submit" :disabled="!form.cv || form.processing">
                            <Upload class="h-4 w-4" />
                            {{ cv ? 'Reemplazar CV' : 'Subir CV' }}
                        </Button>
                        <p v-if="form.recentlySuccessful" class="text-sm text-emerald-600 dark:text-emerald-400">Guardado.</p>
                    </div>
                </form>
            </section>
        </div>
    </AppLayout>
</template>
