type Prompt = {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    content: string;
    image?: string;
    category?: string;
    published_at?: string;
    word_count: number;
    target_models?: string[];
    tags: string[];
    author: string;
    createdAt: string;
};