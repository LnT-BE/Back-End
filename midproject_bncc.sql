CREATE DATABASE IF NOT EXISTS midproject_bncc;
USE midproject_bncc;

CREATE TABLE `users` (
  `id` VARCHAR(10) NOT NULL PRIMARY KEY,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `photo` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `bio` TEXT
);

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `bio`) VALUES
('A001', 'admin', 'BNCC', 'adminBNCC@gmail.com', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', 'Hi my name is Admin, and I like backend development.');
