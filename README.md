# Scholarship & Labour Administration Portal

Laravel 8 application for managing mine labour records, scholarships, welfare grants, budgets, and related administration. The PHP entry point lives at the repository root (`index.php`), while the framework code and dependencies live in the `system/` directory (suitable for AMPPS or similar stacks where the web root is the `portal` folder).

## Requirements

- PHP 7.3 or 8.0 (see `system/composer.json`)
- Composer
- MySQL (or another database supported by Laravel)
- Node.js and npm (only if you compile front-end assets with Laravel Mix)

## Main capabilities

- **Labour management**: profiles, mineral titles / leases, complaints, CNIC updates, exports, printable cards, QR verification (`/verify-labour-card/{id}`)
- **Scholarships**: general, top position, special education, quality education schemes
- **Skill development**: diploma / skill-development schemes
- **Grants**: disabled mine labour, pulmonary, marriage, and deceased mine labour workflows with exports
- **Administration**: users, roles, permissions, offices, schemes, object heads, compilations, reconciliations, budgets, dashboard, fiscal year switching

Notable packages: DomPDF, Laravel Excel, Yajra DataTables, Laravel UI (see `system/composer.json`).

## Installation

1. **Web server**  
   Point the virtual host document root to this `portal` directory so `index.php` is the front controller. Ensure `mod_rewrite` is enabled if you use Apache.

2. **Environment**  
   Create `system/.env` and set at least `APP_NAME`, `APP_URL`, `APP_KEY`, database (`DB_*`), and mail settings as needed. Generate the key after the file exists:

   ```bash
   cd system
   php artisan key:generate
   ```

3. **Dependencies and database**

   ```bash
   cd system
   composer install
   php artisan migrate
   php artisan db:seed
   ```

4. **Front-end assets (optional)**  
   If you change JavaScript or CSS built with Mix:

   ```bash
   cd system
   npm install
   npm run dev
   ```

5. **URL rewriting**  
   Root `.htaccess` is listed in `.gitignore` so local rewrite rules are not committed. Copy a suitable `.htaccess` from another environment or add one that forwards non-file requests to `index.php`, matching your local base URL (for example `http://localhost/portal/` under AMPPS).

## Useful Artisan commands

```bash
cd system
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Default accounts (development)

Created by `DatabaseSeeder` / `UserSeeder`. **Change these passwords before any production use.**

| Role             | Email                 | Password  |
|------------------|-----------------------|-----------|
| Super Admin      | `superadmin@gmail.com` | `12345678` |
| Administrator    | `admin@gmail.com`      | `12345678` |

After seeding, sign in at `/login`. Authenticated users are redirected to `/admin/dashboard`.

## Repository layout

| Path        | Purpose                                      |
|-------------|----------------------------------------------|
| `index.php` | Bootstraps Laravel from `system/`            |
| `system/`   | Laravel application (app, config, routes…)   |
| `.gitignore`| Ignores `.env`, `vendor/`, `.htaccess`, etc.  |

## License

See `system/composer.json` (Laravel default is MIT unless overridden).
