// ---- Universal In-App Browser Detection + dynamic t4d from cookie ----
// (function() {
//   const ua = navigator.userAgent.toLowerCase();

//   const inApp = {
//     facebook:  ua.includes("fban") || ua.includes("fbav") || ua.includes("fb_iab") || ua.includes("fbios"),
//     instagram: ua.includes("instagram"),
//     messenger: ua.includes("fbmessenger") || ua.includes("messenger"),
//     tiktok:    ua.includes("tiktok"),
//     twitter:   ua.includes("twitter"),
//     snapchat:  ua.includes("snapchat"),
//     linkedin:  ua.includes("linkedin"),
//     pinterest: ua.includes("pinterest"),
//     reddit:    ua.includes("reddit")
//   };

//   const isInAppBrowser = Object.values(inApp).some(v => v === true);

//   // --- Get t4d cookie value (JS, not Angular service) ---   
//   if (isInAppBrowser) {
//     const current = window.location.href;
//     const url = new URL(current);

//     // --- Get t4d cookie value (JS, not Angular service) ---
//     const cookieMatch = document.cookie.match(/(?:^|;\s*)t4d\s*=\s*([^;]+)/);
//     const t4dCookieValue = cookieMatch ? decodeURIComponent(cookieMatch[1]) : null;
//     console.log(cookieMatch);
//     console.log(t4dCookieValue);
//     console.log(url.searchParams.has("t4d"));
    
//     // If cookie exists and URL does NOT already have t4d
//     if (t4dCookieValue && !url.searchParams.has("t4d")) {
//       url.searchParams.set("t4d", t4dCookieValue);
//     }

//     // Prevent infinite loop
//     if (current !== url.toString()) {
//       window.location.href = url.toString();
//     }
//   }
// })();

import { bootstrapApplication } from '@angular/platform-browser';
import { appConfig } from './app/app.config';
import { AppComponent } from './app/app.component';

import { register as registerSwiperElements } from 'swiper/element/bundle';

// Register Swiper elements
registerSwiperElements();

bootstrapApplication(AppComponent, appConfig)
  .catch((err) => console.error(err));
