# AGENTS.md

## Project Overview

Laravel 13 item/gudang (warehouse) management app. UI is in Bahasa Indonesia.

## Stack

- **Backend**: Laravel 13, PHP 8.3+, MySQL
- **Auth**: Laravel Breeze
- **RBAC**: Spatie Laravel Permission (roles: Super Admin, Admin Gudang, Staff Gudang, Viewer)
- **Frontend**: Bootstrap 5 + jQuery + DataTables + SweetAlert2 + Alpine.js (NOT Tailwind — see Gotchas)
- **Assets**: Vite with SCSS compilation (`resources/sass/app.scss` entry)
- **Excel**: Maatwebsite Excel for import/export
- **Testing**: PHPUnit 12, SQLite in-memory

## Architecture

- **Repository + Service pattern**: Controllers → Services → Repositories → Eloquent Models
- All services extend `BaseService`, all repositories extend `BaseRepository` (in `app/Repositories/`, `app/Services/`)
- `BaseRepository` resolves its model via `model()` method; new repos must implement this
- New repos should also be registered in `app/Providers/RepositoryServiceProvider.php`
- Custom middleware registered in `bootstrap/app.php`: `permission` → `CheckPermission`, `warehouse.access` → `CheckWarehouseAccess`
- **Policies** (`app/Policies/`): `ItemPolicy` and `WarehousePolicy` combine Spatie permission checks with warehouse-scoping. Super Admin bypasses warehouse scope; non-admin users are restricted to their assigned warehouses.

## Commands

- **First-time setup**: `composer setup` (installs deps, generates key, runs migrations, builds assets)
- **Dev server**: `composer dev` (runs `php artisan serve`, `queue:listen`, `npm run dev` concurrently)
- **Run tests**: `composer test` (clears config cache then runs `php artisan test`)
- **Run single test**: `php artisan test --filter=TestClassName`
- **Lint/format**: `./vendor/bin/pint` (Laravel Pint, uses defaults — no config file)
- **Build assets**: `npm run build`
- **Migrations**: `php artisan migrate`
- **Seed database**: `php artisan db:seed`

## Permissions

Defined in `database/seeders/RolePermissionSeeder.php`. Key permission names:
`item.view`, `item.create`, `item.edit`, `item.delete`, `item.export`, `item.import`, `warehouse.manage`, `user.manage`, `role.manage`, `permission.manage`, `activity-log.view`

Route-level permission checks use Laravel's `can:` middleware (e.g., `can:item.view`).

## Gotchas

- **Tailwind files are Breeze leftovers, do not use**: `tailwind.config.js`, `postcss.config.js`, and `resources/css/app.css` (with `@tailwind` directives) exist from the Breeze scaffold but are NOT compiled or used. The app uses Bootstrap 5 via SCSS (`resources/sass/app.scss`). Only `resources/sass/app.scss` and `resources/js/app.js` are Vite entry points.
- **CDN-loaded assets**: Bootstrap Icons, DataTables CSS, and SweetAlert2 CSS are loaded from CDN in `resources/views/layouts/app.blade.php`, not via Vite.
- `.npmrc` sets `ignore-scripts=true` — npm lifecycle scripts are blocked

## Conventions

- 4-space indent, UTF-8, LF line endings (`.editorconfig`)
- Use `StoreXxxRequest` / `UpdateXxxRequest` form request classes (in `app/Http/Requests/`)
- Seeders run in order: RolePermissionSeeder → WarehouseSeeder → ItemSeeder
- Test coverage is minimal (only Breeze scaffolding tests) — do not assume comprehensive test patterns exist yet
