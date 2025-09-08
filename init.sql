-- Use this file to initialize your PostgreSQL database schema for DeckHeaven
-- Example users table for PostgreSQL
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nick VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    dob DATE NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE
);

-- Cards owned by users
CREATE TABLE IF NOT EXISTS user_cards (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    card_id VARCHAR(64) NOT NULL,
    name VARCHAR(200) NOT NULL,
    image_url TEXT,
    set_name VARCHAR(120),
    multiverseid INT,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(user_id, card_id)
);

-- Seed admin user (fresh DB only or idempotent by email)
INSERT INTO users (nick, email, password, dob, is_admin)
VALUES ('Admin', 'admin@gmail.com', '$2y$10$3LBmIqkHqzT2q7J3UjXw7OTnClf5hW.d83QGP5WvEJ2WgTqo2S7P6', '1970-01-01', TRUE)
ON CONFLICT (email) DO UPDATE SET is_admin = TRUE;
