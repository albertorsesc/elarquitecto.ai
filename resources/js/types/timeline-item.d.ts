import { User } from "./user";

export type TimelineItem = {
  id: number;
  author: User;
  title: string;
  slug?: string;
  description: string;
  excerpt: string;
  content: string;
  created_at: string;
  updated_at: string;
  tags?: {
    id: number;
    name: string;
  }[];
}
