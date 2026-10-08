# General Motors Salaried Retirees Association (GMSRA)

Official website and bespoke custom WordPress theme for the **General Motors Salaried Retirees Association (Oshawa)**.

🌐 **Production Website**: [https://gmsra.durhamweb.design](https://gmsra.durhamweb.design)  
🔌 **Direct Port Fallback**: [http://5.161.161.222:8080](http://5.161.161.222:8080)

---

## ⚡ Project Overview

This repository contains the complete local development environment and custom WordPress implementation for the GMSRA website. It features a bespoke WordPress theme tailored to provide retirees and association members with an accessible, high-contrast, and intuitive digital hub for announcements, events, meeting notes, photo galleries, and membership forms.

### Key Features
- **Bespoke Theme (`gmsra-bespoke`)**: Clean, lightweight PHP and CSS design built from scratch with zero third-party page builders or dependency bloat.
- **Accessible Typography & Contrast**: Scaled fonts and high-contrast WCAG-compliant styling for senior legibility.
- **Dynamic Relative URL Routing**: Theme intercepts internal links and converts them to root-relative paths, preserving custom ports (such as `:8080`) without broken redirects.
- **Dual-Environment Database Configuration**: `wp-config.php` automatically detects whether it is running on LocalWP (`localhost`) or the Hetzner cloud VPS (`ubuntu-2gb-ash-2`) and adjusts database credentials seamlessly without merge conflicts.
- **Dedicated Page Templates**:
  - `front-page.php` — Hero banner, quick announcements, mission statement, and featured cards.
  - `page-about.php` — Association history, leadership, and purpose.
  - `page-news-events.php` — General meetings, community events, and news posts.
  - `page-membership-form.php` — Membership application details and printable/fillable PDF forms.
  - `page-contact-us.php` — Executive contact information and routing.
  - `single.php` — Event recaps, photo galleries, and meeting archives.

---

## 🚀 Live Production Infrastructure

- **Server Host**: `5.161.161.222` (`ubuntu-2gb-ash-2`)
- **Server Path**: `/var/www/gmsra/app/public`
- **Web Server**: Nginx 1.24.0 with Let's Encrypt ECDSA SSL
- **PHP**: PHP 8.3-FPM
- **Database**: MariaDB 10.11 (`gmsra_db`)
- **Primary Contact / Inquiries**: `GMSRA@gmsalariedretirees.com`

---

## 💻 Local Development Setup (Local by Flywheel)

1. Clone or copy this directory into your `Local Sites` directory (`C:\Users\watts\Local Sites\gmsra`).
2. Snapshot of the database is located at `app/sql/local.sql`.
3. To deploy updates to production:
   ```powershell
   git add .
   git commit -m "update message"
   git push origin main
   ssh root@5.161.161.222 "git -C /var/www/gmsra pull origin main && systemctl reload php8.3-fpm"
   ```

---

© 2026 General Motors Salaried Retirees Association (Oshawa). Hosted & engineered by Epic Expressions.