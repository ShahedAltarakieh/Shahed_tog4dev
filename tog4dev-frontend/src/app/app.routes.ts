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
    pathMatch: 'full',
    data: { pageKey: 'home' },
  },
  {
    path: ':lang/individual-projects',
    loadComponent: () => import('./projects/projects.component').then(c => c.ProjectsComponent),
    pathMatch: 'full',
    data: { pageKey: 'individual-projects' },
  },
  {
    path: ':lang/المشاريع-الفردية',
    loadComponent: () => import('./projects/projects.component').then(c => c.ProjectsComponent),
    pathMatch: 'full',
    data: { pageKey: 'individual-projects' },
  },
  {
    path: ':lang/organizations-projects',
    loadComponent: () => import('./organization/organization.component').then(c => c.OrganizationComponent),
    pathMatch: 'full',
    data: { pageKey: 'organizations-projects' },
  },
  {
    path: ':lang/مشاريع-المنظمات',
    loadComponent: () => import('./organization/organization.component').then(c => c.OrganizationComponent),
    pathMatch: 'full',
    data: { pageKey: 'organizations-projects' },
  },
  {
    path: ':lang/crowdfunding',
    loadComponent: () => import('./crowdfunding/crowdfunding.component').then(c => c.CrowdfundingComponent),
    pathMatch: 'full',
    data: { pageKey: 'crowdfunding' },
  },
  {
    path: ':lang/التمويل-الجماعي',
    loadComponent: () => import('./crowdfunding/crowdfunding.component').then(c => c.CrowdfundingComponent),
    pathMatch: 'full',
    data: { pageKey: 'crowdfunding' },
  },
  {
    path: ':lang/individual-projects/:category_slug',
    loadComponent: () => import('./projects/projects.component').then(c => c.ProjectsComponent),
    pathMatch: 'full',
    data: { pageKey: 'individual-projects' },
  },
  {
    path: ':lang/المشاريع-الفردية/:category_slug',
    loadComponent: () => import('./projects/projects.component').then(c => c.ProjectsComponent),
    pathMatch: 'full',
    data: { pageKey: 'individual-projects' },
  },
  {
    path: ':lang/organizations-projects/:category_slug',
    loadComponent: () => import('./organization/organization.component').then(c => c.OrganizationComponent),
    pathMatch: 'full',
    data: { pageKey: 'organizations-projects' },
  },
  {
    path: ':lang/مشاريع-المنظمات/:category_slug',
    loadComponent: () => import('./organization/organization.component').then(c => c.OrganizationComponent),
    pathMatch: 'full',
    data: { pageKey: 'organizations-projects' },
  },
  {
    path: ':lang/crowdfunding/:category_slug',
    loadComponent: () => import('./crowdfunding/crowdfunding.component').then(c => c.CrowdfundingComponent),
    pathMatch: 'full',
    data: { pageKey: 'crowdfunding' },
  },
  {
    path: ':lang/التمويل-الجماعي/:category_slug',
    loadComponent: () => import('./crowdfunding/crowdfunding.component').then(c => c.CrowdfundingComponent),
    pathMatch: 'full',
    data: { pageKey: 'crowdfunding' },
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
    pathMatch: 'full',
    data: { pageKey: 'contact-us' },
  },
  {
    path: ':lang/contact-us',
    loadComponent: () => import('./static-pages/contact/contact.component').then(c => c.ContactComponent),
    pathMatch: 'full',
    data: { pageKey: 'contact-us' },
  },
  {
    path: ':lang/login',
    loadComponent: () => import('./auth/components/login/login.component').then(c => c.LoginComponent),
    pathMatch: 'full',
    canActivate: [redirectNotLoggedUserGuard],
    data: { pageKey: 'login' },
  },
  {
    path: ':lang/تسجيل-الدخول',
    loadComponent: () => import('./auth/components/login/login.component').then(c => c.LoginComponent),
    pathMatch: 'full',
    canActivate: [redirectNotLoggedUserGuard],
    data: { pageKey: 'login' },
  },
  {
    path: ':lang/signup',
    loadComponent: () => import('./auth/components/signup/signup.component').then(c => c.SignupComponent),
    pathMatch: 'full',
    data: { pageKey: 'signup' },
  },
  {
    path: ':lang/إنشاء-حساب',
    loadComponent: () => import('./auth/components/signup/signup.component').then(c => c.SignupComponent),
    pathMatch: 'full',
    data: { pageKey: 'signup' },
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
    data: { pageKey: 'about-us' },
  },
  {
    path: ':lang/من-نحن',
    loadComponent: () => import('./static-pages/about-us/about-us.component').then(c => c.AboutUsComponent),
    pathMatch: 'full',
    data: { pageKey: 'about-us' },
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
    data: { pageKey: 'basket' },
  },
  {
    path: ':lang/السلة',
    loadComponent: () => import('./basket/basket.component').then(c => c.BasketComponent),
    pathMatch: 'full',
    data: { pageKey: 'basket' },
  },
  {
    path: ':lang/subscriptions',
    loadComponent: () => import('./subscriptions/subscriptions.component').then(c => c.SubscriptionsComponent),
    pathMatch: 'full',
    data: { pageKey: 'subscriptions' },
  },
  {
    path: ':lang/الاشتراكات',
    loadComponent: () => import('./subscriptions/subscriptions.component').then(c => c.SubscriptionsComponent),
    pathMatch: 'full',
    data: { pageKey: 'subscriptions' },
  },
  {
    path: ':lang/terms-and-conditions',
    loadComponent: () => import('./static-pages/terms-and-condition/terms-and-conditions.component').then(c => c.TermsAndConditionsComponent),
    pathMatch: 'full',
    data: { pageKey: 'terms-and-conditions' },
  },
  {
    path: ':lang/الشروط-والاحكام',
    loadComponent: () => import('./static-pages/terms-and-condition/terms-and-conditions.component').then(c => c.TermsAndConditionsComponent),
    pathMatch: 'full',
    data: { pageKey: 'terms-and-conditions' },
  },
  {
    path: ':lang/privacy-policy',
    loadComponent: () => import('./static-pages/privacy-policy/privacy-policy.component').then(c => c.PrivacyPolicyComponent),
    pathMatch: 'full',
    data: { pageKey: 'privacy-policy' },
  },
  {
    path: ':lang/سياسة-الخصوصية',
    loadComponent: () => import('./static-pages/privacy-policy/privacy-policy.component').then(c => c.PrivacyPolicyComponent),
    pathMatch: 'full',
    data: { pageKey: 'privacy-policy' },
  },
  {
    path: ':lang/refund-policy',
    loadComponent: () => import('./static-pages/refund-policy/refund-policy.component').then(c => c.RefundPolicyComponent),
    pathMatch: 'full',
    data: { pageKey: 'refund-policy' },
  },
  {
    path: ':lang/سياسة-الإرجاع',
    loadComponent: () => import('./static-pages/refund-policy/refund-policy.component').then(c => c.RefundPolicyComponent),
    pathMatch: 'full',
    data: { pageKey: 'refund-policy' },
  },
  {
    path: ':lang/subscription-policy',
    loadComponent: () => import('./static-pages/subscription-policy/subscription-policy.component').then(c => c.SubscriptionPolicyComponent),
    pathMatch: 'full',
    data: { pageKey: 'subscription-policy' },
  },
  {
    path: ':lang/سياسة-الاشتراكات',
    loadComponent: () => import('./static-pages/subscription-policy/subscription-policy.component').then(c => c.SubscriptionPolicyComponent),
    pathMatch: 'full',
    data: { pageKey: 'subscription-policy' },
  },
  {
    path: ':lang/cookie-policy',
    loadComponent: () => import('./static-pages/cookie-policy/cookie-policy.component').then(c => c.CookiePolicyComponent),
    pathMatch: 'full',
    data: { pageKey: 'cookie-policy' },
  },
  {
    path: ':lang/سياسة-ملفات-تعريف-الارتباط',
    loadComponent: () => import('./static-pages/cookie-policy/cookie-policy.component').then(c => c.CookiePolicyComponent),
    pathMatch: 'full',
    data: { pageKey: 'cookie-policy' },
  },
  {
    path: ':lang/ngoverse',
    loadComponent: () => import('./static-pages/ngoverse/ngoverse.component').then(c => c.NgoverseComponent),
    pathMatch: 'full',
    data: { pageKey: 'ngoverse' },
  },
  {
    path: ':lang/عالم-المنظمات',
    loadComponent: () => import('./static-pages/ngoverse/ngoverse.component').then(c => c.NgoverseComponent),
    pathMatch: 'full',
    data: { pageKey: 'ngoverse' },
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
    path: ':lang/news-gallery',
    redirectTo: ':lang/news',
    pathMatch: 'full'
  },
  {
    path: ':lang/الأخبار-والمعرض',
    redirectTo: ':lang/الأخبار',
    pathMatch: 'full'
  },
  {
    path: ':lang/news',
    loadComponent: () => import('./news-gallery/news/news.component').then(c => c.NewsComponent),
    pathMatch: 'full',
    data: { pageKey: 'news' },
  },
  {
    path: ':lang/الأخبار',
    loadComponent: () => import('./news-gallery/news/news.component').then(c => c.NewsComponent),
    pathMatch: 'full',
    data: { pageKey: 'news' },
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
    pathMatch: 'full',
    data: { pageKey: 'photos' },
  },
  {
    path: ':lang/الصور',
    loadComponent: () => import('./news-gallery/photos/photos.component').then(c => c.PhotosComponent),
    pathMatch: 'full',
    data: { pageKey: 'photos' },
  },
  {
    path: ':lang/videos',
    loadComponent: () => import('./news-gallery/videos/videos.component').then(c => c.VideosComponent),
    pathMatch: 'full',
    data: { pageKey: 'videos' },
  },
  {
    path: ':lang/الفيديو',
    loadComponent: () => import('./news-gallery/videos/videos.component').then(c => c.VideosComponent),
    pathMatch: 'full',
    data: { pageKey: 'videos' },
  },
  {
    path: ':lang/mama-giving-shope',
    loadComponent: () => import('./ramadan/ramadan.component').then(c => c.RamadanComponent),
    pathMatch: 'full',
    data: { pageKey: 'mama-giving-shope' },
  },
  {
    path: ':lang/درب-العطاء-لأمي',
    loadComponent: () => import('./ramadan/ramadan.component').then(c => c.RamadanComponent),
    pathMatch: 'full',
    data: { pageKey: 'mama-giving-shope' },
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
