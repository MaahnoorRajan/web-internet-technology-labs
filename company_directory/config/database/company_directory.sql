CREATE DATABASE IF NOT EXISTS company_directory;

USE company_directory;

CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    founding_date DATE NOT NULL,
    office_address VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    website_url VARCHAR(255) DEFAULT NULL,
    description TEXT NOT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    manager_name VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);