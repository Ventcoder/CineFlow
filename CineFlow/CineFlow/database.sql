-- db setup for cineflow

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS movies (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    genre VARCHAR(50) NOT NULL,
    rating VARCHAR(5) DEFAULT '7.0',
    duration VARCHAR(20) DEFAULT '2h 00m',
    price INTEGER NOT NULL,
    language VARCHAR(50) DEFAULT 'English',
    description TEXT,
    seats_left INTEGER DEFAULT 20,
    emoji VARCHAR(10) DEFAULT '🎬',
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS bookings (
    id SERIAL PRIMARY KEY,
    booking_ref VARCHAR(30) NOT NULL UNIQUE,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    movie_id INTEGER REFERENCES movies(id) ON DELETE SET NULL,
    movie_title VARCHAR(200) NOT NULL,
    seats TEXT NOT NULL,
    total_amount INTEGER NOT NULL,
    show_date VARCHAR(50) NOT NULL,
    show_time VARCHAR(20) DEFAULT '7:30 PM',
    hall VARCHAR(20) DEFAULT 'Hall A',
    status VARCHAR(20) DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS feedback (
    id SERIAL PRIMARY KEY,
    user_name VARCHAR(100) DEFAULT 'Anonymous',
    movie_name VARCHAR(200),
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- initial data inserts

INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@cineflow.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON CONFLICT (email) DO NOTHING;

INSERT INTO movies (title, genre, rating, duration, price, language, description, seats_left, emoji) VALUES
('Interstellar Void',  'Sci-Fi',   '8.9', '2h 49m', 280, 'English', 'A crew of astronauts journey through a wormhole near Saturn in search of a new home for humanity as Earth faces extinction.', 4,  '🚀'),
('Shadow Protocol',    'Thriller', '7.8', '2h 12m', 240, 'English', 'A rogue intelligence operative uncovers a global conspiracy that threatens to rewrite history.', 12, '🔫'),
('The Last Sonata',    'Drama',    '8.2', '1h 58m', 220, 'English', 'A famed pianist losing his hearing races against time to compose one final masterpiece.', 7,  '🎹'),
('Neon Requiem',       'Action',   '7.5', '2h 05m', 260, 'English', 'In a city where corporations rule and justice is for sale, one ex-detective fights to reclaim the streets.', 15, '⚡'),
('Pale Blue Tide',     'Horror',   '7.2', '1h 52m', 200, 'English', 'A marine biologist deep-sea expedition encounters something ancient and sentient.', 3,  '🌊'),
('The Grand Cipher',   'Mystery',  '8.5', '2h 28m', 250, 'English', 'A cryptographer inherits a map containing the locations of seven stolen masterpieces.', 9,  '🔍');

-- queries

INSERT INTO users (name, email, password, role) 
VALUES ('John Doe', 'john@example.com', 'hashedpassword123', 'user');

INSERT INTO bookings (booking_ref, user_id, movie_id, movie_title, seats, total_amount, show_date)
VALUES ('CF-A1B2-C3D4', 2, 1, 'Interstellar Void', '4,5', 560, '15 Apr 2026');

INSERT INTO feedback (user_name, movie_name, rating, message)
VALUES ('Jane Smith', 'Neon Requiem', 5, 'Absolutely stunning visuals!');

SELECT id, title, genre, price FROM movies ORDER BY id ASC;

SELECT * FROM movies WHERE id = 1;

SELECT title, genre, price FROM movies WHERE title ILIKE '%Void%' OR genre ILIKE '%Sci-Fi%';

SELECT COALESCE(SUM(total_amount), 0) AS total_revenue FROM bookings;

SELECT b.booking_ref, b.movie_title, b.seats, u.name, u.email 
FROM bookings b
JOIN users u ON b.user_id = u.id
WHERE u.id = 2;

SELECT user_name, movie_name, message 
FROM feedback 
WHERE rating = 5 
ORDER BY created_at DESC 
LIMIT 5;

UPDATE movies 
SET price = 300 
WHERE id = 3;

UPDATE movies 
SET seats_left = seats_left - 2 
WHERE id = 1 AND seats_left >= 2;

UPDATE users 
SET password = 'newhashedpassword456' 
WHERE email = 'john@example.com';

DELETE FROM movies WHERE id = 6;

DELETE FROM bookings WHERE booking_ref = 'CF-A1B2-C3D4';

DELETE FROM feedback WHERE created_at < NOW() - INTERVAL '1 year';
