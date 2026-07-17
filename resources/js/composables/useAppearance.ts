import { onMounted, ref } from 'vue';

type Appearance = 'light' | 'dark' | 'system';

export function updateTheme(value: Appearance) {
    const isDark = value === 'dark' || (value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

    document.documentElement.classList.toggle('dark', isDark);

    // Mantiene el color-scheme del documento en sintonía con el tema real, para
    // que los controles nativos (scrollbars, inputs, etc.) del panel admin
    // acompañen el selector claro/oscuro.
    document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
}

const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

const handleSystemThemeChange = () => {
    const currentAppearance = localStorage.getItem('appearance') as Appearance | null;
    updateTheme(currentAppearance || 'system');
};

export function initializeTheme() {
    // Initialize theme from saved preference or default to system...
    const savedAppearance = localStorage.getItem('appearance') as Appearance | null;
    updateTheme(savedAppearance || 'system');

    // Set up system theme change listener...
    mediaQuery.addEventListener('change', handleSystemThemeChange);
}

export function useAppearance() {
    const appearance = ref<Appearance>('system');

    onMounted(() => {
        initializeTheme();

        const savedAppearance = localStorage.getItem('appearance') as Appearance | null;

        if (savedAppearance) {
            appearance.value = savedAppearance;
        }
    });

    function updateAppearance(value: Appearance) {
        appearance.value = value;
        localStorage.setItem('appearance', value);
        updateTheme(value);
    }

    return {
        appearance,
        updateAppearance,
    };
}
