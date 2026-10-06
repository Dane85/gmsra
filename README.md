# General Motors Salaried Retirees Association (GMSRA)

Official website and custom bespoke WordPress theme for the **General Motors Salaried Retirees Association (Oshawa)**.

🌐 **Live Website**: [https://gmsalariedretirees.com/](https://gmsalariedretirees.com/)

---

## 📌 Project Overview

This repository contains the complete local development environment and custom WordPress implementation for the GMSRA website. It features a bespoke WordPress theme tailored to provide retirees and association members with an accessible, high-contrast, and intuitive digital hub for announcements, events, meeting notes, photo galleries, and membership forms.

### Key Features
- **Bespoke Theme (`gmsra-bespoke`)**: Clean, lightweight, custom PHP and CSS design built from scratch without bloated page builders.
- **Accessible Typography & Contrast**: Optimized font scaling and high-contrast styling for senior legibility and WCAG compliance.
- **Dedicated Page Templates**:
  - `front-page.php` – Hero banner, quick announcements, mission statement, and featured navigation.
  - `page-about.php` – Association history, purpose, and board leadership.
  - `page-news-events.php` – Upcoming general meetings, community events, and news posts.
  - `page-membership-form.php` – Membership application details, dues instructions, and downloadable/fillable forms.
  - `page-contact-us.php` – Executive contact info and inquiry routing.
  - `single.php` – Event recaps, including the 2026 Annual BBQ gallery and meeting notes.
- **Event Photo Gallery**: Embedded gallery showcase featuring association gatherings.

---

## 📂 Repository Structure

```text
gmsra/
├── app/
│   ├── public/                      # WordPress web root
│   │   ├── wp-admin/                # WordPress core admin
│   │   ├── wp-includes/             # WordPress core includes
│   │   ├── wp-content/
│   │   │   ├── themes/
│   │   │   │   └── gmsra-bespoke/   # Custom GMSRA theme
│   │   │   │       ├── assets/      # CSS, JS, and image assets
│   │   │   │       ├── template-parts/
│   │   │   │       ├── functions.php
│   │   │   │       ├── style.css
│   │   │   │       └── *.php        # Custom page templates
│   │   │   └── uploads/             # Media library files
│   │   └── wp-config.php            # WordPress configuration
│   └── sql/
│       └── local.sql                # Complete database backup export
├── conf/                            # Local by Flywheel server configs (Nginx, PHP, MySQL)
├── .gitignore                       # Repository ignore rules
└── README.md                        # Project documentation
```

---

## 💻 Local Development Setup

This project is configured for **[Local by Flywheel](https://localwp.com/)**:

1. **Importing into Local**:
   - Clone or copy this directory into your `Local Sites` directory.
   - Alternatively, zip the `app/` and `conf/` directories and drag-and-drop into the Local application window.
2. **Database**:
   - A snapshot of the database is located at `app/sql/local.sql`.
   - You can import it using Adminer / Sequel Ace / MySQL CLI via Local's "Open Site Shell".
3. **Environment Specifications**:
   - **PHP**: 8.2+
   - **Web Server**: Nginx
   - **Database**: MySQL 8.0+ / MariaDB
   - **WordPress**: 6.x+

---

## 📄 License & Attribution

- **Theme Development**: Antigravity / Dane
- **Client / Organization**: General Motors Salaried Retirees Association (Oshawa)
