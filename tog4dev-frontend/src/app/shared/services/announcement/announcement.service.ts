import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { environment } from 'environments/environment';
import { map, Observable, shareReplay } from 'rxjs';

export interface Announcement {
  id: number;
  title: string;
  text: string;
  short_text: string | null;
  link: string | null;
  cta_text: string | null;
  badge_type: 'LIVE' | 'INFO' | 'ALERT' | 'NEW';
  target_view: 'desktop' | 'mobile' | 'both';
  source_type: 'manual' | 'news' | 'system';
  is_active: boolean;
  order_no: number;
}

@Injectable({
  providedIn: 'root'
})
export class AnnouncementService {
  private apiUrl = environment.apiUrl;
  private cache$: Observable<Announcement[]> | null = null;

  constructor(private apiService: ApiService) {}

  getAnnouncements(target?: 'desktop' | 'mobile'): Observable<Announcement[]> {
    if (!this.cache$) {
      const url = this.apiUrl + 'api/v1/announcements' + (target ? '?target=' + target : '');
      this.cache$ = this.apiService.get<any>(url).pipe(
        map(this.apiService.extractTypeFromMessage),
        map((res: any) => res?.data || res || []),
        shareReplay(1)
      );
    }
    return this.cache$;
  }

  clearCache(): void {
    this.cache$ = null;
  }
}
