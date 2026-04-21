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

  private readonly STORAGE_KEY = 'tog4dev.siteLanguage';

  constructor() {
    // Persist language selection to localStorage as a fallback when the URL
    // lacks a language prefix (e.g., bookmarked root path).
    if (typeof window !== 'undefined' && window.localStorage) {
      try {
        const stored = window.localStorage.getItem(this.STORAGE_KEY);
        if (stored) { this.siteLanguage$.next(stored); }
      } catch { /* storage unavailable */ }
      this.siteLanguage$.subscribe(code => {
        try { window.localStorage.setItem(this.STORAGE_KEY, code); } catch { /* ignore */ }
      });
    }
  }

  /** Returns the persisted language code if any (browser-only). */
  getStoredLanguage(): string | null {
    if (typeof window === 'undefined' || !window.localStorage) { return null; }
    try { return window.localStorage.getItem(this.STORAGE_KEY); } catch { return null; }
  }

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

  // Pick a per-language entry: current → default → 'en' → first available.
  // For language-preserving URLs, when we fall back to another language's
  // entry we rewrite a leading `/{lang}/` prefix to the active language so
  // `fr` users stay on `/fr/...` instead of being silently swapped to
  // `/en/...`. Non-string-shaped values are returned as-is.
  localized(map: { [key: string]: string } | null | undefined): string {
    if (!map) { return ''; }
    const lang = this.siteLanguage$.value;
    if (map[lang] !== undefined) { return map[lang]; }
    let fallbackKey = '';
    if (this.defaultLanguage && map[this.defaultLanguage] !== undefined) {
      fallbackKey = this.defaultLanguage;
    } else if (map['en'] !== undefined) {
      fallbackKey = 'en';
    } else {
      const keys = Object.keys(map);
      if (!keys.length) { return ''; }
      fallbackKey = keys[0];
    }
    const value = map[fallbackKey];
    if (typeof value !== 'string' || !lang || lang === fallbackKey) { return value; }
    const prefixRe = new RegExp('^(/?)' + fallbackKey + '(/|$)');
    return prefixRe.test(value) ? value.replace(prefixRe, `$1${lang}$2`) : value;
  }
}
