import { Component, OnInit, OnDestroy, AfterViewInit, ElementRef, NgZone, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser, NgClass, NgIf, NgFor } from '@angular/common';

import { StorageService } from 'app/core/storage/storage.service';
import { TranslatePipe } from "@ngx-translate/core";
import { NgoverseSliderComponent } from "./components/ngoverse-slider/ngoverse-slider.component";
import { ContactUsFormComponent } from "../../shared/components/contact-us-form/contact-us-form.component";
import { Subject, takeUntil } from 'rxjs';
import { Meta } from '@angular/platform-browser';

interface PlanContent {
  badge: string;
  name: string;
  description: string;
  price_monthly: string;
  price_yearly: string;
  features: string[];
  note: string;
  cta: string;
}

interface Plan {
  key: 'silver' | 'gold' | 'platinum';
  icon: string;
  featured?: boolean;
  en: PlanContent;
  ar: PlanContent;
}

@Component({
  selector: 'app-ngoverse',
  standalone: true,
  imports: [
    NgIf,
    NgFor,
    NgClass,
    TranslatePipe,
    NgoverseSliderComponent,
    ContactUsFormComponent,
  ],
  templateUrl: './ngoverse.component.html',
  styleUrl: './ngoverse.component.scss'
})
export class NgoverseComponent implements OnInit, OnDestroy, AfterViewInit {
  destroy$ = new Subject<void>();
  currentLang: 'ar' | 'en' = 'en';
  private isBrowser: boolean;
  private observer: IntersectionObserver | null = null;

  plans: Plan[] = [
    {
      key: 'silver',
      icon: 'fas fa-medal',
      en: {
        badge: 'Starter',
        name: 'Silver Plan',
        description: 'Best for NGOs looking to launch and manage their online presence.',
        price_monthly: '$100',
        price_yearly: '$1,000',
        features: [
          'Dynamic News Website',
          'Online Clients Collection',
          'Full Payment Gateway Integration',
          'Smart Admin Dashboard',
          'Easy Content Management & Updates',
          'Professional UX/UI Experience',
          'Responsive Design (All Devices)',
          'Fast News Updates Capability',
          'Ready For ERP Integration',
          'Automated Invoice & Contract Issuing',
          '"Gift of Goodness" Service',
          'Multi-Currency & Multilingual Support',
          'Reporting Console',
          'Online Support per the final SLA',
        ],
        note: 'Note: Custom UX/UI theme, domain management, hosting, backup management, and integration with other systems are excluded.',
        cta: 'Get Started',
      },
      ar: {
        badge: 'البداية',
        name: 'الخطة الفضية',
        description: 'الأفضل للمنظمات غير الربحية التي ترغب في إطلاق موقعها الإلكتروني.',
        price_monthly: '100$',
        price_yearly: '1000$',
        features: [
          'موقع إخباري ديناميكي',
          'تحصيل العمليات المالية عبر الإنترنت',
          'دمج كامل مع بوابات الدفع الإلكتروني',
          'لوحة تحكم ذكية للإدارة',
          'إدارة وتحديث المحتوى بسهولة',
          'تجربة مستخدم وتصميم واجهة احترافي',
          'تصميم متجاوب مع جميع الأجهزة',
          'إمكانية تحديث الأخبار بسرعة',
          'جاهز للتكامل مع أنظمة الـ ERP',
          'إصدار العقود والفواتير بشكل آلي',
          'خدمة "هدية الخير"',
          'دعم العملات المتعددة وتعدد اللغات',
          'وحدة تقارير إدارية',
          'دعم فني عبر الإنترنت وفقاً لاتفاقية مستوى الخدمة (SLA)',
        ],
        note: 'ملاحظة: لا تشمل الخطة تصميم مخصص للواجهة، إدارة النطاق، الاستضافة، إدارة النسخ الاحتياطي أو التكامل مع أنظمة أخرى.',
        cta: 'ابدأ الآن',
      },
    },
    {
      key: 'gold',
      icon: 'fas fa-crown',
      featured: true,
      en: {
        badge: 'Most Popular',
        name: 'Gold Plan',
        description: 'Perfect for NGOs that need full operational and financial resource management.',
        price_monthly: '$150',
        price_yearly: '$1,500',
        features: [
          'Financial ERP System tailored for NGOs',
          'Advanced Accounting Module',
          'CRM for Client Management',
          'Inventory Management System',
          'Website & Payment Gateway Integration',
          'Automated Invoicing, Contracts & Receipts',
          'Customizable Client Communication Templates',
          'Built on the Odoo Platform',
          'Includes 3 User Licenses',
          'Real-time Financial Dashboards & Reports',
          'Online Support per the final SLA',
        ],
        note: 'Note: Custom UX/UI theme, domain management, hosting, backup management, and integration with other systems are excluded.',
        cta: 'Choose Gold',
      },
      ar: {
        badge: 'الأكثر شيوعاً',
        name: 'الخطة الذهبية',
        description: 'مثالية للمنظمات غير الربحية التي تحتاج إلى إدارة كاملة للعمليات والعملاء.',
        price_monthly: '150$',
        price_yearly: '1500$',
        features: [
          'نظام ERP مالي مصمم خصيصاً للمنظمات غير الربحية',
          'وحدة محاسبية متقدمة',
          'نظام CRM لإدارة العملاء',
          'نظام إدارة المخزون',
          'تكامل مع الموقع الإلكتروني وبوابة الدفع',
          'إصدار الفواتير والعقود والإيصالات بشكل آلي',
          'قوالب تواصل قابلة للتخصيص مع العملاء',
          'مبني على منصة أودو',
          'يشمل 3 تراخيص مستخدمين',
          'لوحات تحكم وتقارير مالية لحظية',
          'دعم فني عبر الإنترنت وفقاً لاتفاقية مستوى الخدمة (SLA)',
        ],
        note: 'ملاحظة: لا تشمل الخطة تصميم واجهة مخصصة، إدارة النطاق، الاستضافة، إدارة النسخ الاحتياطي، أو التكامل مع أنظمة أخرى.',
        cta: 'اختر الذهبية',
      },
    },
    {
      key: 'platinum',
      icon: 'fas fa-gem',
      en: {
        badge: 'All-In-One',
        name: 'Platinum Plan',
        description: 'The ultimate solution combining Silver and Gold plans for full-scale growth.',
        price_monthly: '$225',
        price_yearly: '$2,250',
        features: [
          'Everything in the Silver Plan',
          'Everything in the Gold Plan',
          'Priority Support (faster response times) per SLA',
          'Advanced & integrated reporting and analytics',
          'Unlimited Projects & Online Campaigns',
        ],
        note: 'Note: Custom UX/UI theme, domain management, hosting, backup management, and integration with other systems are excluded.',
        cta: 'Go Platinum',
      },
      ar: {
        badge: 'الكل في واحد',
        name: 'الخطة البلاتينية',
        description: 'الحل الأمثل حيث يجمع بين الخطة الفضية والخطة الذهبية لتحقيق نمو شامل.',
        price_monthly: '225$',
        price_yearly: '2250$',
        features: [
          'كل ما هو متوفر في الخطة الفضية',
          'كل ما هو متوفر في الخطة الذهبية',
          'دعم فني ذو أولوية (استجابات أسرع) وفقاً لاتفاقية مستوى الخدمة (SLA)',
          'تقارير وتحليلات متقدمة ومتكاملة',
          'عدد غير محدود من المشاريع والحملات الإلكترونية',
        ],
        note: 'ملاحظة: لا تشمل الخطة تصميم واجهة مخصصة، إدارة النطاق، الاستضافة، إدارة النسخ الاحتياطي، أو التكامل مع أنظمة أخرى.',
        cta: 'اختر البلاتينية',
      },
    },
  ];

  constructor(
    public storageService: StorageService,
    public metaService: Meta,
    private el: ElementRef,
    private ngZone: NgZone,
    @Inject(PLATFORM_ID) platformId: Object,
  ) {
    this.isBrowser = isPlatformBrowser(platformId);
  }

  ngOnInit(): void {
    this.currentLang = (this.storageService.siteLanguage$.value === 'ar') ? 'ar' : 'en';
    this.storageService.siteLanguage$.pipe(takeUntil(this.destroy$)).subscribe(lang => {
      this.currentLang = (lang === 'ar') ? 'ar' : 'en';
      this.updateMetaTags();
    });
  }

  ngAfterViewInit(): void {
    if (this.isBrowser) {
      this.setupScrollAnimations();
    }
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
    this.observer?.disconnect();
  }

  setupScrollAnimations(): void {
    if (!this.isBrowser) return;
    this.observer?.disconnect();

    this.ngZone.runOutsideAngular(() => {
      this.observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
            this.observer?.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

      const sections = this.el.nativeElement.querySelectorAll('.animate-on-scroll');
      sections.forEach((section: Element) => this.observer?.observe(section));
    });
  }

  scrollToContact(event: Event): void {
    event.preventDefault();
    if (!this.isBrowser) return;
    const el = document.getElementById('ngoverse-contact');
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  scrollToPlans(event: Event): void {
    event.preventDefault();
    if (!this.isBrowser) return;
    const el = document.getElementById('ngoverse-plans');
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  updateMetaTags(): void {
    const desc = (this.currentLang === 'ar')
      ? 'نقدّم حلولًا ذكية تخدم الإنسان، وتُمكّن المؤسسات من تنفيذ مشاريع تنموية بجودة وشفافية واستدامة حول العالم'
      : 'Smart solutions for people and institutions to build impactful, transparent, and lasting development worldwide.';
    this.metaService.updateTag({ name: 'description', content: desc });
    this.metaService.updateTag({ property: 'og:title', content: 'Together For Development | معاً للتنمية' });
    this.metaService.updateTag({ property: 'og:description', content: desc });
    this.metaService.updateTag({ property: 'og:image', content: 'https://tog4dev.com/app/assets/images/shared/logo.png' });
    this.metaService.updateTag({ property: 'og:url', content: typeof window !== 'undefined' ? window.location.href : '' });
    this.metaService.updateTag({ property: 'og:type', content: 'website' });
    this.metaService.updateTag({ name: 'twitter:card', content: 'summary_large_image' });
    this.metaService.updateTag({ name: 'twitter:title', content: 'Together For Development | معاً للتنمية' });
    this.metaService.updateTag({ name: 'twitter:description', content: desc });
    this.metaService.updateTag({ name: 'twitter:image', content: 'https://tog4dev.com/app/assets/images/shared/logo.png' });
  }
}
