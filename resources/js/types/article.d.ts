export type Article = {
    id: number;
    title: string;
    content: string;
    excerpt: string;
    published_at: string | null;
    image: string | null;
    created_at: string;
    updated_at: string;
    author?: User;
    category?: {
        id: number;
        name: string;
        slug: string;
    };
    tags?: Array<{
        id: number;
        name: string;
        slug: string;
    }>;
}
