import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import {GetSliderResponse} from "../types/home-slider.types";
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class HomeSliderService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient) { }

  getHomeSliders(lang: 'ar' | 'en'){
    return this.httpClient.get<GetSliderResponse>(this.apiUrl + 'api/v1/sliders', {
      headers: { 'Accept-Language': lang, 'Content-Type': 'application/json' },
      params: {
        '_': new Date().getTime().toString() // Add this to bypass caching
      }
    });
  }
}
