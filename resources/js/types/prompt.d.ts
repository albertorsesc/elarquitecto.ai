import { Category } from './category';
import { Tag } from './tag';

// Re-export imported types
export type { Category, Tag };

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