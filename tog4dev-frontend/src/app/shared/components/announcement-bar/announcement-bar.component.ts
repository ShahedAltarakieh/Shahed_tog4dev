import { Component, OnInit, OnDestroy, AfterViewInit, Inject, PLATFORM_ID, NgZone, HostBinding } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { RouterLink } from '@angular/router';
import { AnnouncementService, Announcement } from 'app/shared/services/announcement/announcement.service';
import { StorageService } from 'app/core/storage/storage.service';

@Component({
  selector: 'app-announcement-bar',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './announcement-bar.component.html',
  styleUrl: './announcement-bar.component.scss'
})
export class AnnouncementBarComponent implements OnInit, AfterViewInit, OnDestroy {
  announcements: Announcement[] = [];
  currentIndex = 0;
  isPaused = false;
  isBrowser = false;
  transitioning = false;
  stickyTop = 0;
  isHeaderScrolled = false;

  @HostBinding('style.top.px')
  get hostStickyTop(): number {
    return this.stickyTop;
  }

  private rotateInterval: any;
  private touchStartX = 0;
  private touchEndX = 0;
  private headerObserver: ResizeObserver | null = null;
  private scrollHandler: (() => void) | null = null;
  private headerHeight = 0;

  readonly badgeColors: Record<string, string> = {
    'LIVE': '#ef4444',
    'INFO': '#3b82f6',
    'ALERT': '#f59e0b',
    'NEW': '#10b981'
  };

  constructor(
    private announcementService: AnnouncementService,
    public storageService: StorageService,
    private ngZone: NgZone,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
    this.isBrowser = isPlatformBrowser(this.platformId);
  }

  ngOnInit(): void {
    if (!this.isBrowser) return;

    const target = window.innerWidth < 768 ? 'mobile' : 'desktop';
    this.announcementService.getAnnouncements(target).subscribe({
      next: (items) => {
        this.announcements = items;
        if (this.announcements.length > 1) {
          this.startAutoRotate();
        }
      }
    });
  }

  ngAfterViewInit(): void {
    if (!this.isBrowser) return;
    this.observeHeaderHeight();
  }

  ngOnDestroy(): void {
    this.stopAutoRotate();
    if (this.headerObserver) {
      this.headerObserver.disconnect();
      this.headerObserver = null;
    }
    if (this.scrollHandler) {
      window.removeEventListener('scroll', this.scrollHandler);
      this.scrollHandler = null;
    }
  }

  get current(): Announcement | null {
    return this.announcements[this.currentIndex] || null;
  }

  get displayText(): string {
    if (!this.current) return '';
    if (window.innerWidth < 768 && this.current.short_text) {
      return this.current.short_text;
    }
    return this.current.text;
  }

  next(): void {
    if (this.announcements.length <= 1 || this.transitioning) return;
    this.transition(() => {
      this.currentIndex = (this.currentIndex + 1) % this.announcements.length;
    });
  }

  prev(): void {
    if (this.announcements.length <= 1 || this.transitioning) return;
    this.transition(() => {
      this.currentIndex = (this.currentIndex - 1 + this.announcements.length) % this.announcements.length;
    });
  }

  goTo(index: number): void {
    if (index === this.currentIndex || this.transitioning) return;
    this.transition(() => {
      this.currentIndex = index;
    });
  }

  onMouseEnter(): void {
    this.isPaused = true;
    this.stopAutoRotate();
  }

  onMouseLeave(): void {
    this.isPaused = false;
    if (this.announcements.length > 1) {
      this.startAutoRotate();
    }
  }

  onTouchStart(event: TouchEvent): void {
    this.touchStartX = event.changedTouches[0].screenX;
  }

  onTouchEnd(event: TouchEvent): void {
    this.touchEndX = event.changedTouches[0].screenX;
    this.handleSwipe();
  }

  isExternalLink(link: string | null): boolean {
    if (!link) return false;
    return link.startsWith('http://') || link.startsWith('https://');
  }

  private transition(changeFn: () => void): void {
    this.transitioning = true;
    setTimeout(() => {
      changeFn();
      setTimeout(() => {
        this.transitioning = false;
      }, 50);
    }, 200);
  }

  private handleSwipe(): void {
    const diff = this.touchStartX - this.touchEndX;
    const isRtl = this.storageService.siteLanguage$.value === 'ar';
    if (Math.abs(diff) > 50) {
      if ((diff > 0 && !isRtl) || (diff < 0 && isRtl)) {
        this.next();
      } else {
        this.prev();
      }
    }
  }

  private startAutoRotate(): void {
    this.stopAutoRotate();
    this.rotateInterval = setInterval(() => {
      this.next();
    }, 4500);
  }

  private stopAutoRotate(): void {
    if (this.rotateInterval) {
      clearInterval(this.rotateInterval);
      this.rotateInterval = null;
    }
  }

  private observeHeaderHeight(): void {
    const headerDesktop = document.querySelector('header.header') as HTMLElement;
    const headerMobile = document.querySelector('header.header-mobile') as HTMLElement;

    const measureHeaderHeight = () => {
      let height = 0;
      if (headerDesktop && window.getComputedStyle(headerDesktop).display !== 'none') {
        height = headerDesktop.getBoundingClientRect().height;
      } else if (headerMobile && window.getComputedStyle(headerMobile).display !== 'none') {
        height = headerMobile.getBoundingClientRect().height;
      }
      this.headerHeight = height;
    };

    const updateStickyTop = () => {
      const activeHeader = (headerDesktop && window.getComputedStyle(headerDesktop).display !== 'none')
        ? headerDesktop
        : headerMobile;
      const isHeaderSticky = activeHeader?.classList.contains('scrolled') ?? false;
      const newTop = isHeaderSticky ? this.headerHeight : 0;
      if (newTop !== this.stickyTop || isHeaderSticky !== this.isHeaderScrolled) {
        this.ngZone.run(() => {
          this.stickyTop = newTop;
          this.isHeaderScrolled = isHeaderSticky;
        });
      }
    };

    measureHeaderHeight();
    updateStickyTop();

    this.ngZone.runOutsideAngular(() => {
      const targets = [headerDesktop, headerMobile].filter(Boolean) as HTMLElement[];
      if (targets.length > 0) {
        this.headerObserver = new ResizeObserver(() => {
          measureHeaderHeight();
          updateStickyTop();
        });
        targets.forEach(t => this.headerObserver!.observe(t));
      }

      this.scrollHandler = () => {
        measureHeaderHeight();
        updateStickyTop();
      };
      window.addEventListener('scroll', this.scrollHandler, { passive: true });
    });
  }
}
