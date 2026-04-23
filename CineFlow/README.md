<<<<<<< HEAD
# 🎬 CineFlow: Simply Elegant Movie Booking

Welcome to the **CineFlow** repository! CineFlow is a beautifully uncomplicated, beginner-friendly web application designed as a simulated movie booking platform. Built with a major emphasis on code readability, minimalism, and elegance, this project is perfect for new developers eager to understand PHP, secure session handling, routing, and PostgreSQL operations.

---

## 🌟 Vision & Design Aesthetic
The application has recently undergone a major redesign to heavily prioritize a **clean, white, and elegant UI**. 
- No more confusing, messy gradients.
- No more overwhelming layouts.
- Replaced with bright, readable typography, perfectly structured cards, and simplified box styles.

The objective was to create a front-end that looks effortlessly premium but resembles something a beginner developer could actually understand and maintain. The entire design logic sits perfectly inside a single, condensed CSS file, meaning you'll never get lost in thousands of lines of styling rules.

---

## 🛠️ Tech Stack
- **Frontend Layer:** Semantic HTML5, Vanilla JavaScript, Vanilla CSS.
- **Backend Core:** PHP 8+ handling native server-side sessions, routing, and security.
- **Database Engine:** PostgreSQL containing heavily sorted, explicitly structured tables.

---

## ✨ Key Features & Architecture

### 1. Robust Role-Based Separation
Not all users are created equal! CineFlow features a strict role-based access protocol governing interactions across the site.
- **Normal Users:** Have their own `dashboard.php` to actively review bookings and exercise CRUD operations (Canceling tickets).
- **Admin Users:** Have a completely isolated suite of tools (`admin.php`, `admin_movies.php`, `admin_users.php`), allowing them full moderation over the ecosystem. Admins can actively Add/Delete movies on the fly and monitor or purge registered platform users.

### 2. Fragmented, Maintainable Code Architecture
Large files are the bane of learning. CineFlow breaks down its systems into logically sound micro-pages so developers always know where to look:
- Authentication is cleanly split across `login.php` & `register.php`.
- Global components sit centrally inside `header.php` and `footer.php`.
- The Admin dashboard is safely divided rather than existing as a single chaotic block of logic.

### 3. Expanded Movie Database Infrastructure
The core SQL structure (provided identically in both `database.sql` and `Cineflow.sql`) delivers a fully functional, pre-loaded ecosystem the second you deploy. 
- Automatically generated mock users.
- A massive repository of exactly **30 fully fleshed out movies**.
- Genres ranging from Cyberpunk to French Romance, perfectly configured with gorgeous dynamic Unsplash imagery. 

---

## 🚀 Getting Started & Deployment Guide

Ready to get CineFlow running on your local machine? Simply follow these steps:

### 1. Clone the Directory
```bash
git clone https://github.com/yourusername/cineflow.git
cd cineflow
```

### 2. Database Preparation
You must set up your PostgreSQL environment to accept the application's data.
- Open **pgAdmin** or run `psql` via your terminal.
- Create a brand new database and name it `Cineflow`.
- Open a query tool within the `Cineflow` database and execute the entire `Cineflow.sql` file provided in the root directory. This will automatically spawn the tables for Users, Movies, Bookings, and Feedback, while injecting the 30 mock movies!

### 3. Configure Backend Connection
Open the `db.php` file inside your preferred code editor. Depending on your PostgreSQL installation, ensure the credentials correctly match your local setup:
```php
$host = 'localhost';
$port = '5432';
$dbname = 'Cineflow';
$user = 'postgres';
$password = 'YOUR_DB_PASSWORD';
```

### 4. Boot the Server
If you are using a local web server tool like XAMPP or WAMPServer, verify PHP and Apache are running, then navigate to your `localhost` folder. 
If you are using the terminal natively, run the PHP built-in web server:
```bash
php -S localhost:8000
```
Then, simply visit `http://localhost:8000/index.php` in your browser!

---

## 🔑 Demonstration Credentials
To immediately witness both sides of the role-based system without doing a manual registration, login using the following pre-built credentials embedded in the SQL setup:

| Account Type | Email | Password |
|--------------|---------------------|-------------|
| **Admin** | admin@cineflow.com | `admin123` |
| **Normal User**| user@cineflow.com | `user123` |

---

## 🤝 Contribution & Modification
CineFlow embraces open-source education! Feel free to modify the `style.css` file to match your personal design aesthetic, implement deeper routing mechanisms, or expand the database to support user reviews and rating aggregates.
=======
# CineFlow
CineFlow is a clean, minimal PHP &amp; PostgreSQL web app for movie ticket booking. Featuring a beautifully simple white UI, secure sessions, and distinct role-based access. Includes dedicated User and Admin dashboards for easy CRUD operations alongside a pre-loaded DB of 30 unique movies. Perfect template for beginners and clean-code enthusiasts!
>>>>>>> 53d39a2d8659aaa51d697d7db3880d59563cef77
