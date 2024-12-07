CREATE TABLE User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(15),
    user_type ENUM('organizer', 'attendee'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Event (
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

CREATE TABLE Booking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    user_id INT,
    booking_status ENUM('confirmed', 'cancelled', 'pending') DEFAULT 'confirmed',
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES Event(event_id),
    FOREIGN KEY (user_id) REFERENCES User(user_id)
);

CREATE TABLE Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    category_description VARCHAR(255)
);

CREATE TABLE EventCategories (
    event_category_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    category_id INT,
    FOREIGN KEY (event_id) REFERENCES Event(event_id),
    FOREIGN KEY (category_id) REFERENCES Categories(category_id)
);





-- Insert sample users
INSERT INTO User (username, email, password, phone_number, user_type, created_at)
VALUES 
('john_doe', 'john@example.com', MD5('password123'), '1234567890', 'attendee', NOW()),
('jane_smith', 'jane@example.com', MD5('securepass'), '0987654321', 'organizer', NOW());

-- Insert sample events
INSERT INTO Event (title, description, location, start_time, end_time, capacity, organizer_id, created_at)
VALUES 
('Live Concert', 'An amazing live concert with popular bands.', 'City Park', '2024-06-11 19:00:00', '2024-06-11 22:00:00', 100, 2, NOW()),
('Tech Meetup', 'A meetup for tech enthusiasts to discuss the latest in technology.', 'Tech Hall', '2024-06-15 15:00:00', '2024-06-15 18:00:00', 50, 2, NOW());

-- Insert sample categories
INSERT INTO Categories (category_name, category_description)
VALUES 
('Music', 'Events related to live music, concerts, and performances.'),
('Technology', 'Events related to technology, startups, and innovations.');

-- Insert sample event categories to link events to categories
INSERT INTO EventCategories (event_id, category_id)
VALUES 
(1, 1), -- Live Concert -> Music Category
(2, 2); -- Tech Meetup -> Technology Category

-- Insert sample bookings
INSERT INTO Booking (event_id, user_id, booking_status, booked_at)
VALUES 
(1, 1, 'confirmed', NOW()), -- John Doe booked the Live Concert
(2, 1, 'pending', NOW()); -- John Doe booked the Tech Meetup
