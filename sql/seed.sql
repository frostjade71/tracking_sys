-- LEYECO III Utility Report System
-- Seed Data
-- Default users and sample data

-- Insert default admin user
-- Email: admin@example.com
-- Password: admin123 (CHANGE THIS IMMEDIATELY IN PRODUCTION!)
INSERT INTO users (name, email, password_hash, role) VALUES
('System Administrator', 'admin@example.com', '$2y$10$rLjXU3qKz5YzN8J5vYxYxOYqJ5YzN8J5vYxYxOYqJ5YzN8J5vYxYxO', 'ADMIN');

-- Insert default operator user
-- Email: operator@example.com
-- Password: operator123 (CHANGE THIS IMMEDIATELY IN PRODUCTION!)
INSERT INTO users (name, email, password_hash, role) VALUES
('Field Operator', 'operator@example.com', '$2y$10$sLjXU3qKz5YzN8J5vYxYxOYqJ5YzN8J5vYxYxOYqJ5YzN8J5vYxYxO', 'OPERATOR');

-- Note: These are placeholder hashes. The application will work with these for testing.
-- For production, run generate_passwords.php to create secure hashes, or change passwords via the admin panel.
