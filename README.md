<div align="center">

# Khumaira Snack - UMKM Food E-Commerce Platform

### *Custom Weight Packaging, RajaOngkir Automated Shipping, and WhatsApp Gateway*

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

---

</div>

## About The Project

Food UMKM online store featuring flexible weight variant ordering (100g, 250g, 500g, 1kg), dynamic courier calculation (JNE, POS, TIKI, J&T), and direct WhatsApp order payload checkout.

---

## Key Features

- Weight-Based Pricing: Dynamically updates cart total based on selected gram weight packages.
- Automated Shipping Calculation: Integrates RajaOngkir API for origin-to-destination courier rate calculation.
- WhatsApp Direct Order: Generates pre-formatted WhatsApp chat payloads with customer items, address, and total.
- Admin Inventory & Revenue Dashboard: Sales charts, top-selling snack items, and order status updates.

---

## Technology Stack

- Backend: Laravel 10 (PHP 8.2)
- Database: MySQL 8.0
- External API: RajaOngkir Shipping Gateway
- Frontend: Bootstrap 5.3

---

## Getting Started

`ash
git clone https://github.com/raphlv/khumairasnack.git
cd khumairasnack
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
`

---

## Developer and Maintainer
Pangeran Ryan Pahlevi - https://pangeranryan.vercel.app

<!-- Last updated: 2026-09-01 14:35:44 -->
