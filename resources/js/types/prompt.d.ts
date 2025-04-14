export interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    tags?: Tag[];
}

export interface Tag {
    id: number;
    name: string;
    slug: string;
    category_id: number;
}

export interface Prompt {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    content: string;
    image_url?: string;
    published_at: string;
    word_count: number | string;
    target_models: string[];
    category: Category[];
    tags: Tag[];
}

export interface ModelsConfig {
    [provider: string]: string[];
}