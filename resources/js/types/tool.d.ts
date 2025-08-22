import type { Category, Tag } from './index'

export interface Tool {
  id: number
  uuid: string
  title: string
  slug: string
  excerpt: string | null
  description: string | null
  business_model: 'free' | 'freemium' | 'paid' | 'subscription' | 'one_time' | 'open_source'
  featured_image: string | null
  featured_image_url?: string | null
  gallery: string[] | null
  website_url: string | null
  pricing_url: string | null
  documentation_url: string | null
  meta_title: string | null
  meta_description: string | null
  meta_keywords: string[] | null
  structured_data: Record<string, any> | null
  is_featured: boolean
  published_at: string | null
  created_at: string
  updated_at: string
  categories?: Category[]
  tags?: Tag[]
}

export interface ToolForm {
  title: string
  slug: string
  excerpt: string
  description: string
  business_model: string
  featured_image: string
  gallery: string[]
  website_url: string
  pricing_url: string
  documentation_url: string
  meta_title: string
  meta_description: string
  meta_keywords: string[]
  categories: number[]
  tags: number[]
  is_featured: boolean
  published_at: string
  [key: string]: any // Allow index signature for form compatibility
}