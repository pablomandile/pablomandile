export interface Localized {
    es: string;
    en: string;
}

export interface Profile {
    name: string;
    photo_url: string | null;
    cv_url: string | null;
    headline: Localized;
    tagline: Localized;
    about: Localized;
    experience: {
        role: Localized;
        company: string;
        period: Localized;
    }[];
    email: string;
    location: Localized;
    socials: {
        github: string;
        linkedin: string;
    };
}

export type TechCategoryKey = 'backend' | 'frontend' | 'database' | 'tools';

export interface Technology {
    id: number;
    name: string;
    category: TechCategoryKey;
    icon: string | null;
}

export interface Project {
    id: number;
    title: string;
    slug: string;
    description: Localized;
    demo_url: string | null;
    repo_url: string | null;
    // Rutas crudas almacenadas (usadas por el admin para reenviar las conservadas).
    images: string[];
    // URLs resueltas de todas las imágenes, en orden (la primera es la portada).
    image_urls: string[];
    // URL de la portada (primera imagen), o null si no hay imágenes.
    cover_url: string | null;
    is_featured: boolean;
    is_published: boolean;
    sort_order: number;
    technologies: Pick<Technology, 'id' | 'name'>[];
}
