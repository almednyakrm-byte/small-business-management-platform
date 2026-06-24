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

CREATE TABLE employees (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  job_title VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE products (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE invoices (
  id INT AUTO_INCREMENT,
  employee_id INT NOT NULL,
  product_id INT NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (employee_id) REFERENCES employees(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE user_roles (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO employees (name, job_title) VALUES
  ('Employee 1', 'Job Title 1'),
  ('Employee 2', 'Job Title 2'),
  ('Employee 3', 'Job Title 3');

INSERT INTO products (name, description, price) VALUES
  ('Product 1', 'Description 1', 10.99),
  ('Product 2', 'Description 2', 20.99),
  ('Product 3', 'Description 3', 30.99);

INSERT INTO invoices (employee_id, product_id, amount) VALUES
  (1, 1, 10.99),
  (2, 2, 20.99),
  (3, 3, 30.99);

INSERT INTO user_roles (user_id, role) VALUES
  (1, 'admin');