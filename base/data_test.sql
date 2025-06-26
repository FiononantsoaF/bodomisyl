INSERT INTO users (name, email, password)
VALUES (
    'fy',
    'fy@gmail.com',
    '$2y$10$H4Dk5GuzVlu3c0VvDUD9su3AYCeUHRPl/LIsAfuW0vl6CwEMK0Mvy'
);

ALTER TABLE clients ADD password VARCHAR(1000) AFTER phone;
