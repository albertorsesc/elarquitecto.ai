import { Category } from './category';
import { Tag } from './tag';

export interface Article {
  id: number;
  uuid: string;
  title: string;
  slug: string;
  body: string;
  original_url: string | null;
  hero_image_url: string | null;
  hero_image_author_name: string | null;
  hero_image_author_url: string | null;
  is_pinned: boolean;
  is_featured: boolean;
  view_count: number;
  published_at: string | null;
  created_at: string;
  updated_at: string;
  category?: Category[];
  tags?: Tag[];
} 