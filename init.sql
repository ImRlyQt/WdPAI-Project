-- Use this file to initialize your PostgreSQL database schema for DeckHeaven
-- Example users table for PostgreSQL
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nick VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    dob DATE NOT NULL
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
