import { ApplicationConfig, importProvidersFrom, inject, provideAppInitializer } from '@angular/core';
import { InMemoryScrollingOptions, provideRouter, withInMemoryScrolling } from '@angular/router';
import { provideAnimations } from '@angular/platform-browser/animations';
import { provideClientHydration } from '@angular/platform-browser';
import { provideHttpClient, withFetch, HttpClient } from '@angular/common/http';
import { withHttpTransferCacheOptions } from '@angular/platform-browser';

import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { translateLoaderFactory } from './translate-loader-factory';

import { routes } from './app.routes';
import { AuthService } from './auth/services/auth.service';

import {HttpsRedirectService} from "./shared/services/https-redirect/https-redirect.service";

const scrollConfig: InMemoryScrollingOptions = {
  scrollPositionRestoration: 'enabled',
  anchorScrolling: 'enabled',
};

export const appConfig: ApplicationConfig = {
  providers: [
    provideRouter(routes, withInMemoryScrolling(scrollConfig)),
    provideClientHydration(
      withHttpTransferCacheOptions({
        includeHeaders: ['Accept-Language'],
        includePostRequests: false,
        filter: () => true,
      })
    ),
    provideAnimations(),
    provideHttpClient(withFetch()),
    importProvidersFrom(
      TranslateModule.forRoot({
        loader: {
          provide: TranslateLoader,
          useFactory: translateLoaderFactory,
          deps: [HttpClient],
        },
      }),
    ),
    provideAppInitializer(() => {
        const initializerFn = (initializeApp)(inject(AuthService), inject(HttpsRedirectService));
        return initializerFn();
      }),
  ]
};

export function initializeApp(authService: AuthService, httpsRedirectService: HttpsRedirectService): () => Promise<void> {
  return async (): Promise<void> => {
    return new Promise<void>((resolve) => {
      // httpsRedirectService.enforceHttps();
      authService.setLoggedInUserCookie();
      resolve();
    });
  };
}