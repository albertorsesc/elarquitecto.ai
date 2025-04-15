export interface Tag {
    id: number;
    name: string;
    slug: string;
    category_id: number;
    category?: import('./category').Category;
    created_at?: string;
    updated_at?: string;
} 