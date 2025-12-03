-- CREATE DATABASE AND TABLES FOR LIBRARY MANAGEMENT SYSTEM
 CREATE DATABASE IF NOT EXISTS library_db;
 USE library_db;
 CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    stock INT DEFAULT 0,
    cover_image VARCHAR(255),         -- path or filename
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,    -- hashed password
    role ENUM('admin','librarian','student','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE borrow_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    due_date DATE NOT NULL,
    status ENUM('borrowed', 'over_due', 'returned') DEFAULT 'borrowed',
    fine_amount DECIMAL(10,2) DEFAULT 0,
    fine_status ENUM('paid', 'unpaid', 'no_fine') DEFAULT 'no_fine',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);
-- ADD description column in books table
ALTER TABLE `books` ADD `description` TEXT NULL DEFAULT NULL AFTER `author`;

-- cache preview-link in books table
ALTER TABLE books 
ADD COLUMN preview_link TEXT NULL,

CREATE TABLE reservations ( id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, book_id INT NOT NULL, status ENUM('pending','approved','rejected','borrrowed','expired') DEFAULT 'pending',borrrow_date DATE NOT NULL reserved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE, FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE );
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(100),
    email VARCHAR(150),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread'
);
ALTER TABLE `contact_messages` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
