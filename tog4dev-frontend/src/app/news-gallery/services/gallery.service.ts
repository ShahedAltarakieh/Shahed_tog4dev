import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { environment } from 'environments/environment';
import { map } from 'rxjs';
import { GalleryPhoto, GalleryVideo, PaginatedResponse } from '../types/news-gallery.types';

@Injectable({
  providedIn: 'root'
})
export class GalleryService {
  private apiUrl = environment.apiUrl;

  constructor(public apiService: ApiService) {}

  getPhotos(lang: 'ar' | 'en', params: { category?: string; search?: string; page?: number; perPage?: number } = {}) {
    const additionalHeaders = { 'Accept-Language': lang };
    const queryParams: Record<string, string> = {
      '_': new Date().getTime().toString(),
    };

    if (params.category) queryParams['category'] = params.category;
    if (params.search) queryParams['search'] = params.search;
    if (params.page) queryParams['page'] = params.page.toString();
    if (params.perPage) queryParams['per-page'] = params.perPage.toString();

    return this.apiService.get<PaginatedResponse<GalleryPhoto>>(this.apiUrl + 'api/v1/gallery/photos', queryParams, additionalHeaders)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }

  getVideos(lang: 'ar' | 'en', params: { category?: string; search?: string; page?: number; perPage?: number; display_target?: string } = {}) {
    const additionalHeaders = { 'Accept-Language': lang };
    const queryParams: Record<string, string> = {
      '_': new Date().getTime().toString(),
    };

    if (params.category) queryParams['category'] = params.category;
    if (params.search) queryParams['search'] = params.search;
    if (params.page) queryParams['page'] = params.page.toString();
    if (params.perPage) queryParams['per-page'] = params.perPage.toString();
    if (params.display_target) queryParams['display_target'] = params.display_target;

    return this.apiService.get<PaginatedResponse<GalleryVideo>>(this.apiUrl + 'api/v1/gallery/videos', queryParams, additionalHeaders)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }
}
