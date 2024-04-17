CREATE TABLE product_product_type (
    product_id INTEGER REFERENCES products(id),
    product_type_id INTEGER REFERENCES products_type(id),
    PRIMARY KEY (product_id, product_type_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);