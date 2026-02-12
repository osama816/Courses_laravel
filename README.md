# 📚 CourseBook - Online Course Booking Platform

<div align="center">

![CourseBook Logo](https://via.placeholder.com/800x230/2563eb/ffffff?text=CourseBook+-+Premium+Learning+Platform)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Tailwind](https://img.shields.io/badge/Tailwind-3.4+-06B6D4?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)](https://mysql.com)
[![AI](https://img.shields.io/badge/AI-Powered-7C3AED?style=for-the-badge&logo=openai)](https://openai.com)

**A high-performance, AI-integrated online learning management system with premium aesthetics.**

[Key Features](#-key-features) • [Tech Stack](#-tech-stack) • [Installation](#-installation) • [Deployment Guide](#-deployment-guide) • [Full Documentation](./DOCUMENTATION.md)

</div>

---

## 🎯 Project Overview

**CourseBook** transforms the online learning experience through a sophisticated, user-centric platform. It combines robust booking mechanics with cutting-edge AI support, multi-gateway payments, and a premium design language compatible with both Light and Dark modes.

---

## ✨ Key Features

### 🤖 Intelligent AI Support (MCP Powered)
- **Context-Aware Assistance**: Learns from student interactions to provide relevant help.
- **Automated Workflow**: Check payment status and booking details directly via chat.
- **Multi-Lingual NLP**: Seamlessly switch between Arabic (RTL) and English (LTR).
- **Proactive Support**: Intelligent course recommendations based on user interest.

### 🎫 Advanced Booking Engine
- **Live Seat Tracking**: Real-time availability using Livewire 3 snapshots.
- **Dynamic Scheduling**: Interactive calendars and course timelines.
- **Comprehensive Lifecycle**: Manage bookings from pending enrollment to course completion.
- **Automated Invoicing**: Instant PDF generation for every confirmed transaction.

### 💳 Global Payment Ecosystem
- **Paymob Integration**: Full support for Visa/Mastercard, Valu, and Mobile Wallets.
- **MyFatoorah Support**: Regional specialized payments (KNET, Mada, Apple Pay).
- **Security First**: 3D Secure verification and automated callback reconciliation.

### 🎨 Premium UI/UX Ecosystem
- **RTL & LTR Ready**: Native support for Arabic and English layouts.
- **Theme-Aware Design**: Optimized for both high-productivity Light mode and sleek Dark mode.
- **Smooth Interactions**: Powered by Framer Motion-like transitions and Micro-animations.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12.x Core, PHP 8.3 Performance Engine.
- **Frontend**: Tailwind CSS 3.4, Alpine.js, Livewire 3 (TALL Stack).
- **Database**: MySQL 8.x with optimized indexing.
- **AI Integration**: Model Context Protocol (MCP) Server for safe data reflection.
- **Payments**: Paymob, MyFatoorah, and PayPal REST SDKs.

---

## 🚀 Installation

### Prerequisites
- PHP 8.3+
- Composer 2.x
- Node.js 18+ & NPM 9+
- MySQL 8.x

### Quick Start
```bash
# Clone & Install
git clone https://github.com/your-repo/coursebook.git
cd coursebook
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database & Cache
php artisan migrate --seed
php artisan storage:link
npm run build

# Start Server
php artisan serve
```

---

## 🌐 Deployment Guide (Shared Hosting / InfinityFree)

Preparing for production on constrained environments:

1. **Root Routing**: Use the provided `.htaccess` in the root folder to point to `public/`.
2. **Path Optimization**: Ensure `index.php` is modified to point to the correct subdirectory (e.g., `Courses/`).
3. **Cache Clearing**: Always run `php artisan optimize` before uploading.
4. **Storage Fix**: Manually copy `storage/app/public` to `public/storage` if symlinks are restricted.

---

## 📄 Documentation

For a deep dive into the technical architecture, database schema, and API references, check out the [Full Documentation Guide](./DOCUMENTATION.md).

---

## 👥 Authors

- **Osama Elgendy** - *Lead Developer* - [LinkedIn](https://www.linkedin.com/in/osama-elgendy-416329331)

---
<div align="center">
  <sub>Built with ❤️ and Laravel 12</sub>
</div>
