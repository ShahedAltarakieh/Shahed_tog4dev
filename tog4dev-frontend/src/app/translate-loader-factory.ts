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
    const langCode = (lang || '').split('-')[0];
    return this.http.get(`${this.prefix}${langCode}${this.suffix}`).pipe(
      catchError(() => {
        if (langCode === CustomTranslateHttpLoader.DEFAULT_LANG) {
          return of({});
        }
        return this.http.get(`${this.prefix}${CustomTranslateHttpLoader.DEFAULT_LANG}${this.suffix}`)
          .pipe(catchError(() => of({})));
      }),
    );
  }
}

export function translateLoaderFactory(http: HttpClient) {
  return new CustomTranslateHttpLoader(http, 'app/assets/i18n/', '.json?v=9.1');
}
