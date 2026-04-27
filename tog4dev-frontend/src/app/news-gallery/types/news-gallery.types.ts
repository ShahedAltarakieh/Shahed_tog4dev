export interface NewsCategory {
  id: number;
  name: string;
  slug: string;
  slug_ar: string;
  slug_en: string;
}

export interface NewsItem {
  id: number;
  title: string;
  slug: string;
  slug_ar: string;
  slug_en: string;
  excerpt: string;
  body: string;
  image: string | null;
  image_tablet: string | null;
  image_mobile: string | null;
  is_featured: boolean;
  published_at: string;
  category: NewsCategory | null;
}

export interface GalleryPhoto {
  id: number;
  title: string;
  description: string;
  slug: string;
  slug_ar: string;
  slug_en: string;
  image: string | null;
  image_tablet: string | null;
  image_mobile: string | null;
  category: NewsCategory | null;
}

export interface GalleryVideo {
  id: number;
  title: string;
  description: string;
  slug: string;
  slug_ar: string;
  slug_en: string;
  video_url: string;
  thumbnail: string | null;
  display_target: 'mobile' | 'desktop' | 'both';
  category: NewsCategory | null;
}

export interface PaginatedResponse<T> {
  data: T[];
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    from: number | null;
    last_page: number;
    path: string;
    per_page: number;
    to: number | null;
    total: number;
  };
}
