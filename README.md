# 🎬 CineFlow – Online Movie Ticket Booking System

A complete multi-page movie ticket booking website built with pure HTML, CSS, and vanilla JavaScript.

---

## 📁 Folder Structure

```
cineflow/
├── index.html          → Home page with hero, search, trending movies, offers
├── movies.html         → All movies grid with genre filter
├── movie.html          → Movie detail page
├── login.html          → Login form
├── register.html       → Registration form
├── seats.html          → Seat selection (20 seats)
├── confirmation.html   → Booking confirmation with ticket
├── feedback.html       → Feedback form with star rating
├── admin.html          → Admin dashboard
├── css/
│   └── style.css       → All styles (dark theme, responsive)
├── js/
│   └── script.js       → All JavaScript logic
└── images/             → (Add movie posters here if needed)
```

---

## 🚀 How to Run

1. Download / clone the project folder
2. Open `index.html` in any modern browser
3. No server, no npm, no framework needed!

---

## 🔑 Login Credentials

| Role  | Email                  | Password   |
|-------|------------------------|------------|
| Admin | admin@cineflow.com     | admin123   |
| User  | Register a new account | your choice |

---

## 📄 Pages Overview

| Page              | Description                                      |
|-------------------|--------------------------------------------------|
| `index.html`      | Hero section, search, trending, offers, footer   |
| `movies.html`     | Browse all movies with genre filter              |
| `movie.html`      | Movie details, rating, warnings, book button     |
| `login.html`      | Email + password login (localStorage)            |
| `register.html`   | Name, email, password registration               |
| `seats.html`      | 20-seat grid, click to select, booking summary   |
| `confirmation.html` | E-ticket with booking ID from localStorage     |
| `feedback.html`   | Star rating + text feedback, shows past reviews  |
| `admin.html`      | Dashboard, add/delete movies, view bookings      |

---

## ⚙️ Features

- **Dark theme** with Bebas Neue + DM Sans typography
- **Responsive** – works on mobile and desktop
- **User auth** – register/login stored in localStorage
- **Seat selection** – click to toggle, taken seats blocked
- **Booking confirmation** – unique booking ID generated
- **Admin panel** – add movies, view bookings, read feedback
- **Genre filtering** on movies page
- **Search** with URL parameter support
- **Star rating** on feedback page

---

## 🛠️ Tech Stack

- HTML5 (semantic, accessible)
- CSS3 (Flexbox, Grid, CSS Variables, Animations)
- Vanilla JavaScript (localStorage, DOM manipulation)
- Google Fonts (Bebas Neue, DM Sans)

---

## 📝 Notes for Students

- All data is stored in **localStorage** (browser storage)
- Clearing browser data will reset all users/bookings
- The movie data is hardcoded in `script.js` as a `MOVIES` array
- You can add real poster images in the `/images/` folder and update the card HTML
- Admin credentials are hardcoded – in production, use a real backend
