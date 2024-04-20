CREATE TABLE product_product_type (
    id SERIAL PRIMARY KEY,
    product_id INTEGER,
    product_type_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (product_type_id) REFERENCES products_type(id) ON DELETE CASCADE
);
