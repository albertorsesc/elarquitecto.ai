export interface Media {
  id: number;
  uuid: string;
  collection_name: string;
  file_name: string;
  mime_type: string;
  disk: string;
  path: string;
  size: number;
  custom_properties?: any;
  is_primary: boolean;
  mediable_id: number;
  mediable_type: string;
  created_at: string;
  updated_at: string;
  url?: string;
} 