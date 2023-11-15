-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/11/2023 às 02:38
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mydb`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cidade`
--

CREATE TABLE `cidade` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `desconto`
--

CREATE TABLE `desconto` (
  `ID` int(11) NOT NULL,
  `Hora_inicio` varchar(45) DEFAULT NULL,
  `Hora_termino` varchar(45) DEFAULT NULL,
  `Valor` varchar(45) DEFAULT NULL,
  `Prazo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `onibus`
--

CREATE TABLE `onibus` (
  `ID` int(11) NOT NULL,
  `Modelo` varchar(45) DEFAULT NULL,
  `Poltrona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `parada`
--

CREATE TABLE `parada` (
  `ID` int(11) NOT NULL,
  `Horario` time DEFAULT NULL,
  `cidade_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `passageiro`
--

CREATE TABLE `passageiro` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(45) DEFAULT NULL,
  `CPF` varchar(45) DEFAULT NULL,
  `Telefone` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `passagem`
--

CREATE TABLE `passagem` (
  `ID` int(11) NOT NULL,
  `Assento` varchar(45) DEFAULT NULL,
  `Preco` varchar(45) DEFAULT NULL,
  `Data` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `viagem`
--

CREATE TABLE `viagem` (
  `ID` int(11) NOT NULL,
  `Saida` time DEFAULT NULL,
  `Chegada` time DEFAULT NULL,
  `cidade_ID` int(11) NOT NULL,
  `Onibus_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Acionadores `viagem`
--
DELIMITER $$
CREATE TRIGGER `after_insert_viagem` AFTER INSERT ON `viagem` FOR EACH ROW BEGIN
    -- Atualiza o contador de viagens do ônibus associado à viagem
    UPDATE onibus
    SET ContadorViagens = ContadorViagens + 1
    WHERE ID = NEW.Onibus_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vwcidade`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vwcidade` (
`ID` int(11)
,`Nome` varchar(45)
);

-- --------------------------------------------------------

--
-- Estrutura para view `vwcidade`
--
DROP TABLE IF EXISTS `vwcidade`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwcidade`  AS SELECT `cidade`.`ID` AS `ID`, `cidade`.`Nome` AS `Nome` FROM `cidade` ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cidade`
--
ALTER TABLE `cidade`
  ADD PRIMARY KEY (`ID`);

--
-- Índices de tabela `onibus`
--
ALTER TABLE `onibus`
  ADD PRIMARY KEY (`ID`);

--
-- Índices de tabela `parada`
--
ALTER TABLE `parada`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_parada_cidade1_idx` (`cidade_ID`);

--
-- Índices de tabela `viagem`
--
ALTER TABLE `viagem`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_viagem_cidade1_idx` (`cidade_ID`),
  ADD KEY `fk_viagem_Onibus1_idx` (`Onibus_ID`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `onibus`
--
ALTER TABLE `onibus`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `parada`
--
ALTER TABLE `parada`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `viagem`
--
ALTER TABLE `viagem`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `parada`
--
ALTER TABLE `parada`
  ADD CONSTRAINT `fk_parada_cidade1` FOREIGN KEY (`cidade_ID`) REFERENCES `cidade` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `viagem`
--
ALTER TABLE `viagem`
  ADD CONSTRAINT `fk_viagem_Onibus1` FOREIGN KEY (`Onibus_ID`) REFERENCES `onibus` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_viagem_cidade1` FOREIGN KEY (`cidade_ID`) REFERENCES `cidade` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
