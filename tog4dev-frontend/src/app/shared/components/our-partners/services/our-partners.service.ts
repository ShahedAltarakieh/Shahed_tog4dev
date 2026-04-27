import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

import {GetPartnerResponse} from "../types/our-partners.types";
import { environment } from 'environments/environment';
@Injectable({
  providedIn: 'root'
})
export class OurPartnersService {
  private apiUrl = environment.apiUrl;
  constructor(public httpClient: HttpClient) { }

  getPartners(lang: string, type: string | null = null, category_id: number | null = null, home_only: boolean | null = null){
    return this.httpClient.get<GetPartnerResponse>(this.apiUrl + 'api/v1/partners', {
      headers: { 'Accept-Language': lang, 'Content-Type': 'application/json' },
      params: {
        'home_only': home_only ?? '',
        '_': new Date().getTime().toString() // Add this to bypass caching
      }
    });
  }
}
