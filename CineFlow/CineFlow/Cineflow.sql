-- db setup for cineflow
-- Run this in your pgAdmin or database tool to setup the tables and data

-- 1. USERS TABLE
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT NOW()
);

-- 2. MOVIES TABLE
CREATE TABLE IF NOT EXISTS movies (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    genre VARCHAR(50) NOT NULL,
    rating VARCHAR(5) DEFAULT '7.0',
    duration VARCHAR(20) DEFAULT '2h 00m',
    price INTEGER NOT NULL,
    language VARCHAR(50) DEFAULT 'English',
    description TEXT,
    seats_left INTEGER DEFAULT 80,
    emoji VARCHAR(10) DEFAULT '🎬',
    banner_url TEXT DEFAULT '',
    backdrop_url TEXT DEFAULT '',
    created_at TIMESTAMP DEFAULT NOW()
);

-- 3. BOOKINGS TABLE
CREATE TABLE IF NOT EXISTS bookings (
    id SERIAL PRIMARY KEY,
    booking_ref VARCHAR(30) NOT NULL UNIQUE,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    movie_id INTEGER REFERENCES movies(id) ON DELETE SET NULL,
    movie_title VARCHAR(200) NOT NULL,
    seats TEXT NOT NULL,
    seat_tier VARCHAR(50) DEFAULT 'Standard',
    snacks_amount INTEGER DEFAULT 0,
    total_amount INTEGER NOT NULL,
    show_date VARCHAR(50) NOT NULL,
    show_time VARCHAR(20) DEFAULT '19:30',
    hall VARCHAR(20) DEFAULT 'Hall A',
    status VARCHAR(20) DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT NOW()
);

-- 4. FEEDBACK TABLE
CREATE TABLE IF NOT EXISTS feedback (
    id SERIAL PRIMARY KEY,
    user_name VARCHAR(100) DEFAULT 'Anonymous',
    movie_name VARCHAR(200),
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- -----------------------------------------------------
-- INITIAL DATA INSERTS
-- -----------------------------------------------------

-- Admins and Users (Passwords are 'admin123' and 'user123')
INSERT INTO users (name, email, password, role) 
VALUES 
('Admin Account', 'admin@cineflow.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('John Doe', 'user@cineflow.com', '$2y$10$x.XF6W5c/nQ0K1fQ.X/G0.T9U7xTqJbU3j0Vwq369mXv1H7yZ/K2m', 'user')
ON CONFLICT (email) DO NOTHING;

-- CLEAR MOVIES if exists so we repopulate with real ones
TRUNCATE TABLE movies CASCADE;

-- 10 Blockbuster Movies with Real Poster URLs (from IMDB / Wikipedia equivalents)
INSERT INTO movies (title, genre, rating, duration, price, language, description, seats_left, emoji, banner_url) VALUES
('Interstellar', 'Sci-Fi', '8.7', '2h 49m', 280, 'English', 'A team of explorers travel through a wormhole in space in an attempt to ensure humanitys survival.', 80, '🚀', 'https://m.media-amazon.com/images/M/MV5BZjdkOTU3MDItN2IxOS00NTZkLWE3MzYtZTlkNDJjYmI5N2RkXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg'),
('The Dark Knight', 'Action', '9.0', '2h 32m', 250, 'English', 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.', 80, '🦇', 'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NjF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg'),
('Inception', 'Sci-Fi', '8.8', '2h 28m', 260, 'English', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.', 80, '🥾', 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SX300.jpg'),
('Avengers: Endgame', 'Action', '8.4', '3h 01m', 300, 'English', 'After the devastating events of Infinity War, the Avengers assemble once more in order to reverse Thanos actions and restore balance to the universe.', 80, '🛡️', 'https://m.media-amazon.com/images/M/MV5BMTc5MDE2ODcwNV5BMl5BanBnXkFtZTgwMzI2NzQ2NzM@._V1_SX300.jpg'),
('Spider-Man: Into the Spider-Verse', 'Animation', '8.4', '1h 57m', 220, 'English', 'Teen Miles Morales becomes the Spider-Man of his universe, and must join with five spider-powered individuals from other dimensions to stop a threat for all realities.', 80, '🕸️', 'https://m.media-amazon.com/images/M/MV5BMjMwNDkxMTgzOF5BMl5BanBnXkFtZTgwNTkwNTQ3NjM@._V1_SX300.jpg'),
('Joker', 'Drama', '8.4', '2h 02m', 240, 'English', 'In Gotham City, mentally troubled comedian Arthur Fleck is disregarded and mistreated by society. He then embarks on a downward spiral of revolution and bloody crime.', 80, '🤡', 'https://m.media-amazon.com/images/M/MV5BNGVjNWI4ZGUtNzE0MS00YTJmLWE0ZDctN2ZiYTk2YmI3NTYyXkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_SX300.jpg'),
('Dune', 'Sci-Fi', '8.0', '2h 35m', 270, 'English', 'A noble family becomes embroiled in a war for control over the galaxys most valuable asset while its heir becomes troubled by visions of a dark future.', 80, '🏜️', 'https://m.media-amazon.com/images/M/MV5BN2FjNmEyNWEtNDVkZi00ZzczLWE3MWYtYmVlzGFhM2QzZTgyXkEyXkFqcGdeQXVyNjIwMTIzNw@@._V1_SX300.jpg'),
('Oppenheimer', 'History', '8.4', '3h 00m', 290, 'English', 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.', 80, '💥', 'https://m.media-amazon.com/images/M/MV5BMDBmYTZjNjUtN2M1MS00MTQ2LTk2ODgtNzc2M2QyZGE5NTVjXkEyXkFqcGdeQXVyMzMwOTU5MDk@._V1_SX300.jpg'),
('Avatar', 'Action', '7.9', '2h 42m', 260, 'English', 'A paraplegic Marine dispatched to the moon Pandora on a unique mission becomes torn between following his orders and protecting the world he feels is his home.', 80, '🌿', 'https://m.media-amazon.com/images/M/MV5BMjEyOTYyMzUxNl5BMl5BanBnXkFtZTcwNTg0MTUzNA@@._V1_SX300.jpg'),
('The Matrix', 'Action', '8.7', '2h 16m', 240, 'English', 'When a beautiful stranger leads computer hacker Neo to a forbidding underworld, he discovers the shocking truth--the life he knows is the elaborate deception of an evil cyber-intelligence.', 80, '💊', 'https://m.media-amazon.com/images/M/MV5BNzQzOTk3OTAtNDQ0Zi00ZTVkLWI0MTEtMDllZjNkYzNjNTc4L2ltYWdlXkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_SX300.jpg');

