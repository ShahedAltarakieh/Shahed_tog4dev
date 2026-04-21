import { Component, ElementRef, HostListener, OnInit, Inject, PLATFORM_ID } from '@angular/core';
import {Location, NgClass, isPlatformBrowser} from '@angular/common';
import { NavigationService } from 'app/shared/services/navigation/navigation.service';
import {Router, RouterLink} from '@angular/router';

import { TranslateModule, TranslateService } from '@ngx-translate/core';

import { AuthService } from 'app/auth/services/auth.service';
import { CookieService } from 'ngx-cookie-service';
import { StorageService, AppLanguage } from 'app/core/storage/storage.service';

import { routeTranslations } from 'app/route-translations';
import { BasketService } from 'app/shared/services/basket/basket.service';
import { BurgerMenuComponent } from './components/burger-menu/burger-menu.component';
import { NgbCollapseModule } from '@ng-bootstrap/ng-bootstrap';
import { SocialMediaComponent } from "../../shared/components/social-media/social-media.component";
import {LogoutService} from "../../shared/services/logout/logout.service";
import {WhatsAppComponent} from "../../shared/components/whats-app/whats-app.component";

@Component({
    selector: 'app-header',
    imports: [RouterLink, TranslateModule, BurgerMenuComponent, NgbCollapseModule, SocialMediaComponent, WhatsAppComponent, NgClass],
    templateUrl: './header.component.html',
    styleUrl: './header.component.scss'
})
export class HeaderComponent implements OnInit {
  homeRoutes: Record<string , string> = {
    ar: 'ar',
    en: 'en'
  };
  ngOverseRoutes: Record<string , string> = {
    ar: 'ar/عالم-المنظمات',
    en: 'en/ngoverse'
  };
  contactUsRoutes: Record<string , string> = {
    ar: 'ar/تواصل-معنا',
    en: 'en/contact-us'
  };
  projectsRoutes: Record<string , string> = {
    ar: 'ar/المشاريع-الفردية',
    en: 'en/individual-projects'
  };
  organizationRoutes: Record<string , string> = {
    ar: 'ar/مشاريع-المنظمات',
    en: 'en/organizations-projects'
  };
  loginRoutes: Record<string, string> = {
    ar: 'ar/تسجيل-الدخول',
    en: 'en/login',
  };
  signupRoutes: Record<string, string> = {
    ar: 'ar/إنشاء-حساب',
    en: 'en/signup',
  };
  crowdfundingRoutes: Record<string, string> = {
    ar: 'ar/التمويل-الجماعي',
    en: 'en/crowdfunding',
  };
  newsGalleryRoutes: Record<string, string> = {
    ar: 'ar/الأخبار-والمعرض',
    en: 'en/news-gallery',
  };
  newsRoutes: Record<string, string> = {
    ar: 'ar/الأخبار',
    en: 'en/news',
  };
  photosRoutes: Record<string, string> = {
    ar: 'ar/الصور',
    en: 'en/photos',
  };
  videosRoutes: Record<string, string> = {
    ar: 'ar/الفيديو',
    en: 'en/videos',
  };
  aboutUsRoutes: Record<string, string> = {
    ar: 'ar/من-نحن',
    en: 'en/about-us',
  };
  profileRoutes: Record<string, string> = {
    ar: 'ar/تعديل-حساب',
    en: 'en/edit-profile',
  };
  basketRoutes: Record<string, string> = {
    ar: 'ar/السلة',
    en: 'en/basket',
  };
  historyRoutes: Record<string, string> = {
    ar: 'ar/الاشتراكات',
    en: 'en/subscriptions',
  };

  isDropdownExpanded = false;
  isNavDropdownOpen = false;
  isMobileMenuCollapse = true;
  isScrolled: boolean = false;
  hideSocialMedia: boolean = false;
  isLanguageDropdownOpen = false;

  @HostListener('document:click', ['$event.target'])
  onOutsideClick(target: HTMLElement) {
    if ((this.elementRef.nativeElement as HTMLElement).querySelector('.profile-box') && !(this.elementRef.nativeElement as HTMLElement).querySelector('.profile-box')!.contains(target)) {
      this.isDropdownExpanded = false;
    }
    if (!(this.elementRef.nativeElement as HTMLElement).querySelector('.nav-dropdown')?.contains(target)) {
      this.isNavDropdownOpen = false;
    }
    if (!(this.elementRef.nativeElement as HTMLElement).querySelector('.language-change')?.contains(target)) {
      this.isLanguageDropdownOpen = false;
    }
  }

  toggleNavDropdown(event: Event): void {
    event.preventDefault();
    event.stopPropagation();
    this.isNavDropdownOpen = !this.isNavDropdownOpen;
  }

  @HostListener('window:scroll', [])
  onWindowScroll() {
    // Change the header style when the scroll position is greater than 50px
    if (typeof window === 'undefined') {
      this.isScrolled = false;
      return;
    }
    this.isScrolled = window.scrollY > 50;

    // Hide side social media once user reaches the our-partners section
    const partners = document.getElementById('our-partners');
    if (partners) {
      const rect = partners.getBoundingClientRect();
      this.hideSocialMedia = rect.top <= window.innerHeight * 0.85;
    } else {
      this.hideSocialMedia = false;
    }
  }

  constructor(
    public storageService: StorageService,
    public location: Location,
    public authService: AuthService,
    public logoutService: LogoutService,
    public basketService: BasketService,
    public router: Router,
    public elementRef: ElementRef,
    public cookieService: CookieService,
    public navService: NavigationService,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.navService.load().subscribe();
      // Initialize scroll-derived state in case page loads at non-zero scroll
      setTimeout(() => this.onWindowScroll(), 0);
    }
  }

  navVisible(key: string): boolean {
    return this.navService.isVisible(key);
  }

  /** Active languages list from storage. */
  get availableLanguages(): AppLanguage[] {
    return this.storageService.availableLanguages$.value;
  }

  /** Current language object. */
  get currentLanguage(): AppLanguage {
    return this.storageService.getCurrentLanguage();
  }

  /** Short label shown next to the globe icon for the *next* language to swap to. */
  get nextLanguageShort(): string {
    const langs = this.availableLanguages;
    if (langs.length < 2) {
      return this.currentLanguage.native_name?.charAt(0) || this.currentLanguage.code.toUpperCase();
    }
    const next = this.getNextLanguage();
    return next.native_name?.charAt(0) || next.code.toUpperCase();
  }

  private getNextLanguage(): AppLanguage {
    const langs = this.availableLanguages;
    const currentCode = this.storageService.siteLanguage$.value;
    const idx = langs.findIndex(l => l.code === currentCode);
    return langs[(idx + 1) % Math.max(langs.length, 1)] || langs[0];
  }

  toggleLanguageDropdown(event: Event): void {
    event.preventDefault();
    event.stopPropagation();
    if (this.availableLanguages.length <= 2) {
      // Legacy single-click toggle behaviour for the EN/AR case.
      this.toggleLanguage();
      return;
    }
    this.isLanguageDropdownOpen = !this.isLanguageDropdownOpen;
  }

  /** Cycles to the next language in the active list (back-compat for EN/AR). */
  toggleLanguage(): void {
    const next = this.getNextLanguage();
    this.selectLanguage(next.code);
  }

  selectLanguage(code: string): void {
    if (!this.storageService.isKnownCode(code)) {
      return;
    }
    this.changeUrlLanguage(code);
    this.storageService.siteLanguage$.next(code);
    this.isLanguageDropdownOpen = false;
  }

  /**
   * Changes the URL to reflect the selected language without reloading the page.
   * EN<->AR keeps using the legacy translated-route mapping; for any other
   * language we just swap the leading language segment.
   */
  changeUrlLanguage(language: string): void {
    const url = decodeURIComponent(this.location.path());
    const segments = url.split('/');
    const currentRoute = decodeURIComponent(segments[segments.length - 1]);
    const knownCodes = this.availableLanguages.map(l => l.code);
    const knownPattern = knownCodes.length
      ? new RegExp(`^/(?:${knownCodes.map(c => c.replace(/[-/\\^$*+?.()|[\]{}]/g, '\\$&')).join('|')})`)
      : /^\/(?:en|ar)/;

    const hasLegacyMapping = (lng: string): lng is 'en' | 'ar' =>
      lng === 'en' || lng === 'ar';

    let newUrl: string;
    if (segments.length > 2 && hasLegacyMapping(language) && (routeTranslations as any)[language]?.[currentRoute]) {
      newUrl = url.replace(/\/(en|ar)\/[^\/]+$/, `/${language}/${encodeURIComponent((routeTranslations as any)[language][currentRoute])}`);
    } else {
      newUrl = url.replace(knownPattern, `/${language}`);
      if (newUrl === url && !knownPattern.test(url)) {
        newUrl = `/${language}` + (url.startsWith('/') ? url : '/' + url);
      }
    }

    this.location.replaceState(newUrl);
  }

  onProfileDropdownClick(event: Event) {
    event.stopPropagation();
    this.isDropdownExpanded = true;
  }

  onProfileBoxClick(event: Event) {
    event.stopPropagation();
    this.isDropdownExpanded = !this.isDropdownExpanded;
  }

  logout(){
    this.logoutService.logout().subscribe({
      next: (value) => {
        this.cookieService.delete("user");
        this.authService.loggedInUser = null;
        this.basketService.quantity = 0;
        this.cookieService.delete('session_id');
        this.generateSessionID();
        this.authService.is_loggedin = false;
        this.router.navigate(['/' + this.storageService.siteLanguage$.value]);
      },
      error: (error) => {
      }
    });
  }

  activeTab(viewLocation: string): boolean {
    const currentPath = decodeURIComponent(this.location.path());
    return currentPath.includes(viewLocation);
  }

  generateSessionID() {
    const unique_id = crypto.randomUUID();
    this.cookieService.set('session_id', unique_id, 20, "/");
  }
}
