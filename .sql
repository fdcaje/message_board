CREATE DATABASE message_board;

-- USE DATABASE message_board;
CREATE TABLE user_profile(
    id VARCHAR(25) NOT NULL PRIMARY KEY,
    fullname VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(100) NOT NULL,
    user_photo BLOB,
    create_at DATETIME NOT NULL DEFAULT NOW()
);

CREATE TABLE user_messages(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    message_text TEXT,
    create_at DATETIME NOT NULL DEFAULT NOW(),
    profile_id VARCHAR(25) REFERENCES user_profile(id)
);

ALTER TABLE
    user_profile
ADD
    COLUMN username VARCHAR(50) NOT NULL UNIQUE
AFTER
    email;

ALTER COLUMN
    user_password ALTER CONSTRAINT VARCHAR(255);

ALTER TABLE
    user_profile CHANGE COLUMN user_password user_password VARCHAR(255);