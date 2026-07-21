<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import type { Certificate } from '@/types/portfolio';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Trash2, Upload } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{ certificates: Certificate[] }>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Certificados', href: '/admin/certificates' }];

const form = useForm({
    title: '',
    image: null as File | null,
    sort_order: 0,
});

const previewUrl = ref<string | null>(null);
const fileKey = ref(0);

function onFileChange(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0] ?? null;
    form.image = file;
    previewUrl.value = file ? URL.createObjectURL(file) : null;
}

function submit(): void {
    form.post(route('admin.certificates.store'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            previewUrl.value = null;
            fileKey.value++; // remonta el input file para limpiarlo
        },
    });
}

function togglePublish(certificate: Certificate): void {
    router.patch(
        route('admin.certificates.update', certificate.id),
        { is_published: !certificate.is_published },
        { preserveScroll: true },
    );
}

function destroy(certificate: Certificate): void {
    if (confirm(`¿Eliminar el certificado "${certificate.title}"?`)) {
        router.delete(route('admin.certificates.destroy', certificate.id), { preserveScroll: true });
    }
}
</script>

<template>
    <Head title="Certificados" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-8 p-4">
            <h1 class="text-xl font-semibold">Certificados</h1>

            <!-- Alta de un nuevo certificado -->
            <form class="max-w-3xl space-y-4 rounded-xl border border-border p-4" @submit.prevent="submit">
                <h2 class="text-sm font-semibold uppercase tracking-widest text-muted-foreground">Subir certificado</h2>

                <div class="grid gap-4 sm:grid-cols-[1fr_auto]">
                    <div class="space-y-2">
                        <Label for="title">Título</Label>
                        <Input id="title" v-model="form.title" placeholder="Nombre del curso / certificado" required />
                        <p v-if="form.errors.title" class="text-sm text-destructive">{{ form.errors.title }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="sort_order">Orden</Label>
                        <Input id="sort_order" v-model="form.sort_order" type="number" min="0" class="w-24" />
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="image">Imagen del certificado</Label>
                    <img v-if="previewUrl" :src="previewUrl" alt="Vista previa" class="aspect-[4/3] w-64 rounded-lg border border-border object-cover" />
                    <Input :key="fileKey" id="image" type="file" accept="image/*" required @change="onFileChange" />
                    <p class="text-xs text-muted-foreground">JPG, PNG o WebP · máx. 4 MB.</p>
                    <p v-if="form.errors.image" class="text-sm text-destructive">{{ form.errors.image }}</p>
                </div>

                <Button type="submit" :disabled="form.processing">
                    <Upload class="h-4 w-4" />
                    Subir certificado
                </Button>
            </form>

            <!-- Listado -->
            <section class="space-y-4">
                <h2 class="text-sm font-semibold uppercase tracking-widest text-muted-foreground">Cargados</h2>

                <p v-if="!certificates.length" class="text-sm text-muted-foreground">Todavía no hay certificados cargados.</p>

                <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="certificate in certificates" :key="certificate.id" class="overflow-hidden rounded-xl border border-border">
                        <img :src="certificate.image_url ?? ''" :alt="certificate.title" class="aspect-[4/3] w-full object-cover" />
                        <div class="space-y-3 p-3">
                            <p class="font-medium">{{ certificate.title }}</p>
                            <div class="flex items-center justify-between">
                                <label class="flex cursor-pointer items-center gap-2 text-sm text-foreground">
                                    <input
                                        type="checkbox"
                                        :checked="certificate.is_published"
                                        class="h-4 w-4 rounded border-input accent-violet-500"
                                        @change="togglePublish(certificate)"
                                    />
                                    Publicado
                                </label>
                                <Button variant="ghost" size="icon" aria-label="Eliminar" @click="destroy(certificate)">
                                    <Trash2 class="h-4 w-4 text-destructive" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
