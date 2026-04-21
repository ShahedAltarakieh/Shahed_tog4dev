import { Inject, Injectable, PLATFORM_ID, Renderer2, RendererFactory2 } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';

@Injectable({
  providedIn: 'root',
})
export class TextDirectionService {
  renderer: Renderer2;
  appDirection: string = 'rtl';

  constructor(
    @Inject(PLATFORM_ID) public platformId: Object,
    rendererFactory: RendererFactory2,
  ) {
    this.renderer = rendererFactory.createRenderer(null, null);
  }

  /**
   * Sets the text direction for the application. Direction is sourced from
   * the active language metadata (admin-managed in the database) — never
   * derived from the language code itself, so RTL languages added in the
   * future work without code changes. When direction is unknown we default
   * to LTR (the only safe default that does not silently flip the layout).
   * @param lang The language code (used for the html `lang` attribute).
   * @param direction Direction from the active language metadata.
   */
  setDirection(lang: string, direction?: 'ltr' | 'rtl') {
    const resolved: 'ltr' | 'rtl' = direction === 'rtl' || direction === 'ltr'
      ? direction
      : 'ltr';
    this.appDirection = resolved;

    // Only set the attribute if running in the browser
    if (isPlatformBrowser(this.platformId)) {
      this.renderer.setAttribute(document.documentElement, 'dir', resolved);
      this.renderer.setAttribute(document.documentElement, 'lang', lang);
    }
  }
}
