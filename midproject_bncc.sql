CREATE DATABASE IF NOT EXISTS midproject_bncc;
USE midproject_bncc;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `profile_pic` VARCHAR(255) NULL,
  `bio` TEXT
);

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `bio`, `profile_pic`) VALUES
('A001', 'admin', 'BNCC', 'adminBNCC@gmail.com', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', 'Hi my name is Admin, and I like backend development.', 'admin_profile.jpg');
