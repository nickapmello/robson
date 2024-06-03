CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `cpf` VARCHAR(14) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `telefone` VARCHAR(15) NOT NULL,
    `senha` VARCHAR(255) NOT NULL
);

CREATE TABLE `login_control` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `idusuario` INT NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `criado` DATETIME NOT NULL,
    `expira` DATETIME NOT NULL,
    FOREIGN KEY (`idusuario`) REFERENCES `users`(`id`) ON DELETE CASCADE
);
