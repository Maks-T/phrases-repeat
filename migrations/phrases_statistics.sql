CREATE TABLE phrase_statistics (
                                   id INT AUTO_INCREMENT PRIMARY KEY,
                                   phrase_id INT NOT NULL,
                                   user_id INT NOT NULL,
                                   repetitions INT DEFAULT 0,
                                   correct INT DEFAULT 0,
                                   incorrect INT DEFAULT 0,
                                   last_reviewed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                   FOREIGN KEY (phrase_id) REFERENCES phrases(id),
                                   FOREIGN KEY (user_id) REFERENCES users(id)
);