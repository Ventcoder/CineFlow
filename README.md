# CineFlow – Premium Movie Ticket Booking System

CineFlow is an enterprise-grade, minimalistic, and highly secure cinema ticket booking platform built natively with pure PHP (No Frameworks), PostgreSQL, and Vanilla JavaScript/CSS. 

Designed for high performance, concurrency safety, and aesthetic excellence, the codebase uses a modular file structure with advanced security protections including atomic SQL transactions, robust session management, and server-side validation.

---

##  Key Features

### 1. Robust Security & Authentication
- Secure Hashing: Passwords are mathematically hashed using `password_hash()` and `password_verify()`.
- Session Fixation Prevention: Uses `session_regenerate_id(true)` seamlessly upon login to regenerate secure token hashes.
- Global `Remember Me` Cookies: Secure 30-day SHA-256 hashed persistent cookies to auto-resume sessions natively mapped across all pages.
- Strict SQL Injection Prevention: Every single database query strictly utilizes `pg_query_params()` binding.
- XSS Prevention: All dynamic data output to the DOM is securely bounded utilizing `htmlspecialchars()`.

## 2. Advanced Booking Logic & Concurrency Control
- Atomic Postgres Seat Locking: The checkout natively prevents race-conditions and overbooking by rejecting conflicting concurrent transactions using a hardware-level `UPDATE ... WHERE ... AND seats_left >= X RETURNING id` lock constraint.
- **Digital Tickets:** Generates an alphanumeric pseudo-random `booking_ref` combined with a CSS-rendered digital Checkerboard QR code for user entrance upon successful processing.

## 3. Native File Ecosystem & Routing
The core workflow is carefully tracked chronologically.
- `01_page_home.php`: Dynamic grid and Unsplash fetching landing hub.
- `02_page_catalog.php`: Server-bridged JSON API for rapid front-end JavaScript sorting and filtering natively.
- `03_page_movie_details.php`: Dynamic parameter fetching.
- `04_page_seat_booking.php`: A responsive 8x10 CSS native flex-grid checking physical space and mapping booked coordinates vs. free coordinates.
- `05_page_cinema_snacks.php`: F&B order aggregation.
- `06_page_payment_gateway.php`: UI mock processing facade. 
- `07_page_ticket_success.php`: The backend confirmation handler and lock executor.

## 4. Admin Management Controls
- Dynamic dashboard analyzing real-time Postgre analytics via `COUNT()` and `SUM()` aggregates.
- Role-based gating checks natively (`$_SESSION['user_role'] === 'admin'`).
- Fast CRUD interface to inject or remove movies dynamically from the database.

---

## Technology Stack

- Backend Architecture: PHP 8+ (Vanilla/Procedural)
- Database Engine: PostgreSQL (pg_connect driver)
- Front-End Render: HTML5 Semantic markup natively
- Styling UI/UX: Vanilla CSS 
- Interactivity Engine: Vanilla ES6 JavaScript

---

## Setup & Deployment Guide

1. Environment Setup:
   - Start an Apache web server with the PHP module loaded (e.g., using XAMPP/WAMP/MAMP).
   - Ensure you configure your server's `php.ini` to enable the PostgreSQL extension (`extension=pgsql`).
   - Start your PostgreSQL database service.

2. **Database Initialization:**
   - Create a fresh Postgres database designated for `cineflow`.
   - Execute the internal `Cineflow.sql` payload or trigger `init_db.php` in a browser to actively scaffold the `users`, `movies`, `bookings`, and `feedback` schemas securely.

3. Routing the Hub:
   - Clone or dump the `CineFlow` project directly into your Apache document root (`htdocs` or `/var/www/html/`).
   - Open a browser and target `http://localhost/Cineflow/01_page_home.php` to launch the platform.

---

## 👨‍💻 Developed By
Architected specifically with advanced logic workflows mapped exclusively for seamless Enterprise Viva/Lab Demonstrations.
