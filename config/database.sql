-- Create database
-- CREATE DATABASE IF NOT EXISTS car_rental_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE car_rental_db;

-- users
CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  address VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- admin
CREATE TABLE IF NOT EXISTS admin (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(120),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- cars
CREATE TABLE IF NOT EXISTS cars (
  car_id INT AUTO_INCREMENT PRIMARY KEY,
  car_name VARCHAR(120) NOT NULL,
  brand VARCHAR(80) NOT NULL,
  model VARCHAR(80) NOT NULL,
  year INT,
  price_per_day DECIMAL(10,2) NOT NULL,
  fuel_type VARCHAR(40),
  seats INT,
  transmission VARCHAR(40),
  image VARCHAR(255),
  availability ENUM('available','booked') DEFAULT 'available',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- bookings
CREATE TABLE IF NOT EXISTS bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  car_id INT NOT NULL,
  pickup_date DATE NOT NULL,
  return_date DATE NOT NULL,
  total_days INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  payment_status ENUM('pending','paid') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_book_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_book_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- cart
CREATE TABLE IF NOT EXISTS cart (
  cart_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  car_id INT NOT NULL,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- contact_messages
CREATE TABLE IF NOT EXISTS contact_messages (
  message_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL,
  subject VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed admin (username: admin, password: admin123)
INSERT INTO admin (username, password, email)
VALUES ('admin', '$2y$10$09oJkH7k8fQp5jFfCkP8eOMJxR7PqgC64k8oXyS7aY7bqLh5u3m6S', 'admin@example.com')
ON DUPLICATE KEY UPDATE email=email;

-- Seed sample cars
INSERT INTO cars (car_name, brand, model, year, price_per_day, fuel_type, seats, transmission, image, availability)
VALUES
('City Cruiser', 'Honda', 'City', 2022, 2500.00, 'Petrol', 5, 'Automatic', 'assets/images/cars/honda_city.jpg', 'available'),
('Swift Ride', 'Maruti', 'Swift', 2021, 1800.00, 'Petrol', 5, 'Manual', 'assets/images/cars/swift.jpg', 'available'),
('Eco Move', 'Toyota', 'Innova Crysta', 2020, 3500.00, 'Diesel', 7, 'Automatic', 'assets/images/cars/innova.jpg', 'available')
ON DUPLICATE KEY UPDATE availability=availability;



-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Cars Table
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_name VARCHAR(100) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    price_per_day DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    status ENUM('available', 'booked') DEFAULT 'available'
);

-- Create Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    car_id INT,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (car_id) REFERENCES cars(id)
);

-- 1. Add the missing 'role' column
ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user';

-- 2. Make your specific user ('admin@example.com') an Admin
UPDATE users SET role = 'admin' WHERE email = 'admin@car.com';

-- update bookins table 
ALTER TABLE bookings ADD COLUMN location VARCHAR(255) NOT NULL AFTER car_id;
ALTER TABLE bookings ADD COLUMN phone_number VARCHAR(20) NOT NULL AFTER location;
ALTER TABLE bookings ADD COLUMN drop_location VARCHAR(255) NOT NULL AFTER location;

