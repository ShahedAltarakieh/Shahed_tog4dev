import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { environment } from 'environments/environment';
import { BehaviorSubject, Observable, catchError, map, of, shareReplay, tap } from 'rxjs';

export interface PageMaintenanceInfo {
  page_key: string;
  label_en: string | null;
  label_ar: string | null;
  message_en: string | null;
  message_ar: string | null;
  starts_at?: string | null;
  ends_at?: string | null;
}

@Injectable({ providedIn: 'root' })
export class PageMaintenanceService {
  private apiUrl = environment.apiUrl;
  private cache$?: Observable<PageMaintenanceInfo[]>;
  private byKey: Record<string, PageMaintenanceInfo> = {};
  readonly active$ = new BehaviorSubject<PageMaintenanceInfo[]>([]);

  constructor(private apiService: ApiService) {}

  load(): Observable<PageMaintenanceInfo[]> {
    if (!this.cache$) {
      const url = this.apiUrl + 'api/v1/page-maintenance';
      this.cache$ = this.apiService.get<any>(url).pipe(
        map(this.apiService.extractTypeFromMessage),
        map((res: any) => (res?.data || res || []) as PageMaintenanceInfo[]),
        tap((items) => {
          this.byKey = {};
          items.forEach((it) => { this.byKey[it.page_key] = it; });
          this.active$.next(items);
        }),
        catchError(() => {
          this.cache$ = undefined;
          return of([] as PageMaintenanceInfo[]);
        }),
        shareReplay(1)
      );
    }
    return this.cache$;
  }

  getActive(pageKey: string | null | undefined): PageMaintenanceInfo | null {
    if (!pageKey) return null;
    return this.byKey[pageKey] || null;
  }
}
