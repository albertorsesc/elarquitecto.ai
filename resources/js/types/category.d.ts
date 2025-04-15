export interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    tags?: Array<import('./tag').Tag>;
} 