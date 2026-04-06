# Tog4Dev - Fundraising & Donation Platform

## Project Overview

Tog4Dev is a fundraising and charitable donation platform targeting Jordanian users, supporting features like emergency relief projects, subscriptions, crowdfunding, and payment integrations (Orange Money, eFAWATEERcom, PayTabs, Network/Mastercard).

The project uses a decoupled architecture:
- **Frontend**: Angular 19 with SSR (Server-Side Rendering) via `@angular/ssr`
- **Backend**: Laravel 10.x (PHP) — not currently served in Replit, APIs point to external backend

## Project Structure

```
tog4dev-backend/     # Laravel 10 PHP backend
  app/               # Controllers, Models, Services
  config/            # Laravel configuration
  database/          # Migrations and seeders
  routes/            # API and web routes
  composer.json      # PHP dependencies

tog4dev-frontend/    # Angular 19 frontend
  src/               # Application source
    app/             # Components, services, modules
    assets/          # Images, i18n JSON files
    environments/    # Environment configs
  angular.json       # Angular CLI config (port 5000, host 0.0.0.0, allowedHosts: all)
  package.json       # npm dependencies
  server.ts          # SSR Express server
  proxy.conf.json    # Dev proxy to external backend API
```

## Running the App

The frontend runs in development mode with Angular CLI dev server:

```
cd tog4dev-frontend && npm start
```

Serves on: `http://0.0.0.0:5000`

## Key Configuration

- **API URL (dev)**: `https://tog4dev-backend.dev/` (points to external backend)
- **API URL (prod)**: `https://admin.tog4dev.com/`
- **Dev proxy**: `/api/v1/**` → `https://stage-admin.tog4dev.com/api/v1`
- **Languages**: Arabic (ar) and English (en) with `@ngx-translate`
- **Styling**: Bootstrap 5.3 + SCSS + FontAwesome

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
| Database | MySQL |
| Package Manager (FE) | npm |
| Package Manager (BE) | Composer |
| i18n | @ngx-translate (AR/EN) |
