export const routeTranslations: RouteTranslations = {
  en:
  {
    'ar': 'en',
    'تواصل-معنا': 'contact-us',
    'الصفحة-الرئيسية': 'home',
    'تسجيل-الدخول': 'login',
    'المشاريع-الفردية': 'individual-projects',
    'مشاريع-المنظمات': 'organizations-projects',
    'التمويل-الجماعي': 'crowdfunding',
    'من-نحن': 'about-us',
    'تعديل-حساب': 'edit-profile',
    'إنشاء-حساب': 'signup',
    'السلة': 'basket',
    'نسيت-كلمة-المرور': 'forget-password',
    'استعادة-كلمة-المرور': 'reset-password',
    'الاشتراكات': 'subscriptions',
    'الشروط-والاحكام': 'terms-and-conditions',
    'سياسة-الخصوصية': 'privacy-policy',
    'سياسة-الإرجاع': 'refund-policy',
    'سياسة-الاشتراكات': 'subscription-policy',
    'سياسة-ملفات-تعريف-الارتباط': 'cookie-policy',
    'عالم-المنظمات': 'ngoverse',
    'رمضان-2026': 'ramadan-2026',
    'درب-العطاء-لأمي': 'mama-giving-shope',
    'الأخبار': 'news',
    'الصور': 'photos',
    'الفيديو': 'videos'
  },
  ar:
  {
    'en': 'ar',
    'contact-us': 'تواصل-معنا',
    'home': 'الصفحة-الرئيسية',
    'login': 'تسجيل-الدخول',
    'individual-projects': 'المشاريع-الفردية',
    'organizations-projects': 'مشاريع-المنظمات',
    'crowdfunding': 'التمويل-الجماعي',
    'about-us': 'من-نحن',
    'edit-profile': 'تعديل-حساب',
    'basket': 'السلة',
    'forget-password': 'نسيت-كلمة-المرور',
    'reset-password': 'استعادة-كلمة-المرور',
    'signup': 'إنشاء-حساب',
    'subscriptions': 'الاشتراكات',
    'terms-and-conditions':'الشروط-والاحكام',
    'privacy-policy': 'سياسة-الخصوصية',
    'refund-policy': 'سياسة-الإرجاع',
    'subscription-policy': 'سياسة-الاشتراكات',
    'cookie-policy': 'سياسة-ملفات-تعريف-الارتباط',
    'ngoverse': 'عالم-المنظمات',
    'ramadan-2026': 'رمضان-2026',
    'mama-giving-shope': 'درب-العطاء-لأمي',
    'news': 'الأخبار',
    'photos': 'الصور',
    'videos': 'الفيديو'
  }
};


export interface RouteTranslations {
  [key: string]: {
    [route: string]: string;
  };
}
