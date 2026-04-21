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

/**
 * Loads the admin-managed list of active languages from the backend and pushes
 * it into StorageService. Falls back silently to the built-in EN/AR list if the
 * API is unreachable so the app keeps working.
 */
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

  /**
   * Loads languages from the API. The backend payload includes a `version`
   * hash (md5 of max(updated_at)); when `force=true` we always re-hit the
   * API and refresh the in-memory list if the version has changed. Useful
   * for the admin to call after toggling language activation so the
   * frontend picks up changes without a full page reload.
   */
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

  /**
   * Forces a fresh fetch from the API regardless of the cached version.
   */
  refresh(): Observable<AppLanguage[]> {
    return this.load(true);
  }

  /** Current cached version hash, or null if never loaded successfully. */
  get currentVersion(): string | null {
    return this.loadedVersion;
  }

  /**
   * Starts a background poll that re-queries the languages API every
   * `intervalMs` milliseconds (default 5 minutes). Because `load(force=true)`
   * only mutates state when the server's `version` hash actually changes,
   * polling is cheap and admin updates propagate to already-open clients
   * without a hard refresh. Runs outside the Angular zone so it does not
   * trigger needless change detection cycles, and is a no-op on the server.
   */
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
