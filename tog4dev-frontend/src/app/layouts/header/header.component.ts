import { Component, ElementRef, HostListener, OnInit, Inject, PLATFORM_ID } from '@angular/core';
import {Location, NgClass, isPlatformBrowser} from '@angular/common';
import { NavigationService } from 'app/shared/services/navigation/navigation.service';
import {Router, RouterLink} from '@angular/router';

import { TranslateModule, TranslateService } from '@ngx-translate/core';

import { AuthService } from 'app/auth/services/auth.service';
import { CookieService } from 'ngx-cookie-service';
import { StorageService } from 'app/core/storage/storage.service';

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
  homeRoutes: Record<'ar' | 'en' , string> = {
    ar: 'ar',
    en: 'en'
  };
  ngOverseRoutes: Record<'ar' | 'en' , string> = {
    ar: 'ar/عالم-المنظمات',
    en: 'en/ngoverse'
  };
  contactUsRoutes: Record<'ar' | 'en' , string> = {
    ar: 'ar/تواصل-معنا',
    en: 'en/contact-us'
  };
  projectsRoutes: Record<'ar' | 'en' , string> = {
    ar: 'ar/المشاريع-الفردية',
    en: 'en/individual-projects'
  };
  organizationRoutes: Record<'ar' | 'en' , string> = {
    ar: 'ar/مشاريع-المنظمات',
    en: 'en/organizations-projects'
  };
  loginRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/تسجيل-الدخول',
    en: 'en/login',
  };
  signupRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/إنشاء-حساب',
    en: 'en/signup',
  };
  crowdfundingRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/التمويل-الجماعي',
    en: 'en/crowdfunding',
  };
  newsGalleryRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/الأخبار-والمعرض',
    en: 'en/news-gallery',
  };
  newsRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/الأخبار',
    en: 'en/news',
  };
  photosRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/الصور',
    en: 'en/photos',
  };
  videosRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/الفيديو',
    en: 'en/videos',
  };
  aboutUsRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/من-نحن',
    en: 'en/about-us',
  };
  profileRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/تعديل-حساب',
    en: 'en/edit-profile',
  };
  basketRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/السلة',
    en: 'en/basket',
  };
  historyRoutes: Record<'ar' | 'en', string> = {
    ar: 'ar/الاشتراكات',
    en: 'en/subscriptions',
  };

  isDropdownExpanded = false;
  isNavDropdownOpen = false;
  isMobileMenuCollapse = true;
  isScrolled: boolean = false;

  @HostListener('document:click', ['$event.target'])
  onOutsideClick(target: HTMLElement) {
    if ((this.elementRef.nativeElement as HTMLElement).querySelector('.profile-box') && !(this.elementRef.nativeElement as HTMLElement).querySelector('.profile-box')!.contains(target)) {
      this.isDropdownExpanded = false;
    }
    if (!(this.elementRef.nativeElement as HTMLElement).querySelector('.nav-dropdown')?.contains(target)) {
      this.isNavDropdownOpen = false;
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
    this.isScrolled = typeof window !== 'undefined' ? window.scrollY > 50 : false;
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
    }
  }

  navVisible(key: string): boolean {
    return this.navService.isVisible(key);
  }

  /**
   * Test - Used to toggle the site language
   */
  toggleLanguage(): void {
    const toggledLanguage: 'en' | 'ar' = this.storageService.siteLanguage$.value === 'ar' ? 'en' : 'ar';
    this.changeUrlLanguage(toggledLanguage);
    this.storageService.siteLanguage$.next(toggledLanguage);
  }

  /**
   * Changes the URL to reflect the selected language without reloading the page.
   */
  changeUrlLanguage(language: 'en' | 'ar'): void {
    const url = decodeURIComponent(this.location.path());
    const segments = url.split('/');
    const currentRoute = decodeURIComponent(segments[segments.length - 1]);
    const newUrl = (segments.length > 2) 
                  ? url.replace(/\/(en|ar)\/[^\/]+$/, `/${language}/${encodeURIComponent(routeTranslations[language][currentRoute])}`)
                  : url.replace(/\/(en|ar)/, `/${language}`);

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
