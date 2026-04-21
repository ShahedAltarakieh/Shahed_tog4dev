import { Injectable } from '@angular/core';
import {GetCategoryResponse} from "../types/categories.types";
import {HttpClient, HttpParams} from "@angular/common/http";
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class CategoriesService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient) { }

  getCategories(lang: string, type: string | null = null, id: number| null = null) {
    return this.httpClient.get<GetCategoryResponse>(this.apiUrl + 'api/v1/categories', {
      headers: { 'Accept-Language': lang, 'Content-Type': 'application/json' },
      params: {
        'type': type ?? '',
        'id': id ?? '',
        '_': new Date().getTime().toString() // Add this to bypass caching
      }
    });
  }
}
