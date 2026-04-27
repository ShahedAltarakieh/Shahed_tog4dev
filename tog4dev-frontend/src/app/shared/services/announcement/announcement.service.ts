import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { environment } from 'environments/environment';
import { map, Observable, of, shareReplay, catchError } from 'rxjs';

export interface Announcement {
  id: number;
  title: string | null;
  title_ar: string | null;
  text: string;
  text_ar: string | null;
  short_text: string | null;
  short_text_ar: string | null;
  link: string | null;
  cta_text: string | null;
  cta_text_ar: string | null;
  badge_type: 'LIVE' | 'INFO' | 'ALERT' | 'NEW';
  target_view: 'desktop' | 'mobile' | 'both';
  source_type: 'manual' | 'news' | 'system';
}

/**
 * Pick the value matching the requested locale, falling back to the other
 * language when the preferred one is empty/null.
 */
export function pickLocalized(en: string | null | undefined, ar: string | null | undefined, lang: string): string {
  const en_ = (en ?? '').toString();
  const ar_ = (ar ?? '').toString();
  if (lang === 'ar') {
    return ar_.trim() ? ar_ : en_;
  }
  return en_.trim() ? en_ : ar_;
}

@Injectable({
  providedIn: 'root'
})
export class AnnouncementService {
  private apiUrl = environment.apiUrl;
  private cacheMap = new Map<string, Observable<Announcement[]>>();

  constructor(private apiService: ApiService) {}

  getAnnouncements(target?: 'desktop' | 'mobile'): Observable<Announcement[]> {
    const cacheKey = target || 'all';

    if (!this.cacheMap.has(cacheKey)) {
      const url = this.apiUrl + 'api/v1/announcements' + (target ? '?target=' + target : '');
      const request$ = this.apiService.get<any>(url).pipe(
        map(this.apiService.extractTypeFromMessage),
        map((res: any) => res?.data || res || []),
        catchError(() => {
          this.cacheMap.delete(cacheKey);
          return of([]);
        }),
        shareReplay(1)
      );
      this.cacheMap.set(cacheKey, request$);
    }

    return this.cacheMap.get(cacheKey)!;
  }

  clearCache(): void {
    this.cacheMap.clear();
  }
}
