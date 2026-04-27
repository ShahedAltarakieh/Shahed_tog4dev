import { Component, OnDestroy, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators, ReactiveFormsModule } from '@angular/forms';
import { Meta, DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';

import { TranslateModule } from '@ngx-translate/core';
import { StorageService } from 'app/core/storage/storage.service';

import { ContactUsFormComponent } from 'app/shared/components/contact-us-form/contact-us-form.component';
import { Subject, takeUntil } from 'rxjs';
import { ContactInfo, ContactInfoService } from './services/contact-info.service';

@Component({
    selector: 'app-contact',
    imports: [ReactiveFormsModule, TranslateModule, ContactUsFormComponent],
    templateUrl: './contact.component.html',
    styleUrl: './contact.component.scss'
})
export class ContactComponent implements OnInit, OnDestroy {
  contactForm: FormGroup;
  isSubmitted: boolean = false;
  isLoading: boolean = false;
  destroy$ = new Subject<void>();

  info: ContactInfo | null = null;
  socials: { key: string; url: string; icon: string; label: string }[] = [];
  mapEmbedSafe: SafeResourceUrl | null = null;

  private socialMeta: Record<string, { icon: string; label: string }> = {
    facebook:  { icon: 'fab fa-facebook-f',     label: 'Facebook' },
    instagram: { icon: 'fab fa-instagram',      label: 'Instagram' },
    snapchat:  { icon: 'fab fa-snapchat-ghost', label: 'Snapchat' },
    twitter:   { icon: 'fab fa-x-twitter',      label: 'X / Twitter' },
    linkedin:  { icon: 'fab fa-linkedin-in',    label: 'LinkedIn' },
    youtube:   { icon: 'fab fa-youtube',        label: 'YouTube' },
    tiktok:    { icon: 'fab fa-tiktok',         label: 'TikTok' },
  };

  constructor(
    private fb: FormBuilder,
    private storageService: StorageService,
    public metaService: Meta,
    private contactInfoService: ContactInfoService,
    private sanitizer: DomSanitizer,
  ) {
    this.contactForm = this.fb.group({
      name: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      message: ['', Validators.required],
    });
  }

  ngOnInit(): void {
    this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(lang => {
      this.updateMetaTags();
      this.loadInfo(lang || 'en');
    });
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }

  private loadInfo(lang: string): void {
    this.contactInfoService.fetch(lang).subscribe({
      next: (info) => {
        this.info = info;
        this.socials = this.buildSocials(info.social_links);
        this.mapEmbedSafe = this.safeMapUrl(info.map_embed_url);
      },
      error: () => {
        // Reset to deterministic fallback state so the template renders defaults.
        this.info = null;
        this.socials = [];
        this.mapEmbedSafe = null;
      },
    });
  }

  /** Only trust Google Maps embed URLs (https). Anything else is rejected. */
  private safeMapUrl(url: string | null | undefined): SafeResourceUrl | null {
    if (!url) return null;
    const trimmed = url.trim();
    const allowed = /^https:\/\/(www\.)?google\.[a-z.]+\/maps[^\s'"<>]*$/i;
    if (!allowed.test(trimmed)) return null;
    return this.sanitizer.bypassSecurityTrustResourceUrl(trimmed);
  }

  private buildSocials(links: Record<string, string | undefined>): { key: string; url: string; icon: string; label: string }[] {
    if (!links) return [];
    return Object.keys(this.socialMeta)
      .filter(key => !!links[key])
      .map(key => ({
        key,
        url: links[key] as string,
        icon: this.socialMeta[key].icon,
        label: this.socialMeta[key].label,
      }));
  }

  /** Phone display: "+962 779 400 900" — keep digits, group 3-3-3 after country code */
  prettyPhone(raw: string | null | undefined): string {
    if (!raw) return '';
    const trimmed = raw.replace(/\s+/g, '');
    return trimmed;
  }

  /** Build the wa.me link from the WhatsApp number. */
  whatsappLink(): string {
    const num = (this.info?.whatsapp_number || '').replace(/\D/g, '');
    return num ? `https://wa.me/${num}` : '#';
  }

  updateMetaTags(): void {
    const isAr = this.storageService.siteLanguage$.value === 'ar';
    const desc = isAr
      ? 'نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم'
      : 'Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide.';

    this.metaService.updateTag({ name: 'description', content: desc });
    this.metaService.updateTag({ property: 'og:title', content: 'Together For Development | معاً للتنمية' });
    this.metaService.updateTag({ property: 'og:description', content: desc });
    this.metaService.updateTag({ property: 'og:image', content: 'https://tog4dev.com/app/assets/images/shared/logo.png' });
    this.metaService.updateTag({ property: 'og:url', content: typeof window !== 'undefined' ? window.location.href : '' });
    this.metaService.updateTag({ property: 'og:type', content: 'website' });
    this.metaService.updateTag({ name: 'twitter:card', content: 'https://tog4dev.com/app/assets/images/shared/logo.png' });
    this.metaService.updateTag({ name: 'twitter:title', content: 'Together For Development | معاً للتنمية' });
    this.metaService.updateTag({ name: 'twitter:description', content: desc });
    this.metaService.updateTag({ name: 'twitter:image', content: 'https://tog4dev.com/app/assets/images/shared/logo.png' });
  }
}
