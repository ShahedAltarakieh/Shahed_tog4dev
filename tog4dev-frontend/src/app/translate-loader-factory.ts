import { HttpClient } from '@angular/common/http';
import { Injector } from '@angular/core';

import { Observable, catchError, of } from 'rxjs';

import { TranslateLoader } from '@ngx-translate/core';

import { StorageService } from './core/storage/storage.service';

/**
 * Loads i18n JSON files for a language. The fallback chain is, in order:
 *   1. The full BCP-47 code requested (e.g. `pt-br.json`)
 *   2. The base language code (e.g. `pt.json`)
 *   3. The currently-active default language file as reported by the
 *      backend `/api/v1/languages` payload (so the admin can rename or
 *      change the default without the frontend hard-coding `en`).
 *   4. The hard-coded `en.json` as a final safety net.
 *
 * If every step fails we resolve with `{}` so the app keeps rendering keys
 * instead of throwing.
 */
export class CustomTranslateHttpLoader implements TranslateLoader {
  private static HARD_FALLBACK = 'en';

  constructor(
    private http: HttpClient,
    private prefix: string = '/assets/i18n/',
    private suffix: string = '.json',
    private getRuntimeDefault: () => string = () => CustomTranslateHttpLoader.HARD_FALLBACK,
  ) {}

  getTranslation(lang: string): Observable<any> {
    const fullCode = (lang || '').toLowerCase();
    const baseCode = fullCode.split('-')[0];
    const runtimeDefault = (this.getRuntimeDefault() || '').toLowerCase();
    const hardFallback = CustomTranslateHttpLoader.HARD_FALLBACK;

    // De-duplicate so we never request the same file twice in a row.
    const chain: string[] = [];
    [fullCode, baseCode, runtimeDefault, hardFallback].forEach(code => {
      if (code && !chain.includes(code)) { chain.push(code); }
    });

    return this.tryChain(chain, 0);
  }

  private tryChain(chain: string[], index: number): Observable<any> {
    if (index >= chain.length) { return of({}); }
    return this.http.get(`${this.prefix}${chain[index]}${this.suffix}`).pipe(
      catchError(() => this.tryChain(chain, index + 1)),
    );
  }
}

export function translateLoaderFactory(http: HttpClient, injector: Injector) {
  return new CustomTranslateHttpLoader(
    http,
    'app/assets/i18n/',
    '.json?v=9.1',
    () => {
      // Resolved lazily so the loader picks up admin changes to the default
      // language without needing the bootstrap order to be perfect.
      try {
        return injector.get(StorageService).defaultLanguage || 'en';
      } catch {
        return 'en';
      }
    },
  );
}
