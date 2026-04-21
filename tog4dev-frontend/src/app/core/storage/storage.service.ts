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

  /**
   * Resolves a value out of a per-language map (e.g. legacy `Record<'en'|'ar', string>`
   * route tables) for the *current* site language. Falls back to the entry for the
   * default language and finally to the first map entry, so newly added languages
   * never produce broken routerLinks/URLs.
   */
  localized(map: { [key: string]: string } | null | undefined): string {
    if (!map) { return ''; }
    const lang = this.siteLanguage$.value;
    if (map[lang] !== undefined) { return map[lang]; }
    if (this.defaultLanguage && map[this.defaultLanguage] !== undefined) {
      return map[this.defaultLanguage];
    }
    if (map['en'] !== undefined) { return map['en']; }
    const keys = Object.keys(map);
    return keys.length ? map[keys[0]] : '';
  }
}
