<h1 align="center">🌌 ANAKT Blog</h1>
<p align="center"><em>A fan-operated archive for the Alien Stage universe</em></p>

<p align="center">
  <img src="public/images/ANAKT-logo.png" alt="ANAKT Logo" width="200" />
</p>

<p align="center">
  <a href="https://laravel.com/"><img src="https://img.shields.io/badge/built%20with-Laravel-red?style=flat&logo=laravel" alt="Laravel Badge"></a>
  <a href="#"><img src="https://img.shields.io/badge/frontend-Blade%20%2B%20Vite-blue?style=flat&logo=tailwind-css" alt="Blade/Vite Badge"></a>
  <a href="#"><img src="https://img.shields.io/badge/UI-Radix%20UI-lightgrey?style=flat" alt="Radix UI Badge"></a>
  <a href="#"><img src="https://img.shields.io/badge/license-Fan%20Project-important?style=flat" alt="Fan License Badge"></a>
</p>

---

## 🧭 Overview

**ANAKT** is a CRUD-based blog platform developed in Laravel, designed to document the rise and fall of Earth’s final performers — the contestants of the **Alien Stage** universe. 

> _“To document is to remember. To remember is to resist.”_

---

## ✨ Features

- 📝 CRUD for blog posts and memory entries  
- 👥 Role-based access (Admins & Garden Contributors)  
- 📊 Radix UI-powered Admin Dashboard  
- 💬 Comments and post interaction  
- 🔎 Search, filters, and user statistics  
- 🖼️ Post image uploads  
- 🔐 Auth via Laravel Breeze  
- 📁 Personal profile and post tracking

---

## ⚙️ Tech Stack

| Layer      | Tech                                |
|------------|-------------------------------------|
| Backend    | Laravel 11                          |
| Frontend   | Blade + Vite + Tailwind CSS         |
| UI Library | Radix UI                            |
| Database   | MySQL (phpMyAdmin for local dev)    |
| Auth       | Laravel Breeze                      |

---

## 🚀 Getting Started

```bash
# 1. Clone this repo
git clone https://github.com/jashuleyn/ANAKT-blog.git
cd ANAKT-blog

# 2. Install PHP and JS dependencies
composer install
npm install && npm run dev

# 3. Configure the environment
cp .env.example .env
php artisan key:generate

# 4. Set up database
php artisan migrate --seed

# 5. Run the app
php artisan serve

