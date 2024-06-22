CREATE TABLE `user` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `gender` VARCHAR(64),
    `name` VARCHAR(16) NOT NULL,
    `surname` VARCHAR(32),
    `email` VARCHAR(64) UNIQUE,
    `age` INT,
    `private` BOOLEAN,
    `description` TEXT
);
CREATE TABLE `friends_list` (
    `user_id` INT,
    `friend_id` INT,
    PRIMARY KEY (`user_id`, `friend_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`),
    FOREIGN KEY (`friend_id`) REFERENCES `user`(`id`)
);
CREATE TABLE `messages` (
    `message_id` INT PRIMARY KEY AUTO_INCREMENT,
    `sender_id` INT NOT NULL,
    `receiver_id` INT NOT NULL,
    `message_content` TEXT NOT NULL,
    `sent_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `read_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`sender_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`receiver_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
);