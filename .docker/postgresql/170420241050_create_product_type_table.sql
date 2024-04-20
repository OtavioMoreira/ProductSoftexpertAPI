CREATE TABLE products_type (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    description TEXT,
    percentage INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);