CREATE DATABASE IF NOT EXISTS midproject_bncc;
USE midproject_bncc;

CREATE TABLE `users` (
  `id` VARCHAR(10) NOT NULL PRIMARY KEY,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `photo` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `bio` TEXT,
  `remember_token` VARCHAR(255) DEFAULT NULL
);

INSERT INTO `users` (`id`, `first_name`, `last_name`, `photo`, `email`, `password`, `bio`, `remember_token`) VALUES
('A001', 'admin', 'BNCC', 'default.jpg', 'adminBNCC@gmail.com', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', 'Hi my name is Admin, and I like backend development.', NULL);

