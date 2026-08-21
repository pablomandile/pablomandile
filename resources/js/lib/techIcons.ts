// Logos de las tecnologías. Los SVG viven en el repo (resources/js/assets/tech-icons),
// así que no depende de ningún CDN externo: Vite los emite con hash de contenido.
//
// El nombre del archivo es el slug del logo: `laravel-original.svg` (Devicon,
// mismo nombre que la clase `devicon-laravel-original`) u `openai.svg`.
const files = import.meta.glob('../assets/tech-icons/*.svg', {
    eager: true,
    query: '?url',
    import: 'default',
}) as Record<string, string>;

// Slug completo -> URL, p. ej. `laravel-original`.
const urlBySlug: Record<string, string> = {};
// Marca -> URL (primer segmento del slug), para tolerar una variante que no tengamos:
// `php-original` cae en el `php-plain` que sí está.
const urlByBrand: Record<string, string> = {};

for (const [path, url] of Object.entries(files)) {
    const slug = path.split('/').pop()!.replace(/\.svg$/, '');
    urlBySlug[slug] = url;
    const brand = slug.split('-')[0];
    urlByBrand[brand] ??= url;
}

/** Slugs de logo disponibles, ordenados (los usa el selector del panel). */
export const availableTechIcons: string[] = Object.keys(urlBySlug).sort();

function slugify(value: string): string {
    return value
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

/** URL del logo de un slug suelto (acepta el prefijo `devicon-`), o null. */
export function techIconUrlBySlug(slug: string): string | null {
    const clean = slug.trim().toLowerCase().replace(/^devicon-/, '');

    if (!clean) {
        return null;
    }

    return urlBySlug[clean] ?? urlByBrand[clean.split('-')[0]] ?? null;
}

/**
 * URL del logo de una tecnología: primero por el ícono configurado en el panel
 * y, si no tiene uno, probando con su nombre (así una tecnología nueva ya
 * muestra el logo si lo tenemos). Null si no hay logo para mostrar.
 */
export function techIconUrl(technology: { name: string; icon?: string | null }): string | null {
    return techIconUrlBySlug(technology.icon ?? '') ?? techIconUrlBySlug(slugify(technology.name));
}
