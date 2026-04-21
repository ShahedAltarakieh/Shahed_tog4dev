import { HttpClient } from '@angular/common/http';
import { Injector } from '@angular/core';

import { Observable, catchError, of } from 'rxjs';

import { TranslateLoader } from '@ngx-translate/core';

import { StorageService } from './core/storage/storage.service';

// Loader that tries: full BCP-47 → base code → runtime default → 'en'.
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
    const chain: string[] = [];
    [fullCode, baseCode, runtimeDefault, CustomTranslateHttpLoader.HARD_FALLBACK].forEach(c => {
      if (c && !chain.includes(c)) { chain.push(c); }
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
      try { return injector.get(StorageService).defaultLanguage || 'en'; }
      catch { return 'en'; }
    },
  );
}
