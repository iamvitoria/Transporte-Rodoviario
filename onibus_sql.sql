-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/11/2023 às 17:46
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
-- Banco de dados: `onibus`
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
  `desconto_ID` int(11) NOT NULL
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
  `Assento` int(11) NOT NULL,
  `Preco` decimal(10,0) NOT NULL,
  `compra_passageiro_ID` int(11) NOT NULL,
  `viagem_ID` int(11) NOT NULL,
  `viagem_cidade_origem` int(11) NOT NULL,
  `viagem_cidade_destino` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Acionadores `passagem`
--
DELIMITER $$
CREATE TRIGGER `tr_atualizar_preco_passagem` AFTER INSERT ON `passagem` FOR EACH ROW BEGIN
    DECLARE novo_preco DECIMAL(10,0);

    -- Lógica para calcular o novo preço
    -- Exemplo: novo_preco = alguma_formula();

    -- Atualizar o preço na tabela de Passagem
    UPDATE passagem
    SET Preco = novo_preco
    WHERE ID = NEW.ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `viagem`
--

CREATE TABLE `viagem` (
  `ID` int(11) NOT NULL,
  `Saida` time DEFAULT NULL,
  `Chegada` time DEFAULT NULL,
  `Onibus_ID` int(11) NOT NULL,
  `cidade_origem` int(11) NOT NULL,
  `cidade_destino` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_cidade`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_cidade` (
`ID` int(11)
,`Nome` varchar(45)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_compra`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_compra` (
`ID` int(11)
,`passageiro_ID` int(11)
,`desconto_ID` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_desconto`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_desconto` (
`ID` int(11)
,`Hora_inicio` varchar(45)
,`Hora_termino` varchar(45)
,`Valor` varchar(45)
,`Prazo` varchar(45)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_onibus`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_onibus` (
`ID` int(11)
,`Modelo` varchar(45)
,`Poltrona` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_parada`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_parada` (
`ID` int(11)
,`Horario` time
,`cidade_ID` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_passageiro`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_passageiro` (
`ID` int(11)
,`Nome` varchar(45)
,`CPF` varchar(45)
,`Telefone` varchar(45)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_passagem`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_passagem` (
`ID` int(11)
,`Assento` int(11)
,`Preco` decimal(10,0)
,`compra_passageiro_ID` int(11)
,`viagem_ID` int(11)
,`viagem_cidade_origem` int(11)
,`viagem_cidade_destino` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_viagem`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_viagem` (
`ID` int(11)
,`Saida` time
,`Chegada` time
,`Onibus_ID` int(11)
,`cidade_origem` int(11)
,`cidade_destino` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura para view `vw_cidade`
--
DROP TABLE IF EXISTS `vw_cidade`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_cidade`  AS SELECT `cidade`.`ID` AS `ID`, `cidade`.`Nome` AS `Nome` FROM `cidade` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_compra`
--
DROP TABLE IF EXISTS `vw_compra`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_compra`  AS SELECT `compra`.`ID` AS `ID`, `compra`.`passageiro_ID` AS `passageiro_ID`, `compra`.`desconto_ID` AS `desconto_ID` FROM `compra` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_desconto`
--
DROP TABLE IF EXISTS `vw_desconto`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_desconto`  AS SELECT `desconto`.`ID` AS `ID`, `desconto`.`Hora_inicio` AS `Hora_inicio`, `desconto`.`Hora_termino` AS `Hora_termino`, `desconto`.`Valor` AS `Valor`, `desconto`.`Prazo` AS `Prazo` FROM `desconto` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_onibus`
--
DROP TABLE IF EXISTS `vw_onibus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_onibus`  AS SELECT `ID` AS `ID`, `Modelo` AS `Modelo`, `Poltrona` AS `Poltrona` FROM `onibus` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_parada`
--
DROP TABLE IF EXISTS `vw_parada`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_parada`  AS SELECT `parada`.`ID` AS `ID`, `parada`.`Horario` AS `Horario`, `parada`.`cidade_ID` AS `cidade_ID` FROM `parada` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_passageiro`
--
DROP TABLE IF EXISTS `vw_passageiro`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_passageiro`  AS SELECT `passageiro`.`ID` AS `ID`, `passageiro`.`Nome` AS `Nome`, `passageiro`.`CPF` AS `CPF`, `passageiro`.`Telefone` AS `Telefone` FROM `passageiro` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_passagem`
--
DROP TABLE IF EXISTS `vw_passagem`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_passagem`  AS SELECT `passagem`.`ID` AS `ID`, `passagem`.`Assento` AS `Assento`, `passagem`.`Preco` AS `Preco`, `passagem`.`compra_passageiro_ID` AS `compra_passageiro_ID`, `passagem`.`viagem_ID` AS `viagem_ID`, `passagem`.`viagem_cidade_origem` AS `viagem_cidade_origem`, `passagem`.`viagem_cidade_destino` AS `viagem_cidade_destino` FROM `passagem` ;

-- --------------------------------------------------------

--
-- Estrutura para view `vw_viagem`
--
DROP TABLE IF EXISTS `vw_viagem`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_viagem`  AS SELECT `viagem`.`ID` AS `ID`, `viagem`.`Saida` AS `Saida`, `viagem`.`Chegada` AS `Chegada`, `viagem`.`Onibus_ID` AS `Onibus_ID`, `viagem`.`cidade_origem` AS `cidade_origem`, `viagem`.`cidade_destino` AS `cidade_destino` FROM `viagem` ;

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
  ADD KEY `fk_viagem_passagem_cidade2_idx` (`viagem_cidade_destino`);

--
-- Índices de tabela `viagem`
--
ALTER TABLE `viagem`
  ADD PRIMARY KEY (`ID`,`cidade_origem`,`cidade_destino`),
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
  ADD CONSTRAINT `fk_viagem_Onibus1` FOREIGN KEY (`Onibus_ID`) REFERENCES `onibus` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
