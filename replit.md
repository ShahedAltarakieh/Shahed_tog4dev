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

## Notes

- The migration `2024_06_21` was renamed to `2024_12_03_000312` to fix ordering (it references `payments` table created later)
- The `2025_09_06_214444` migration was wrapped with `hasColumn` check to avoid duplicate column error
- `app.component.ts` was patched with `isPlatformBrowser()` guards to prevent SSR crashes from browser-only APIs (sessionStorage, window)
