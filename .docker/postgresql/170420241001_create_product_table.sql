CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    price DECIMAL(10, 2),
    qtd INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);