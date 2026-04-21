import { Injectable } from '@angular/core';

import { BehaviorSubject } from 'rxjs';

export interface AppLanguage {
  code: string;
  name: string;
  native_name: string;
  direction: 'ltr' | 'rtl';
  is_default: boolean;
  position?: number;
}

/**
 * Built-in fallback languages used when the API is unreachable. Mirrors the
 * legacy hard-coded EN/AR behaviour so existing pages keep working.
 */
export const FALLBACK_LANGUAGES: AppLanguage[] = [
  { code: 'en', name: 'English', native_name: 'English', direction: 'ltr', is_default: true,  position: 1 },
  { code: 'ar', name: 'Arabic',  native_name: 'العربية', direction: 'rtl', is_default: false, position: 2 },
];

@Injectable({
  providedIn: 'root'
})
export class StorageService {
  /** Current active language code (e.g. 'en', 'ar', 'fr'). */
  siteLanguage$: BehaviorSubject<string> = new BehaviorSubject<string>('en');

  /** All active languages from backend (or fallback). */
  availableLanguages$: BehaviorSubject<AppLanguage[]> = new BehaviorSubject<AppLanguage[]>(FALLBACK_LANGUAGES);

  /** Default language code. */
  defaultLanguage: string = 'en';

  constructor() { }

  /** Returns the AppLanguage object for the current code, or the default. */
  getCurrentLanguage(): AppLanguage {
    const code = this.siteLanguage$.value;
    const langs = this.availableLanguages$.value;
    return langs.find(l => l.code === code)
        || langs.find(l => l.code === this.defaultLanguage)
        || FALLBACK_LANGUAGES[0];
  }

  /** Lookup helper. */
  findLanguage(code: string): AppLanguage | undefined {
    return this.availableLanguages$.value.find(l => l.code === code);
  }

  /** True if the given code matches an active language. */
  isKnownCode(code: string): boolean {
    return !!this.findLanguage(code);
  }
}
