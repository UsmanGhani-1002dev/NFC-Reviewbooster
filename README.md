# ReviewBooster 🚀
### Premium NFC Reputation Management & Review Gating Solution
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

ReviewBooster is a sophisticated platform designed to help businesses skyrocket their online reputation. By leveraging NFC technology and smart "review gating," businesses can capture positive feedback on Google while handling negative experiences privately.

---

## 🌟 Key Features

### 🔐 Smart Review Gating
Automatically filter reviews:
- **Positive Experience (4-5 Stars):** Redirected instantly to the official Google Review page.
- **Negative Experience (1-2 Stars):** Captured via a private internal feedback form, allowing the business to resolve issues privately.

### 🏷️ Multi-Product Support
Seamlessly manage different physical NFC hardware:
- **NFC Cards:** Premium PVC cards for hand-to-hand interaction.
- **NFC Stickers:** Versatile stickers for tables, menus, or windows.
- **Acrylic Stands:** Professional counter-top displays for high-visibility spots.

### 🏢 Business Management
- **Centralized Dashboard:** Manage multiple business locations from a single account.
- **Dynamic QR Codes:** Every NFC link comes with a fallback QR code for non-NFC devices.
- **Live Preview:** Real-time mobile mockup preview while configuring cards.

### 📊 Admin & Analytics
- **Full Control:** Admin panel to manage businesses, cards, and users.
- **Subscription Engine:** Built-in tiered plans with Card and Review limits.
- **Analytics:** Track scans, review counts, and average ratings per location.

---

## 🛠️ Technology Stack

- **Backend:** Laravel 11.x (PHP 8.2+)
- **Database:** MySQL
- **Frontend:** TailwindCSS, Blade, Alpine.js
- **APIs:** Google Places API (Business Search & Verification)
- **Hardare:** Web NFC API integration for browser-based NFC encoding.

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- Google Maps API Key
- Strip Keys

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/UsmanGhani-1002dev/Reviewbooster.git
   cd reviewbooster
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup:**
   Configure your DB credentials in `.env` then run:
   ```bash
   php artisan migrate --seed
   ```

5. **Link Storage:**
   ```bash
   php artisan storage:link
   ```

6. **Run Locally:**
   ```bash
   php artisan serve
   ```

---

## 📝 License
This project is licensed under the MIT License.

---
*Developed by **Usman Ghani** & **ReviewBooster Team***
