import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { ProjectItem } from 'app/shared/components/project-list-item/types/project-list-item.types';
import { map } from 'rxjs';
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class ProjectsService {
  private apiUrl = environment.apiUrl;
  constructor(public apiService: ApiService) { }

  getProjects(lang: 'ar' | 'en', type: string | null = null, category_id: number | null = null) {
    const additionalHeaders = {
      'Accept-Language': lang,
    };
    return this.apiService.get<{ data: ProjectItem[] }>(this.apiUrl + 'api/v1/items', {
      'type': type ?? '',
      'category_id': category_id?.toString() ?? '',
      '_': new Date().getTime().toString() // Add this to bypass caching
    }, additionalHeaders)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }
  getProject(lang: 'ar' | 'en', slug: string | null) {
    const additionalHeaders = {
      'Accept-Language': lang,
    };
    return this.apiService.get<{ data: ProjectItem }>(this.apiUrl + 'api/v1/items/' + slug, {
      '_': new Date().getTime().toString() // Add this to bypass caching
    }, additionalHeaders)
        .pipe(map(this.apiService.extractTypeFromMessage));
  }
}
