import { Injectable } from '@angular/core';
import { ApiService } from 'app/core/api/api.service';
import { environment } from 'environments/environment';
import { map, Observable, of, shareReplay, catchError } from 'rxjs';

export interface NavSetting {
  page_key: string;
  label_en: string | null;
  label_ar: string | null;
  visible: boolean;
  order: number;
}

@Injectable({ providedIn: 'root' })
export class NavigationService {
  private apiUrl = environment.apiUrl;
  private cache$?: Observable<NavSetting[]>;
  private visibilityMap: Record<string, boolean> = {};

  constructor(private apiService: ApiService) {}

  load(): Observable<NavSetting[]> {
    if (!this.cache$) {
      const url = this.apiUrl + 'api/v1/navigation';
      this.cache$ = this.apiService.get<any>(url).pipe(
        map(this.apiService.extractTypeFromMessage),
        map((res: any) => (res?.data || res || []) as NavSetting[]),
        map((items: NavSetting[]) => {
          this.visibilityMap = {};
          items.forEach((it) => { this.visibilityMap[it.page_key] = !!it.visible; });
          return items;
        }),
        catchError(() => {
          this.cache$ = undefined;
          return of([] as NavSetting[]);
        }),
        shareReplay(1)
      );
    }
    return this.cache$;
  }

  isVisible(pageKey: string): boolean {
    if (!(pageKey in this.visibilityMap)) return true;
    return this.visibilityMap[pageKey];
  }
}
