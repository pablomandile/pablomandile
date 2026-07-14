import { useIntersectionObserver } from '@vueuse/core';
import { ref } from 'vue';

/**
 * Revela un elemento la primera vez que entra al viewport.
 * Usar junto a las clases CSS .reveal / .reveal-visible de app.css.
 */
export function useRevealOnScroll(threshold = 0.15) {
    const target = ref<HTMLElement | null>(null);
    const revealed = ref(false);

    const { stop } = useIntersectionObserver(
        target,
        ([entry]) => {
            if (entry?.isIntersecting) {
                revealed.value = true;
                stop();
            }
        },
        { threshold },
    );

    return { target, revealed };
}
