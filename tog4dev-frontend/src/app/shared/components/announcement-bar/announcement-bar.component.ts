import { Component, OnInit, OnDestroy, Inject, PLATFORM_ID } from '@angular/core';
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
export class AnnouncementBarComponent implements OnInit, OnDestroy {
  announcements: Announcement[] = [];
  currentIndex = 0;
  isPaused = false;
  isBrowser = false;
  transitioning = false;

  private rotateInterval: any;
  private touchStartX = 0;
  private touchEndX = 0;

  readonly badgeColors: Record<string, string> = {
    'LIVE': '#ef4444',
    'INFO': '#3b82f6',
    'ALERT': '#f59e0b',
    'NEW': '#10b981'
  };

  constructor(
    private announcementService: AnnouncementService,
    public storageService: StorageService,
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

  ngOnDestroy(): void {
    this.stopAutoRotate();
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
}
