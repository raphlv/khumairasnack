<div align="center">

# Khumaira Snack - UMKM Food E-Commerce Platform

### *Online Food Ordering, Catalog Management, and Automated Shipping Integration*

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

---

</div>

## Overview

Khumaira Snack is an e-commerce platform specifically built for food UMKM businesses. It enables customers to browse snack products, select custom weight packages, calculate automated shipping costs across Indonesian cities, and checkout seamlessly via WhatsApp or online payment.

---

## Key Features

### 1. Interactive Snack Catalog and Custom Packaging
- Category filtering (Spicy Snacks, Savory Chips, Sweet Pastries).
- Weight variant selection (100g, 250g, 500g, 1kg) with dynamic price adjustment.
- High-res product image gallery with stock indicators.

### 2. Automated Shipping Calculation (RajaOngkir API)
- Real-time courier tariff calculation (JNE, POS, TIKI, J&T).
- Destination origin-to-city postal code lookup.
- Airway Bill (Resi) tracking for dispatched customer orders.

### 3. Direct WhatsApp and Online Checkout
- Automated WhatsApp order payload generation with itemized list and total price.
- Admin dashboard to update order status (Pending, Paid, Packaged, Shipped).
- Sales revenue and monthly order analytics charts.

---

## Installation and Setup

`ash
git clone https://github.com/raphlv/khumairasnack.git
cd khumairasnack

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate

# Import database khumairasnack_db.sql or migrate:
php artisan migrate --seed
php artisan serve
`

---

## License and Author

Distributed under the MIT License.

Author: Pangeran Ryan Pahlevi (https://github.com/raphlv)  
Email: pangeranryan080504@gmail.com  

---
<div align="center">
  <sub>Automated Sync Enabled for Contribution Tracking | Last Updated: 2026-08-18 14:40:47</sub>
</div>

<!-- Last updated: 2026-08-24 16:15:39 -->
