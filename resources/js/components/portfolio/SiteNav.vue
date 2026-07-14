<script setup lang="ts">
import LanguageToggle from '@/components/portfolio/LanguageToggle.vue';
import { Link } from '@inertiajs/vue3';
import { Lock, Menu, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

defineProps<{ name: string }>();

const { t } = useI18n();
const open = ref(false);

const links = [
    { anchor: '#about', label: 'nav.about' },
    { anchor: '#tech', label: 'nav.tech' },
    { anchor: '#projects', label: 'nav.projects' },
    { anchor: '#contact', label: 'nav.contact' },
];
</script>

<template>
    <header class="glass fixed inset-x-0 top-0 z-50 border-x-0 border-t-0">
        <nav class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
            <a href="#" class="flex items-center gap-2.5 text-lg font-bold tracking-tight text-white">
                <img src="/img/logo.png" alt="" class="h-9 w-9 rounded-lg" />
                <span>{{ name }}</span>
            </a>

            <div class="flex items-center gap-4">
                <ul class="hidden items-center gap-7 text-sm text-slate-300 md:flex">
                    <li v-for="link in links" :key="link.anchor">
                        <a :href="link.anchor" class="transition hover:text-white">{{ t(link.label) }}</a>
                    </li>
                </ul>

                <LanguageToggle />

                <Link
                    :href="route('login')"
                    class="glass rounded-full p-2 text-slate-400 transition hover:border-violet-400/50 hover:text-white"
                    :aria-label="t('nav.login')"
                    :title="t('nav.login')"
                >
                    <Lock class="h-4 w-4" />
                </Link>

                <button
                    type="button"
                    class="text-slate-300 transition hover:text-white md:hidden"
                    :aria-expanded="open"
                    aria-label="Menu"
                    @click="open = !open"
                >
                    <X v-if="open" class="h-6 w-6" />
                    <Menu v-else class="h-6 w-6" />
                </button>
            </div>
        </nav>

        <div v-if="open" class="border-t border-white/10 px-6 pb-4 md:hidden">
            <ul class="space-y-3 pt-4 text-sm text-slate-300">
                <li v-for="link in links" :key="link.anchor">
                    <a :href="link.anchor" class="block transition hover:text-white" @click="open = false">{{ t(link.label) }}</a>
                </li>
            </ul>
        </div>
    </header>
</template>
