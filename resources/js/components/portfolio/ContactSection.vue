<script setup lang="ts">
import GithubIcon from '@/components/portfolio/icons/GithubIcon.vue';
import LinkedinIcon from '@/components/portfolio/icons/LinkedinIcon.vue';
import { useLocalized } from '@/composables/useLocalized';
import { useRevealOnScroll } from '@/composables/useRevealOnScroll';
import type { Profile } from '@/types/portfolio';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle, MapPin } from 'lucide-vue-next';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{ profile: Profile }>();

const { t } = useI18n();
const { l } = useLocalized();
const { target, revealed } = useRevealOnScroll();

const showForm = ref(false);
const sent = ref(false);

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
    website: '', // honeypot: debe quedar vacío
});

function submit(): void {
    form.post(route('contact'), {
        preserveScroll: true,
        onSuccess: () => {
            sent.value = true;
            form.reset();
        },
    });
}

const fieldClass =
    'w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2.5 text-white placeholder:text-slate-500 backdrop-blur-md transition focus:border-violet-400/50 focus:outline-none focus:ring-1 focus:ring-violet-400/50';
</script>

<template>
    <section id="contact" ref="target" class="reveal py-24 text-center" :class="{ 'reveal-visible': revealed }">
        <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ t('contact.title') }}</h2>
        <div class="mx-auto mt-3 h-1 w-16 rounded-full bg-gradient-to-r from-violet-500 to-cyan-500"></div>

        <p class="mx-auto mt-8 max-w-md text-lg leading-relaxed text-slate-400">{{ t('contact.blurb') }}</p>

        <!-- Botón que despliega el formulario -->
        <button
            v-if="!showForm && !sent"
            type="button"
            class="mt-10 inline-block rounded-full bg-gradient-to-r from-violet-500 to-cyan-500 px-8 py-4 font-semibold text-white shadow-lg shadow-violet-500/25 transition hover:scale-[1.03] hover:shadow-violet-500/40"
            @click="showForm = true"
        >
            {{ t('contact.form_toggle') }}
        </button>

        <!-- Mensaje de éxito -->
        <div v-if="sent" class="glass mx-auto mt-10 max-w-md rounded-2xl p-6 text-center">
            <p class="text-lg font-medium text-white">{{ t('contact.success') }}</p>
        </div>

        <!-- Formulario -->
        <form
            v-else-if="showForm"
            class="glass mx-auto mt-10 max-w-xl space-y-4 rounded-2xl p-6 text-left sm:p-8"
            @submit.prevent="submit"
        >
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="c_name" class="mb-1.5 block text-sm text-slate-300">{{ t('contact.name') }}</label>
                    <input id="c_name" v-model="form.name" type="text" required :class="fieldClass" />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label for="c_email" class="mb-1.5 block text-sm text-slate-300">{{ t('contact.email') }}</label>
                    <input id="c_email" v-model="form.email" type="email" required :class="fieldClass" />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
                </div>
            </div>

            <div>
                <label for="c_subject" class="mb-1.5 block text-sm text-slate-300">{{ t('contact.subject') }}</label>
                <input id="c_subject" v-model="form.subject" type="text" required :class="fieldClass" />
                <p v-if="form.errors.subject" class="mt-1 text-sm text-red-400">{{ form.errors.subject }}</p>
            </div>

            <div>
                <label for="c_message" class="mb-1.5 block text-sm text-slate-300">{{ t('contact.message') }}</label>
                <textarea id="c_message" v-model="form.message" rows="5" required :class="fieldClass"></textarea>
                <p v-if="form.errors.message" class="mt-1 text-sm text-red-400">{{ form.errors.message }}</p>
            </div>

            <!-- Honeypot: oculto para humanos, atractivo para bots -->
            <input v-model="form.website" type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true" />

            <button
                type="submit"
                :disabled="form.processing"
                class="flex w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-violet-500 to-cyan-500 px-8 py-3 font-semibold text-white shadow-lg shadow-violet-500/25 transition hover:shadow-violet-500/40 disabled:opacity-60"
            >
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                {{ form.processing ? t('contact.sending') : t('contact.send') }}
            </button>
        </form>

        <p class="mt-8 text-sm text-slate-500">
            {{ t('contact.or_email') }}
            <a :href="`mailto:${profile.email}`" class="text-cyan-400 transition hover:text-cyan-300">{{ profile.email }}</a>
        </p>

        <div class="mt-8 flex items-center justify-center gap-4 text-xl text-slate-400">
            <a
                :href="profile.socials.github"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="GitHub"
                class="glass rounded-full p-3 transition hover:border-cyan-400/50 hover:text-white"
            >
                <GithubIcon />
            </a>
            <a
                :href="profile.socials.linkedin"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="LinkedIn"
                class="glass rounded-full p-3 transition hover:border-cyan-400/50 hover:text-white"
            >
                <LinkedinIcon />
            </a>
        </div>

        <p class="mt-8 inline-flex items-center gap-1.5 text-sm text-slate-500">
            <MapPin class="h-4 w-4" />
            {{ l(profile.location) }}
        </p>
    </section>
</template>
