# Tog4Dev - Fundraising & Donation Platform

## Project Overview

Tog4Dev is a fundraising and charitable donation platform targeting Jordanian users, supporting features like emergency relief projects, subscriptions, crowdfunding, and payment integrations (Orange Money, eFAWATEERcom, PayTabs, Network/Mastercard).

The project uses a decoupled architecture:
- **Frontend**: Angular 19 with SSR (Server-Side Rendering) via `@angular/ssr`
- **Backend**: Laravel 10.x (PHP 8.2) with PostgreSQL database

## Project Structure

```
tog4dev-backend/     # Laravel 10 PHP backend (port 3000)
  app/               # Controllers, Models, Services
  config/            # Laravel configuration
  database/          # Migrations and seeders (107 migrations)
  routes/            # API (v1, v2) and web routes
  .env               # Environment config (DB, mail, etc.)
  composer.json      # PHP dependencies

tog4dev-frontend/    # Angular 19 frontend (port 5000)
  src/               # Application source
    app/             # Components, services, modules
    assets/          # Images, i18n JSON files
    environments/    # Environment configs (dev uses /backend/ proxy)
  angular.json       # Angular CLI config (port 5000, host 0.0.0.0, allowedHosts: all)
  package.json       # npm dependencies
  server.ts          # SSR Express server
  proxy.conf.json    # Dev proxy: /backend/api/** → localhost:3000
```

## Workflows

- **Start application** (port 5000, webview): `cd tog4dev-frontend && npm start`
- **Backend API** (port 3000, console): `cd tog4dev-backend && php artisan serve --host=0.0.0.0 --port=3000`

## Key Configuration

- **Frontend API URL (dev)**: `/backend/` (proxied to Laravel backend on port 3000)
- **Frontend API URL (prod)**: `https://admin.tog4dev.com/`
- **Dev proxy**: `/backend/api/**` → `http://localhost:3000`
- **Database**: PostgreSQL (Replit built-in, connection: pgsql)
- **Languages**: Arabic (ar) and English (en) with `@ngx-translate`
- **Styling**: Bootstrap 5.3 + SCSS + FontAwesome
- **SSR**: Fixed `isPlatformBrowser` guards in `app.component.ts` for SSR compatibility

## Database

- **Connection**: PostgreSQL (Replit built-in)
- **Config**: `tog4dev-backend/.env` with DB_CONNECTION=pgsql
- All 107 migrations applied successfully
- Originally designed for MySQL; adapted to PostgreSQL with minor migration fixes

## Deployment

- **Target**: Autoscale
- **Build**: `cd tog4dev-frontend && npm install --legacy-peer-deps && npm run build:prod`
- **Run**: `cd tog4dev-frontend && PORT=5000 node dist/tog4dev-frontend/server/server.mjs`

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | Angular 19, TypeScript, SCSS, Bootstrap 5.3 |
| SSR | @angular/ssr, Express |
| Backend | Laravel 10, PHP 8.2 |
| Database | PostgreSQL (Replit) |
| Package Manager (FE) | npm |
| Package Manager (BE) | Composer |
| i18n | @ngx-translate (AR/EN) |
| Auth | Laravel Sanctum (API tokens) |
| Payments | PayTabs, Orange Money, Network/Mastercard |

## News & Gallery Module

Full-stack module for news articles and media gallery (photos/videos).

**Backend** (`tog4dev-backend/`):
- Models: `News`, `NewsCategory`, `GalleryPhoto`, `GalleryVideo` (with Sluggable, Spatie Media)
- Migrations: `news_categories`, `news`, `gallery_photos`, `gallery_videos`, `add_display_target_to_gallery_videos`
- API Controllers: `NewsController` (index/show/related/categories/search), `GalleryController` (photos/videos)
- Admin Controllers: `NewsAdminController`, `GalleryAdminController`, `NewsCategoryAdminController` (return Blade views, AJAX delete/status toggle)
- Admin Blade Views: `admin/news/`, `admin/gallery/photos/`, `admin/gallery/videos/`, `admin/news_categories/` (index/create/edit each)
- Admin UI: DataTables listing, Dropify file upload, Switchery toggles, SweetAlert2 delete confirm, Bootstrap 4 forms, reusable image-upload-notes partial
- Admin image upload fields include image guidelines (recommended size, max file size, allowed extensions) via `includes/admin/image-upload-notes.blade.php`
- Video management includes: cover image upload with preview, `display_target` field (mobile/desktop/both) with dropdown selector, thumbnail shown in listing table
- Routes: `/api/v1/news/*`, `/api/v1/gallery/*`, `/api/v1/search?q=` (unified search); admin routes under `master` middleware at `news-management/*`, `news-categories/*`, `gallery-management/photos/*`, `gallery-management/videos/*`
- Sidebar: "News & Gallery" section with links to News, Categories, Photos, Videos

**Frontend** (`tog4dev-frontend/src/app/news-gallery/`):
- Services: `NewsService`, `GalleryService`
- Pages: `NewsComponent`, `NewsDetailComponent` (modernized card layout with gradient hero, skeleton loaders, icon-only share buttons, enhanced related cards), `PhotosComponent` (uniform 4:3 grid, lightbox with prev/next navigation + keyboard support), `VideosComponent` (embedded player)
- Routes: `/en/news`, `/en/news/:slug`, `/en/photos`, `/en/videos` + Arabic equivalents (landing page removed; `/en/news-gallery` redirects to `/en/news`)
- Navigation: "News & Gallery" dropdown in header with SVG icons (News/Photos/Videos), click+hover toggle, rotating arrow indicator, closes on outside click
- Features: debounced search, category filtering, pagination, share (Facebook/WhatsApp/Copy link), breadcrumbs, loading/empty/error states, "Read More" buttons

## Footer

Redesigned 3-band footer layout:
- **Newsletter band** (top): Teal background with frosted-glass newsletter signup form, decorative circles
- **Main columns** (middle): Dark teal background with 4-column grid — brand column (logo, description, address, social icons) + 3 link columns (Projects, Explore More, About)
- **Bottom bar**: Darker teal with legal links, payment badges, copyright, and Artikeys credit
- **Responsive**: 4-col → 2-col (tablet) → 1-col (mobile) grid, newsletter stacks vertically on tablet
- **Accessibility**: `aria-label` on social icons, `sr-only` label for email input, `rel="noopener noreferrer"` on external links
- **RTL**: Logical properties (`padding-inline-start`), RTL-safe underline positioning
- Files: `layouts/footer/footer.component.{html,scss,ts}`, `layouts/footer/components/stay-in-touch-form/`

## Admin Dashboard Redesign

Major visual and structural overhaul of the Laravel admin dashboard:

**Modern CSS Theme** (`public/css/admin-modern.css` + `admin-modern-rtl.css`):
- Custom design system with CSS custom properties (colors, spacing, shadows, radii, typography)
- Inter font family, smooth transitions, modern scrollbar styling
- Upgraded cards (soft borders, subtle shadows, hover effects), tables (uppercase headers, clean borders), forms (focus rings, custom selects), badges (soft-color scheme), buttons (rounded, primary/success/danger tokens)
- KPI card component with colored icon badges, value/label hierarchy, and change indicators
- Quick actions grid with icon buttons
- DataTables pagination, search, and export button refinements
- Dropify, SweetAlert2, Selectize, Select2, Quill editor style overrides
- Empty state and breadcrumb components
- Full RTL support via `admin-modern-rtl.css`

**Sidebar Reorganization** (`includes/admin/side-bar.blade.php`):
- Grouped into labeled sections: MAIN, CONTENT, BUSINESS, USERS, COMMUNICATION, SYSTEM
- Section headers styled as uppercase labels (`sidebar-section-title`)
- Updated icons for better visual consistency
- Dark gradient sidebar background with hover states

**Dashboard Home Redesign** (`admin/dashboard.blade.php`):
- Welcome header with date range picker
- 4 primary KPI cards (today/week/month/year) with colored icon badges and % change indicators
- Secondary KPI row: Total Users, Active Subscriptions, News Published, Pending Requests
- All payments summary card + custom date range card
- Quick Actions grid (6 shortcut buttons) + Operations Panel (unread messages, failed payments, notifications, system health)
- Recent Activity Feed with payments and new user registrations (alongside influencer table at 8:4 split)
- Charts and influencer table with cleaner card layout

**Phase 2: New System Pages** (`resources/views/admin/system/`):
- Activity Logs (`system/activity-logs.blade.php`) — filterable activity timeline
- Notifications Center (`system/notifications.blade.php`) — alerts with severity badges
- Settings Center (`system/settings.blade.php`) — tabbed navigation (General, Appearance, Security, Profile) with custom JS tab switching
- System Health (`system/system-health.blade.php`) — KPIs, environment info, content overview, revenue/user charts, today's snapshot
- Reports Center (`system/reports.blade.php`) — revenue/transaction/payment method charts with daily/monthly breakdown tables
- Media Library (`system/media-library.blade.php`) — photo/video grid management

**Phase 3: Premium Admin Dashboard Rebuild**:
- Login Page: Split-layout with brand panel (logo in original colors, feature list) + clean form (password toggle, error display, language switcher, mobile responsive)
- Topbar: Search bar (Ctrl+K → command palette), notifications bell, language switcher with active state, user avatar dropdown (username, role, profile/security links, logout)
- Sidebar: Professional IA — "Customers" (Users/Influencers/Admins), "Content Management", "News & Media", "Messages", "System Settings" with consistent Font Awesome icons
- Full topbar CSS: `.topbar-search`, `.topbar-icon-btn`, `.topbar-user-btn`, `.topbar-dropdown-menu`, `.topbar-user-menu`, `.topbar-user-header` classes
- RTL support for all topbar elements in `admin-modern-rtl.css`
- 20+ new translation keys added to both EN and AR language files
- Duplicate translation keys cleaned up across both locale files

**AdminSystemController** (`app/Http/Controllers/AdminSystemController.php`):
- 6 methods: activityLogs, notifications, settings, systemHealth, reportsCenter, mediaLibrary
- Routes: `system.activity-logs`, `system.notifications`, `system.settings`, `system.health`, `system.reports`, `system.media-library` (all under `/system` prefix with `master` middleware)

**Premium UX Components** (`public/js/admin-components.js`):
- Command Palette (Ctrl+K) — search across pages with keyboard navigation
- Toast notification system (`window.AdminToast.show()`) — success/error/warning/info variants with auto-dismiss
- Confirm dialog (`window.AdminConfirm.show()`) — promise-based confirmation modals

**Enhanced CSS Components** (`public/css/admin-modern.css`):
- Activity timeline (`.activity-timeline`, `.activity-item`, `.activity-icon`)
- Operations panel (`.operations-list`, `.operation-item`)
- Settings navigation (`.settings-nav`, `.settings-nav-item`)
- Media grid (`.media-grid`, `.media-card`, `.media-thumb`)
- Status chips, action dropdowns, bulk actions bar, column chooser
- Form enhancements (focus rings, help text, character counters)
- Skeleton loading animation
- Sticky table headers

**Page Header Modernization** (`includes/admin/header.blade.php`):
- Breadcrumb navigation
- Clean button row with icons
- Responsive flex layout

**Files changed**: `public/css/admin-modern.css`, `public/css/admin-modern-rtl.css`, `public/js/admin-components.js`, `includes/admin/side-bar.blade.php`, `includes/admin/header.blade.php`, `admin/dashboard.blade.php`, `layouts/admin/show.blade.php`, `layouts/admin/add.blade.php`, `app/Http/Controllers/AdminSystemController.php`, `app/Http/Controllers/DashboardController.php`, `resources/views/admin/system/*`, `resources/lang/en/app.php`, `resources/lang/ar/app.php`, `routes/web.php`

## Announcement Bar Module

Full-stack announcement bar for displaying rotating announcements below the site header.

**Backend** (`tog4dev-backend/`):
- Model: `Announcement` with scopes `active()`, `inDate()`, `forTarget($target)`
- Migration: `announcements` table (id, title, text, short_text, link, cta_text, badge_type, target_view, source_type, news_id, is_active, order_no, start_date, end_date)
- Admin Controller: `AnnouncementAdminController` (CRUD, toggle status, reorder) with strict validation for `source_type` (manual/news) and `news_id` (required when source is news)
- API Controller: `AnnouncementApiController` — `GET /api/v1/announcements?target=desktop|mobile`
- Admin Views: `admin/announcements/` (index, create, edit) with:
  - Source type selector (Manual vs Linked News) with auto-fill from news articles
  - Interactive live preview showing how announcements appear on the frontend
  - Desktop/mobile preview toggle
  - Locale-aware news URL generation (EN/AR slugs)
  - Drag reorder, status toggles, SweetAlert2 delete confirmation
- Announcement management is centralized in Admin → Announcements (removed from News forms)
- News forms retain auto-excerpt generation from body content (server-side + client-side)
- Sidebar: Standalone "Announcements" link with bullhorn icon above News & Media section
- Translation keys: 45+ announcement-related keys in both EN and AR

**Frontend** (`tog4dev-frontend/`):
- Service: `AnnouncementService` (`shared/services/announcement/`) with per-target caching via `shareReplay`, error-resilient (clears cache on failure, doesn't permanently cache errors)
- Component: `AnnouncementBarComponent` (`shared/components/announcement-bar/`) — standalone Angular component
- Features: auto-rotate (4.5s) with smooth fade transitions, pause on hover, swipe navigation, clickable messages when link exists, responsive (short_text for mobile), RTL support
- Admin-only visibility control: no close/dismiss button, no sessionStorage hide — bar always visible when active announcements exist, controlled exclusively from Admin Dashboard
- Badge types: LIVE (red), INFO (blue), ALERT (amber), NEW (green)
- Target views: desktop, mobile, both — API filters by target param
- Integration: Added to `app.component.html` below `<app-header>`

## Notes

- The migration `2024_06_21` was renamed to `2024_12_03_000312` to fix ordering (it references `payments` table created later)
- The `2025_09_06_214444` migration was wrapped with `hasColumn` check to avoid duplicate column error
- `app.component.ts` was patched with `isPlatformBrowser()` guards to prevent SSR crashes from browser-only APIs (sessionStorage, window)
- SSR compatibility fixes applied across ~20 components: all `window.location.href`, `window.scrollY`, `window.pageYOffset`, `window.location.origin` calls wrapped with `typeof window !== 'undefined'` guards
- All 6 Swiper slider components (home-slider, project-slider, our-stories, testimonials, about-us-slider, ngoverse-slider) protected with `isPlatformBrowser()` guard in `initSwiper()` to prevent `i.children is not iterable` SSR errors
- `MetaPixelService.fbq` getter guarded against SSR (`typeof window !== 'undefined'`) to prevent `window is not defined` errors during server rendering
