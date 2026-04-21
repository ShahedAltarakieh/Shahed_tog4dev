import { HttpClient } from '@angular/common/http';

import { Observable, catchError, of } from 'rxjs';

import { TranslateLoader } from '@ngx-translate/core';

/**
 * Loads i18n JSON files. If a translation file for the requested language is
 * missing (e.g. an admin added a new language but no JSON exists yet) the
 * loader silently falls back to the default language file ('en') so the app
 * never breaks with empty strings.
 */
export class CustomTranslateHttpLoader implements TranslateLoader {
  private static DEFAULT_LANG = 'en';

  constructor(
    private http: HttpClient,
    private prefix: string = '/assets/i18n/',
    private suffix: string = '.json',
  ) {}

  getTranslation(lang: string): Observable<any> {
    // Try the full BCP-47 code first (e.g. "pt-br.json"), then fall back to
    // the base language code (e.g. "pt.json"), then to the default language.
    const fullCode = (lang || '').toLowerCase();
    const baseCode = fullCode.split('-')[0];
    const defaultCode = CustomTranslateHttpLoader.DEFAULT_LANG;

    const tryLoad = (code: string, next: () => Observable<any>): Observable<any> =>
      this.http.get(`${this.prefix}${code}${this.suffix}`).pipe(catchError(() => next()));

    return tryLoad(fullCode, () => {
      if (baseCode && baseCode !== fullCode) {
        return tryLoad(baseCode, () => this.loadDefault(defaultCode, fullCode));
      }
      return this.loadDefault(defaultCode, fullCode);
    });
  }

  private loadDefault(defaultCode: string, requested: string): Observable<any> {
    if (requested === defaultCode) {
      return of({});
    }
    return this.http.get(`${this.prefix}${defaultCode}${this.suffix}`)
      .pipe(catchError(() => of({})));
  }
}

export function translateLoaderFactory(http: HttpClient) {
  return new CustomTranslateHttpLoader(http, 'app/assets/i18n/', '.json?v=9.1');
}
