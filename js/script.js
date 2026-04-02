// ============================================
// CineFlow – script.js
// Vanilla JS: auth, seats, feedback, admin
// ============================================

// ───────────────────────────────────────────
// MOVIES DATA (shared across pages)
// ───────────────────────────────────────────
const MOVIES = [
  { id: 1, title: "Interstellar Void", genre: "Sci-Fi", rating: "8.9", duration: "2h 49m", price: 280, desc: "A crew of astronauts journey through a wormhole near Saturn in search of a new home for humanity as Earth faces extinction. A breathtaking odyssey across time and space.", language: "English", seats_left: 4 },
  { id: 2, title: "Shadow Protocol", genre: "Thriller", rating: "7.8", duration: "2h 12m", price: 240, desc: "A rogue intelligence operative uncovers a global conspiracy that threatens to rewrite history. Every decision leads deeper into the labyrinth.", language: "English", seats_left: 12 },
  { id: 3, title: "The Last Sonata", genre: "Drama", rating: "8.2", duration: "1h 58m", price: 220, desc: "A famed pianist losing his hearing races against time to compose one final masterpiece — a love letter to the music that defined his life.", language: "English", seats_left: 7 },
  { id: 4, title: "Neon Requiem", genre: "Action", rating: "7.5", duration: "2h 05m", price: 260, desc: "In a city where corporations rule and justice is for sale, one ex-detective fights to reclaim the streets from the syndicate that destroyed his family.", language: "English", seats_left: 15 },
  { id: 5, title: "Pale Blue Tide", genre: "Horror", rating: "7.2", duration: "1h 52m", price: 200, desc: "A marine biologist's deep-sea expedition encounters something ancient and sentient. Some discoveries were meant to stay buried in the dark.", language: "English", seats_left: 3 },
  { id: 6, title: "The Grand Cipher", genre: "Mystery", rating: "8.5", duration: "2h 28m", price: 250, desc: "A cryptographer inherits a map containing the locations of seven stolen masterpieces, each guarded by an elaborate puzzle designed by her late grandfather.", language: "English", seats_left: 9 },
];

const EMOJIS = ["🚀", "🔫", "🎹", "⚡", "🌊", "🔍"];

// ───────────────────────────────────────────
// UTILITIES
// ───────────────────────────────────────────
function $(sel, ctx) { return (ctx || document).querySelector(sel); }
function $$(sel, ctx) { return [...(ctx || document).querySelectorAll(sel)]; }

function showToast(id, msg, type) {
  const el = document.getElementById(id);
  if (!el) return;
  el.textContent = msg;
  el.className = 'toast ' + type;
  setTimeout(() => el.classList.remove(type), 3500);
}

function generateId() {
  return 'CF' + Date.now().toString(36).toUpperCase() + Math.random().toString(36).slice(2,5).toUpperCase();
}

// ───────────────────────────────────────────
// NAVBAR – hamburger menu
// ───────────────────────────────────────────
function initNavbar() {
  const hamburger = $('.hamburger');
  const navLinks = $('.nav-links');
  if (!hamburger || !navLinks) return;

  hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('open');
  });

  // Close on outside click
  document.addEventListener('click', (e) => {
    if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
      navLinks.classList.remove('open');
    }
  });

  // Highlight active link
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  $$('.nav-links a').forEach(a => {
    if (a.getAttribute('href') === currentPage) a.classList.add('active');
  });

  // Show/hide login vs profile
  updateNavAuth();
}

function updateNavAuth() {
  const user = JSON.parse(localStorage.getItem('cf_user') || 'null');
  const loginLink = document.getElementById('nav-login');
  const logoutLink = document.getElementById('nav-logout');
  if (loginLink) loginLink.style.display = user ? 'none' : '';
  if (logoutLink) logoutLink.style.display = user ? '' : 'none';
}

function logout() {
  localStorage.removeItem('cf_user');
  window.location.href = 'index.html';
}

// ───────────────────────────────────────────
// HOME PAGE – search
// ───────────────────────────────────────────
function initSearch() {
  const btn = document.getElementById('search-btn');
  const input = document.getElementById('search-input');
  if (!btn || !input) return;

  function doSearch() {
    const q = input.value.trim();
    if (q) window.location.href = `movies.html?q=${encodeURIComponent(q)}`;
  }

  btn.addEventListener('click', doSearch);
  input.addEventListener('keydown', e => { if (e.key === 'Enter') doSearch(); });
}

// ───────────────────────────────────────────
// MOVIES PAGE
// ───────────────────────────────────────────
function initMoviesPage() {
  const grid = document.getElementById('movies-grid');
  if (!grid) return;

  const params = new URLSearchParams(window.location.search);
  const q = params.get('q') || '';

  const info = document.getElementById('search-results-info');
  let movies = MOVIES;

  if (q) {
    movies = MOVIES.filter(m =>
      m.title.toLowerCase().includes(q.toLowerCase()) ||
      m.genre.toLowerCase().includes(q.toLowerCase())
    );
    if (info) info.textContent = `${movies.length} result${movies.length !== 1 ? 's' : ''} for "${q}"`;
    if (document.getElementById('search-input')) document.getElementById('search-input').value = q;
  } else {
    if (info) info.textContent = `${movies.length} movies now showing`;
  }

  renderMovieCards(grid, movies);
}

function renderMovieCards(grid, movies) {
  if (!movies.length) {
    grid.innerHTML = '<p style="color:var(--gray);grid-column:1/-1;">No movies found.</p>';
    return;
  }
  grid.innerHTML = movies.map((m, i) => `
    <div class="movie-card" style="animation-delay:${i * 0.06}s">
      <div class="poster">
        <div class="poster-placeholder">
          <div class="movie-icon">${EMOJIS[m.id - 1] || '🎬'}</div>
          <div class="movie-title-ph">${m.title}</div>
        </div>
        <div class="rating">⭐ ${m.rating}</div>
      </div>
      <div class="card-body">
        <div class="genre-tag">${m.genre} · ${m.duration}</div>
        <div class="title">${m.title}</div>
        <div class="card-footer">
          <div class="price">From <strong>₹${m.price}</strong></div>
          <a href="movie.html?id=${m.id}" class="btn btn-primary btn-sm">Book</a>
        </div>
      </div>
    </div>
  `).join('');
}

// Home page trending
function initTrending() {
  const grid = document.getElementById('trending-grid');
  if (!grid) return;
  renderMovieCards(grid, MOVIES.slice(0, 3));
}

// ───────────────────────────────────────────
// MOVIE DETAIL PAGE
// ───────────────────────────────────────────
function initMovieDetail() {
  const container = document.getElementById('movie-detail');
  if (!container) return;

  const params = new URLSearchParams(window.location.search);
  const id = parseInt(params.get('id'));
  const movie = MOVIES.find(m => m.id === id);

  if (!movie) {
    container.innerHTML = '<p style="color:var(--gray)">Movie not found.</p>';
    return;
  }

  document.title = `${movie.title} – CineFlow`;

  container.innerHTML = `
    <div class="breadcrumb">
      <a href="movies.html">Movies</a>
      <span>›</span>
      <span>${movie.title}</span>
    </div>
    <div class="movie-detail-inner">
      <div class="detail-poster">
        <div class="poster-placeholder" style="height:100%;min-height:360px;">
          <div class="movie-icon" style="font-size:72px">${EMOJIS[movie.id - 1] || '🎬'}</div>
          <div class="movie-title-ph" style="font-size:16px">${movie.title}</div>
        </div>
      </div>
      <div class="detail-meta">
        <div class="genre-tag">${movie.genre}</div>
        <h1>${movie.title}</h1>
        <div class="detail-stats">
          <div class="stat"><span class="stat-label">Rating</span><span class="stat-value">⭐ ${movie.rating}</span></div>
          <div class="stat"><span class="stat-label">Duration</span><span class="stat-value">${movie.duration}</span></div>
          <div class="stat"><span class="stat-label">Language</span><span class="stat-value">${movie.language}</span></div>
          <div class="stat"><span class="stat-label">Price</span><span class="stat-value">₹${movie.price}</span></div>
        </div>
        <p class="detail-desc">${movie.desc}</p>
        ${movie.seats_left <= 6 ? `<div class="seats-warning">⚠️ Only ${movie.seats_left} seats left! Book now.</div>` : ''}
        <a href="seats.html?id=${movie.id}" class="btn btn-primary">Select Seats →</a>
      </div>
    </div>
  `;
}

// ───────────────────────────────────────────
// REGISTER
// ───────────────────────────────────────────
function initRegister() {
  const form = document.getElementById('register-form');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const name = form.querySelector('#reg-name').value.trim();
    const email = form.querySelector('#reg-email').value.trim().toLowerCase();
    const pass = form.querySelector('#reg-pass').value;
    const pass2 = form.querySelector('#reg-pass2').value;

    if (!name || !email || !pass) return showToast('reg-toast', 'All fields are required.', 'error');
    if (pass !== pass2) return showToast('reg-toast', 'Passwords do not match.', 'error');
    if (pass.length < 6) return showToast('reg-toast', 'Password must be at least 6 characters.', 'error');

    const users = JSON.parse(localStorage.getItem('cf_users') || '[]');
    if (users.find(u => u.email === email)) return showToast('reg-toast', 'Email already registered.', 'error');

    users.push({ name, email, pass });
    localStorage.setItem('cf_users', JSON.stringify(users));
    showToast('reg-toast', 'Account created! Redirecting...', 'success');
    setTimeout(() => window.location.href = 'login.html', 1500);
  });
}

// ───────────────────────────────────────────
// LOGIN
// ───────────────────────────────────────────
function initLogin() {
  const form = document.getElementById('login-form');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const email = form.querySelector('#login-email').value.trim().toLowerCase();
    const pass = form.querySelector('#login-pass').value;

    // Admin shortcut
    if (email === 'admin@cineflow.com' && pass === 'admin123') {
      localStorage.setItem('cf_user', JSON.stringify({ name: 'Admin', email, role: 'admin' }));
      showToast('login-toast', 'Welcome, Admin! Redirecting...', 'success');
      setTimeout(() => window.location.href = 'admin.html', 1200);
      return;
    }

    const users = JSON.parse(localStorage.getItem('cf_users') || '[]');
    const user = users.find(u => u.email === email && u.pass === pass);

    if (!user) return showToast('login-toast', 'Invalid email or password.', 'error');

    localStorage.setItem('cf_user', JSON.stringify({ name: user.name, email: user.email }));
    showToast('login-toast', `Welcome back, ${user.name}!`, 'success');
    setTimeout(() => window.location.href = 'index.html', 1200);
  });
}

// ───────────────────────────────────────────
// SEAT SELECTION
// ───────────────────────────────────────────
function initSeats() {
  const grid = document.getElementById('seats-grid');
  if (!grid) return;

  const params = new URLSearchParams(window.location.search);
  const id = parseInt(params.get('id'));
  const movie = MOVIES.find(m => m.id === id) || MOVIES[0];

  // Set movie info
  const movieTitle = document.getElementById('seat-movie-title');
  const moviePrice = document.getElementById('seat-movie-price');
  if (movieTitle) movieTitle.textContent = movie.title;
  if (moviePrice) moviePrice.textContent = '₹' + movie.price;

  const totalSeats = 20;
  // Randomly mark some as taken
  const takenSeats = [3, 7, 11, 15, 18];
  let selectedSeats = [];

  // Render seats
  grid.innerHTML = '';
  for (let i = 1; i <= totalSeats; i++) {
    const seat = document.createElement('div');
    seat.className = 'seat';
    seat.textContent = i;
    seat.dataset.seat = i;

    if (takenSeats.includes(i)) {
      seat.classList.add('taken');
      seat.title = 'Already booked';
    } else {
      seat.addEventListener('click', () => toggleSeat(seat, i, movie));
    }

    grid.appendChild(seat);
  }

  function toggleSeat(seatEl, num, movie) {
    if (selectedSeats.includes(num)) {
      selectedSeats = selectedSeats.filter(s => s !== num);
      seatEl.classList.remove('selected');
    } else {
      selectedSeats.push(num);
      seatEl.classList.add('selected');
    }
    updateSummary(movie);
  }

  function updateSummary(movie) {
    const selDisplay = document.getElementById('summary-seats');
    const totalDisplay = document.getElementById('summary-total');
    if (selDisplay) selDisplay.textContent = selectedSeats.length ? selectedSeats.join(', ') : '—';
    if (totalDisplay) totalDisplay.textContent = '₹' + (selectedSeats.length * movie.price);
  }

  // Confirm booking
  const confirmBtn = document.getElementById('confirm-booking');
  if (confirmBtn) {
    confirmBtn.addEventListener('click', () => {
      if (!selectedSeats.length) {
        alert('Please select at least one seat.');
        return;
      }
      const booking = {
        id: generateId(),
        movieId: movie.id,
        movieTitle: movie.title,
        genre: movie.genre,
        seats: selectedSeats,
        price: movie.price,
        total: selectedSeats.length * movie.price,
        date: new Date().toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }),
        time: '7:30 PM',
        hall: 'Hall A',
        bookedAt: new Date().toISOString()
      };

      localStorage.setItem('cf_last_booking', JSON.stringify(booking));

      // Save to all bookings list
      const all = JSON.parse(localStorage.getItem('cf_bookings') || '[]');
      all.push(booking);
      localStorage.setItem('cf_bookings', JSON.stringify(all));

      window.location.href = 'confirmation.html';
    });
  }
}

// ───────────────────────────────────────────
// CONFIRMATION PAGE
// ───────────────────────────────────────────
function initConfirmation() {
  const card = document.getElementById('ticket-card');
  if (!card) return;

  const booking = JSON.parse(localStorage.getItem('cf_last_booking') || 'null');
  if (!booking) {
    card.innerHTML = '<p style="color:var(--gray);text-align:center;padding:24px">No booking found.</p>';
    return;
  }

  card.innerHTML = `
    <div class="ticket-header">
      <span class="ticket-logo">CINEFLOW</span>
      <span class="ticket-id">#${booking.id}</span>
    </div>
    <hr class="ticket-divider">
    <div class="ticket-body">
      <div class="ticket-row"><span class="ticket-key">Movie</span><span class="ticket-val">${booking.movieTitle}</span></div>
      <div class="ticket-row"><span class="ticket-key">Genre</span><span class="ticket-val">${booking.genre}</span></div>
      <div class="ticket-row"><span class="ticket-key">Date</span><span class="ticket-val">${booking.date}</span></div>
      <div class="ticket-row"><span class="ticket-key">Show Time</span><span class="ticket-val">${booking.time}</span></div>
      <div class="ticket-row"><span class="ticket-key">Hall</span><span class="ticket-val">${booking.hall}</span></div>
      <div class="ticket-row"><span class="ticket-key">Seats</span><span class="ticket-val">${booking.seats.join(', ')}</span></div>
      <div class="ticket-row"><span class="ticket-key">Amount Paid</span><span class="ticket-val">₹${booking.total}</span></div>
    </div>
  `;
}

// ───────────────────────────────────────────
// FEEDBACK
// ───────────────────────────────────────────
function initFeedback() {
  const form = document.getElementById('feedback-form');
  if (!form) return;

  // Star rating
  let currentRating = 0;
  const stars = $$('.star');

  stars.forEach((star, index) => {
    star.addEventListener('mouseover', () => highlightStars(index + 1));
    star.addEventListener('mouseleave', () => highlightStars(currentRating));
    star.addEventListener('click', () => { currentRating = index + 1; highlightStars(currentRating); });
  });

  function highlightStars(count) {
    stars.forEach((s, i) => { s.classList.toggle('active', i < count); });
  }

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const name = form.querySelector('#fb-name').value.trim();
    const movie = form.querySelector('#fb-movie').value.trim();
    const text = form.querySelector('#fb-text').value.trim();

    if (!text) return showToast('fb-toast', 'Please enter your feedback.', 'error');

    const feedback = {
      id: generateId(),
      name: name || 'Anonymous',
      movie,
      rating: currentRating,
      text,
      date: new Date().toLocaleDateString('en-IN')
    };

    const all = JSON.parse(localStorage.getItem('cf_feedback') || '[]');
    all.push(feedback);
    localStorage.setItem('cf_feedback', JSON.stringify(all));

    showToast('fb-toast', '🎉 Thank you for your feedback!', 'success');
    form.reset();
    currentRating = 0;
    highlightStars(0);

    renderFeedbackList();
  });

  renderFeedbackList();
}

function renderFeedbackList() {
  const list = document.getElementById('feedback-list');
  if (!list) return;

  const all = JSON.parse(localStorage.getItem('cf_feedback') || '[]').reverse();
  if (!all.length) {
    list.innerHTML = '<p style="color:var(--gray);font-size:13px">No feedback yet. Be the first!</p>';
    return;
  }

  list.innerHTML = all.slice(0, 5).map(f => `
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:20px;margin-bottom:16px">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
        <strong style="font-size:14px">${f.name}</strong>
        <span style="font-size:12px;color:var(--gray)">${f.date}</span>
      </div>
      ${f.movie ? `<div style="font-size:11px;color:var(--gray);letter-spacing:2px;text-transform:uppercase;margin-bottom:8px">${f.movie}</div>` : ''}
      ${f.rating ? `<div style="margin-bottom:8px;font-size:14px">${'★'.repeat(f.rating)}${'☆'.repeat(5 - f.rating)}</div>` : ''}
      <p style="font-size:13px;color:var(--gray);line-height:1.7">${f.text}</p>
    </div>
  `).join('');
}

// ───────────────────────────────────────────
// ADMIN PAGE
// ───────────────────────────────────────────
function initAdmin() {
  const sidebar = $$('.sidebar-nav li a');
  if (!sidebar.length) return;

  // Sidebar tab switching
  sidebar.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const target = link.dataset.tab;
      if (!target) return;
      sidebar.forEach(l => l.classList.remove('active'));
      link.classList.add('active');
      $$('.admin-section').forEach(sec => sec.classList.remove('active'));
      const sec = document.getElementById(target);
      if (sec) sec.classList.add('active');
    });
  });

  // Load movies table
  loadAdminMoviesTable();
  loadBookingsTable();
  loadAdminStats();

  // Add movie form
  const addForm = document.getElementById('add-movie-form');
  if (addForm) {
    addForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const title = addForm.querySelector('#am-title').value.trim();
      const genre = addForm.querySelector('#am-genre').value.trim();
      const price = addForm.querySelector('#am-price').value;
      if (!title || !genre || !price) return showToast('am-toast', 'All fields required.', 'error');

      const custom = JSON.parse(localStorage.getItem('cf_custom_movies') || '[]');
      const newId = 100 + custom.length + 1;
      custom.push({ id: newId, title, genre, price: parseInt(price), rating: '7.0', duration: '2h 00m', language: 'English', desc: 'Coming soon.', seats_left: 20 });
      localStorage.setItem('cf_custom_movies', JSON.stringify(custom));
      showToast('am-toast', 'Movie added!', 'success');
      addForm.reset();
      loadAdminMoviesTable();
      loadAdminStats();
    });
  }
}

function getAllMovies() {
  const custom = JSON.parse(localStorage.getItem('cf_custom_movies') || '[]');
  return [...MOVIES, ...custom];
}

function loadAdminMoviesTable() {
  const tbody = document.getElementById('movies-tbody');
  if (!tbody) return;

  const movies = getAllMovies();
  tbody.innerHTML = movies.map((m, i) => `
    <tr>
      <td><div class="table-poster">${EMOJIS[m.id - 1] || '🎬'}</div></td>
      <td><strong>${m.title}</strong></td>
      <td>${m.genre}</td>
      <td>₹${m.price}</td>
      <td>⭐ ${m.rating}</td>
      <td>
        <div class="action-btns">
          <button class="btn btn-outline btn-sm" onclick="editMovie(${m.id})">Edit</button>
          <button class="btn btn-danger btn-sm" onclick="deleteMovie(${m.id})">Delete</button>
        </div>
      </td>
    </tr>
  `).join('');
}

function loadBookingsTable() {
  const tbody = document.getElementById('bookings-tbody');
  if (!tbody) return;

  const bookings = JSON.parse(localStorage.getItem('cf_bookings') || '[]').reverse();
  if (!bookings.length) {
    tbody.innerHTML = '<tr><td colspan="6" style="color:var(--gray);text-align:center;padding:32px">No bookings yet.</td></tr>';
    return;
  }

  tbody.innerHTML = bookings.map(b => `
    <tr>
      <td><code style="font-size:11px;color:var(--gray)">#${b.id}</code></td>
      <td>${b.movieTitle}</td>
      <td>${b.seats.join(', ')}</td>
      <td>${b.date}</td>
      <td>₹${b.total}</td>
      <td><span style="color:var(--success);font-size:12px;font-weight:600">✓ Confirmed</span></td>
    </tr>
  `).join('');
}

function loadAdminStats() {
  const movies = getAllMovies();
  const bookings = JSON.parse(localStorage.getItem('cf_bookings') || '[]');
  const users = JSON.parse(localStorage.getItem('cf_users') || '[]');
  const revenue = bookings.reduce((sum, b) => sum + (b.total || 0), 0);

  const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
  set('stat-movies', movies.length);
  set('stat-bookings', bookings.length);
  set('stat-users', users.length);
  set('stat-revenue', '₹' + revenue.toLocaleString('en-IN'));
}

function editMovie(id) {
  const title = prompt('Enter new title for movie ID ' + id + ':');
  if (!title) return;
  const custom = JSON.parse(localStorage.getItem('cf_custom_movies') || '[]');
  const idx = custom.findIndex(m => m.id === id);
  if (idx !== -1) { custom[idx].title = title; localStorage.setItem('cf_custom_movies', JSON.stringify(custom)); }
  loadAdminMoviesTable();
}

function deleteMovie(id) {
  if (!confirm('Delete this movie?')) return;
  const custom = JSON.parse(localStorage.getItem('cf_custom_movies') || '[]');
  const filtered = custom.filter(m => m.id !== id);
  localStorage.setItem('cf_custom_movies', JSON.stringify(filtered));
  // Cannot delete base movies, just show message
  loadAdminMoviesTable();
  loadAdminStats();
  showToast('am-toast', 'Movie removed from list.', 'success');
}

// ───────────────────────────────────────────
// INIT – auto-detect page and run
// ───────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initNavbar();
  initSearch();
  initTrending();
  initMoviesPage();
  initMovieDetail();
  initRegister();
  initLogin();
  initSeats();
  initConfirmation();
  initFeedback();
  initAdmin();
});
