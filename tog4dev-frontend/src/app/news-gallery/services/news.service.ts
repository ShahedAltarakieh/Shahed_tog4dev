import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { environment } from 'environments/environment';
import { map } from 'rxjs';
import { NewsItem, NewsCategory, PaginatedResponse } from '../types/news-gallery.types';

@Injectable({
  providedIn: 'root'
})
export class NewsService {
  private apiUrl = environment.apiUrl;

  constructor(public apiService: ApiService) {}

  getNews(lang: string, params: { category?: string; search?: string; page?: number; perPage?: number; featured?: boolean } = {}) {
    const additionalHeaders = { 'Accept-Language': lang };
    const queryParams: Record<string, string> = {
      '_': new Date().getTime().toString(),
    };

    if (params.category) queryParams['category'] = params.category;
    if (params.search) queryParams['search'] = params.search;
    if (params.page) queryParams['page'] = params.page.toString();
    if (params.perPage) queryParams['per-page'] = params.perPage.toString();
    if (params.featured) queryParams['featured'] = '1';

    return this.apiService.get<PaginatedResponse<NewsItem>>(this.apiUrl + 'api/v1/news', queryParams, additionalHeaders)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }

  getNewsArticle(lang: string, slug: string) {
    const additionalHeaders = { 'Accept-Language': lang };
    return this.apiService.get<{ data: NewsItem }>(this.apiUrl + 'api/v1/news/' + slug, {
      '_': new Date().getTime().toString(),
    }, additionalHeaders)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }

  getRelatedNews(lang: string, slug: string, limit: number = 4) {
    const additionalHeaders = { 'Accept-Language': lang };
    return this.apiService.get<{ data: NewsItem[] }>(this.apiUrl + 'api/v1/news/' + slug + '/related', {
      'limit': limit.toString(),
      '_': new Date().getTime().toString(),
    }, additionalHeaders)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }

  getCategories(lang: string) {
    const additionalHeaders = { 'Accept-Language': lang };
    return this.apiService.get<{ data: NewsCategory[] }>(this.apiUrl + 'api/v1/news/categories', {
      '_': new Date().getTime().toString(),
    }, additionalHeaders)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }

  searchNews(lang: string, query: string, page: number = 1) {
    const additionalHeaders = { 'Accept-Language': lang };
    return this.apiService.get<PaginatedResponse<NewsItem>>(this.apiUrl + 'api/v1/news/search', {
      'q': query,
      'page': page.toString(),
      '_': new Date().getTime().toString(),
    }, additionalHeaders)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }
}
