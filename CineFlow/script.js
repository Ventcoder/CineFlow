// ============================================
// CineFlow – script.js (PREMIUM PHP VERSION)
// ============================================

function $(sel, ctx) { return (ctx || document).querySelector(sel); }
function $$(sel, ctx) { return [...(ctx || document).querySelectorAll(sel)]; }

// Hamburger menu
function initNavbar() {
  const hamburger = $('.hamburger');
  const navLinks = $('.nav-links');
  if (!hamburger || !navLinks) return;

  hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('open');
  });

  document.addEventListener('click', (e) => {
    if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
      navLinks.classList.remove('open');
    }
  });
}

function initSearch() {
  const btn = document.getElementById('search-btn');
  const input = document.getElementById('search-input');
  if (!btn || !input) return;

  function doSearch() {
    const q = input.value.trim();
    if (q) window.location.href = `02_page_catalog.php?q=${encodeURIComponent(q)}`;
  }

  btn.addEventListener('click', doSearch);
  input.addEventListener('keydown', e => { if (e.key === 'Enter') doSearch(); });
}

// Seat Selection Logic
function initSeats() {
  const grid = document.getElementById('seats-grid');
  if (!grid) return;

  const movieId = parseInt(grid.getAttribute('data-movie-id')) || 1;
  const movieTitle = grid.getAttribute('data-movie-title') || "Movie";
  const basePrice = parseInt(grid.getAttribute('data-movie-price')) || 250;

  const totalSeats = 150;
  // Generate a very sparse set of taken seats so it doesn't look broken
  const takenSeats = [(movieId * 2) % 150, (movieId * 5) % 150, (movieId * 7) % 150, (movieId * 11) % 150, (movieId * 17) % 150, (movieId * 23) % 150, (movieId * 31) % 150];

  let selectedSeatsData = [];
  grid.innerHTML = '';
  
  // 15 rows x 10 seats
  for (let r = 0; r < 15; r++) {
    
    // Inject Row Label directly into the grid
    const rowLabel = document.createElement('div');
    rowLabel.textContent = String.fromCharCode(65 + r);
    rowLabel.style.display = 'flex';
    rowLabel.style.alignItems = 'center';
    rowLabel.style.justifyContent = 'center';
    rowLabel.style.fontWeight = 'bold';
    rowLabel.style.color = 'var(--text-muted)';
    rowLabel.style.fontSize = '12px';
    grid.appendChild(rowLabel);

    for (let c = 1; c <= 10; c++) {
      let i = (r * 10) + c;
      const seat = document.createElement('div');
      seat.className = 'seat';
      seat.textContent = c;
      
      let tier = 'Standard';
      let seatPrice = basePrice;
      
      // Top 3 rows VIP, next 5 rows Premium, bottom 7 Standard
      if (r < 3) {
          tier = 'VIP';
          seatPrice += 150;
      } else if (r < 8) {
          tier = 'Premium';
          seatPrice += 50;
      }
      
      // Color-code available seats
      seat.classList.add('seat-' + tier.toLowerCase());

      const rowLabel = String.fromCharCode(65 + r); // A, B, C...
      const seatId = `${rowLabel}${c}`;

      if (takenSeats.includes(i)) {
        seat.classList.add('taken');
        seat.title = `${seatId} (Booked)`;
        seat.innerHTML = `<span style="opacity:0.3; font-size:14px;">✕</span>`;
      } else {
        seat.title = `${tier} - ${seatId} - ₹${seatPrice}`;
        seat.addEventListener('click', () => {
          const index = selectedSeatsData.findIndex(s => s.id === seatId);
          if (index !== -1) {
            selectedSeatsData.splice(index, 1);
            seat.classList.remove('selected');
          } else {
            if(selectedSeatsData.length >= 10) {
                alert("You can only select up to 10 seats.");
                return;
            }
            selectedSeatsData.push({ id: seatId, price: seatPrice, tier: tier });
            seat.classList.add('selected');
          }
          updateSummary();
        });
      }
      grid.appendChild(seat);
    }
  }

  // Date and Time Chips
  const dateChips = document.getElementById('date-selector');
  let selectedDateStr = "Today";
  
  if (dateChips) {
      dateChips.innerHTML = '';
      const today = new Date();
      for(let d=0; d<4; d++) {
          const dateParam = new Date(today);
          dateParam.setDate(today.getDate() + d);
          const dayName = d === 0 ? "Today" : d === 1 ? "Tomorrow" : dateParam.toLocaleDateString('en-US', {weekday:'short'});
          const dateDate = dateParam.getDate();
          
          const chip = document.createElement('div');
          chip.className = `chip ${d===0 ? 'active' : ''}`;
          chip.innerHTML = `<strong>${dayName}</strong> <span style="opacity:0.7;font-size:12px">${dateDate}</span>`;
          chip.onclick = () => {
            $$('#date-selector .chip').forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            selectedDateStr = `${dayName}, ${dateParam.toLocaleDateString('en-US', {month:'short', day:'numeric'})}`;
            $('#summary-date').textContent = selectedDateStr;
          };
          dateChips.appendChild(chip);
      }
      selectedDateStr = `Today, ${today.toLocaleDateString('en-US', {month:'short', day:'numeric'})}`;
      $('#summary-date').textContent = selectedDateStr;
  }

  let selectedTimeStr = "09:00 AM";
  $$('#time-selector .chip').forEach(chip => {
      chip.addEventListener('click', () => {
          $$('#time-selector .chip').forEach(c => c.classList.remove('active'));
          chip.classList.add('active');
          selectedTimeStr = chip.innerText;
          $('#summary-time').textContent = selectedTimeStr;
      });
  });

  // Init Screen Selection
  let selectedScreenStr = "Screen 1 (IMAX)";
  const screenChips = $$('#screen-selector .chip');
  if (screenChips.length > 0) {
      screenChips.forEach(chip => {
          chip.addEventListener('click', () => {
              // Update Active State
              screenChips.forEach(c => c.classList.remove('active'));
              chip.classList.add('active');
              
              // Update Summary text visually
              selectedScreenStr = chip.innerText;
              $('#summary-screen').textContent = selectedScreenStr;
              
              // Flash animation to simulate switching screens
              const seatsGrid = $('#seats-grid');
              if (seatsGrid) {
                  seatsGrid.style.opacity = '0.3';
                  setTimeout(() => { seatsGrid.style.opacity = '1'; }, 250);
              }
          });
      });
  }

  function updateSummary() {
    let subtotal = selectedSeatsData.reduce((sum, s) => sum + s.price, 0);
    
    $('#summary-count').textContent = selectedSeatsData.length;
    $('#summary-seats').textContent = selectedSeatsData.length ? selectedSeatsData.map(s => s.id).join(', ') : '—';
    $('#summary-subtotal').textContent = '₹' + subtotal;
    $('#summary-total').textContent = '₹' + subtotal;
  }

  const continueBtn = document.getElementById('continue-snacks');
  if (continueBtn) {
    continueBtn.addEventListener('click', () => {
      if (!selectedSeatsData.length) return alert('Please select at least one seat.');
      
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '05_page_cinema_snacks.php';
      
      const addField = (name, value) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
      };

      addField('movie_id', movieId);
      addField('movie_title', movieTitle);
      addField('seats', selectedSeatsData.map(s=>s.id).join(','));
      addField('tickets_total', selectedSeatsData.reduce((sum, s) => sum + s.price, 0));
      addField('show_date', selectedDateStr);
      addField('show_time', selectedTimeStr);
      addField('screen', selectedScreenStr);
      
      document.body.appendChild(form);
      
      // Smooth fade transition
      document.body.style.opacity = '0';
      document.body.style.transition = 'opacity 0.4s ease';
      setTimeout(() => form.submit(), 400);
    });
  }
}

// Snacks Logic
function initSnacks() {
    const container = document.getElementById('snacks-container');
    if(!container || typeof TICKETS_TOTAL === 'undefined') return;

    const SNACKS = [
        { id: 'S1', name: 'Large Popcorn', price: 250, icon: '🍿', desc: 'Butter salted large popcorn' },
        { id: 'S2', name: 'Nacho Chips', price: 200, icon: '🧀', desc: 'Crispy nachos with jalapeño cheese' },
        { id: 'S3', name: 'Cold Drink (Large)', price: 150, icon: '🥤', desc: '750ml refreshing cola' },
        { id: 'S4', name: 'Caramel Popcorn', price: 280, icon: '🍿', desc: 'Sweet and crunchy caramel popcorn' }
    ];

    let cart = { 'S1': 0, 'S2': 0, 'S3': 0, 'S4': 0 };

    function renderSnacks() {
        container.innerHTML = SNACKS.map(s => `
            <div class="snack-card">
                <div class="snack-icon">${s.icon}</div>
                <div class="snack-info">
                    <h4>${s.name}</h4>
                    <div style="font-size:13px; color:var(--text-muted); margin-bottom:5px;">${s.desc}</div>
                    <div class="snack-price">₹${s.price}</div>
                </div>
                <div class="qty-control">
                    <button class="qty-btn" onclick="updateSnack('${s.id}', -1)">-</button>
                    <span style="font-weight:bold; width:20px; text-align:center;">${cart[s.id]}</span>
                    <button class="qty-btn" onclick="updateSnack('${s.id}', 1)">+</button>
                </div>
            </div>
        `).join('');
    }

    window.updateSnack = function(id, d) {
        if(cart[id] + d >= 0 && cart[id] + d <= 10) {
            cart[id] += d;
            
            let fbTotal = 0;
            SNACKS.forEach(s => { fbTotal += cart[s.id] * s.price; });
            
            document.getElementById('fb-total').innerText = '₹' + fbTotal;
            document.getElementById('grand-total').innerText = '₹' + (TICKETS_TOTAL + fbTotal);
            document.getElementById('btn-total').innerText = (TICKETS_TOTAL + fbTotal);
            document.getElementById('input-snacks-total').value = fbTotal;
            
            renderSnacks();
        }
    };

    renderSnacks();
    
    document.getElementById('pay-btn').addEventListener('click', () => {
        // Simulate payment gateway delay
        const btn = document.getElementById('pay-btn');
        btn.innerHTML = 'Processing Payment...';
        btn.disabled = true;
        setTimeout(() => {
            document.getElementById('checkout-form').submit();
        }, 1500);
    });
}

document.addEventListener('DOMContentLoaded', () => {
  initNavbar();
  initSearch();
  initSeats();
  initSnacks();
});
