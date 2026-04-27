import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { map } from 'rxjs';
import {GetContributionsResponse} from "../types/QuickContributions.types";
import { environment } from 'environments/environment';

@Injectable({
  providedIn: 'root'
})
export class QuickContributionService {
  private apiUrl = environment.apiUrl;
  constructor(public apiService: ApiService) { }

  getContribution(lang: 'ar' | 'en', type: string | null = null, category_id: number | null = null) {
    const additionalHeaders = {
      'Accept-Language': lang,
    };
    return this.apiService.get<{ data: GetContributionsResponse[] }>(this.apiUrl + 'api/v1/quick-contributions', {
      'type_id': type ?? '',
      'category_id': category_id?.toString() ?? '',
      '_': new Date().getTime().toString() // Add this to bypass caching
    }, additionalHeaders)
      .pipe(map(this.apiService.extractTypeFromMessage));
  }
}
