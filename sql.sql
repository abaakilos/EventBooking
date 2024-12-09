-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS EventBooking;
USE EventBooking;

-- Create User table if it doesn't exist
CREATE TABLE IF NOT EXISTS User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(15),
    user_type ENUM('organizer', 'attendee'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Event table if it doesn't exist
CREATE TABLE IF NOT EXISTS Event (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    start_time DATETIME,
    end_time DATETIME,
    capacity INT,
    organizer_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES User(user_id)
);

-- Create Booking table if it doesn't exist
CREATE TABLE IF NOT EXISTS Booking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    user_id INT,
    booking_status ENUM('confirmed', 'cancelled', 'pending') DEFAULT 'confirmed',
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES Event(event_id),
    FOREIGN KEY (user_id) REFERENCES User(user_id)
);

-- Create Categories table if it doesn't exist
CREATE TABLE IF NOT EXISTS Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    category_description VARCHAR(255)
);

-- Create EventCategories table if it doesn't exist
CREATE TABLE IF NOT EXISTS EventCategories (
    event_category_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    category_id INT,
    FOREIGN KEY (event_id) REFERENCES Event(event_id),
    FOREIGN KEY (category_id) REFERENCES Categories(category_id)
);

-- Insert sample users if they do not exist
INSERT INTO User (username, email, password, phone_number, user_type, created_at)
SELECT 'john_doe', 'john@example.com', MD5('password123'), '1234567890', 'attendee', NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM User WHERE username = 'john_doe');

INSERT INTO User (username, email, password, phone_number, user_type, created_at)
SELECT 'jane_smith', 'jane@example.com', MD5('securepass'), '0987654321', 'organizer', NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM User WHERE username = 'jane_smith');

-- Insert sample events if they do not exist
INSERT INTO Event (title, description, location, start_time, end_time, capacity, organizer_id, created_at)
SELECT 'Live Concert', 'An amazing live concert with popular bands.', 'City Park', '2024-06-11 19:00:00', '2024-06-11 22:00:00', 100, 2, NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Event WHERE title = 'Live Concert');

INSERT INTO Event (title, description, location, start_time, end_time, capacity, organizer_id, created_at)
SELECT 'Tech Meetup', 'A meetup for tech enthusiasts to discuss the latest in technology.', 'Tech Hall', '2024-06-15 15:00:00', '2024-06-15 18:00:00', 50, 2, NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Event WHERE title = 'Tech Meetup');

-- Insert sample categories if they do not exist
INSERT INTO Categories (category_name, category_description)
SELECT 'Music', 'Events related to live music, concerts, and performances.'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Categories WHERE category_name = 'Music');

INSERT INTO Categories (category_name, category_description)
SELECT 'Technology', 'Events related to technology, startups, and innovations.'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Categories WHERE category_name = 'Technology');

-- Insert sample event categories to link events to categories if they do not exist
INSERT INTO EventCategories (event_id, category_id)
SELECT 1, 1
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM EventCategories WHERE event_id = 1 AND category_id = 1);

INSERT INTO EventCategories (event_id, category_id)
SELECT 2, 2
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM EventCategories WHERE event_id = 2 AND category_id = 2);

-- Insert sample bookings if they do not exist
INSERT INTO Booking (event_id, user_id, booking_status, booked_at)
SELECT 1, 1, 'confirmed', NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Booking WHERE event_id = 1 AND user_id = 1);

INSERT INTO Booking (event_id, user_id, booking_status, booked_at)
SELECT 2, 1, 'pending', NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Booking WHERE event_id = 2 AND user_id = 1);

-- Create Trigger to handle event booking and update capacity
DROP TRIGGER IF EXISTS eventBooking;
DELIMITER $$

CREATE TRIGGER eventBooking
BEFORE INSERT 
ON Booking
FOR EACH ROW 
BEGIN
    IF (SELECT capacity FROM Event WHERE event_id = NEW.event_id) = 0 THEN 
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "Event not booked, fully booked";
    END IF;
    
    UPDATE Event 
    SET capacity = capacity - 1
    WHERE event_id = NEW.event_id;
END $$

DELIMITER ;
