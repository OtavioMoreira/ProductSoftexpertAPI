CREATE TABLE sales (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    qtd INT NOT NULL,
    purchaseValue DECIMAL(10, 2),
    taxValue DECIMAL(10, 2),
    totalValuePurchase DECIMAL(10, 2),
    totalTaxValuePurchase DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);