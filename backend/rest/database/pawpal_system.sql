


-- -----------------------------------------------------
-- PawPal System Database - MariaDB Friendly
-- -----------------------------------------------------

DROP DATABASE IF EXISTS pawpal_system;
CREATE DATABASE pawpal_system CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE pawpal_system;

-- -------------------------------
-- Table: users
-- -------------------------------
CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255),
    phone_number VARCHAR(50),
    date_of_birth DATE,
    address VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    zip_code VARCHAR(20),
    about_me TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------
-- Table: admins
-- -------------------------------
CREATE TABLE admins (
    admin_id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    PRIMARY KEY (admin_id),
    INDEX idx_user_id (user_id),
    CONSTRAINT fk_admin_user FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------
-- Table: shelters
-- -------------------------------
CREATE TABLE shelters (
    shelter_id INT NOT NULL AUTO_INCREMENT,
    admin_id INT,
    name VARCHAR(255),
    address VARCHAR(255),
    phone VARCHAR(50),
    working_days VARCHAR(255),
    working_hours VARCHAR(255),
    dog_count INT DEFAULT 0,
    cat_count INT DEFAULT 0,
    other_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (shelter_id),
    INDEX idx_admin_id (admin_id),
    CONSTRAINT fk_shelter_admin FOREIGN KEY (admin_id)
        REFERENCES admins(admin_id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------
-- Table: pets
-- -------------------------------
CREATE TABLE pets (
    pet_id INT NOT NULL AUTO_INCREMENT,
    shelter_id INT,
    name VARCHAR(255),
    type ENUM('dog', 'cat', 'other'),
    age INT,
    size ENUM('small', 'medium', 'large'),
    gender ENUM('male', 'female', 'unknown'),
    availability ENUM('available', 'pending', 'adopted') DEFAULT 'available',
    about TEXT,
    characteristics TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (pet_id),
    INDEX idx_shelter_id (shelter_id),
    CONSTRAINT fk_pet_shelter FOREIGN KEY (shelter_id)
        REFERENCES shelters(shelter_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------
-- Table: adoption_requests
-- -------------------------------
CREATE TABLE adoption_requests (
    request_id INT NOT NULL AUTO_INCREMENT,
    pet_id INT,
    user_id INT,
    admin_id INT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    PRIMARY KEY (request_id),
    INDEX idx_pet_id (pet_id),
    INDEX idx_user_id (user_id),
    INDEX idx_admin_id (admin_id),
    CONSTRAINT fk_request_pet FOREIGN KEY (pet_id)
        REFERENCES pets(pet_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_request_user FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_request_admin FOREIGN KEY (admin_id)
        REFERENCES admins(admin_id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------
-- Table: reviews
-- -------------------------------
CREATE TABLE reviews (
    review_id INT NOT NULL AUTO_INCREMENT,
    shelter_id INT,
    user_id INT,
    rating INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (review_id),
    INDEX idx_shelter_review (shelter_id),
    INDEX idx_user_review (user_id),
    CONSTRAINT fk_review_shelter FOREIGN KEY (shelter_id)
        REFERENCES shelters(shelter_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_review_user FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
