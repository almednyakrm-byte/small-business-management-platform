CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE customers (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  address VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE sales (
  id INT AUTO_INCREMENT,
  customer_id INT NOT NULL,
  sale_date DATE NOT NULL,
  total DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (customer_id),
  FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

CREATE TABLE reports (
  id INT AUTO_INCREMENT,
  report_type ENUM('sales', 'accounting') NOT NULL,
  data JSON NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_permissions (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  page_name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  KEY (user_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY (user_id, page_name)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO customers (name, phone, address) VALUES
  ('Customer 1', '0123456789', 'Address 1'),
  ('Customer 2', '0987654321', 'Address 2');

INSERT INTO sales (customer_id, sale_date, total) VALUES
  (1, '2022-01-01', 100.00),
  (2, '2022-01-15', 200.00);

INSERT INTO reports (report_type, data) VALUES
  ('sales', '{"data": ["2022-01-01", 100.00], "total": 100.00}'),
  ('accounting', '{"data": ["2022-01-01", 100.00], "total": 100.00}');

INSERT INTO user_permissions (user_id, page_name) VALUES
  (1, 'الصفحة الرئيسية'),
  (1, 'قائمة العملاء'),
  (1, 'قائمة المبيعات'),
  (1, 'تقرير المبيعات'),
  (1, 'تقرير المحاسبات'),
  (1, 'تسجيل الدخول'),
  (1, 'تسجيل'),
  (1, 'قائمة العملاء'),
  (1, 'قائمة المبيعات');