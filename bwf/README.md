# Bramley Window Factory — Window & Door Pricing Engine (Laravel 11)

Production CPQ system: product-first catalogue, per-product configurators with live
database-driven pricing, quotation engine with PDF output, and a separate Staff Portal
CMS where every price, rule and product is editable without code changes.

## Requirements
- PHP 8.3+ with extensions: pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, gd (for PDF)
- Composer 2
- MySQL 8 (or MariaDB 10.6+)

## Install (fresh server or local)
```bash
# 1. put this folder at your web root (or clone it), then:
composer install

# 2. environment
cp .env.example .env
php artisan key:generate
# edit .env → set DB_DATABASE / DB_USERNAME / DB_PASSWORD (create the MySQL database first)

# 3. database — creates ALL tables and seeds the full catalogue
php artisan migrate --seed

# 4. run
php artisan serve          # local:   http://127.0.0.1:8000
# production: point your vhost/document-root at /public (Apache .htaccess included; for nginx use the standard Laravel try_files rule)
```

No npm build is required to run — the storefront uses the Tailwind CDN so the site works
immediately after `composer install`. (You can later switch to a compiled Tailwind build if preferred.)

## Log in to the Staff Portal
- URL: `/staff/login` (link also in the footer)
- Email: `admin@bramleywindowfactory.co.uk`
- Password: `ChangeMe!2026`  ← **change this immediately after first login/deploy**

## What's seeded
- Categories: Windows, Doors, Conservatories, Glass + all sub-ranges (Bramley navigation)
- **58 individual casement window products** — each with its own URL
  (`/casement-windows/casement-01` … `/casement-windows/casement-58`), page, diagram,
  configurator and database record. Casements 1–31 carry verified base list prices;
  32–58 and other ranges are marked unverified (amber in Staff Portal) until you enter
  factory pricing.
- Sliding sash, tilt & turn, French, composite, patio, bi-fold and uPVC door products.
- Full attribute templates (verified option sets: 8 colours w/ lead times, 17 glass styles,
  cills, handles, U-values, Georgian bars with conditional bar counts, thresholds, etc.)
- 18 pricing rules + 6 validation rules (min/max sizes, max area, and the enforced
  toughened-glass rule for glazing below 800 mm).
- Price lists: TRADE / DEALER / RETAIL. VAT: 20% standard, 0% new-build. Delivery per order.

## Where things live
- Pricing engine: `app/Services/PricingEngine.php` (generic evaluator — contains **no prices**)
- Rules & rates: database tables `pricing_rules`, `validation_rules`, `price_lists`,
  `vat_rules`, `delivery_rules` — all editable in the Staff Portal
- Catalogue: `categories`, `products`, `attribute_groups`, `attributes`, `attribute_options`
- Quotes: `quotes`, `quote_items` (frozen JSON snapshots — quotes never reprice)
- Storefront views: `resources/views/shop/` · Quote/PDF: `resources/views/quote/`
- Staff Portal: `resources/views/admin/` · routes in `routes/web.php`

## PDF quotes
`barryvdh/laravel-dompdf` is included in composer.json; the "Download PDF" button uses it
automatically. If the package is removed the route falls back to a print-friendly HTML view.

## Important commercial note
Amber-flagged rates are **example placeholders** — replace them with your confirmed factory
pricing in the Staff Portal before going live. Verified figures (green) came from the
published reference list prices.
