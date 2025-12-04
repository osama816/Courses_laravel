# 📚 CourseBook - Online Course Booking Platform

<div align="center">

![CourseBook Logo](https://via.placeholder.com/800x200/667eea/ffffff?text=CourseBook+-+Learn+Anything,+Anywhere)

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire)](https://laravel-livewire.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)](https://mysql.com)
[![AI Powered](https://img.shields.io/badge/AI-Powered-00D9FF?style=for-the-badge&logo=openai)](https://openai.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

**A modern, full-featured online course booking platform with AI-powered student support**

[Features](#-features) • [AI Support](#-ai-support) • [Installation](#-installation) • [Documentation](#-documentation) • [Support](#-support)

</div>

---

## 📖 Table of Contents

- [About](#-about-the-project)
- [Features](#-features)
- [AI Support System](#-ai-support-system)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [MCP Server Setup](#-mcp-server-setup)
- [Payment Gateways](#-payment-gateways-integration)
- [Usage](#-usage)
- [Project Structure](#-project-structure)
- [API Documentation](#-api-documentation)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-contact)

---

## 🎯 About The Project

**CourseBook** is a comprehensive online learning platform that allows users to browse, book, and manage their course enrollments seamlessly. Built with modern web technologies, it offers a robust booking system with multiple payment gateway integrations and **AI-powered intelligent support** to assist students 24/7.

### 🌟 Why CourseBook?

- **🤖 AI-Powered Support**: Intelligent chatbot for instant student assistance
- **Multi-Language Support**: Full RTL support for Arabic and LTR for English
- **Multiple Payment Gateways**: Integrated with Paymob, MyFatoorah, and PayPal
- **Real-Time Updates**: Live seat availability tracking with Livewire
- **Secure & Scalable**: Built on Laravel's robust architecture
- **Beautiful UI/UX**: Modern, responsive design with smooth animations
- **Complete Booking Management**: From browsing to completion

---

## ✨ Features

### 🤖 **AI Support System** ⭐ NEW
- ✅ **24/7 Intelligent Chatbot**: AI-powered support assistant
- ✅ **Payment Status Inquiry**: Check payment status in real-time
- ✅ **Booking Information**: Get instant booking details
- ✅ **Invoice Generation**: Download invoices through chat
- ✅ **Course Information**: Ask about courses, schedules, and availability
- ✅ **Multi-Language Support**: Supports Arabic and English conversations
- ✅ **Context-Aware Responses**: Understands student queries intelligently
- ✅ **MCP Integration**: Powered by Model Context Protocol for reliable data access

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
- ✅ **AI-assisted booking support**

### 💳 Payment Integration
- ✅ **Paymob** integration for card payments
- ✅ **MyFatoorah** integration for multiple payment methods
- ✅ **PayPal** integration (ready)
- ✅ Secure payment callbacks
- ✅ Payment status tracking
- ✅ Transaction history
- ✅ Automatic payment confirmation
- ✅ **AI-powered payment inquiry**

### 🌐 Multi-Language Support
- ✅ Full Arabic (RTL) support
- ✅ English (LTR) support
- ✅ Easy language switching
- ✅ Localized content and dates
- ✅ Translation files for all modules
- ✅ **AI support in both languages**

### 🎨 UI/UX Features
- ✅ Modern, responsive design
- ✅ Dark/Light theme toggle
- ✅ Smooth animations and transitions
- ✅ Interactive components
- ✅ Flash message system
- ✅ Loading states
- ✅ Empty states
- ✅ Error handling
- ✅ **Floating AI chat widget**

### 📊 Dashboard & Analytics
- ✅ Booking statistics
- ✅ Payment status overview
- ✅ Course enrollment tracking
- ✅ User activity monitoring
- ✅ **AI interaction analytics**

---

## 🤖 AI Support System

### Overview

CourseBook features an advanced AI-powered support system that provides instant assistance to students. The system is built using:

- **MCP (Model Context Protocol) Server**: Provides secure and structured access to student data
- **LLM Integration**: Powered by advanced language models for natural conversations
- **Real-time Data Access**: Connects directly to the database for up-to-date information

### What Can AI Support Do?

#### 💰 Payment Inquiries
```
Student: "What is my payment status for the Web Development course?"
AI: "Let me check your payment status... Your payment for the Web Development 
     course is confirmed. Transaction ID: TXN123456. Would you like me to 
     generate an invoice?"
```

#### 📋 Booking Information
```
Student: "Show me my active bookings"
AI: "You have 2 active bookings:
     1. Web Development (Confirmed) - Starts: Jan 15, 2025
     2. Data Science Basics (Pending Payment) - Starts: Feb 1, 2025
     
     Would you like more details about any of these?"
```

#### 🧾 Invoice Generation
```
Student: "Can I get my invoice for booking #123?"
AI: "Sure! I've generated your invoice for Web Development course.
     [Download Invoice] 
     
     Booking Details:
     - Course: Web Development
     - Amount: $299
     - Status: Paid
     - Date: Jan 10, 2025"
```

#### 📚 Course Information
```
Student: "Tell me about available programming courses"
AI: "We have 5 programming courses available:
     1. Web Development with Laravel - $299 (5 seats left)
     2. Python for Beginners - $199 (10 seats left)
     3. React Advanced - $349 (3 seats left)
     
     Would you like to know more about any specific course?"
```

### AI Support Features

| Feature | Description | Status |
|---------|-------------|--------|
| Payment Status | Real-time payment verification | ✅ Active |
| Booking Details | Complete booking information | ✅ Active |
| Invoice Generation | Automated invoice creation | ✅ Active |
| Course Search | Intelligent course recommendations | ✅ Active |
| Multi-Language | Arabic & English support | ✅ Active |
| Context Awareness | Remembers conversation history | ✅ Active |
| Secure Access | User-specific data protection | ✅ Active |

### AI Support Architecture

```
┌─────────────────┐
│   Student UI    │
│  (Chat Widget)  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Laravel API    │
│   Controller    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   MCP Server    │
│  (Data Access)  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│      LLM        │
│  (AI Engine)    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│    Response     │
│   to Student    │
└─────────────────┘
```

---

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze
- **Real-time**: Livewire 3.x
- **AI Integration**: MCP Server + LLM
- **API**: RESTful API

### Frontend
- **CSS Framework**: Bootstrap 5.3
- **Icons**: Bootstrap Icons
- **JavaScript**: Vanilla JS + Alpine.js
- **Template Engine**: Blade
- **Chat Widget**: Custom AI Chat Component

### AI & MCP
- **MCP Server**: Model Context Protocol
- **LLM Provider**: OpenAI / Anthropic Claude
- **Context Management**: Session-based conversation
- **Data Security**: Encrypted user data access

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
- **LLM API Key** (OpenAI or Claude)

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

# Install MCP Server dependencies (if separate)
cd mcp-server
npm install
cd ..
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

### 5️⃣ AI & MCP Configuration

Add to your `.env` file:

```env
# AI Configuration
AI_PROVIDER=openai  # or 'claude'
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_MODEL=gpt-4-turbo-preview

# OR for Claude
CLAUDE_API_KEY=your_claude_api_key_here
CLAUDE_MODEL=claude-3-opus-20240229

# MCP Server Configuration
MCP_SERVER_URL=http://localhost:3000
MCP_SERVER_ENABLED=true
MCP_SERVER_TIMEOUT=30

# AI Support Settings
AI_SUPPORT_ENABLED=true
AI_MAX_CONVERSATION_LENGTH=50
AI_RESPONSE_CACHE_ENABLED=true
```

### 6️⃣ Run Migrations

```bash
# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### 7️⃣ Storage Setup

```bash
# Create symbolic link for storage
php artisan storage:link

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

### 8️⃣ Build Assets

```bash
# Compile assets for development
npm run dev

# OR build for production
npm run build
```

### 9️⃣ Start Development Servers

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start MCP Server
cd mcp-server
npm start

# Terminal 3: Start Vite (for hot reload)
npm run dev

# Application will be available at:
# http://localhost:8000
```

---

## ⚙️ Configuration

### MCP Server Setup

#### Install MCP Server

```bash
# Create MCP server directory
mkdir mcp-server && cd mcp-server

# Initialize Node.js project
npm init -y

# Install dependencies
npm install @modelcontextprotocol/sdk express dotenv mysql2
```

#### MCP Server Configuration

Create `mcp-server/index.js`:

```javascript
const { MCPServer } = require('@modelcontextprotocol/sdk');
const mysql = require('mysql2/promise');
require('dotenv').config();

const server = new MCPServer({
  name: 'CourseBook MCP Server',
  version: '1.0.0'
});

// Database connection
const pool = mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_DATABASE,
  waitForConnections: true,
  connectionLimit: 10
});

// Tool: Get Payment Status
server.addTool({
  name: 'get_payment_status',
  description: 'Get payment status for a student booking',
  parameters: {
    userId: { type: 'number', required: true },
    bookingId: { type: 'number', required: false }
  },
  handler: async ({ userId, bookingId }) => {
    const query = bookingId 
      ? 'SELECT * FROM payments WHERE user_id = ? AND booking_id = ?'
      : 'SELECT * FROM payments WHERE user_id = ?';
    
    const params = bookingId ? [userId, bookingId] : [userId];
    const [rows] = await pool.query(query, params);
    return rows;
  }
});

// Tool: Get Bookings
server.addTool({
  name: 'get_user_bookings',
  description: 'Get all bookings for a user',
  parameters: {
    userId: { type: 'number', required: true },
    status: { type: 'string', required: false }
  },
  handler: async ({ userId, status }) => {
    let query = `
      SELECT b.*, c.title, c.price, c.instructor 
      FROM bookings b 
      JOIN courses c ON b.course_id = c.id 
      WHERE b.user_id = ?
    `;
    const params = [userId];
    
    if (status) {
      query += ' AND b.status = ?';
      params.push(status);
    }
    
    const [rows] = await pool.query(query, params);
    return rows;
  }
});

// Tool: Generate Invoice
server.addTool({
  name: 'generate_invoice',
  description: 'Generate invoice for a booking',
  parameters: {
    bookingId: { type: 'number', required: true }
  },
  handler: async ({ bookingId }) => {
    const query = `
      SELECT b.*, c.title, c.price, u.name, u.email,
             p.transaction_id, p.payment_method, p.paid_at
      FROM bookings b
      JOIN courses c ON b.course_id = c.id
      JOIN users u ON b.user_id = u.id
      LEFT JOIN payments p ON b.id = p.booking_id
      WHERE b.id = ?
    `;
    
    const [rows] = await pool.query(query, [bookingId]);
    return rows[0];
  }
});

// Start server
const PORT = process.env.MCP_PORT || 3000;
server.listen(PORT, () => {
  console.log(`MCP Server running on port ${PORT}`);
});
```

#### Environment Setup for MCP

Create `mcp-server/.env`:

```env
DB_HOST=127.0.0.1
DB_USER=your_db_user
DB_PASSWORD=your_db_password
DB_DATABASE=coursebook
MCP_PORT=3000
```

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

### Multi-Language Configuration

The application supports Arabic and English out of the box:

```env
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

Available locales: `en`, `ar`

---

## 💳 Payment Gateways Integration

### Supported Payment Methods

| Gateway | Status | Supported Methods | Currency | AI Support |
|---------|--------|-------------------|----------|------------|
| Paymob | ✅ Active | Card, Wallet, Cash | EGP, USD | ✅ Yes |
| MyFatoorah | ✅ Active | Card, Apple Pay, Google Pay, KNET | KWD, SAR, AED, BHD | ✅ Yes |
| PayPal | 🔜 Coming Soon | PayPal Balance, Card | Multiple | 🔜 Planned |

---

## 📘 Usage

### Using AI Support

#### For Students

1. **Access AI Chat**
   - Click on the AI support icon (bottom right)
   - Start typing your question
   - Get instant responses

2. **Check Payment Status**
   ```
   You: "What's the status of my payment?"
   AI: "Your recent payment for Web Development is confirmed..."
   ```

3. **View Bookings**
   ```
   You: "Show my bookings"
   AI: "You have 2 active bookings: [details...]"
   ```

4. **Get Invoice**
   ```
   You: "I need invoice for booking 123"
   AI: "Here's your invoice [Download link]"
   ```

#### AI Support Commands

| Command | Description | Example |
|---------|-------------|---------|
| Payment status | Check payment | "What's my payment status?" |
| My bookings | View bookings | "Show my bookings" |
| Invoice | Get invoice | "Generate invoice for booking #123" |
| Course info | Course details | "Tell me about Web Development course" |
| Help | Get help | "What can you do?" |

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
│   │   │   └── AISupportController.php  ⭐ NEW
│   │   └── Livewire/
│   │       ├── FormRegister.php
│   │       ├── Login.php
│   │       ├── AvailableSeats.php
│   │       └── AIChatWidget.php  ⭐ NEW
│   ├── Models/
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── Booking.php
│   │   ├── Payment.php
│   │   └── AIConversation.php  ⭐ NEW
│   ├── Services/
│   │   ├── PaymobPaymentService.php
│   │   ├── MyFatoorahPaymentService.php
│   │   ├── BasePaymentService.php
│   │   ├── AISupportService.php  ⭐ NEW
│   │   └── MCPClientService.php  ⭐ NEW
│   └── Interfaces/
│       └── PaymentGatewayInterface.php
├── mcp-server/  ⭐ NEW
│   ├── index.js
│   ├── tools/
│   │   ├── payment.js
│   │   ├── booking.js
│   │   └── invoice.js
│   ├── package.json
│   └── .env
├── database/
│   ├── migrations/
│   │   └── xxxx_create_ai_conversations_table.php  ⭐ NEW
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── bookings/
│   │   ├── courses/
│   │   ├── payment/
│   │   ├── ai-support/  ⭐ NEW
│   │   │   └── chat-widget.blade.php
│   │   └── components/
│   └── lang/
│       ├── ar/
│       │   └── ai.php  ⭐ NEW
│       └── en/
│           └── ai.php  ⭐ NEW
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

### AI Support Endpoints

#### Chat with AI

```http
POST /api/ai/chat
```

**Request:**
```json
{
  "message": "What is my payment status?",
  "user_id": 1,
  "conversation_id": "uuid-string"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "message": "Your payment for Web Development course is confirmed...",
    "conversation_id": "uuid-string",
    "context": {
      "payment_status": "confirmed",
      "booking_id": 123
    }
  }
}
```

---

## 🧪 Testing AI Support

```bash
# Test MCP Server connection
php artisan ai:test-mcp

# Test AI response
php artisan ai:test-chat "What is my payment status?"

# Run AI support tests
php artisan test --filter=AISupportTest
```

---

## 🐛 Known Issues & Roadmap

### Known Issues
- [ ] PDF invoice generation (in progress)
- [ ] Email notifications for booking reminders
- [ ] AI response caching optimization

### Roadmap
- [x] AI-powered support system ✅
- [x] MCP Server integration ✅
- [x] Real-time payment status ✅
- [ ] Voice-based AI support
- [ ] AI course recommendations
- [ ] Predictive analytics dashboard
- [ ] PayPal integration completion
- [ ] Admin dashboard
- [ ] Mobile app (React Native)
- [ ] Docker containerization

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

## 👥 Contact

**Project Maintainer**: Osama Elgendy

- Email: elgendyo240@gmail.com
- LinkedIn: [Osama Elgendy](https://www.linkedin.com/in/osama-elgendy-416329331?originalSubdomain=eg)
- GitHub: [@osama816](https://github.com/osama816)

**Project Link**: [https://github.com/osama816/Courses_laravel](https://github.com/osama816/Courses_laravel)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Livewire](https://laravel-livewire.com) - Dynamic components
- [Bootstrap](https://getbootstrap.com) - CSS Framework
- [OpenAI](https://openai.com) / [Anthropic](https://anthropic.com) - AI Models
- [Model Context Protocol](https://modelcontextprotocol.io) - MCP Server
- [Paymob](https://paymob.com) - Payment gateway
- [MyFatoorah](https://myfatoorah.com) - Payment gateway

---

## 📊 Project Stats

![GitHub stars](https://img.shields.io/github/stars/osama816/Courses_laravel?style=social)
![GitHub forks](https://img.shields.io/github/forks/osama816/Courses_laravel?style=social)
![AI Powered](https://img.shields.io/badge/AI-Powered-00D9FF?style=flat-square)

---

<div align="center">

**Made with ❤️ and 🤖 by [Osama Elgendy](https://github.com/osama816)**

⭐ **Star this repo if you find it helpful!** ⭐

</div>
