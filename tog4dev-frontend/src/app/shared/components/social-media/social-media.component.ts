import { AfterViewInit, Component, Inject, NgZone, OnDestroy, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser, NgClass } from '@angular/common';
import { StorageService } from 'app/core/storage/storage.service';

@Component({
    selector: 'app-social-media',
    standalone: true,
    imports: [NgClass],
    templateUrl: './social-media.component.html',
    styleUrl: './social-media.component.scss'
})
export class SocialMediaComponent implements AfterViewInit, OnDestroy {
  hidden = false;
  private observer: IntersectionObserver | null = null;
  private retryTimer: any = null;
  private readonly isBrowser: boolean;

  constructor(
    public storageService: StorageService,
    private ngZone: NgZone,
    @Inject(PLATFORM_ID) platformId: Object,
  ) {
    this.isBrowser = isPlatformBrowser(platformId);
  }

  ngAfterViewInit(): void {
    if (!this.isBrowser) return;
    this.attachFooterObserver();
  }

  private attachFooterObserver(retries = 10): void {
    const footer = document.querySelector('footer.footer');
    if (!footer) {
      if (retries > 0) {
        this.retryTimer = setTimeout(() => this.attachFooterObserver(retries - 1), 300);
      }
      return;
    }

    this.ngZone.runOutsideAngular(() => {
      this.observer = new IntersectionObserver((entries) => {
        const isVisible = entries[0]?.isIntersecting ?? false;
        if (isVisible !== !this.hidden) return;
        this.ngZone.run(() => this.hidden = isVisible);
      }, { rootMargin: '0px 0px -10% 0px', threshold: 0 });

      this.observer.observe(footer);
    });
  }

  ngOnDestroy(): void {
    if (this.retryTimer) clearTimeout(this.retryTimer);
    this.observer?.disconnect();
  }
}
