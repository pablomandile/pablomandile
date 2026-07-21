<script setup lang="ts">
import ImageCarousel from '@/components/portfolio/ImageCarousel.vue';
import SectionTitle from '@/components/portfolio/SectionTitle.vue';
import { Dialog, DialogContent, DialogDescription, DialogTitle } from '@/components/ui/dialog';
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';
import type { Certificate } from '@/types/portfolio';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{ certificates: Certificate[] }>();

const { t } = useI18n();
const { target, revealed } = useRevealOnScroll(0.05);

const images = computed<string[]>(() => props.certificates.map((c) => c.image_url).filter((u): u is string => !!u));

// Carrusel en línea.
const current = ref(0);
// Dialog ampliado.
const open = ref(false);
const dialogIndex = ref(0);

function enlarge(index: number): void {
    dialogIndex.value = index;
    open.value = true;
}
</script>

<template>
    <section id="certificates" ref="target" class="reveal py-24" :class="{ 'reveal-visible': revealed }">
        <SectionTitle :title="t('certificates.title')" />

        <p v-if="!certificates.length" class="text-slate-400">{{ t('certificates.empty') }}</p>

        <div v-else class="mx-auto mt-4 max-w-3xl">
            <ImageCarousel
                :images="images"
                :alt="certificates[current]?.title ?? ''"
                height-class="max-h-[60vh]"
                enlargeable
                :keyboard="false"
                @update:index="current = $event"
                @image-click="enlarge"
            />
            <p class="mt-5 text-center text-lg font-medium text-white">{{ certificates[current]?.title }}</p>
            <p class="mt-1 text-center text-xs text-slate-500">{{ t('certificates.tap_hint') }}</p>
        </div>

        <!-- Ampliado -->
        <Dialog v-model:open="open">
            <DialogContent class="max-w-4xl gap-4 border-white/10 bg-night p-4 text-white sm:p-6">
                <DialogTitle class="pr-8 text-lg font-semibold text-white">{{ certificates[dialogIndex]?.title }}</DialogTitle>
                <DialogDescription class="sr-only">{{ certificates[dialogIndex]?.title }}</DialogDescription>
                <ImageCarousel
                    :images="images"
                    :start-index="dialogIndex"
                    :alt="certificates[dialogIndex]?.title ?? ''"
                    @update:index="dialogIndex = $event"
                />
            </DialogContent>
        </Dialog>
    </section>
</template>
