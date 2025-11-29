# 📚 CourseBook - Online Course Booking Platform

<div align="center">

![CourseBook Logo](https://via.placeholder.com/800x200/667eea/ffffff?text=CourseBook+-+Learn+Anything,+Anywhere)

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire)](https://laravel-livewire.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

**A modern, full-featured online course booking and management platform built with Laravel**

[Features](#-features) • [Demo](#-demo) • [Installation](#-installation) • [Documentation](#-documentation) • [Support](#-support)

</div>

---

## 📖 Table of Contents

- [About](#-about-the-project)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Payment Gateways](#-payment-gateways-integration)
- [Usage](#-usage)
- [Project Structure](#-project-structure)
- [API Documentation](#-api-documentation)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-contact)

---

## 🎯 About The Project

**CourseBook** is a comprehensive online learning platform that allows users to browse, book, and manage their course enrollments seamlessly. Built with modern web technologies, it offers a robust booking system with multiple payment gateway integrations.

### 🌟 Why CourseBook?

- **Multi-Language Support**: Full RTL support for Arabic and LTR for English
- **Multiple Payment Gateways**: Integrated with Paymob, MyFatoorah, and PayPal
- **Real-Time Updates**: Live seat availability tracking with Livewire
- **Secure & Scalable**: Built on Laravel's robust architecture
- **Beautiful UI/UX**: Modern, responsive design with smooth animations
- **Complete Booking Management**: From browsing to completion

---

## ✨ Features

### 🔐 Authentication & Authorization
- ✅ User registration and login
- ✅ Email verification
- ✅ Password reset functionality
- ✅ Profile management
- ✅ Multi-language authentication (AR/EN)

### 📚 Course Management
- ✅ Browse courses with advanced filtering
- ✅ Course details with instructor information
- ✅ Course ratings and reviews
- ✅ Real-time seat availability
- ✅ Course categories and levels
- ✅ Course duration and pricing

### 🎫 Booking System
- ✅ Easy course booking workflow
- ✅ Multiple payment methods (Online/Cash)
- ✅ Booking status tracking (Pending, Confirmed, Cancelled)
- ✅ My Bookings dashboard with statistics
- ✅ Booking details with timeline
- ✅ Invoice generation
- ✅ Booking cancellation with seat restoration

### 💳 Payment Integration
- ✅ **Paymob** integration for card payments
- ✅ **MyFatoorah** integration for multiple payment methods
- ✅ **PayPal** integration (ready)
- ✅ Secure payment callbacks
- ✅ Payment status tracking
- ✅ Transaction history
- ✅ Automatic payment confirmation

### 🌐 Multi-Language Support
- ✅ Full Arabic (RTL) support
- ✅ English (LTR) support
- ✅ Easy language switching
- ✅ Localized content and dates
- ✅ Translation files for all modules

### 🎨 UI/UX Features
- ✅ Modern, responsive design
- ✅ Dark/Light theme toggle
- ✅ Smooth animations and transitions
- ✅ Interactive components
- ✅ Flash message system
- ✅ Loading states
- ✅ Empty states
- ✅ Error handling

### 📊 Dashboard & Analytics
- ✅ Booking statistics
- ✅ Payment status overview
- ✅ Course enrollment tracking
- ✅ User activity monitoring

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze
- **Real-time**: Livewire 3.x
- **API**: RESTful API

### Frontend
- **CSS Framework**: Bootstrap 5.3
- **Icons**: Bootstrap Icons
- **JavaScript**: Vanilla JS + Alpine.js
- **Template Engine**: Blade

### Payment Gateways
- **Paymob**: Egyptian payment gateway
- **MyFatoorah**: Middle East payment solution
- **PayPal**: International payment processor

### Development Tools
- **Package Manager**: Composer & NPM
- **Version Control**: Git
- **Code Style**: PSR-12
- **Testing**: PHPUnit (ready)

---

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **MySQL** >= 8.0
- **Git**

### Required PHP Extensions
```bash
php -m | grep -E 'openssl|pdo|mbstring|tokenizer|xml|ctype|json|bcmath|fileinfo'
```

---

## 🚀 Installation

### 1️⃣ Clone the Repository

```bash
# Clone the project
git clone https://github.com/yourusername/coursebook.git

# Navigate to project directory
cd coursebook
```

### 2️⃣ Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3️⃣ Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4️⃣ Database Configuration

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coursebook
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Create the database:
```bash
mysql -u root -p
CREATE DATABASE coursebook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

### 5️⃣ Run Migrations

```bash
# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### 6️⃣ Storage Setup

```bash
# Create symbolic link for storage
php artisan storage:link

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

### 7️⃣ Build Assets

```bash
# Compile assets for development
npm run dev

# OR build for production
npm run build
```

### 8️⃣ Start Development Server

```bash
# Start Laravel server
php artisan serve

# Application will be available at:
# http://localhost:8000
```

---

## ⚙️ Configuration

### Payment Gateways Setup

#### 🔵 Paymob Configuration

1. Register at [Paymob](https://paymob.com)
2. Get your API credentials
3. Add to `.env`:

```env
PAYMOB_BASE_URL=https://accept.paymob.com
PAYMOB_API_KEY=your_api_key_here
PAYMOB_INTEGRATION_ID_CARD=your_card_integration_id
PAYMOB_INTEGRATION_ID_WALLET=your_wallet_integration_id
PAYMOB_INTEGRATION_ID_CASH=your_cash_integration_id
```

#### 🟢 MyFatoorah Configuration

1. Register at [MyFatoorah](https://myfatoorah.com)
2. Get your API credentials
3. Add to `.env`:

```env
MYFATOORAH_BASE_URL=https://apitest.myfatoorah.com
MYFATOORAH_API_KEY=your_api_key_here
```

For production:
```env
MYFATOORAH_BASE_URL=https://api.myfatoorah.com
```

#### 🔴 PayPal Configuration (Optional)

```env
PAYPAL_MODE=sandbox # or live
PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id
PAYPAL_SANDBOX_SECRET=your_sandbox_secret
PAYPAL_LIVE_CLIENT_ID=your_live_client_id
PAYPAL_LIVE_SECRET=your_live_secret
```

### Multi-Language Configuration

The application supports Arabic and English out of the box:

```env
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

Available locales: `en`, `ar`

### Email Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@coursebook.com
MAIL_FROM_NAME="CourseBook"
```

---

## 💳 Payment Gateways Integration

### Supported Payment Methods

| Gateway | Status | Supported Methods | Currency |
|---------|--------|-------------------|----------|
| Paymob | ✅ Active | Card, Wallet, Cash | EGP, USD |
| MyFatoorah | ✅ Active | Card, Apple Pay, Google Pay, KNET, etc. | KWD, SAR, AED, BHD, etc. |
| PayPal | 🔜 Coming Soon | PayPal Balance, Card | Multiple |

### Payment Flow

```
User selects course
      ↓
Chooses payment method
      ↓
Redirected to payment gateway
      ↓
Payment processed
      ↓
Callback to application
      ↓
Booking confirmed
      ↓
Confirmation email sent
```

### Testing Payment Gateways

#### Paymob Test Cards
```
Card Number: 4987654321098769
Expiry: Any future date
CVV: 123
```

#### MyFatoorah Test Cards
```
Card Number: 5123450000000008
Expiry: 05/21
CVV: 100
```

---

## 📘 Usage

### User Journey

1. **Browse Courses**
   - Visit homepage
   - Browse available courses
   - Filter by category, level, or price

2. **Book a Course**
   - Click on course
   - Review details
   - Click "Book Now"
   - Choose payment method

3. **Complete Payment**
   - For online payment: redirected to gateway
   - For cash: booking confirmed pending payment

4. **Manage Bookings**
   - View "My Bookings"
   - Track booking status
   - Download invoices
   - Start course when confirmed

### Admin Features

```bash
# Create admin user
php artisan make:admin

# Or via tinker
php artisan tinker
User::factory()->create(['email' => 'admin@coursebook.com', 'role' => 'admin']);
```

---

## 📁 Project Structure

```
coursebook/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── BookingController.php
│   │   │   ├── CourseController.php
│   │   │   ├── PaymentController.php
│   │   │   └── ...
│   │   └── Livewire/
│   │       ├── FormRegister.php
│   │       ├── Login.php
│   │       └── AvailableSeats.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── Booking.php
│   │   ├── Payment.php
│   │   └── ...
│   ├── Services/
│   │   ├── PaymobPaymentService.php
│   │   ├── MyFatoorahPaymentService.php
│   │   └── BasePaymentService.php
│   └── Interfaces/
│       └── PaymentGatewayInterface.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── bookings/
│   │   │   ├── index.blade.php
│   │   │   ├── show.blade.php
│   │   │   └── create.blade.php
│   │   ├── courses/
│   │   ├── payment/
│   │   └── components/
│   │       └── flash-messages.blade.php
│   └── lang/
│       ├── ar/
│       │   ├── auth.php
│       │   ├── booking.php
│       │   ├── payment.php
│       │   └── nav.php
│       └── en/
├── routes/
│   ├── web.php
│   └── api.php
├── public/
├── .env
├── composer.json
├── package.json
└── README.md
```

---

## 🔌 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication
Include Bearer token in headers:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

### Endpoints

#### Bookings

```http
GET /api/bookings
```
Get user's bookings

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "course_id": 5,
      "status": "confirmed",
      "created_at": "2024-01-15T10:30:00Z"
    }
  ]
}
```

#### Payment Processing

```http
POST /api/payment/process
```

**Request Body:**
```json
{
  "booking_id": 1,
  "gateway_type": "myfatoorah",
  "InvoiceValue": 99.99,
  "currency": "USD",
  "CustomerName": "John Doe",
  "CustomerEmail": "john@example.com"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "url": "https://payment-gateway.com/pay/xxx",
    "booking_id": 1
  }
}
```

---

## 🎨 Customization

### Theme Colors

Edit `resources/css/app.css`:

```css
:root {
  --primary-color: #667eea;
  --secondary-color: #764ba2;
  --success-color: #10b981;
  --danger-color: #ef4444;
  --warning-color: #f59e0b;
}
```

### Adding New Payment Gateway

1. Create service class:
```php
class YourPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    public function sendPayment(Request $request) { }
    public function callBack(Request $request): bool { }
}
```

2. Register in `PaymentServiceProvider`:
```php
return match ($gatewayType) {
    'your_gateway' => $app->make(YourPaymentService::class),
    // ...
};
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=BookingTest

# Generate coverage report
php artisan test --coverage
```

---

## 🤝 Contributing

Contributions are what make the open-source community amazing! Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 coding style
- Write descriptive commit messages
- Add tests for new features
- Update documentation

---

## 🐛 Known Issues & Roadmap

### Known Issues
- [ ] PDF invoice generation (in progress)
- [ ] Email notifications for booking reminders

### Roadmap
- [ ] PayPal integration completion
- [ ] Admin dashboard
- [ ] Course reviews and ratings system
- [ ] Video streaming for courses
- [ ] Mobile app (React Native)
- [ ] API documentation with Swagger
- [ ] Automated testing suite
- [ ] Docker containerization

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

## 👥 Contact

**Project Maintainer**: Your Name

- Email: elgendyo240@gmail.com
- LinkedIn: [Your LinkedIn](https://www.linkedin.com/in/osama-elgendy-416329331?originalSubdomain=eg)

**Project Link**: [https://github.com/osama816/Courses_laravel](https://github.com/osama816/Courses_laravel)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Livewire](https://laravel-livewire.com) - Dynamic components
- [Bootstrap](https://getbootstrap.com) - CSS Framework
- [Bootstrap Icons](https://icons.getbootstrap.com) - Icon library
- [Paymob](https://paymob.com) - Payment gateway
- [MyFatoorah](https://myfatoorah.com) - Payment gateway

---

## 📊 Project Stats

![GitHub stars](https://img.shields.io/github/stars/osama816/Courses_laravel?style=social)
![GitHub forks](https://img.shields.io/github/forks/osama816/Courses_laravel?style=social)
![GitHub watchers](https://img.shields.io/github/watchers/osama816/Courses_laravel?style=social)
![GitHub issues](https://img.shields.io/github/issues/osama816/Courses_laravel)
![GitHub pull requests](https://img.shields.io/github/issues-pr/osama816/Courses_laravel)

---

<div align="center">

**Made with ❤️ by [Osama Elgendy](https://github.com/osama816)**

⭐ **Star this repo if you find it helpful!** ⭐

</div>
