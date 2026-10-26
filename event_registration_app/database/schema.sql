-- Repository setup schema reconstructed from the coursework queries.
-- Import into a NEW empty database. All sample records are fictional.
CREATE DATABASE IF NOT EXISTS event_registration CHARACTER SET utf8mb4;
USE event_registration;
CREATE TABLE categories (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL);
CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(150) NOT NULL);
CREATE TABLE events (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(150) NOT NULL, description TEXT, event_date DATETIME NOT NULL,
 location VARCHAR(150) NOT NULL, category_id INT NOT NULL,
 FOREIGN KEY (category_id) REFERENCES categories(id)
);
CREATE TABLE registrations (
 id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, event_id INT NOT NULL,
 registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY unique_user_event (user_id, event_id),
 FOREIGN KEY (user_id) REFERENCES users(id),
 FOREIGN KEY (event_id) REFERENCES events(id)
);
INSERT INTO categories (name) VALUES ('Workshop'), ('Community');
INSERT INTO users (name) VALUES ('Demo Student A'), ('Demo Student B');
INSERT INTO events (title,description,event_date,location,category_id)
VALUES ('Sample Coding Workshop','Fictional event for local testing.','2027-01-15 14:00:00','Demo Room',1);
