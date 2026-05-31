# Stocket — Architecture Overview

## Tech Stack

| Component       | Technology                          |
|-----------------|-------------------------------------|
| Framework       | Laravel 10                          |
| PHP             | ^8.1                                |
| Frontend        | Laravel Jetstream (Livewire v3)     |
| CSS             | Tailwind CSS                        |
| Database        | MySQL / PostgreSQL / SQLite         |
| Auth            | Laravel Sanctum + Fortify           |
| Roles & Perms   | Spatie laravel-permission v6        |
| PDF             | barryvdh/laravel-dompdf             |
| JS              | Alpine.js (via Livewire)            |
| Build           | Vite                                |

## Directory Structure

```
app/
├── Console/Commands/
│   ├── CheckNotifications.php   — creates in-app notifications (every 6h)
│   ├── CheckStockLevels.php     — sends low-stock email alerts (daily 08:00)
│   └── InventorySnapshot.php    — records daily inventory value (daily 00:00)
├── Http/Controllers/
│   ├── DashboardController.php      — BI dashboard + PDF exports
│   ├── ProductController.php        — CRUD + CSV import
│   ├── StockEntryController.php     — stock entries (inbound)
│   ├── StockExitController.php      — stock exits (outbound, invoicing)
│   ├── SupplierController.php       — CRUD (admin only)
│   ├── CustomerController.php       — CRUD + sales history (admin only)
│   ├── ChantierController.php       — CRUD construction sites (admin only)
│   ├── UserController.php           — CRUD users (admin only)
│   └── StockHistoryController.php   — unified entry/exit history viewer
├── Mail/
│   └── LowStockAlert.php            — mailable for low stock email alerts
├── Models/
│   ├── User.php          — Authenticatable, HasRoles, SoftDeletes
│   ├── Product.php       — SoftDeletes
│   ├── StockEntry.php    — SoftDeletes, belongsTo Product/Supplier/Chantier
│   ├── StockExit.php     — SoftDeletes, belongsTo Product/Customer/Chantier
│   ├── Customer.php      — SoftDeletes, hasMany StockExit
│   ├── Supplier.php      — SoftDeletes
│   └── Chantier.php      — hasMany User/StockEntry/StockExit
├── Notifications/
│   ├── CriticalStockAlert.php   — database notification for critical stock
│   └── OverdueInvoice.php       — database notification for overdue invoices
├── View/Components/
│   ├── AppLayout.php
│   └── GuestLayout.php
└── Providers/...
```

## Database Schema

### Tables

**users**
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | |
| email | string | unique |
| password | string | bcrypt |
| chantier_id | bigint FK→chantiers | nullable, for site_manager role |
| two_factor_secret | text | nullable |
| two_factor_recovery_codes | text | nullable |
| two_factor_confirmed_at | timestamp | nullable |
| remember_token | string | |
| profile_photo_path | string | |
| current_team_id | bigint | nullable (Jetstream teams) |
| deleted_at | timestamp | soft deletes |
| created_at / updated_at | timestamp | |

**products**
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | |
| category | string | default 'Divers' |
| purchase_price | decimal(10,2) | |
| stock | integer | |
| alert_quantity | integer | default 20, threshold for low-stock email |
| safety_stock | integer | default 10, threshold for critical in-app alert |
| description | text | nullable |
| deleted_at | timestamp | soft deletes |

**stock_entries** (inbound)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| product_id | bigint FK→products | |
| supplier_id | bigint FK→suppliers | |
| chantier_id | bigint FK→chantiers | nullable |
| quantity | integer | |
| document | string | nullable, reference doc number |
| deleted_at | timestamp | soft deletes |

**stock_exits** (outbound / sales)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| product_id | bigint FK→products | |
| customer_id | bigint FK→customers | nullable |
| chantier_id | bigint FK→chantiers | nullable |
| quantity | integer | |
| unit_price | decimal(10,2) | selling price |
| paid_amount | decimal(10,2) | amount already paid |
| amount_due | decimal(10,2) | remaining debt |
| payment_status | string | 'paid', 'partial', 'unpaid' |
| document | string | nullable |
| deleted_at | timestamp | soft deletes |

**suppliers**
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| nit | string | unique, tax ID |
| name | string | |
| phone | string | |
| email | string | unique |
| address | string | |
| deleted_at | timestamp | soft deletes |

**customers**
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| document_id | string | unique, ID/document number |
| customer_type | string | 'individual', 'artisan', 'entreprise' |
| name | string | |
| email | string | unique |
| address | string | |
| phone | string | |
| ice | string | nullable, for enterprise type |
| deleted_at | timestamp | soft deletes |

**chantiers** (construction sites)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | |
| created_at / updated_at | timestamp | |

**inventory_snapshots**
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| date | date | unique |
| total_value | decimal(15,2) | SUM(stock × purchase_price) |

**chantier_product** (pivot)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| chantier_id | bigint FK→chantiers | |
| product_id | bigint FK→products | |
| quantity_consumed | integer | |

**roles / permissions** (Spatie)
Standard Spatie permission tables: `permissions`, `roles`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`.

**notifications** (Laravel)
Standard Laravel notifications table with UUID primary key.

### ER Relationships

```
User *──1 Chantier (optional, for site_manager role)

Product 1──* StockEntry
Product 1──* StockExit
Product *──* Chantier (via chantier_product pivot)

Supplier 1──* StockEntry

Customer 1──* StockExit

Chantier 1──* StockEntry
Chantier 1──* StockExit
Chantier 1──* User
```

## Routes (web.php)

All routes are behind `auth:sanctum`, Jetstream session, and `verified` middleware.

### Dashboard
- `GET /dashboard` — BI dashboard with KPIs
- `GET /dashboard/export/bi` — PDF BI report
- `GET /dashboard/export/factures` — PDF invoices report

### Resource Controllers (all with trash/restore/forceDelete)
- `users` — CRUD (admin only)
- `customers` — CRUD + `GET /customers/{id}/sales` (admin only)
- `products` — CRUD + `POST /products/import` CSV import (admin/storekeeper)
- `suppliers` — CRUD (admin only)
- `chantiers` — CRUD, no show route (admin only)
- `entries` — CRUD (all roles, site_manager scoped to own chantier)
- `exits` — CRUD + `GET /exits/pending` + `PATCH /exits/{id}/mark-paid` (all roles)

### Stock History
- `GET /historique-stock` — unified view of entries + exits

### Notifications
- `PATCH /notifications/{id}/read` — mark one as read
- `POST /notifications/read-all` — mark all as read

## Roles & Permissions

Three roles managed via **Spatie laravel-permission**:

| Role | Access |
|------|--------|
| **admin** | Full access — dashboard, users, products, suppliers, customers, chantiers, entries, exits, stock history, notifications |
| **storekeeper** | Products (view/create/edit), Entries (view/create), Exits (view/create), Stock history |
| **site_manager** | Scoped to their assigned chantier — entries, exits, stock history. Cannot create/edit/delete users, suppliers, customers, or products. Cannot update or delete entries/exits. |

## Key Business Logic

### Stock Movement
- **Entry creation** → adds quantity to `products.stock`
- **Exit creation** → subtracts quantity from `products.stock` (validates sufficient stock)
- **Entry/Exit update** → adjusts stock by the quantity difference
- **Soft delete** does NOT reverse stock changes (design choice: audit trail)

### Invoicing & Debt Tracking
- Each `StockExit` tracks `unit_price`, `paid_amount`, `amount_due`, and `payment_status`
- `GET /exits/pending` shows unpaid/partial exits
- `PATCH /exits/{id}/mark-paid` sets `payment_status=paid`, `amount_due=0`
- Dashboard KPI `globalDebt` sums all `amount_due` where status is unpaid/partial

### Dashboard KPIs (date-filterable)
- Total inventory value (stock × purchase_price)
- Global debt (sum of amount_due on unpaid/partial exits)
- Monthly sales (sum of quantity × unit_price in date range)
- Stock alerts count + healthy stock percentage
- Revenue (paid exits) in date range
- Pending debt in date range
- Best-selling product in date range
- Top 5 chantiers by consumption (doughnut chart)
- Category distribution (doughnut chart)
- 7-day entry/exit activity (line chart)

### Scheduled Tasks

| Command | Schedule | Description |
|---------|----------|-------------|
| `inventory:snapshot` | daily 00:00 | Records total inventory value in `inventory_snapshots` table |
| `app:check-stock-levels` | daily 08:00 | Sends email alert (via `LowStockAlert` mailable) if any product stock ≤ alert_quantity |
| `notifications:check` | every 6h | Creates in-app DB notifications for overdue invoices (>7 days unpaid) and critical stock (stock ≤ safety_stock). Avoids duplicate unread notifications. |

### Notifications System
- **Overdue invoice**: created when a StockExit has payment_status unpaid/partial and is >7 days old
- **Critical stock**: created when a product's stock ≤ safety_stock threshold
- Both target all admin users via the `notifications` (database) channel
- Displayed in a dropdown in the nav bar via Livewire
- Notification badge shows unread count

### CSV Import
- `POST /products/import` accepts a semicolon-delimited CSV
- Truncates existing products before import to avoid duplicates
- Maps columns: name, category, purchase_price, stock, description

## Frontend Architecture

### Layout
- `resources/views/layouts/app.blade.php` — main authenticated layout
  - Renders `x-banner` (flash messages)
  - Renders `@livewire('navigation-menu')` (navbar)
  - Renders optional `$header` section
  - Renders `$slot` for page content
- `resources/views/layouts/guest.blade.php` — unauthenticated layout

### Navigation Menu
- `resources/views/navigation-menu.blade.php` — Livewire component
- Role-based link visibility (admin sees all, storekeeper/site_manager see subset)
- Notification dropdown with mark-as-read
- User settings dropdown (profile, logout)
- Responsive: hamburger menu on mobile

### Components
- Blade components under `resources/views/components/` (Jetstream default + custom)
- Tailwind-designed with custom color palette (cream, ink, sage, accent)

### Views per Resource
Each controller has a corresponding directory under `resources/views/`:
- `products/`, `suppliers/`, `customers/`, `chantiers/`, `entries/`, `exits/`, `users/`, `stock-history/`, `profile/`, `auth/`, `api/`
- Each typically includes: `index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`, plus `trash.blade.php` for soft-delete resources

### PDF Views
- `resources/views/pdf/layout.blade.php` — shared PDF layout
- `resources/views/pdf/bi-report.blade.php` — business intelligence report
- `resources/views/pdf/factures.blade.php` — invoices report

### Email Views
- `resources/views/emails/low-stock-alert.blade.php` — low stock notification markdown email

## Configuration

Key config files:
- `.env` — database, mail, app settings
- `config/permission.php` — Spatie role/permission tables config
- `config/jetstream.php` — Jetstream features stack (Livewire)
- `tailwind.config.js` — custom design tokens
- `vite.config.js` — build configuration

## Testing

- PHPUnit with `phpunit.xml`
- Standard Laravel test structure under `tests/`
- Factories available in `database/factories/`

## Deployment

- Dockerfile included for containerized deployment
- Cron entry required for scheduled tasks:
  ```
  * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
  ```
- Default admin credentials: `admin@stocket.com` / `12345678`
