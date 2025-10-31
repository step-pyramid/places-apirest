-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS wshop_api;
USE wshop_api;

-- Create places table
CREATE TABLE IF NOT EXISTS places (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    rating DECIMAL(3, 2) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO places (name, description, category, address, city, rating) VALUES
('Central Park Cafe', 'Cozy cafe with great coffee and pastries', 'cafe', '123 Park Avenue', 'New York', 4.5),
('Louvre Museum', 'World''s largest art museum', 'museum', 'Rue de Rivoli', 'Paris', 4.8),
('Eiffel Tower', 'Iconic iron tower', 'landmark', 'Champ de Mars', 'Paris', 4.7),
('Brooklyn Bridge', 'Historic suspension bridge', 'landmark', 'Brooklyn Bridge', 'New York', 4.6),
('Shakespeare & Company', 'Famous English-language bookstore', 'shop', '37 Rue de la Bûcherie', 'Paris', 4.9),
('Metropolitan Museum', 'Encyclopedic art museum', 'museum', '1000 5th Avenue', 'New York', 4.7),
('Notre-Dame Cathedral', 'Medieval Catholic cathedral', 'landmark', '6 Parvis Notre-Dame', 'Paris', 4.8),
('Starbucks Reserve', 'Premium coffee experience', 'cafe', '61 9th Avenue', 'New York', 4.3),
('Galeries Lafayette', 'Luxury department store', 'shop', '40 Boulevard Haussmann', 'Paris', 4.6),
('Central Park', 'Urban park in Manhattan', 'park', 'Central Park', 'New York', 4.9);