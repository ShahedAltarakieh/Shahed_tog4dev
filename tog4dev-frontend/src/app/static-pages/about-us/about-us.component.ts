import { Component, OnInit, OnDestroy, Inject, PLATFORM_ID } from '@angular/core';
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
export class AboutUsComponent implements OnInit, OnDestroy {
  pageData: AboutPageData | null = null;
  sections: AboutSection[] = [];
  loading = true;
  error = false;
  private isBrowser: boolean;

  private sub: Subscription | null = null;

  constructor(
    public metaService: Meta,
    public storageService: StorageService,
    private aboutService: AboutService,
    @Inject(PLATFORM_ID) platformId: Object
  ) {
    this.isBrowser = isPlatformBrowser(platformId);
  }

  ngOnInit(): void {
    if (this.isBrowser) {
      this.loadAboutPage();
    } else {
      this.loading = false;
    }
  }

  ngOnDestroy(): void {
    this.sub?.unsubscribe();
  }

  loadAboutPage(): void {
    const lang = (this.storageService.siteLanguage$.value === 'en') ? 'en' : 'ar';
    this.loading = true;
    this.error = false;

    this.sub = this.aboutService.getAboutPage(lang as 'ar' | 'en', 'JO').subscribe({
      next: (data) => {
        this.pageData = data;
        this.sections = data?.sections || [];
        this.loading = false;
        if (data?.meta) {
          this.updateMetaTags(data.meta);
        }
      },
      error: () => {
        this.loading = false;
        this.error = true;
      }
    });
  }

  getSectionByKey(key: string): AboutSection | undefined {
    return this.sections.find(s => s.section_key === key);
  }

  updateMetaTags(meta: { title: string; description: string; og_image: string }): void {
    if (meta.description) {
      this.metaService.updateTag({ name: 'description', content: meta.description });
      this.metaService.updateTag({ property: 'og:description', content: meta.description });
      this.metaService.updateTag({ name: 'twitter:description', content: meta.description });
    }
    if (meta.title) {
      this.metaService.updateTag({ property: 'og:title', content: meta.title });
      this.metaService.updateTag({ name: 'twitter:title', content: meta.title });
    }
    if (meta.og_image) {
      this.metaService.updateTag({ property: 'og:image', content: meta.og_image });
      this.metaService.updateTag({ name: 'twitter:image', content: meta.og_image });
    }
    this.metaService.updateTag({ property: 'og:type', content: 'website' });
    this.metaService.updateTag({ name: 'twitter:card', content: 'summary_large_image' });
    this.metaService.updateTag({
      property: 'og:url',
      content: typeof window !== 'undefined' ? window.location.href : ''
    });
  }
}
