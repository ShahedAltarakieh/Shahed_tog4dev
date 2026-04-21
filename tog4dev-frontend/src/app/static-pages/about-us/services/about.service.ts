import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { environment } from 'environments/environment';
import { map, Observable } from 'rxjs';

export interface AboutSectionItem {
  id: number;
  title: string;
  description: string;
  image: string;
  icon: string;
  link: string;
  value: string;
  label: string;
  social_links: Record<string, string>;
  extra: Record<string, any>;
  sort_order: number;
}

export interface AboutSection {
  id: number;
  section_key: string;
  title: string;
  subtitle: string;
  body: string;
  image: string;
  video_url: string;
  cta_text: string;
  cta_link: string;
  layout: string;
  settings: Record<string, any>;
  sort_order: number;
  is_visible: boolean;
  items: AboutSectionItem[];
}

export interface AboutPageData {
  id: number;
  country_code: string;
  status: string;
  version: number;
  meta: {
    title: string;
    description: string;
  };
  sections: AboutSection[];
}

@Injectable({
  providedIn: 'root'
})
export class AboutService {
  private apiUrl = environment.apiUrl;

  constructor(private apiService: ApiService) {}

  getAboutPage(lang: string, country: string = 'JO'): Observable<AboutPageData> {
    const additionalHeaders = { 'Accept-Language': lang };
    const queryParams: Record<string, string> = {
      country: country,
    };

    return this.apiService.get<any>(this.apiUrl + 'api/v1/about', queryParams, additionalHeaders)
      .pipe(
        map(this.apiService.extractTypeFromMessage),
        map((body: any) => body?.data || body)
      );
  }
}
