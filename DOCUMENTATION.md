# CourseBook Technical Documentation

Welcome to the internal technical guide for the CourseBook platform. This document provides a deep dive into our architecture and implementation details.

---

## 🏛️ System Architecture

CourseBook is built on the **Laravel Enterprise Architecture**, optimized for scalability and performance.

- **Monolithic Core**: Handles routing, business logic, and UI rendering.
- **Service Layer**: Decouples payment gateways and AI logic from controllers.
- **Real-time Layer**: Utilizes Laravel Livewire 3 for ultra-responsive seat tracking and filtering.
- **AI/MCP Bridge**: A secure sub-layer that reflects database state to AI models without exposing sensitive credentials.

---

## 📊 Database Schema

Our relational schema is designed for 3rd Normal Form (3NF) compliance:

### Core Tables
- **`users`**: Extended profile data, multi-language preferences, and role assignments.
- **`courses`**: Main product table with pricing, categorical data, and instructor relations.
- **`bookings`**: Pivot-like table tracking student-to-course relationships with complex status states.
- **`payments`**: Transaction records linked to `bookings`, storing gateway IDs and timestamps.
- **`instructors`**: Details for course creators.
- **`categories`**: Tree structure for course organization.

---

## 💳 Payment Integrations

We support a unified payment interface through `PaymentGatewayInterface`:

### Flow
1. **Initiation**: Student selects a course and gateway.
2. **Redirect**: Application prepares the payload and redirects to Paymob/MyFatoorah.
3. **Webhook/Callback**: Gateway sends a POST request to our `/payment/callback` endpoint.
4. **Reconciliation**: We verify the HMAC/Signature and update the `booking` status.

---

## 🤖 AI Support & MCP

The AI Chatbot is not just a text-replier; it is technically integrated:

- **MCP (Model Context Protocol)**: We expose several read-only tools to the AI:
  - `get_payment_status(booking_id)`
  - `check_availability(course_id)`
  - `generate_student_invoice(user_id)`
- **Security**: The AI does not have write access to the database. It only "reads" delegated data through the MCP server.

---

## 🌐 Localization (i18n)

- **Architecture**: We use `spatie/laravel-translatable` for model attributes (Course titles, descriptions).
- **Static Content**: Controlled via `lang/ar` and `lang/en`.
- **RTL Support**: Dynamically swapped CSS bundles based on `app()->getLocale()`.

---

## 🛠️ Performance & Scalability

- **Caching**: Extensive use of `config:cache` and `route:cache` for production.
- **Optimization**: All images are served through a localized storage link.
- **Database**: Active use of Eager Loading (`with(['instructor', 'category'])`) to prevent N+1 issues.

---

## 🔒 Security Measures

- **Authentication**: Secured via Laravel Breeze & Sanctum.
- **Role Management**: Spatie Permissions (`can:manage-courses`, `can:view-reports`).
- **Data Integrity**: Global Middlewares for CSRF and XSS protection.

---
<sub>Last Updated: February 11, 2026</sub>
