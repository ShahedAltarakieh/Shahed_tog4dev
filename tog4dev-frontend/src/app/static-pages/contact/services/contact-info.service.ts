import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { environment } from 'environments/environment';

export interface ContactSocialLinks {
  facebook?: string;
  instagram?: string;
  snapchat?: string;
  twitter?: string;
  linkedin?: string;
  youtube?: string;
  tiktok?: string;
  [key: string]: string | undefined;
}

export interface ContactInfo {
  lang: string;
  city_country: string | null;
  street_short: string | null;
  company_name: string | null;
  address_line1: string | null;
  address_line2: string | null;
  working_hours: string | null;
  phone_sub: string | null;
  landline_sub: string | null;
  email_sub: string | null;
  phone_primary: string | null;
  whatsapp_number: string | null;
  landline: string | null;
  email_primary: string | null;
  extra_phones: string[];
  extra_emails: string[];
  social_links: ContactSocialLinks;
  map_link: string | null;
  map_embed_url: string | null;
  map_lat: number | null;
  map_lng: number | null;
}

@Injectable({ providedIn: 'root' })
export class ContactInfoService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  fetch(lang: string = 'en'): Observable<ContactInfo> {
    return this.http
      .get<{ data: ContactInfo }>(this.apiUrl + 'api/v1/contact-info', {
        headers: { 'Accept-Language': lang },
        params:  { lang },
      })
      .pipe(map(res => res.data));
  }
}
