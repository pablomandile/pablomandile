export interface Localized {
    es: string;
    en: string;
}

export interface Profile {
    name: string;
    headline: Localized;
    tagline: Localized;
    about: { es: string[]; en: string[] };
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
    preview_image: string | null;
    preview_image_url: string | null;
    is_featured: boolean;
    is_published: boolean;
    sort_order: number;
    technologies: Pick<Technology, 'id' | 'name'>[];
}
