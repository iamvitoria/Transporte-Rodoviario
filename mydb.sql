-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/11/2023 às 19:01
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
-- Estrutura para tabela `compra`
--

CREATE TABLE `compra` (
  `ID` int(11) NOT NULL,
  `passageiro_ID` int(11) NOT NULL,
  `desconto_ID` int(11) NOT NULL,
  `Data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `desconto`
--

CREATE TABLE `desconto` (
  `ID` int(11) NOT NULL,
  `Hora_inicio` time DEFAULT NULL,
  `Hora_termino` time DEFAULT NULL,
  `Valor` varchar(45) DEFAULT NULL,
  `Prazo` date DEFAULT NULL,
  `passagem_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `descontos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `descontos` (
`ID` int(11)
,`Passagem` int(11)
,`Desconto` varchar(45)
);

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
  `Assento` int(11) NOT NULL,
  `Preco` decimal(10,0) NOT NULL,
  `compra_passageiro_ID` int(11) NOT NULL,
  `viagem_ID` int(11) NOT NULL,
  `viagem_cidade_origem` int(11) NOT NULL,
  `viagem_cidade_destino` int(11) NOT NULL,
  `compra_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `passagens`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `passagens` (
`ID` int(11)
,`Passageiro` varchar(45)
,`Viagem` int(11)
,`Assento` int(11)
,`Preco` decimal(10,0)
,`DataCompra` date
);

-- --------------------------------------------------------

--
-- Estrutura para tabela `viagem`
--

CREATE TABLE `viagem` (
  `ID` int(11) NOT NULL,
  `saida` date DEFAULT NULL,
  `chegada` date DEFAULT NULL,
  `Onibus_ID` int(11) NOT NULL,
  `cidade_origem` int(11) NOT NULL,
  `cidade_destino` int(11) NOT NULL,
  `horario` time DEFAULT NULL,
  `parada_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Acionadores `viagem`
--
DELIMITER $$
CREATE TRIGGER `after_insert_viagem` AFTER INSERT ON `viagem` FOR EACH ROW BEGIN
	UPDATE Onibus
    SET ContadorViagens = ContadorViagens+1
    WHERE ID = NEW.Onibus_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viagens`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viagens` (
`ID` int(11)
,`Origem` varchar(45)
,`Destino` varchar(45)
,`Onibus` varchar(45)
);

-- --------------------------------------------------------

--
-- Estrutura para view `descontos`
--
DROP TABLE IF EXISTS `descontos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `descontos`  AS SELECT `d`.`ID` AS `ID`, `p`.`ID` AS `Passagem`, `d`.`Valor` AS `Desconto` FROM (`desconto` `d` join `passagem` `p` on(`d`.`passagem_ID` = `p`.`ID`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `passagens`
--
DROP TABLE IF EXISTS `passagens`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `passagens`  AS SELECT `p`.`ID` AS `ID`, `pa`.`Nome` AS `Passageiro`, `v`.`ID` AS `Viagem`, `p`.`Assento` AS `Assento`, `p`.`Preco` AS `Preco`, `c`.`Data` AS `DataCompra` FROM (((`passagem` `p` join `viagem` `v` on(`p`.`viagem_ID` = `v`.`ID`)) join `compra` `c` on(`p`.`compra_ID` = `c`.`ID`)) join `passageiro` `pa` on(`c`.`ID` = `pa`.`ID`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `viagens`
--
DROP TABLE IF EXISTS `viagens`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viagens`  AS SELECT `v`.`ID` AS `ID`, `c1`.`Nome` AS `Origem`, `c2`.`Nome` AS `Destino`, `o`.`Modelo` AS `Onibus` FROM (((`viagem` `v` join `cidade` `c1` on(`v`.`cidade_origem` = `c1`.`ID`)) join `cidade` `c2` on(`v`.`cidade_destino` = `c2`.`ID`)) join `onibus` `o` on(`v`.`Onibus_ID` = `o`.`ID`)) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cidade`
--
ALTER TABLE `cidade`
  ADD PRIMARY KEY (`ID`);

--
-- Índices de tabela `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_compra_passageiro1_idx` (`passageiro_ID`),
  ADD KEY `fk_compra_desconto1_idx` (`desconto_ID`);

--
-- Índices de tabela `desconto`
--
ALTER TABLE `desconto`
  ADD KEY `fk_desconto_passagem` (`passagem_ID`);

--
-- Índices de tabela `onibus`
--
ALTER TABLE `onibus`
  ADD PRIMARY KEY (`ID`);

--
-- Índices de tabela `parada`
--
ALTER TABLE `parada`
  ADD PRIMARY KEY (`ID`,`cidade_ID`),
  ADD KEY `fk_parada_cidade1_idx` (`cidade_ID`);

--
-- Índices de tabela `passagem`
--
ALTER TABLE `passagem`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_passagem_compra1_idx` (`compra_passageiro_ID`),
  ADD KEY `fk_passagem_viagem1_idx` (`viagem_ID`),
  ADD KEY `fk_viagem_passagem_cidade1_idx` (`viagem_cidade_origem`),
  ADD KEY `fk_viagem_passagem_cidade2_idx` (`viagem_cidade_destino`),
  ADD KEY `fk_passagem_compra` (`compra_ID`);

--
-- Índices de tabela `viagem`
--
ALTER TABLE `viagem`
  ADD PRIMARY KEY (`ID`,`cidade_origem`,`cidade_destino`),
  ADD KEY `fk_viagem_Onibus1_idx` (`Onibus_ID`),
  ADD KEY `FK_viagem_parada` (`parada_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `onibus`
--
ALTER TABLE `onibus`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `parada`
--
ALTER TABLE `parada`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `viagem`
--
ALTER TABLE `viagem`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `desconto`
--
ALTER TABLE `desconto`
  ADD CONSTRAINT `fk_desconto_passagem` FOREIGN KEY (`passagem_ID`) REFERENCES `passagem` (`ID`);

--
-- Restrições para tabelas `parada`
--
ALTER TABLE `parada`
  ADD CONSTRAINT `fk_parada_cidade1` FOREIGN KEY (`cidade_ID`) REFERENCES `cidade` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `passagem`
--
ALTER TABLE `passagem`
  ADD CONSTRAINT `fk_passagem_compra` FOREIGN KEY (`compra_ID`) REFERENCES `compra` (`ID`);

--
-- Restrições para tabelas `viagem`
--
ALTER TABLE `viagem`
  ADD CONSTRAINT `FK_viagem_parada` FOREIGN KEY (`parada_id`) REFERENCES `parada` (`ID`),
  ADD CONSTRAINT `fk_viagem_Onibus1` FOREIGN KEY (`Onibus_ID`) REFERENCES `onibus` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
