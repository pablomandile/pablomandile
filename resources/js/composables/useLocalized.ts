import { useI18n } from 'vue-i18n';
import type { Localized } from '@/types/portfolio';

/**
 * Resuelve campos bilingües ({ es, en }) provenientes del backend
 * según el idioma activo de vue-i18n.
 */
export function useLocalized() {
    const { locale } = useI18n();

    function l(value: Localized): string;
    function l<T>(value: Record<string, T>): T;
    function l(value: Record<string, unknown>) {
        return value[locale.value] ?? value.es;
    }

    return { l, locale };
}
