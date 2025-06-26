CREATE DATABASE domisyl;
SET sql_mode = '';
USE domisyl;

CREATE TABLE job_category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NULL,
    phone VARCHAR(20),
    password VARCHAR(100),
    address VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    employee_id INT NOT NULL,
    service_id INT NOT NULL,
    subscription_id INT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status ENUM('confirmed', 'pending', 'cancelled') DEFAULT 'pending',
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

CREATE TABLE waiting_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    is_wait int not null,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id),
);

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    job_categ_id NOT NULL,
    specialty VARCHAR(100),
    is_active int not null,
    phone VARCHAR(20),
    email VARCHAR(150) UNIQUE NULL,
    address VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_categ_id) REFERENCES job_category(id)
);

CREATE TABLE service_category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    service_category_id NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    duration_minutes INT NOT NULL,  --- durée par séance(60 min, 90 min)
    validity_days  DECIMAL(10, 2),       --exemple : 30jours
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_category_id) REFERENCES service_category(id)
);

CREATE TABLE service_session (
    services_id INT,
    session_id INT,
    total_session INT DEFAULT 0,
    session_per_period INT NULL DEFAULT 0, -- Séances autorisées par période
    period_type ENUM('week', 'month') NULL,  --par semaine ou par mois 
    FOREIGN KEY (session_id) REFERENCES sessions(id)
    FOREIGN KEY (services_id) REFERENCES services(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

CREATE TABLE sessions(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    service_id INT NOT NULL,
    status ENUM('active', 'expired', 'cancelled') DEFAULT 'active',
    total_session INT DEFAULT 0,
    used_session INT DEFAULT 0,
    period_start DATE,
    period_end DATE,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NULL,
    subscription_id INT NULL,
    payment_method ENUM('paypal', 'stripe', 'mobile_money', 'bank_transfer') NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    deposit DECIMAL(10, 2) DEFAULT 0,
    balance DECIMAL(10, 2) DEFAULT 0,
    status ENUM('paid', 'partial', 'pending') DEFAULT 'pending',
    paid_at DATETIME,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id),
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id)

);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id INT NOT NULL,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    issued_date DATE NOT NULL,
    file_url VARCHAR(255),
    FOREIGN KEY (payment_id) REFERENCES payments(id)
);

CREATE TABLE client_infos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    preferences TEXT,
    restrictions TEXT,
    history TEXT,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    service_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

-- CREATE TABLE service_benefits (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     service_category_id INT NOT NULL,
--     benefit TEXT NOT NULL,
--     FOREIGN KEY (service_category_id) REFERENCES services_category(id)
-- );

-- CREATE TABLE subscription_usage(
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     subscription_id INT NOT NULL,
--     appointment_id INT NOT NULL,
--     used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (subscription_id) REFERENCES subscriptions(id),
--     FOREIGN KEY (appointment_id) REFERENCES appointments(id)
-- );

