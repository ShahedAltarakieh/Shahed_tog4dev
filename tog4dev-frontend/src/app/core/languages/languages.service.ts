import { Injectable, NgZone } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, Subscription, catchError, interval, map, of, tap } from 'rxjs';

import { environment } from 'environments/environment';
import { AppLanguage, FALLBACK_LANGUAGES, StorageService } from 'app/core/storage/storage.service';

interface LanguagesResponse {
  data: AppLanguage[];
  default: string;
  version: string;
}

@Injectable({ providedIn: 'root' })
export class LanguagesService {
  private apiUrl = environment.apiUrl + 'api/v1/languages';
  private loadedVersion: string | null = null;
  private pollSub: Subscription | null = null;

  constructor(
    private http: HttpClient,
    private storageService: StorageService,
    private zone: NgZone,
  ) {}

  // Refresh cached list; updates state only when server `version` changes.
  load(force = false): Observable<AppLanguage[]> {
    if (this.loadedVersion && !force) {
      return of(this.storageService.availableLanguages$.value);
    }
    return this.http.get<LanguagesResponse>(this.apiUrl).pipe(
      map(res => {
        const list = (res?.data && res.data.length > 0) ? res.data : FALLBACK_LANGUAGES;
        const defaultCode = res?.default
          || (list.find(l => l.is_default)?.code)
          || list[0]?.code
          || 'en';
        const version = res?.version || '';
        return { list, defaultCode, version };
      }),
      catchError(() => of({ list: FALLBACK_LANGUAGES, defaultCode: 'en', version: '' })),
      tap(({ list, defaultCode, version }) => {
        if (version !== this.loadedVersion) {
          this.storageService.availableLanguages$.next(list);
          this.storageService.defaultLanguage = defaultCode;
          this.loadedVersion = version;
        }
      }),
      map(({ list }) => list),
    );
  }

  refresh(): Observable<AppLanguage[]> {
    return this.load(true);
  }

  get currentVersion(): string | null {
    return this.loadedVersion;
  }

  // Background poll. No-op on the server.
  startAutoRevalidation(intervalMs = 5 * 60 * 1000): void {
    if (this.pollSub || typeof window === 'undefined') { return; }
    this.zone.runOutsideAngular(() => {
      this.pollSub = interval(intervalMs).subscribe(() => {
        this.zone.run(() => this.refresh().subscribe());
      });
    });
  }

  stopAutoRevalidation(): void {
    this.pollSub?.unsubscribe();
    this.pollSub = null;
  }
}
