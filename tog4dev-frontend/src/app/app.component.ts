import {AfterViewInit, ChangeDetectorRef, Component, OnInit, Inject, PLATFORM_ID} from '@angular/core';
import {Location, NgIf, isPlatformBrowser} from '@angular/common';
import {RouterOutlet, ActivatedRoute, Router, NavigationStart, NavigationEnd, NavigationError, ActivatedRouteSnapshot} from '@angular/router';
import { PageMaintenanceService, PageMaintenanceInfo } from './shared/services/page-maintenance/page-maintenance.service';
import { MaintenancePageComponent } from './shared/components/maintenance-page/maintenance-page.component';

import { StorageService } from './core/storage/storage.service';
import { TextDirectionService } from 'app/text-direction.service';
import { TranslateService } from '@ngx-translate/core';

import { FooterComponent } from './layouts/footer/footer.component';
import { HeaderComponent } from './layouts/header/header.component';
import { AnnouncementBarComponent } from './shared/components/announcement-bar/announcement-bar.component';

import { routeTranslations } from 'app/route-translations';
import { ApiService } from 'app/core/api/api.service';
import { filter, map } from 'rxjs';
import { ReferralService } from './shared/services/referral/referral-service.service';
import { EditProfileService } from "./profile/edit-profile/services/edit-profile.service";
import { AuthService } from "./auth/services/auth.service";
import { CookieService } from "ngx-cookie-service";
import {BasketService} from "./shared/services/basket/basket.service";
import {CartService} from "./shared/services/cart/cart.service";
import {LoaderComponent} from "./shared/components/loader/loader.component";
import {ScrollToTopComponent} from "./shared/components/scroll-to-top/scroll-to-top.component";
import { environment } from 'environments/environment';
import { GoogleTagManagerService } from './shared/services/google-tag-manager-service/google-tag-manager-service.service';
@Component({
    selector: 'app-root',
    imports: [RouterOutlet, HeaderComponent, FooterComponent, LoaderComponent, NgIf, AnnouncementBarComponent, MaintenancePageComponent, ScrollToTopComponent],
    templateUrl: './app.component.html',
    styleUrl: './app.component.scss'
})
export class AppComponent implements OnInit, AfterViewInit{
  private apiUrl = environment.apiUrl;
  private isProd = environment.production;
  title = 'Together for Development';
  isLoading = true;
  translatedRoutes!: { [route: string]: string; };
  maintenanceInfo: PageMaintenanceInfo | null = null;

  constructor( 
    public directionService: TextDirectionService,
    public location: Location,
    public storageService: StorageService,
    public translate: TranslateService,
    public cartService: CartService,
    public editProfileService: EditProfileService,
    public authService: AuthService,
    public cookieService: CookieService,
    private referralService: ReferralService,
    private router: Router,
    public basketService: BasketService,
    private route: ActivatedRoute,
    public apiService: ApiService,
    private gtm: GoogleTagManagerService,
    public pageMaintenanceService: PageMaintenanceService,
    @Inject(PLATFORM_ID) private platformId: Object
   ) {
    translate.addLangs(['en', 'ar']);
        const queryParams = { ...this.route.snapshot.queryParams };
  }

  ngAfterViewInit() {
    if (!isPlatformBrowser(this.platformId)) {
      return;
    }

    this.editProfileService.getUserInfo().subscribe({
      next: (value: any) => {
        this.authService.is_loggedin = true;
        this.authService.setLoggedInUserCookie();
      },
      error: () => {
        this.cookieService.delete("user");
        this.authService.loggedInUser = null;
        this.basketService.quantity = 0;
      }
    });

    this.cartService.getCart("ar").subscribe({
      next: (value: any) => {
        const items = value.data;
        if(items.length > 0){
          this.basketService.quantity = items.length;
        }
      }
    });
  }

  ngOnInit(): void { 
    this.setSiteLanguageFromUrl();
  
    this.storageService.siteLanguage$.subscribe((lang: 'en' | 'ar') => {
      this.handleSiteLanguage(lang);
    });

    // Page maintenance: load on both server (SSR) and browser so the initial
    // SSR render reflects maintenance state instead of flickering after hydration.
    this.pageMaintenanceService.load().subscribe(() => {
      this.updateMaintenanceForCurrentRoute();
    });

    this.router.events.subscribe(event => {
      if (event instanceof NavigationEnd) {
        this.updateMaintenanceForCurrentRoute();
      }
    });

    if (!isPlatformBrowser(this.platformId)) {
      return;
    }

    this.router.events.subscribe(event => {
      if (event instanceof NavigationStart) {
        this.isLoading = true;
      } else if (event instanceof NavigationEnd || event instanceof NavigationError) {
        setTimeout( () => {
          this.isLoading = false;
        }, 1000)
      }
    });

    // Safety: hide initial-load loader after 1.5s even if no NavigationEnd fires
    setTimeout(() => { this.isLoading = false; }, 1500);

    if(this.isProd){
      if (window.location.protocol !== 'https:') {
        window.location.href = 'https://' + window.location.host + window.location.pathname;
      }
    }

    this.handleT4dParam();

    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe((event: any) => {
      this.gtm.pushEvent({
        event: 'page_view',
        page_path: event.urlAfterRedirects
      });
    });

    var session_id = this.cookieService.get("session_id");
    if(session_id == null || session_id == ''){
      this.generateSessionID();
    }

    if(this.cookieService.get("session_id") == null){
      window.location.href = 'https://' + window.location.host + window.location.pathname;
    }

    this.route.queryParams.subscribe(params => {
      const referralCode = params['t4d'];            
      if (referralCode != undefined && referralCode != null) {
        this.cookieService.delete('t4d');
        this.cookieService.set('t4d', referralCode, 20, "/");
      }
      
    });
    var ip :any = null;

    this.apiService.get<any>('https://api.ipify.org?format=json').pipe(
      map(this.apiService.extractTypeFromMessage))
        .subscribe(value => {          
          if(value){
            if(value.ip){
              ip = value.ip;
              this.route.queryParams.subscribe(params => {
                const referralCode = params['t4d'];
                if (referralCode != undefined && referralCode != null) {
                  this.cookieService.delete('t4d');
                  this.cookieService.set('t4d', referralCode, 20, "/");
                  this.referralService.trackReferral(referralCode, ip).subscribe({
                    next: response => console.log('Referral logged', response),
                    error: err => console.error('Error logging referral', err),
                  });
                }
              });
              this.apiService.get<any>('https://api.country.is/' + ip).pipe(
                map(this.apiService.extractTypeFromMessage))
                .subscribe(value => {
                  if(value){
                    if(value.country){
                      this.cookieService.set('countryCode', value.country, 20, "/");
                      this.apiService.get<any>(this.apiUrl + 'api/v1/get-currency/' + value.country).pipe(
                          map(this.apiService.extractTypeFromMessage))
                          .subscribe(value => {
                          if(value){
                            this.cookieService.set('rate', value.rate, 20, "/");
                            this.cookieService.set('currency', value.currency, 20, "/");
                          }
                        });
                    }
                  }
                });
            }
          }
    });
  }

  /**
   * Used to handle the site translation, cotent and routes
   */
  /**
   * Walks the activated route tree to find the deepest `data.pageKey` and
   * checks the page maintenance service. If the page is currently under
   * update, sets `maintenanceInfo` so the template renders the maintenance
   * screen instead of the router-outlet.
   */
  updateMaintenanceForCurrentRoute(): void {
    let snap: ActivatedRouteSnapshot | null = this.route.snapshot;
    let pageKey: string | undefined;
    while (snap) {
      if (snap.data && snap.data['pageKey']) {
        pageKey = snap.data['pageKey'];
      }
      snap = snap.firstChild;
    }
    this.maintenanceInfo = this.pageMaintenanceService.getActive(pageKey);
  }

  setSiteLanguageFromUrl(): void {
    const siteLanguage = this.location.path().includes('/ar') ? 'ar' : 'en';
    this.storageService.siteLanguage$.next(siteLanguage);
  }

  /**
   * Used to handle the site ( content and routes ) Language
   * @param lang en or ar
   */
  handleSiteLanguage = (lang: 'en' | 'ar'): void => {
    this.translate.use(lang);
    this.directionService.setDirection(lang);
    this.translatedRoutes = routeTranslations[lang];
  }

  generateSessionID() {
    const unique_id = crypto.randomUUID();
    this.cookieService.set('session_id', unique_id, 20, "/");
  }

  /**
   * Detects if currently in Facebook in-app browser
   */
  isFacebookInAppBrowser(): boolean {
    if (typeof navigator === 'undefined') {
      return false;
    }
    const ua = navigator.userAgent.toLowerCase();
    return ua.includes('fban') || 
           ua.includes('fbav') || 
           ua.includes('fb_iab') || 
           ua.includes('fbios') ||
           (ua.includes('iphone') && ua.includes('version') && !ua.includes('safari') && document.referrer.includes('facebook'));
  }

  /**
   * Adds t4d query parameter from cookie to URL
   */
  addT4dParam(url: string): string {
    const t4dValue = this.cookieService.get('t4d');
    
    // Only add if cookie exists and URL doesn't already have t4d param
    if (!t4dValue) {
      return url;
    }
    
    try {
      const urlObj = new URL(url);
      // Only add if not already present
      if (!urlObj.searchParams.has('t4d')) {
        urlObj.searchParams.set('t4d', t4dValue);
      }
      return urlObj.toString();
    } catch (error) {
      // If URL parsing fails (relative URL), handle manually
      if (url.includes('t4d=')) {
        return url; // Already has t4d param
      }
      const separator = url.includes('?') ? '&' : '?';
      return `${url}${separator}t4d=${encodeURIComponent(t4dValue)}`;
    }
  }

  /**
   * Handles adding t4d param from cookie when in Facebook app
   * When user opens link in external browser, preserves t4d param
   */
  handleT4dParam(): void {
    if (this.isFacebookInAppBrowser()) {
      const currentUrl = window.location.href;
      const urlParams = new URLSearchParams(window.location.search);
      
      // If no t4d param exists, add it from cookie
      if (!urlParams.has('t4d')) {
        const urlWithParams = this.addT4dParam(currentUrl);
        
        // Store in sessionStorage for when user opens in external browser
        sessionStorage.setItem('fb_redirect_url', urlWithParams);
        
        // Update URL without reload
        if (window.history && window.history.replaceState) {
          try {
            window.history.replaceState({}, '', urlWithParams);
          } catch (e) {
            console.error('Error updating URL:', e);
          }
        }
      }
    } else {
      // Not in Facebook app - check if we have stored URL from Facebook app
      const storedUrl = sessionStorage.getItem('fb_redirect_url');
      if (storedUrl) {
        // User opened in external browser from Facebook app
        sessionStorage.removeItem('fb_redirect_url');
        const urlObj = new URL(storedUrl);
        const currentUrlObj = new URL(window.location.href);
        
        // Merge t4d param from stored URL if not already present
        if (urlObj.searchParams.has('t4d') && !currentUrlObj.searchParams.has('t4d')) {
          currentUrlObj.searchParams.set('t4d', urlObj.searchParams.get('t4d') || '');
          
          // Update URL with t4d param
          if (window.history && window.history.replaceState) {
            window.history.replaceState({}, '', currentUrlObj.toString());
          }
        }
      }
    }
  }
}

