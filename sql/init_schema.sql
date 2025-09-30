-- init_schema.sql
CREATE TABLE IF NOT EXISTS admin_users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT (now())
);

CREATE TABLE IF NOT EXISTS products (
  id SERIAL PRIMARY KEY,
  name_ar TEXT,
  description_ar TEXT,
  category VARCHAR(100),
  type VARCHAR(50),
  price NUMERIC(12,2),
  price_label VARCHAR(100),
  warranty VARCHAR(100),
  tags TEXT,
  image_url VARCHAR(255),
  badge_text VARCHAR(100),
  whatsapp_message TEXT,
  created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT (now())
);