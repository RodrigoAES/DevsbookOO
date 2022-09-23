-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23-Set-2022 às 17:45
-- Versão do servidor: 10.9.2-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `devsbookoo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `postcomments`
--

CREATE TABLE `postcomments` (
  `id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `postlikes`
--

CREATE TABLE `postlikes` (
  `id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `postlikes`
--

INSERT INTO `postlikes` (`id`, `id_post`, `id_user`, `created_at`) VALUES
(1, 3, 2, '2022-09-23 11:12:17'),
(4, 2, 2, '2022-09-23 12:29:46');

-- --------------------------------------------------------

--
-- Estrutura da tabela `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `posts`
--

INSERT INTO `posts` (`id`, `id_user`, `type`, `created_at`, `body`) VALUES
(1, 2, 'text', '2022-09-20 17:54:54', 'sdagvdsafgsdaf'),
(2, 3, 'text', '2022-09-20 18:09:41', 'testando pedro'),
(3, 2, 'text', '2022-09-21 18:16:26', 'test');

-- --------------------------------------------------------

--
-- Estrutura da tabela `userrelations`
--

CREATE TABLE `userrelations` (
  `id` int(11) NOT NULL,
  `user_from` int(11) NOT NULL,
  `user_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `userrelations`
--

INSERT INTO `userrelations` (`id`, `user_from`, `user_to`) VALUES
(1, 2, 3),
(2, 3, 2),
(3, 6, 2),
(4, 6, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `work` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'avatar.jpg',
  `cover` varchar(50) DEFAULT 'cover.jpg',
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `birthdate`, `city`, `work`, `avatar`, `cover`, `token`) VALUES
(2, 'rodrigoaes.20@hotmail.com', '$2y$10$Or2VO9HXd4oi16l2q2DsmeyMfe84wCoFmBwh5hq8Cz3vN1xrXxyMW', 'Rodrigo Alves do Espirito Santo', '1997-05-15', 'SÃ£o Paulo', 'Desempregado', '32ab5675d7095c904c074a2a135d20f3.jpg', '3dcc031adb4387eaeb6423890e44fafb.jpg', '4dab0df4aced64c603273edae0cbe2c9'),
(3, 'pedroTest@hotmail.com', '$2y$10$v48iyMxm/F5CJJiPC6cyR.jl55QKvUpv87s8fOAkjEW65jDUtHHu6', 'Pedro Test', '1997-08-01', '', '', 'avatar.jpg', 'cover.jpg', '13e79f2e44b404bb32932a17a44c11be'),
(6, 'pauloTest@gmail.com', '$2y$10$0JJDPZfTTS1yHCPM9tjC..R7n0NYrkK5cMbTc6LS7S6dSCIbIJ6a6', 'Paulo test', '1997-08-01', NULL, NULL, 'avatar.jpg', 'cover.jpg', 'ad573e0b30d59c21e46292bae866eff0'),
(7, 'pe@gmail.com', '$2y$10$5cyBqoCQPf2.2fzSVNWERetoTjdNynpqrKqiwk8xyqzi..9gmbup2', 'Pe', '1997-05-15', NULL, NULL, 'avatar.jpg', 'cover.jpg', 'fd1f81ed64cce9c201c6a959f9d03e64');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `postcomments`
--
ALTER TABLE `postcomments`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `postlikes`
--
ALTER TABLE `postlikes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `userrelations`
--
ALTER TABLE `userrelations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `postcomments`
--
ALTER TABLE `postcomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `postlikes`
--
ALTER TABLE `postlikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `userrelations`
--
ALTER TABLE `userrelations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
