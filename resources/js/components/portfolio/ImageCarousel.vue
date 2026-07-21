<script setup lang="ts">
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const props = withDefaults(
    defineProps<{
        images: string[];
        alt?: string;
        startIndex?: number;
        heightClass?: string;
        enlargeable?: boolean;
        keyboard?: boolean;
    }>(),
    { alt: '', startIndex: 0, heightClass: 'max-h-[72vh]', enlargeable: false, keyboard: true },
);

const emit = defineEmits<{ 'update:index': [number]; 'image-click': [number] }>();

const { t } = useI18n();

const current = ref(props.startIndex);
const hasMultiple = computed(() => props.images.length > 1);

watch(
    () => props.startIndex,
    (value) => {
        current.value = clamp(value);
    },
);

watch(current, (value) => emit('update:index', value));

function clamp(index: number): number {
    const total = props.images.length;
    if (total === 0) {
        return 0;
    }
    return ((index % total) + total) % total;
}

function go(delta: number): void {
    current.value = clamp(current.value + delta);
}

function goTo(index: number): void {
    current.value = clamp(index);
}

function onKeydown(event: KeyboardEvent): void {
    if (!hasMultiple.value) {
        return;
    }
    if (event.key === 'ArrowLeft') {
        go(-1);
    } else if (event.key === 'ArrowRight') {
        go(1);
    }
}

// Swipe táctil en móvil.
let touchStartX = 0;
function onTouchStart(event: TouchEvent): void {
    touchStartX = event.changedTouches[0].screenX;
}
function onTouchEnd(event: TouchEvent): void {
    const deltaX = event.changedTouches[0].screenX - touchStartX;
    if (hasMultiple.value && Math.abs(deltaX) > 40) {
        go(deltaX < 0 ? 1 : -1);
    }
}

onMounted(() => {
    if (props.keyboard) {
        window.addEventListener('keydown', onKeydown);
    }
});
onBeforeUnmount(() => window.removeEventListener('keydown', onKeydown));
</script>

<template>
    <div class="relative select-none" @touchstart.passive="onTouchStart" @touchend.passive="onTouchEnd">
        <div class="overflow-hidden rounded-xl bg-night-soft">
            <div class="flex transition-transform duration-300 ease-out" :style="{ transform: `translateX(-${current * 100}%)` }">
                <div v-for="(image, index) in images" :key="index" class="flex w-full shrink-0 items-center justify-center">
                    <img
                        :src="image"
                        :alt="images.length > 1 ? `${alt} (${index + 1}/${images.length})` : alt"
                        class="w-full object-contain"
                        :class="[heightClass, enlargeable ? 'cursor-zoom-in' : '']"
                        @click="enlargeable && emit('image-click', current)"
                    />
                </div>
            </div>
        </div>

        <template v-if="hasMultiple">
            <button
                type="button"
                class="absolute left-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-black/50 text-white shadow-lg backdrop-blur-md transition hover:border-violet-400/70 hover:bg-black/70"
                :aria-label="t('projects.prev_image')"
                @click="go(-1)"
            >
                <ChevronLeft class="h-6 w-6 drop-shadow" />
            </button>
            <button
                type="button"
                class="absolute right-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-black/50 text-white shadow-lg backdrop-blur-md transition hover:border-violet-400/70 hover:bg-black/70"
                :aria-label="t('projects.next_image')"
                @click="go(1)"
            >
                <ChevronRight class="h-6 w-6 drop-shadow" />
            </button>

            <div
                class="absolute right-3 top-3 rounded-full border border-white/20 bg-black/50 px-2.5 py-1 text-xs font-medium text-white shadow-lg backdrop-blur-md"
            >
                {{ current + 1 }} / {{ images.length }}
            </div>

            <div class="mt-4 flex items-center justify-center gap-2">
                <button
                    v-for="(image, index) in images"
                    :key="index"
                    type="button"
                    class="h-2 rounded-full transition-all"
                    :class="index === current ? 'w-6 bg-gradient-to-r from-violet-500 to-cyan-400' : 'w-2 bg-white/25 hover:bg-white/50'"
                    :aria-label="t('projects.go_to_image', { n: index + 1 })"
                    :aria-current="index === current"
                    @click="goTo(index)"
                ></button>
            </div>
        </template>
    </div>
</template>
