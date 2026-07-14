<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Localized } from '@/types/portfolio';
import { Head, useForm } from '@inertiajs/vue3';
import { Plus, Trash2 } from 'lucide-vue-next';

interface ExperienceItem {
    id?: number;
    role: Localized;
    company: string;
    period: Localized;
}

const props = defineProps<{
    about: Localized;
    experiences: ExperienceItem[];
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Sobre mí', href: '/admin/about' }];

const form = useForm({
    about: { es: props.about?.es ?? '', en: props.about?.en ?? '' },
    experiences: props.experiences.map((experience) => ({
        role: { es: experience.role.es, en: experience.role.en },
        company: experience.company,
        period: { es: experience.period.es, en: experience.period.en },
    })),
});

function addExperience(): void {
    form.experiences.push({
        role: { es: '', en: '' },
        company: '',
        period: { es: '', en: '' },
    });
}

function removeExperience(index: number): void {
    form.experiences.splice(index, 1);
}

function submit(): void {
    form.put(route('admin.about.update'), { preserveScroll: true });
}

const textareaClass =
    'flex min-h-40 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2';
</script>

<template>
    <Head title="Sobre mí" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <form class="flex flex-1 flex-col gap-8 p-4" @submit.prevent="submit">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Sobre mí</h1>
                <div class="flex items-center gap-3">
                    <p v-if="form.recentlySuccessful" class="text-sm text-emerald-600 dark:text-emerald-400">Guardado.</p>
                    <Button type="submit" :disabled="form.processing">Guardar cambios</Button>
                </div>
            </div>

            <!-- Texto Sobre mí -->
            <section class="max-w-3xl space-y-6">
                <div>
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-muted-foreground">Texto de presentación</h2>
                    <p class="mt-1 text-xs text-muted-foreground">Separá cada párrafo con una línea en blanco.</p>
                </div>

                <div class="space-y-2">
                    <Label for="about_es">Español</Label>
                    <textarea id="about_es" v-model="form.about.es" :class="textareaClass"></textarea>
                    <p v-if="form.errors['about.es']" class="text-sm text-destructive">{{ form.errors['about.es'] }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="about_en">Inglés</Label>
                    <textarea id="about_en" v-model="form.about.en" :class="textareaClass"></textarea>
                    <p v-if="form.errors['about.en']" class="text-sm text-destructive">{{ form.errors['about.en'] }}</p>
                </div>
            </section>

            <!-- Experiencia -->
            <section class="max-w-3xl space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-muted-foreground">Experiencia</h2>
                    <Button type="button" variant="outline" size="sm" @click="addExperience">
                        <Plus class="h-4 w-4" />
                        Agregar
                    </Button>
                </div>

                <p v-if="!form.experiences.length" class="text-sm text-muted-foreground">Sin experiencias cargadas.</p>

                <div
                    v-for="(experience, index) in form.experiences"
                    :key="index"
                    class="space-y-4 rounded-xl border border-border p-4"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">#{{ index + 1 }}</span>
                        <Button type="button" variant="ghost" size="icon" aria-label="Quitar" @click="removeExperience(index)">
                            <Trash2 class="h-4 w-4 text-destructive" />
                        </Button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label>Puesto (ES)</Label>
                            <Input v-model="experience.role.es" placeholder="Programador Full-Stack" />
                        </div>
                        <div class="space-y-2">
                            <Label>Puesto (EN)</Label>
                            <Input v-model="experience.role.en" placeholder="Full-Stack Developer" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label>Empresa / Organización</Label>
                        <Input v-model="experience.company" placeholder="Ministerio de Educación" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label>Período (ES)</Label>
                            <Input v-model="experience.period.es" placeholder="Feb 2022 — Presente" />
                        </div>
                        <div class="space-y-2">
                            <Label>Período (EN)</Label>
                            <Input v-model="experience.period.en" placeholder="Feb 2022 — Present" />
                        </div>
                    </div>
                </div>

                <p v-if="form.errors.experiences" class="text-sm text-destructive">{{ form.errors.experiences }}</p>
            </section>

            <div class="max-w-3xl border-t border-border pt-6">
                <Button type="submit" :disabled="form.processing">Guardar cambios</Button>
            </div>
        </form>
    </AppLayout>
</template>
