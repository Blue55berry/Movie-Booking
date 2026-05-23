CREATE DATABASE IF NOT EXISTS movie_booking;
USE movie_booking;

CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    show_time DATETIME NOT NULL,
    total_seats INT NOT NULL
);

CREATE TABLE IF NOT EXISTS seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seat_label VARCHAR(10) NOT NULL UNIQUE,
    is_booked BOOLEAN DEFAULT 0
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    name VARCHAR(255) NULL,
    user_name VARCHAR(255) NULL,
    seats INT NOT NULL
);

INSERT IGNORE INTO seats (seat_label, is_booked) VALUES 
('A1', 0), ('A2', 0), ('A3', 0), ('A4', 0), ('A5', 0), ('A6', 0), ('A7', 0), ('A8', 0),
('B1', 0), ('B2', 0), ('B3', 0), ('B4', 0), ('B5', 0), ('B6', 0), ('B7', 0), ('B8', 0),
('C1', 0), ('C2', 0), ('C3', 0), ('C4', 0), ('C5', 0), ('C6', 0), ('C7', 0), ('C8', 0),
('D1', 0), ('D2', 0), ('D3', 0), ('D4', 0), ('D5', 0), ('D6', 0), ('D7', 0), ('D8', 0),
('E1', 0), ('E2', 0), ('E3', 0), ('E4', 0), ('E5', 0), ('E6', 0), ('E7', 0), ('E8', 0);
