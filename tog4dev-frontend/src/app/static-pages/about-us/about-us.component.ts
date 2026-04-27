import { Component, OnInit, OnDestroy, Inject, PLATFORM_ID, AfterViewInit, ElementRef, NgZone } from '@angular/core';
import { isPlatformBrowser, CommonModule } from '@angular/common';
import { StorageService } from 'app/core/storage/storage.service';
import { TranslatePipe } from '@ngx-translate/core';
import { Meta } from '@angular/platform-browser';
import { AboutService, AboutPageData, AboutSection } from './services/about.service';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-about-us',
  standalone: true,
  imports: [CommonModule, TranslatePipe],
  templateUrl: './about-us.component.html',
  styleUrl: './about-us.component.scss'
})
export class AboutUsComponent implements OnInit, OnDestroy, AfterViewInit {
  pageData: AboutPageData | null = null;
  sections: AboutSection[] = [];
  loading = true;
  error = false;
  currentLang: 'ar' | 'en' = 'ar';
  animatedCounters: Map<number, number> = new Map();
  private isBrowser: boolean;
  private sub: Subscription | null = null;
  private langSub: Subscription | null = null;
  private observer: IntersectionObserver | null = null;

  constructor(
    public metaService: Meta,
    public storageService: StorageService,
    private aboutService: AboutService,
    private el: ElementRef,
    private ngZone: NgZone,
    @Inject(PLATFORM_ID) platformId: Object
  ) {
    this.isBrowser = isPlatformBrowser(platformId);
  }

  ngOnInit(): void {
    const initialLang = this.storageService.siteLanguage$.getValue();
    this.currentLang = (initialLang === 'en') ? 'en' : 'ar';
    this.loadAboutPage();

    if (this.isBrowser) {
      this.langSub = this.storageService.siteLanguage$.subscribe((lang) => {
        const next = (lang === 'en') ? 'en' : 'ar';
        if (next !== this.currentLang) {
          this.currentLang = next;
          this.loadAboutPage();
        }
      });
    }
  }

  ngAfterViewInit(): void {
    if (this.isBrowser) {
      this.setupScrollAnimations();
    }
  }

  ngOnDestroy(): void {
    this.sub?.unsubscribe();
    this.langSub?.unsubscribe();
    this.observer?.disconnect();
  }

  loadAboutPage(): void {
    this.sub?.unsubscribe();
    this.loading = true;
    this.error = false;

    this.sub = this.aboutService.getAboutPage(this.currentLang, 'JO').subscribe({
      next: (data) => {
        this.pageData = data;
        this.sections = data?.sections || [];
        this.loading = false;
        if (data?.meta) {
          this.updateMetaTags(data.meta);
        }
        setTimeout(() => this.setupScrollAnimations(), 100);
      },
      error: () => {
        this.loading = false;
        this.error = true;
      }
    });
  }

  setupScrollAnimations(): void {
    if (!this.isBrowser) return;
    this.observer?.disconnect();

    this.ngZone.runOutsideAngular(() => {
      this.observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
            if (entry.target.classList.contains('stats-section')) {
              this.ngZone.run(() => this.startCounters());
            }
            this.observer?.unobserve(entry.target);
          }
        });
      }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

      const sections = this.el.nativeElement.querySelectorAll('.animate-on-scroll');
      sections.forEach((section: Element) => this.observer?.observe(section));
    });
  }

  startCounters(): void {
    const statsSection = this.getSectionByKey('stats');
    if (!statsSection) return;

    statsSection.items.forEach((item) => {
      const target = this.parseNumber(item.value);
      if (target === 0) {
        this.animatedCounters.set(item.id, 0);
        return;
      }

      let current = 0;
      const duration = 2000;
      const steps = 60;
      const increment = target / steps;
      const stepTime = duration / steps;

      const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
          current = target;
          clearInterval(timer);
        }
        this.animatedCounters.set(item.id, Math.floor(current));
      }, stepTime);
    });
  }

  parseNumber(value: string): number {
    if (!value) return 0;
    const arabicToAscii = value.replace(/[٠-٩]/g, (d) =>
      String.fromCharCode(d.charCodeAt(0) - 0x0660 + 48)
    );
    const cleaned = arabicToAscii.replace(/[^0-9]/g, '');
    return parseInt(cleaned, 10) || 0;
  }

  extractNumericParts(value: string): { prefix: string; number: string; suffix: string } {
    if (!value) return { prefix: '', number: '', suffix: '' };
    const normalized = value.replace(/[٠-٩]/g, (d) =>
      String.fromCharCode(d.charCodeAt(0) - 0x0660 + 48)
    );
    const match = normalized.match(/^([^0-9]*?)([\d,.\s]+)(.*)$/);
    if (!match) return { prefix: '', number: value, suffix: '' };
    return { prefix: match[1], number: match[2].trim(), suffix: match[3] };
  }

  formatCounter(itemId: number, originalValue: string): string {
    const count = this.animatedCounters.get(itemId);
    if (count === undefined) return originalValue;

    const parts = this.extractNumericParts(originalValue);
    const formatted = count.toLocaleString();
    return `${parts.prefix}${formatted}${parts.suffix}`;
  }

  getSectionByKey(key: string): AboutSection | undefined {
    return this.sections.find(s => s.section_key === key);
  }

  hasContent(section: AboutSection): boolean {
    if (!section) return false;
    const hasText = !!(section.title || section.subtitle || section.body);
    const hasItems = !!(section.items && section.items.length > 0);
    const hasMedia = !!(section.image || section.video_url);
    const hasCta = !!(section.cta_text && section.cta_link);
    return hasText || hasItems || hasMedia || hasCta;
  }

  updateMetaTags(meta: { title: string; description: string }): void {
    if (meta.description) {
      this.metaService.updateTag({ name: 'description', content: meta.description });
      this.metaService.updateTag({ property: 'og:description', content: meta.description });
      this.metaService.updateTag({ name: 'twitter:description', content: meta.description });
    }
    if (meta.title) {
      this.metaService.updateTag({ property: 'og:title', content: meta.title });
      this.metaService.updateTag({ name: 'twitter:title', content: meta.title });
    }
    this.metaService.updateTag({ property: 'og:type', content: 'website' });
    this.metaService.updateTag({ name: 'twitter:card', content: 'summary_large_image' });
    this.metaService.updateTag({
      property: 'og:url',
      content: typeof window !== 'undefined' ? window.location.href : ''
    });
  }
}
