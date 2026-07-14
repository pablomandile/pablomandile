import { createI18n } from 'vue-i18n';
import en from './locales/en';
import es from './locales/es';

export type Locale = 'es' | 'en';

const stored = typeof window !== 'undefined' ? localStorage.getItem('locale') : null;
const initialLocale: Locale = stored === 'en' || stored === 'es' ? stored : 'es';

if (typeof document !== 'undefined') {
    document.documentElement.lang = initialLocale;
}

export const i18n = createI18n({
    legacy: false,
    locale: initialLocale,
    fallbackLocale: 'es',
    messages: { es, en },
});

export function setLocale(locale: Locale): void {
    i18n.global.locale.value = locale;
    localStorage.setItem('locale', locale);
    document.documentElement.lang = locale;
}
