# Stocket - Systeme de Gestion des Stocks

This project is a Laravel-based inventory management system for the BTP (Construction) sector. It allows you to manage products, suppliers, customers, stock entries and exits, track unpaid invoices, monitor inventory value over time, and receive low-stock alerts.

## Features

- Full CRUD for Users, Customers, Suppliers, Products, Stock Entries, and Stock Exits.
- Stock Entries and Exits with automatic stock deduction and validation.
- Business Intelligence Dashboard with Chart.js visualizations (doughnut, line charts).
- Inventory Snapshots - daily automated recording of total inventory value to track trends.
- Low Stock Email Alerts - automatic emails when products fall below their alert threshold.
- Unpaid Invoices (Impayes) - dedicated page with Mark as Paid action and Global Debt KPI.
- Per-product alert and safety stock thresholds.
- In-app Notifications - bell icon dropdown with overdue invoice alerts and critical stock alerts.
- Date-filterable KPIs - filter revenue, pending debt, and best-selling product by date range.
- SoftDeletes with restore/force-delete for all resources.
- Role-based access control (Admin, Storekeeper, Site Manager) via Spatie Permission.
- Responsive design with Tailwind CSS.
- Built with Laravel Jetstream (Livewire stack) for authentication and profile management.

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js and NPM
- A database (MySQL, PostgreSQL, or SQLite)

## Installation

Follow these steps step by step:

### 1. Clone the repository

Open your terminal and run:

```bash
git clone https://github.com/oumaimasbai12/gestionStock.git
cd gestionStock
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install JavaScript dependencies

```bash
npm install
npm run build
```

### 4. Configure environment

Copy the example environment file:

```bash
cp .env.example .env
```

Generate the application encryption key:

```bash
php artisan key:generate
```

### 5. Set up your database

Open the `.env` file in a text editor and update these lines with your database information:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

For SQLite (simplest option), change to:

```
DB_CONNECTION=sqlite
```

Then create an empty file: `touch database/database.sqlite`

### 6. Run database migrations and seeders

This creates the tables and fills them with sample data:

```bash
php artisan migrate --seed
```

### 7. Configure mail (optional, for low stock email alerts)

By default the project uses Mailpit for local development. For production, update these in `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Stocket"
```

### 8. Start the development server

```bash
php artisan serve
```

Open your browser and go to `http://localhost:8000`.

### 9. Login

Default admin account created by the seeder:

- Email: `admin@stocket.com`
- Password: `12345678`

## Scheduled Tasks

The following commands run automatically via the scheduler. Add this cron entry to your server:

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

| Command | Schedule | Description |
|---------|----------|-------------|
| `inventory:snapshot` | Daily at 00:00 | Records total inventory value |
| `app:check-stock-levels` | Daily at 08:00 | Sends low stock email alerts |
| `notifications:check` | Every 6 hours | Creates in-app notifications for overdue invoices and critical stock |

## Project Structure

```
app/
├── Console/Commands/
│   ├── CheckNotifications.php
│   ├── CheckStockLevels.php
│   └── InventorySnapshot.php
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── ProductController.php
│   ├── SupplierController.php
│   ├── CustomerController.php
│   ├── StockEntryController.php
│   └── StockExitController.php
├── Mail/
│   └── LowStockAlert.php
├── Models/
│   ├── Product.php
│   ├── Supplier.php
│   ├── Customer.php
│   ├── StockEntry.php
│   └── StockExit.php
└── Notifications/
    ├── CriticalStockAlert.php
    └── OverdueInvoice.php
```
