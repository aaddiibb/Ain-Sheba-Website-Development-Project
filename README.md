# Ain Sheba — Legal Awareness & Civic Rights Training Portal

A full-stack web application that helps Bangladeshi citizens understand their legal rights through structured online programs, live consultations with lawyers, and verified certificates of completion.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Blade templates, Bootstrap 5, Vanilla JS |
| Database | MySQL 8 |
| PDF generation | `barryvdh/laravel-dompdf` |
| Icons | Bootstrap Icons |

---

## Setup

### Requirements

- PHP 8.2+
- Composer
- MySQL 8
- XAMPP (or any LAMP/LEMP stack)

### Installation

```bash
# Clone the repository
git clone <repo-url>
cd "Ain Sheba"

# Install PHP dependencies
composer install

# Copy and configure environment
cp .env.example .env
php artisan key:generate

# Edit .env — set DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Run migrations and seed demo data
php artisan migrate:fresh --seed
```

### Serving locally

Place the project folder inside `xampp/htdocs/` and start Apache + MySQL via XAMPP Control Panel, then visit `http://localhost/Ain%20Sheba/public`.

---

## Default Credentials

All accounts use password: **`password`**

| Role | Email |
|---|---|
| Admin | admin@ainsheba.test |
| Lawyer 1 | lawyer1@ainsheba.test |
| Lawyer 2 | lawyer2@ainsheba.test |
| Lawyer 3 | lawyer3@ainsheba.test |
| Lawyer 4 | lawyer4@ainsheba.test |
| Citizen 1–12 | citizen1@ainsheba.test … citizen12@ainsheba.test |

---

## Key Features

### Public
- Browse 8 published legal awareness programs with filtering by area, level, and language
- View lawyer profiles with availability, programs, and ratings
- Public certificate verification by code
- Contact form

### Citizens
- Register for programs and track module progress
- Complete modules and receive a verifiable PDF certificate on completion
- Book consultations with lawyers by available date and time slot
- Leave star ratings and reviews for programs
- Receive in-app notifications (program updates, consultation confirmations)

### Lawyers
- Create and manage programs with modules, assessments, and resource links
- Drag-to-reorder modules
- Set weekly availability schedule for consultations
- Respond to consultation bookings and manage their status

### Admin
- Dashboard with platform-wide statistics
- User management (view, activate/deactivate)
- Program status management (publish, archive, draft)
- Legal area management (CRUD)
- Consultation oversight
- Contact message inbox

---

## Roles

| Role | Description |
|---|---|
| `citizen` | Learners who enrol in programs and book consultations |
| `lawyer` | Practitioners who create programs and accept consultations |
| `admin` | Platform administrators with full oversight |

---

## Certificate Verification

Every completed certificate has a unique code (e.g. `CERT-A1B2C3D4E5`). Anyone can verify a certificate at:

```
/verify/{certificate_code}
```

---

## Deployment

### XAMPP local demo (Windows)

1. Copy the project folder into `E:\xampp\htdocs\Ain Sheba\`
2. Open XAMPP Control Panel and start **Apache** and **MySQL**
3. Create the database: open `http://localhost/phpmyadmin` → New → `ain_sheba_db`
4. Set `.env` values: `DB_DATABASE=ain_sheba_db`, `DB_USERNAME=root`, `DB_PASSWORD=`
5. Run in the project root:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   ```
6. Visit `http://localhost/Ain%20Sheba/public/`

### Shared hosting (cPanel)

1. Upload all project files (except `public/`) to a private directory, e.g. `~/ain_sheba/`
2. Upload the contents of `public/` to `public_html/` (or your subdomain's document root)
3. Edit `public/index.php` — update both `require` paths to point to `~/ain_sheba/`
4. Create the MySQL database and user via cPanel → MySQL Databases
5. Set the production `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://yourdomain.com`, and DB credentials
6. SSH into the server and run:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
7. Ensure `storage/` and `bootstrap/cache/` are writable: `chmod -R 775 storage bootstrap/cache`

---

## Project Structure (key directories)

```
app/
  Http/Controllers/
    Admin/          — admin panel controllers
    Citizen/        — citizen dashboard and learning controllers
    Lawyer/         — lawyer panel controllers
    Public/         — public-facing pages
  Models/           — Eloquent models
database/
  migrations/       — all schema migrations
  seeders/          — LegalAreaSeeder + DemoSeeder
resources/views/
  admin/            — admin panel Blade views
  citizen/          — citizen panel Blade views
  lawyer/           — lawyer panel Blade views
  public/           — public-facing Blade views
  layouts/          — shared layout files
  components/       — reusable Blade components
public/
  css/app.css       — custom styles
  js/app.js         — vanilla JS utilities
```
