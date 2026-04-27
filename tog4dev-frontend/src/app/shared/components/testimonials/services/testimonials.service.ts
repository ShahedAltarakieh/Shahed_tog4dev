import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import { GetTestimonialResponse } from "../types/testimonial.types";
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class TestimonialsService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient) { }

  getTestimonials(lang: 'ar' | 'en', type: string | null = null, category_id: number | null = null, home_only: boolean | null = null) {
    return this.httpClient.get<GetTestimonialResponse>(this.apiUrl + 'api/v1/testimonials', {
      headers: { 'Accept-Language': lang, 'Content-Type': 'application/json' },
      params: {
        'type': type ?? '',
        'category_id': category_id ?? '',
        'home_only': home_only ?? '',
        '_': new Date().getTime().toString() // Add this to bypass caching
      }
    });
  }
}
