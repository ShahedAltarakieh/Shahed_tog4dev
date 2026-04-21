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
   * Sets the text direction for the application.
   * @param lang The language code (used for the html `lang` attribute).
   * @param direction Optional explicit direction ('ltr' | 'rtl'). When omitted
   *                  falls back to the legacy AR=RTL/everything-else=LTR rule
   *                  for backward compatibility with callers that pass only a
   *                  language code.
   */
  setDirection(lang: string, direction?: 'ltr' | 'rtl') {
    const resolved = direction ?? (lang === 'ar' ? 'rtl' : 'ltr');
    this.appDirection = resolved;

    // Only set the attribute if running in the browser
    if (isPlatformBrowser(this.platformId)) {
      this.renderer.setAttribute(document.documentElement, 'dir', resolved);
      this.renderer.setAttribute(document.documentElement, 'lang', lang);
    }
  }
}
