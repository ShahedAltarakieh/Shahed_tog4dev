import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import {GetFactResponse} from "../types/fact-and-statistics.types";
import { environment } from 'environments/environment';

@Injectable({
  providedIn: 'root'
})
export class FactAndStatisticsService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient) { }

  getFactsAndStatistics(lang: string, type: string | null = null, category_id: number | null = null){
    return this.httpClient.get<GetFactResponse>(this.apiUrl + 'api/v1/facts', {
      headers: { 'Accept-Language': lang, 'Content-Type': 'application/json' },
      params: {
        'type': type ?? '',
        'category_id': category_id ?? '',
        '_': new Date().getTime().toString() // Add this to bypass caching
      }
    });
  }
}
