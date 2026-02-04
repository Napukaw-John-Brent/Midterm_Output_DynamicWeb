-- Budget App database - run this whole file to create everything from scratch
-- In phpMyAdmin: create DB first if needed, then select budget_app and run the CREATE TABLE statements
-- Or run: mysql -u root < budget_app.sql

CREATE DATABASE IF NOT EXISTS budget_app;
USE budget_app;

-- Users (login/register)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255)
);

-- Budgets per user per month
CREATE TABLE IF NOT EXISTS budgets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  month VARCHAR(20),
  total_budget DECIMAL(10,2)
);

-- Expenses per user
CREATE TABLE IF NOT EXISTS expenses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  amount DECIMAL(10,2),
  category VARCHAR(50),
  date DATE
);

-- Deals (shared list, compared to remaining budget)
CREATE TABLE IF NOT EXISTS deals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100),
  price DECIMAL(10,2)
);

-- Saved QR data per user
CREATE TABLE IF NOT EXISTS qr_codes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  qr_data TEXT
);
