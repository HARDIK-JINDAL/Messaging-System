-- Create the database
CREATE DATABASE IF NOT EXISTS messagingSystem;
USE messagingSystem;

-- Create Users table
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    username VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Messages table
CREATE TABLE Messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    sender_email VARCHAR(255) NOT NULL,
    receiver_id INT NOT NULL,
    receiver_email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Create Log table
CREATE TABLE Log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_email VARCHAR(255) NOT NULL,
    receiver_email VARCHAR(255) NULL,
    message_id INT NULL,
    log_message TEXT NOT NULL,
    action VARCHAR(100) NOT NULL,
    log_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trigger: After user registers
DELIMITER $$

CREATE TRIGGER after_user_insert
AFTER INSERT ON Users
FOR EACH ROW
BEGIN
    INSERT INTO Log (sender_email, log_message, action) 
    VALUES (NEW.email, CONCAT('User ', NEW.username, ' created.'), 'User Created');
END $$

DELIMITER ;

-- Trigger: After user deletes account
DELIMITER $$

CREATE TRIGGER after_user_delete
AFTER DELETE ON Users
FOR EACH ROW
BEGIN
    INSERT INTO Log (sender_email, log_message, action) 
    VALUES (OLD.email, CONCAT('User ', OLD.username, ' deleted.'), 'User Deleted');
END $$

DELIMITER ;

-- Trigger: After message is sent
DELIMITER $$

CREATE TRIGGER after_message_insert
AFTER INSERT ON Messages
FOR EACH ROW
BEGIN
    INSERT INTO Log (sender_email, receiver_email, message_id, log_message, action) 
    VALUES (NEW.sender_email, NEW.receiver_email, NEW.message_id, 
            CONCAT('Message sent from ', NEW.sender_email, ' to ', NEW.receiver_email), 
            'Message Sent');
END $$

DELIMITER ;

-- Trigger: After message is updated
DELIMITER $$

CREATE TRIGGER after_message_update
AFTER UPDATE ON Messages
FOR EACH ROW
BEGIN
    INSERT INTO Log (sender_email, receiver_email, message_id, log_message, action) 
    VALUES (OLD.sender_email, OLD.receiver_email, OLD.message_id, 
            CONCAT('Message edited by ', OLD.sender_email), 
            'Message Edited');
END $$

DELIMITER ;

-- Trigger: Before message is inserted - sets IDs
DELIMITER $$

CREATE TRIGGER before_message_insert
BEFORE INSERT ON Messages
FOR EACH ROW
BEGIN
    SET NEW.sender_id = (SELECT user_id FROM Users WHERE email = NEW.sender_email);
    SET NEW.receiver_id = (SELECT user_id FROM Users WHERE email = NEW.receiver_email);
END $$

DELIMITER ;

-- Insert dummy users
INSERT INTO Users (user_id, email, password_hash, username, created_at) VALUES
(1, 'alice@example.com', 'hashed_pass_1', 'Alice', NOW()),
(2, 'bob@example.com', 'hashed_pass_2', 'Bob', NOW()),
(3, 'charlie@example.com', 'hashed_pass_3', 'Charlie', NOW());

-- Insert dummy messages
INSERT INTO Messages (message_id, sender_id, sender_email, receiver_id, receiver_email, message, sent_at) VALUES
(1, 1, 'alice@example.com', 2, 'bob@example.com', 'Hey Bob, how are you?', NOW()),
(2, 2, 'bob@example.com', 1, 'alice@example.com', 'Hey Alice, I am good!', NOW()),
(3, 3, 'charlie@example.com', 1, 'alice@example.com', 'Alice, let’s catch up!', NOW());

-- Procedure: Fetch all messages by user
DELIMITER $$

CREATE PROCEDURE FetchUserMessages(IN user_email VARCHAR(255))
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE msg TEXT;
    DECLARE msg_time DATETIME;
    
    DECLARE msg_cursor CURSOR FOR 
    SELECT message, sent_at 
    FROM Messages 
    WHERE sender_email = user_email OR receiver_email = user_email;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN msg_cursor;

    read_loop: LOOP
        FETCH msg_cursor INTO msg, msg_time;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SELECT msg AS Message, msg_time AS SentTime;
    END LOOP;

    CLOSE msg_cursor;
END $$

DELIMITER ;

-- Procedure: Fetch user activity
DELIMITER $$

CREATE PROCEDURE FetchUserActivity(IN user_email VARCHAR(255))
BEGIN
    SELECT 
        U.username AS UserName,
        M.message AS MessageText,
        M.sent_at AS MessageTime,
        L.action AS LogAction,
        L.log_message AS LogDetails,
        L.log_time AS LogTime
    FROM Users U
    LEFT JOIN Messages M ON U.email = M.sender_email OR U.email = M.receiver_email
    LEFT JOIN Log L ON M.message_id = L.message_id
    WHERE U.email = user_email
    ORDER BY M.sent_at DESC, L.log_time DESC;
END $$

DELIMITER ;

-- View: MessageView
CREATE VIEW MessageView AS
SELECT 
    m.message_id,
    u1.username AS Sender,
    u1.email AS SenderEmail,
    u2.username AS Receiver,
    u2.email AS ReceiverEmail,
    m.message,
    m.sent_at
FROM Messages m
JOIN Users u1 ON m.sender_id = u1.user_id
JOIN Users u2 ON m.receiver_id = u2.user_id;

-- Sample calls to procedures
-- CALL FetchUserMessages('alice@example.com');
-- CALL FetchUserActivity('alice@example.com');

-- To test the view
-- SELECT * FROM MessageView;
