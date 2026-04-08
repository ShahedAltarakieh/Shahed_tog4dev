import { Routes } from '@angular/router';

import { redirectNotLoggedUserGuard } from './auth/guards/redirect-not-logged-user.guard';

export const routes: Routes = [
  {
    path: ':lang/home',
    redirectTo: '/en',
    pathMatch: 'full',
  },
  {
    path: ':lang/الصفحة-الرئيسية',
    redirectTo: '/ar',
    pathMatch: 'full',
  },
  {
    path: ':lang',
    loadComponent: () => import('./home/home.component').then(c => c.HomeComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/individual-projects',
    loadComponent: () => import('./projects/projects.component').then(c => c.ProjectsComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/المشاريع-الفردية',
    loadComponent: () => import('./projects/projects.component').then(c => c.ProjectsComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/organizations-projects',
    loadComponent: () => import('./organization/organization.component').then(c => c.OrganizationComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/مشاريع-المنظمات',
    loadComponent: () => import('./organization/organization.component').then(c => c.OrganizationComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/crowdfunding',
    loadComponent: () => import('./crowdfunding/crowdfunding.component').then(c => c.CrowdfundingComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/التمويل-الجماعي',
    loadComponent: () => import('./crowdfunding/crowdfunding.component').then(c => c.CrowdfundingComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/individual-projects/:category_slug',
    loadComponent: () => import('./projects/projects.component').then(c => c.ProjectsComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/المشاريع-الفردية/:category_slug',
    loadComponent: () => import('./projects/projects.component').then(c => c.ProjectsComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/organizations-projects/:category_slug',
    loadComponent: () => import('./organization/organization.component').then(c => c.OrganizationComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/مشاريع-المنظمات/:category_slug',
    loadComponent: () => import('./organization/organization.component').then(c => c.OrganizationComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/crowdfunding/:category_slug',
    loadComponent: () => import('./crowdfunding/crowdfunding.component').then(c => c.CrowdfundingComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/التمويل-الجماعي/:category_slug',
    loadComponent: () => import('./crowdfunding/crowdfunding.component').then(c => c.CrowdfundingComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/individual-projects/:category_slug/:slug',
    loadComponent: () => import('./project-page/project-page.component').then(c => c.ProjectPageComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/المشاريع-الفردية/:category_slug/:slug',
    loadComponent: () => import('./project-page/project-page.component').then(c => c.ProjectPageComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/organizations-projects/:category_slug/:slug',
    loadComponent: () => import('./organization-page/organization-page.component').then(c => c.OrganizationPageComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/مشاريع-المنظمات/:category_slug/:slug',
    loadComponent: () => import('./organization-page/organization-page.component').then(c => c.OrganizationPageComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/crowdfunding/:category_slug/:slug',
    loadComponent: () => import('./crowdfunding-page/crowdfunding-page.component').then(c => c.CrowdfundingPageComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/التمويل-الجماعي/:category_slug/:slug',
    loadComponent: () => import('./crowdfunding-page/crowdfunding-page.component').then(c => c.CrowdfundingPageComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/تواصل-معنا',
    loadComponent: () => import('./static-pages/contact/contact.component').then(c => c.ContactComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/contact-us',
    loadComponent: () => import('./static-pages/contact/contact.component').then(c => c.ContactComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/login',
    loadComponent: () => import('./auth/components/login/login.component').then(c => c.LoginComponent),
    pathMatch: 'full',
    canActivate: [redirectNotLoggedUserGuard],
  },
  {
    path: ':lang/تسجيل-الدخول',
    loadComponent: () => import('./auth/components/login/login.component').then(c => c.LoginComponent),
    pathMatch: 'full',
    canActivate: [redirectNotLoggedUserGuard],
  },
  {
    path: ':lang/signup',
    loadComponent: () => import('./auth/components/signup/signup.component').then(c => c.SignupComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/إنشاء-حساب',
    loadComponent: () => import('./auth/components/signup/signup.component').then(c => c.SignupComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/forget-password',
    loadComponent: () => import('./auth/components/forget-password/forget-password.component').then(c => c.ForgetPasswordComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/نسيت-كلمة-المرور',
    loadComponent: () => import('./auth/components/forget-password/forget-password.component').then(c => c.ForgetPasswordComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/reset-password/:token',
    loadComponent: () => import('./auth/components/reset-password/reset-password.component').then(c => c.ResetPasswordComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/استعادة-كلمة-المرور/:token',
    loadComponent: () => import('./auth/components/reset-password/reset-password.component').then(c => c.ResetPasswordComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/about-us',
    loadComponent: () => import('./static-pages/about-us/about-us.component').then(c => c.AboutUsComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/من-نحن',
    loadComponent: () => import('./static-pages/about-us/about-us.component').then(c => c.AboutUsComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/edit-profile',
    loadComponent: () => import('./profile/edit-profile/edit-profile.component').then(c => c.EditProfileComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/تعديل-حساب',
    loadComponent: () => import('./profile/edit-profile/edit-profile.component').then(c => c.EditProfileComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/basket',
    loadComponent: () => import('./basket/basket.component').then(c => c.BasketComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/السلة',
    loadComponent: () => import('./basket/basket.component').then(c => c.BasketComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/subscriptions',
    loadComponent: () => import('./subscriptions/subscriptions.component').then(c => c.SubscriptionsComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/الاشتراكات',
    loadComponent: () => import('./subscriptions/subscriptions.component').then(c => c.SubscriptionsComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/terms-and-conditions',
    loadComponent: () => import('./static-pages/terms-and-condition/terms-and-conditions.component').then(c => c.TermsAndConditionsComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/الشروط-والاحكام',
    loadComponent: () => import('./static-pages/terms-and-condition/terms-and-conditions.component').then(c => c.TermsAndConditionsComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/privacy-policy',
    loadComponent: () => import('./static-pages/privacy-policy/privacy-policy.component').then(c => c.PrivacyPolicyComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/سياسة-الخصوصية',
    loadComponent: () => import('./static-pages/privacy-policy/privacy-policy.component').then(c => c.PrivacyPolicyComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/refund-policy',
    loadComponent: () => import('./static-pages/refund-policy/refund-policy.component').then(c => c.RefundPolicyComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/سياسة-الإرجاع',
    loadComponent: () => import('./static-pages/refund-policy/refund-policy.component').then(c => c.RefundPolicyComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/subscription-policy',
    loadComponent: () => import('./static-pages/subscription-policy/subscription-policy.component').then(c => c.SubscriptionPolicyComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/سياسة-الاشتراكات',
    loadComponent: () => import('./static-pages/subscription-policy/subscription-policy.component').then(c => c.SubscriptionPolicyComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/cookie-policy',
    loadComponent: () => import('./static-pages/cookie-policy/cookie-policy.component').then(c => c.CookiePolicyComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/سياسة-ملفات-تعريف-الارتباط',
    loadComponent: () => import('./static-pages/cookie-policy/cookie-policy.component').then(c => c.CookiePolicyComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/ngoverse',
    loadComponent: () => import('./static-pages/ngoverse/ngoverse.component').then(c => c.NgoverseComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/عالم-المنظمات',
    loadComponent: () => import('./static-pages/ngoverse/ngoverse.component').then(c => c.NgoverseComponent),
    pathMatch: 'full',
  },
  // {
  //   path: ':lang/ramadan-2026',
  //   loadComponent: () => import('./ramadan/ramadan.component').then(c => c.RamadanComponent),
  //   pathMatch: 'full',
  // },
  // {
  //   path: ':lang/رمضان-2026',
  //   loadComponent: () => import('./ramadan/ramadan.component').then(c => c.RamadanComponent),
  //   pathMatch: 'full',
  // },
  {
    path: ':lang/news',
    loadComponent: () => import('./news-gallery/news/news.component').then(c => c.NewsComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/الأخبار',
    loadComponent: () => import('./news-gallery/news/news.component').then(c => c.NewsComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/news/:slug',
    loadComponent: () => import('./news-gallery/news-detail/news-detail.component').then(c => c.NewsDetailComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/الأخبار/:slug',
    loadComponent: () => import('./news-gallery/news-detail/news-detail.component').then(c => c.NewsDetailComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/photos',
    loadComponent: () => import('./news-gallery/photos/photos.component').then(c => c.PhotosComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/الصور',
    loadComponent: () => import('./news-gallery/photos/photos.component').then(c => c.PhotosComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/videos',
    loadComponent: () => import('./news-gallery/videos/videos.component').then(c => c.VideosComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/الفيديو',
    loadComponent: () => import('./news-gallery/videos/videos.component').then(c => c.VideosComponent),
    pathMatch: 'full'
  },
  {
    path: ':lang/mama-giving-shope',
    loadComponent: () => import('./ramadan/ramadan.component').then(c => c.RamadanComponent),
    pathMatch: 'full',
  },
  {
    path: ':lang/درب-العطاء-لأمي',
    loadComponent: () => import('./ramadan/ramadan.component').then(c => c.RamadanComponent),
    pathMatch: 'full',
  },
  {
    path: 's/:code',
    loadComponent: () => import('./shortlink-redirect/shortlink-redirect.component').then(c => c.ShortlinkRedirectComponent),
    pathMatch: 'full',
  },
  {
    path: '**',
    loadComponent: () => import('./notfound-redirect/notfound-redirect.component').then(c => c.NotfoundRedirectComponent),
    pathMatch: 'full',
  }
];
