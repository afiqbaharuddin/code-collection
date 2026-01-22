-- Atlantis Asia Blog Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS atlantis_blog;
USE atlantis_blog;

-- Table for blog posts
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for comments
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Table for admin users
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert content related to Atlantis Asia Business
INSERT INTO posts (title, content, author) VALUES 
('Empowering Hotels with Digital Travel Systems', 
'In the rapidly evolving hospitality industry of Asia, hotels are constantly seeking ways to maximize their potential. At Atlantis Asia, we understand that technology is the key to unlocking this growth. We offer comprehensive digital travel systems and solutions tailored for small, medium, and large hotel chains.\n\nOur mission is to help hotels achieve greater success through digital sales, marketing, and management solutions. With over $450 Million in gross bookings processed through our systems annually, we have the proven expertise to elevate your hotel''s performance.',
'Atlantis Team'),

('Why Your Hotel Needs a Channel Manager', 
'Managing inventory across multiple Online Travel Agencies (OTAs) can be a nightmare without the right tools. Overbooking, rate disparity, and manual updates are common pitfalls that can damage your revenue and reputation.\n\nOur **Channel Manager** solution allows you to reach out to over 330+ Online Travel Agencies worldwide with just a few clicks. It ensures consistent performance, increased revenue, and improved customer relationships by synchronizing your rates and availability in real-time across all platforms.',
'Atlantis Team'),

('Seamless Property Management Integration', 
'Efficiency is the backbone of a successful hotel operation. A modern Property Management System (PMS) should do more than just check guests in and out. It needs to be the central nervous system of your hotel.\n\nAtlantis offers pre-integrated solutions that cover everything from Door Lock Systems and Housekeeping to comprehensive Rooms Management. Furthermore, our Global System Integrations allow effortless connection with industry leaders like Visa, MasterCard, Google Hotels, and Oracle Hospitality, powering your business forward.',
'Atlantis Team');

-- Insert default admin user (username: admin, password: admin123)
-- Password is hashed using PHP password_hash()
INSERT INTO admin_users (username, password) VALUES 
('admin', '$2y$10$EKvNfLNLxlXDQXqj5F5XPOqZPJxGPgOxHDCPPdQXVGXqYXqGQjKAm');

-- Insert sample comments
INSERT INTO comments (post_id, name, email, comment) VALUES 
(1, 'Michael Tan', 'michael.t@hotelgroup.asia', 'We have been looking for a solution like this for our chain in Malaysia.'),
(2, 'Sarah Jenkins', 'sarah.j@travelworld.com', 'The channel manager integration with 330+ OTAs is impressive. Does it support Agoda and Traveloka?'),
(3, 'David Lee', 'david.lee@techhospitality.com', 'Integration with Oracle Hospitality is a huge plus for us.');

-- Add indexes for better performance
CREATE INDEX idx_comments_post_id ON comments(post_id);
CREATE INDEX idx_posts_created_at ON posts(created_at);
CREATE INDEX idx_comments_created_at ON comments(created_at);
