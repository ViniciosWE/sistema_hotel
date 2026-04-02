-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/10/2025 às 20:43
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bdhotel`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `controle`
--

CREATE TABLE `controle` (
  `id` int(11) NOT NULL,
  `hospede_id` int(11) NOT NULL,
  `paisOrigem` varchar(100) DEFAULT NULL,
  `previsaoEstadia` varchar(100) DEFAULT NULL,
  `ciasAereas` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `controle`
--

INSERT INTO `controle` (`id`, `hospede_id`, `paisOrigem`, `previsaoEstadia`, `ciasAereas`) VALUES
(6, 7, 'Paraguai', '2 semanas', 'GOL,AZUL,TRIP'),
(7, 8, 'Brasil', '1 semana', 'GOL, AZUL'),
(12, 9, 'Argentina', '1 semana', 'GOL, GLOBAL');

-- --------------------------------------------------------

--
-- Estrutura para tabela `hospede`
--

CREATE TABLE `hospede` (
  `id` int(11) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sobrenome` varchar(100) NOT NULL,
  `sexo` char(1) DEFAULT NULL,
  `dataNascimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `hospede`
--

INSERT INTO `hospede` (`id`, `cpf`, `nome`, `sobrenome`, `sexo`, `dataNascimento`) VALUES
(6, '04879806037', 'joao', 'fafaf', 'M', '2005-01-17'),
(7, '5155615516', 'vinicios', 'weide', 'M', '2052-08-19'),
(8, '5155615518', 'vinicios', 'weide', 'M', '2052-08-19'),
(9, '04879806040', 'joooooooooooooooooo', 'fgsafafa', 'F', '2005-01-17');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `controle`
--
ALTER TABLE `controle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospede_id` (`hospede_id`);

--
-- Índices de tabela `hospede`
--
ALTER TABLE `hospede`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `controle`
--
ALTER TABLE `controle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `hospede`
--
ALTER TABLE `hospede`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `controle`
--
ALTER TABLE `controle`
  ADD CONSTRAINT `controle_ibfk_1` FOREIGN KEY (`hospede_id`) REFERENCES `hospede` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
