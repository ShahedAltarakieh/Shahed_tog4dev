import { AfterViewInit, Component, HostListener, Inject, NgZone, OnDestroy, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser, NgClass } from '@angular/common';

@Component({
  selector: 'app-scroll-to-top',
  standalone: true,
  imports: [NgClass],
  template: `
    <button
      type="button"
      class="scroll-to-top-btn"
      [ngClass]="{ 'visible': visible }"
      [attr.aria-label]="'Scroll to top'"
      (click)="scrollTop()"
    >
      <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
        <path d="M12 5l-7 7h4v7h6v-7h4z" fill="currentColor"/>
      </svg>
    </button>
  `,
  styles: [`
    :host {
      position: fixed;
      bottom: 92px;
      inset-inline-end: 22px;
      z-index: 999;
      pointer-events: none;
    }
    .scroll-to-top-btn {
      pointer-events: auto;
      width: 46px;
      height: 46px;
      border-radius: 50%;
      border: none;
      background: #13585D;
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 22px rgba(13, 67, 71, 0.30);
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transform: translateY(12px) scale(0.9);
      transition: opacity 240ms ease, transform 240ms ease, visibility 240ms ease, background-color 200ms ease;
    }
    .scroll-to-top-btn.visible {
      opacity: 1;
      visibility: visible;
      transform: translateY(0) scale(1);
    }
    .scroll-to-top-btn:hover {
      background: #0d4347;
      transform: translateY(-2px) scale(1.04);
    }
    .scroll-to-top-btn:focus-visible {
      outline: 3px solid rgba(254, 205, 15, 0.55);
      outline-offset: 3px;
    }
    @media (max-width: 576px) {
      :host { bottom: 80px; inset-inline-end: 16px; }
      .scroll-to-top-btn { width: 42px; height: 42px; }
    }
  `]
})
export class ScrollToTopComponent implements AfterViewInit, OnDestroy {
  visible = false;
  private readonly isBrowser: boolean;
  private boundScroll?: () => void;

  constructor(@Inject(PLATFORM_ID) platformId: Object, private ngZone: NgZone) {
    this.isBrowser = isPlatformBrowser(platformId);
  }

  ngAfterViewInit(): void {
    if (!this.isBrowser) return;
    this.ngZone.runOutsideAngular(() => {
      this.boundScroll = () => this.onScroll();
      window.addEventListener('scroll', this.boundScroll, { passive: true });
      this.onScroll();
    });
  }

  private onScroll(): void {
    const shouldShow = (window.scrollY || document.documentElement.scrollTop || 0) > 320;
    if (shouldShow !== this.visible) {
      this.ngZone.run(() => (this.visible = shouldShow));
    }
  }

  scrollTop(): void {
    if (!this.isBrowser) return;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  ngOnDestroy(): void {
    if (this.isBrowser && this.boundScroll) {
      window.removeEventListener('scroll', this.boundScroll);
    }
  }
}
