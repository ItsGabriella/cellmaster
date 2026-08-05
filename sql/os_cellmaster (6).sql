-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/08/2026 às 00:50
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `os_cellmaster`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `idcargos` int(11) NOT NULL COMMENT 'Identificador único do cargo. Chave primária da tabela.',
  `nome_cargos` varchar(20) NOT NULL COMMENT 'Nome do cargo desempenhado pelo funcionário (ex.: Gerente, Técnico, Atendente).',
  `descricao` varchar(60) DEFAULT NULL COMMENT 'Descrição das responsabilidades, atribuições ou observações referentes ao cargo.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cargos`
--

INSERT INTO `cargos` (`idcargos`, `nome_cargos`, `descricao`) VALUES
(1, 'Gerente', 'Acesso a todas as funcionalidades do sistema'),
(2, 'Técnico', 'Acesso à OS, Estoque e Orçamento'),
(3, 'Atendente', 'Acesso ao cadastro de clientes, OS e Orçamento'),
(4, 'Cliente', 'Acesso apenas a sua OS');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL COMMENT 'Identificador único do cliente. Chave primária da tabela.',
  `nome_clien` varchar(80) NOT NULL COMMENT 'Nome completo do cliente.',
  `endereco_clien` varchar(80) DEFAULT NULL COMMENT 'Endereço residencial ou comercial do cliente.',
  `cpf_clien` varchar(15) NOT NULL COMMENT 'Cadastro de Pessoa Física (CPF) do cliente.',
  `tel_clien` varchar(15) NOT NULL COMMENT 'Número de telefone para contato com o cliente.',
  `email_clien` varchar(85) NOT NULL COMMENT 'Endereço de e-mail do cliente.',
  `cargos_idcargos` int(11) NOT NULL DEFAULT 4 COMMENT 'Identificador do cargo vinculado ao cliente. Chave estrangeira para a tabela cargos.',
  `senha` varchar(255) NOT NULL COMMENT 'Senha criptografada (hash) utilizada para autenticação do cliente.',
  `data_cadastro` date NOT NULL COMMENT 'Data em que o cliente foi cadastrado no sistema.',
  `token_recuperacao` varchar(255) DEFAULT NULL,
  `token_expiracao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nome_clien`, `endereco_clien`, `cpf_clien`, `tel_clien`, `email_clien`, `cargos_idcargos`, `senha`, `data_cadastro`, `token_recuperacao`, `token_expiracao`) VALUES
(1, 'Joao Pereira', 'Rua A, 123', '111.111.111-11', '(47) 99999-1111', 'joao@email.com', 4, '202cb962ac59075b964b07152d234b70', '2026-08-02', NULL, NULL),
(2, 'Maria Souza', 'Rua B, 456', '222.222.222-22', '(47) 99999-2222', 'maria@email.com', 4, 'e10adc3949ba59abbe56e057f20f883e', '2026-08-02', NULL, NULL),
(3, 'João Pedro Silva', 'Rua das Palmeiras, 125 - Joinville/SC', '123.456.789-01', '(47) 99911-2233', 'joao.silva@email.com', 4, 'e10adc3949ba59abbe56e057f20f883e', '2026-08-03', NULL, NULL),
(4, 'Maria Aparecida Souza', 'Av. Getúlio Vargas, 850 - Joinville/SC', '234.567.890-12', '(47) 99822-3344', 'maria.souza@email.com', 4, 'e10adc3949ba59abbe56e057f20f883e', '2026-08-03', NULL, NULL),
(5, 'Carlos Eduardo Oliveira', 'Rua XV de Novembro, 430 - Joinville/SC', '345.678.901-23', '(47) 99733-4455', 'carlos.oliveira@email.com', 4, 'e10adc3949ba59abbe56e057f20f883e', '0000-00-00', NULL, NULL),
(6, 'Fernanda Lima Santos', 'Rua Blumenau, 1020 - Joinville/SC', '456.789.012-34', '(47) 99644-5566', 'fernanda.santos@email.com', 4, 'e10adc3949ba59abbe56e057f20f883e', '2026-08-02', NULL, NULL),
(7, 'Lucas Henrique Costa', 'Rua Santa Catarina, 215 - Joinville/SC', '567.890.123-45', '(47) 99555-6677', 'lucas.costa@email.com', 4, 'e10adc3949ba59abbe56e057f20f883e', '2026-08-02', NULL, NULL),
(8, 'Juliana Martins', 'Rua Otto Boehm, 98 - Joinville/SC', '678.901.234-56', '(47) 99466-7788', 'juliana.martins@email.com', 4, 'e10adc3949ba59abbe56e057f20f883e', '2026-08-02', NULL, NULL),
(9, 'Rafael Almeida', 'Rua Albano Schmidt, 312 - Joinville/SC', '789.012.345-67', '(47) 99377-8899', 'rafael.almeida@email.com', 4, 'e10adc3949ba59abbe56e057f20f883e', '0000-00-00', NULL, NULL),
(11, 'Gabriel Rodrigues', 'Rua Tuiuti, 654 - Joinville/SC', '901.234.567-89', '(47) 99199-1010', 'gabriel.rodrigues@email.com', 4, 'e10adc3949ba59abbe56e057f20f883e', '2026-08-03', NULL, NULL),
(13, 'Gabriella Teste', 'Rua Teste', '356.346.346-36', '(36) 46346-3563', 'gabriella.galdino1808@gmail.com', 4, '$2y$10$4fNKD5Q3ir3SfBHkKpaiF.Z4FjMf4aiTLmRp6Yu0wLdehd0IbccKK', '0000-00-00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `idfuncionario` int(11) NOT NULL COMMENT 'Identificador único do funcionário. Chave primária da tabela.',
  `nome_func` varchar(80) NOT NULL COMMENT 'Nome completo do funcionário.',
  `cargos_idcargos` int(11) NOT NULL COMMENT 'Identificador do cargo ocupado pelo funcionário. Chave estrangeira para a tabela cargos.',
  `endereco_func` varchar(75) NOT NULL COMMENT 'Endereço residencial do funcionário.',
  `tel_func` varchar(15) DEFAULT NULL COMMENT 'Número de telefone para contato com o funcionário.',
  `cpf_func` varchar(15) NOT NULL COMMENT 'Cadastro de Pessoa Física (CPF) do funcionário.',
  `email_func` varchar(85) NOT NULL COMMENT 'Endereço de e-mail utilizado pelo funcionário.',
  `senha_func` varchar(64) NOT NULL COMMENT 'Senha criptografada (hash) utilizada para autenticação do funcionário no sistema.',
  `data_cadastro` date DEFAULT NULL COMMENT 'Data em que o funcionário foi cadastrado no sistema.',
  `foto` varchar(30) DEFAULT NULL COMMENT 'Nome ou caminho da foto de perfil do funcionário armazenada no sistema.',
  `token_recuperacao` varchar(255) DEFAULT NULL,
  `token_expiracao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`idfuncionario`, `nome_func`, `cargos_idcargos`, `endereco_func`, `tel_func`, `cpf_func`, `email_func`, `senha_func`, `data_cadastro`, `foto`, `token_recuperacao`, `token_expiracao`) VALUES
(1, 'Gabriella Galdino', 1, 'Rua das Palmeiras, 934', '(47) 93843-4834', '839.343.554-00', 'gabriella@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-03', '6a73b6bdce549.png', NULL, NULL),
(2, 'Julia De andrade', 1, 'Rua das Flores, 120 - Joinville/SC', '(47) 99911-1111', '111.111.111-11', 'julia@empresa.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-03', NULL, NULL, NULL),
(3, 'Maria Eduarda Souza', 2, 'Rua Blumenau, 350 - Joinville/SC', '(47) 99922-2222', '222.222.222-22', 'maria.souza@empresa.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-03', NULL, NULL, NULL),
(4, 'Lucas Henrique Alves', 3, 'Rua Tuiuti, 480 - Joinville/SC', '(47) 99933-3333', '333.333.333-33', 'lucas.alves@empresa.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-03', NULL, NULL, NULL),
(5, 'Fernanda Cristina Lima', 1, 'Rua Otto Boehm, 210 - Joinville/SC', '(47) 99944-4444', '444.444.444-44', 'fernanda.lima@empresa.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-03', NULL, NULL, NULL),
(6, 'Gabriel Martins', 2, 'Rua Santa Catarina, 750 - Joinville/SC', '(47) 99955-5555', '555.555.555-55', 'gabriel.martins@empresa.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-03', NULL, NULL, NULL),
(7, 'Patrícia Oliveira', 1, 'Rua São Paulo, 160 - Joinville/SC', '(47) 99966-6666', '666.666.666-66', 'patricia.oliveira@empresa.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-02', NULL, NULL, NULL),
(8, 'Rafael Costa', 3, 'Rua Dona Francisca, 980 - Joinville/SC', '(47) 99977-7777', '777.777.777-77', 'rafael.costa@empresa.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-02', NULL, NULL, NULL),
(9, 'Juliana Ferreira', 3, 'Rua XV de Novembro, 420 - Joinville/SC', '(47) 99988-8888', '888.888.888-88', 'juliana.ferreira@empresa.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-02', NULL, NULL, NULL),
(10, 'Carlos Eduardo Silva', 2, 'Rua Albano Schmidt, 315 - Joinville/SC', '(47) 99999-9999', '999.999.999-99', 'carlos.silva@empresa.com', 'e10adc3949ba59abbe56e057f20f883e', '2026-08-02', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL COMMENT 'Identificador único da notificação. Chave primária da tabela.',
  `mensagem` varchar(255) NOT NULL COMMENT 'Conteúdo da mensagem da notificação exibida ao usuário.',
  `usuario` varchar(100) DEFAULT NULL COMMENT 'Nome ou identificador do usuário destinatário da notificação.',
  `tipo` varchar(50) DEFAULT 'info' COMMENT 'Categoria da notificação (ex.: info, sucesso, aviso ou erro).',
  `data_criacao` datetime DEFAULT current_timestamp() COMMENT 'Data e hora em que a notificação foi criada no sistema.',
  `lida` tinyint(1) DEFAULT 0 COMMENT 'Indica se a notificação foi visualizada pelo usuário (0 = Não, 1 = Sim).'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id`, `mensagem`, `usuario`, `tipo`, `data_criacao`, `lida`) VALUES
(1, 'Alterou a peça: Conector de Carga USB-C # (ID #8)', 'Gabriella Galdino', 'alerta', '2026-08-02 14:42:24', 1),
(2, 'Alterou a peça: Conector de Carga USB-C # (ID #8)', 'Gabriella Galdino', 'alerta', '2026-08-02 14:42:29', 1),
(3, 'Excluiu a peça ID #8', NULL, 'perigo', '2026-08-02 14:48:52', 1),
(4, 'Alterou a peça: Tela LCD Redmi Note 10 #8 (ID #7)', NULL, 'alerta', '2026-08-02 14:49:15', 1),
(5, 'Excluiu a peça ID #7', 'Atendente', 'perigo', '2026-08-02 14:50:05', 1),
(6, 'Alterou a peça: Conector de Carga USB-C # (ID #6)', 'Gabriella Galdino', 'alerta', '2026-08-02 14:50:43', 1),
(7, 'Alterou a peça: Conector de Carga USB-C # (ID #6)', 'Gabriella Galdino', 'alerta', '2026-08-02 14:57:11', 1),
(8, 'Alterou o funcionário: Julia Schoepping (ID #2)', 'Gabriella Galdino', 'alerta', '2026-08-02 15:09:15', 1),
(9, 'Alterou a peça: Tela Touch iPhone 11 #373 (ID #5)', 'Gabriella Galdino', 'alerta', '2026-08-02 21:17:32', 1),
(10, 'Cadastrou o funcionário: Larissa Martins', 'Gabriella Galdino', 'sucesso', '2026-08-02 21:54:57', 1),
(11, 'Cadastrou o funcionário: Maria Silva', 'Gabriella Galdino', 'sucesso', '2026-08-02 22:27:08', 1),
(12, 'Excluiu o funcionário ID #4', 'Gabriella Galdino', 'perigo', '2026-08-02 22:53:11', 1),
(13, 'Cadastrou o funcionário: Maria Silva', 'Gabriella Galdino', 'sucesso', '2026-08-02 22:53:34', 1),
(14, 'Excluiu o funcionário ID #4', 'Gabriella Galdino', 'perigo', '2026-08-02 22:57:29', 1),
(15, 'Cadastrou o funcionário: Maria Silva', 'Gabriella Galdino', 'sucesso', '2026-08-02 22:57:45', 1),
(16, 'Excluiu o cliente ID #1', 'Maria Silva', 'perigo', '2026-08-03 00:07:18', 1),
(17, 'Excluiu o funcionário ID #0', 'Maria Silva', 'perigo', '2026-08-03 00:16:35', 1),
(18, 'Excluiu o funcionário ID #0', 'Maria Silva', 'perigo', '2026-08-03 00:18:05', 1),
(19, 'Excluiu o funcionário ID #0', 'Maria Silva', 'perigo', '2026-08-03 00:18:12', 1),
(20, 'Excluiu o funcionário ID #0', 'Maria Silva', 'perigo', '2026-08-03 00:20:53', 1),
(21, 'Excluiu o funcionário ID #0', 'Gabriella Galdino', 'perigo', '2026-08-03 17:09:37', 1),
(22, 'Excluiu o funcionário ID #11', 'Gabriella Galdino', 'perigo', '2026-08-03 17:10:05', 1),
(23, 'Excluiu a peça ID #5', 'Gabriella Galdino', 'perigo', '2026-08-03 17:12:49', 1),
(24, 'Excluiu o serviço ID #2', 'Gabriella Galdino', 'perigo', '2026-08-03 17:15:54', 1),
(25, 'Cadastrou o serviço: Troca de bateria', 'Gabriella Galdino', 'sucesso', '2026-08-03 17:16:27', 1),
(26, 'Cadastrou o serviço: Troca de botão', 'Gabriella Galdino', 'sucesso', '2026-08-03 21:14:33', 1),
(27, 'Alterou o serviço: Troca de botão (ID #3)', 'Gabriella Galdino', 'alerta', '2026-08-03 21:14:49', 1),
(28, 'Alterou o serviço: Troca de bateria (ID #2)', 'Gabriella Galdino', 'alerta', '2026-08-03 21:21:48', 1),
(29, 'Cadastrou o cliente: Gabriella Teste', 'Gabriella Galdino', 'sucesso', '2026-08-04 13:50:22', 1),
(30, 'Excluiu o cliente ID #12', 'Gabriella Galdino', 'perigo', '2026-08-04 16:57:16', 1),
(31, 'Cadastrou o relatório: Teste4', 'Gabriella Galdino', 'sucesso', '2026-08-04 17:50:55', 1),
(32, 'Alterou o relatório: Teste4 (ID #4)', 'Gabriella Galdino', 'alerta', '2026-08-04 17:51:48', 1),
(33, 'Alterou o relatório: Teste4 (ID #4)', 'Gabriella Galdino', 'alerta', '2026-08-04 17:51:55', 1),
(34, 'Excluiu o relatório ID #4', 'Gabriella Galdino', 'perigo', '2026-08-04 17:52:03', 1),
(35, 'Cadastrou o relatório: Relatório Serviços', 'Gabriella Galdino', 'sucesso', '2026-08-04 20:36:01', 1),
(36, 'Excluiu o relatório ID #4', 'Gabriella Galdino', 'perigo', '2026-08-04 20:45:54', 1),
(37, 'Alterou a peça: Tela Touch iPhone 11 #253 (ID #1)', 'Gabriella Galdino', 'alerta', '2026-08-04 20:47:15', 1),
(38, 'Cadastrou o funcionário: teste', 'Gabriella Galdino', 'sucesso', '2026-08-04 21:40:51', 1),
(39, 'Alterou o funcionário: teste (ID #11)', 'Gabriella Galdino', 'alerta', '2026-08-04 21:41:06', 1),
(40, 'Excluiu o funcionário ID #11', 'Gabriella Galdino', 'perigo', '2026-08-04 21:41:09', 1),
(41, 'Excluiu o cliente ID #10', 'Gabriella Galdino', 'perigo', '2026-08-04 21:42:33', 1),
(42, 'Cadastrou a peça: peça', 'Gabriella Galdino', 'sucesso', '2026-08-04 21:47:55', 1),
(43, 'Alterou a peça: peça (ID #7)', 'Gabriella Galdino', 'alerta', '2026-08-04 21:48:11', 1),
(44, 'Alterou o serviço: Troca de bateria (ID #2)', 'Gabriella Galdino', 'alerta', '2026-08-04 21:48:49', 1),
(45, 'Cadastrou o serviço: ', 'Gabriella Galdino', 'sucesso', '2026-08-04 21:49:11', 1),
(46, 'Cadastrou o relatório: relatorio de serviços', 'Gabriella Galdino', 'sucesso', '2026-08-04 21:50:36', 1),
(47, 'Alterou o relatório: relatorio de serviços (ID #4)', 'Gabriella Galdino', 'alerta', '2026-08-04 21:54:49', 1),
(48, 'Excluiu o relatório ID #1', 'Gabriella Galdino', 'perigo', '2026-08-04 21:55:25', 1),
(49, 'Alterou o relatório: relatorio de serviços (ID #4)', 'Gabriella Galdino', 'alerta', '2026-08-04 21:56:16', 1),
(50, 'Alterou o relatório: relatorio de serviços (ID #4)', 'Gabriella Galdino', 'alerta', '2026-08-04 21:59:17', 1),
(51, 'Cadastrou a peça: Botão Power Moto G9 #447', 'Atendente', 'sucesso', '2026-08-05 15:31:58', 1),
(52, 'Cadastrou a peça: Tela Touch iPhone 11 #329', 'Atendente', 'sucesso', '2026-08-05 15:32:06', 1),
(53, 'Cadastrou o serviço: Troca de Tela - 169', 'Atendente', 'sucesso', '2026-08-05 17:22:26', 1),
(54, 'Cadastrou o serviço: Formatação de Sistema - 138', 'Atendente', 'sucesso', '2026-08-05 17:22:30', 1),
(55, 'Excluiu o serviço ID #3', 'Gabriella Galdino', 'perigo', '2026-08-05 17:24:16', 1),
(56, 'Cadastrou o relatório: Relatório de Faturamento Mensal - 616', 'Atendente', 'sucesso', '2026-08-05 17:31:46', 1),
(57, 'Cadastrou o relatório: Relatório de Produtividade da Equipe - 594', 'Atendente', 'sucesso', '2026-08-05 17:32:29', 1),
(58, 'Cadastrou o relatório: Relatório de Faturamento Mensal - 908', 'Atendente', 'sucesso', '2026-08-05 17:32:35', 1),
(59, 'Excluiu o relatório ID #7', 'Gabriella Galdino', 'perigo', '2026-08-05 17:32:59', 1),
(60, 'Alterou o funcionário: Fernanda Cristina Lima (ID #5)', 'Gabriella Galdino', 'alerta', '2026-08-05 19:01:11', 1),
(61, 'Cadastrou o relatório: Relatório de Produtividade da Equipe - 477', 'Atendente', 'sucesso', '2026-08-05 19:33:10', 0),
(62, 'Cadastrou o relatório: Relatório de Orçamentos Aprovados - 510', 'Atendente', 'sucesso', '2026-08-05 19:33:16', 0),
(63, 'Cadastrou o relatório: Relatório de Controle de Estoque - 712', 'Atendente', 'sucesso', '2026-08-05 19:41:38', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamento`
--

CREATE TABLE `orcamento` (
  `idorcamento` int(11) NOT NULL COMMENT 'Identificador único do orçamento. Chave primária da tabela.',
  `cliente_idcliente` int(11) DEFAULT NULL COMMENT 'Identificador do cliente associado ao orçamento. Chave estrangeira para a tabela cliente.',
  `funcionario_idfuncionario` int(11) DEFAULT NULL COMMENT 'Identificador do funcionário responsável pela elaboração do orçamento. Chave estrangeira para a tabela funcionario.',
  `defeito` varchar(100) DEFAULT NULL COMMENT 'Descrição do defeito informado pelo cliente no equipamento.',
  `observacoes` varchar(100) DEFAULT NULL COMMENT 'Observações adicionais sobre o orçamento ou o equipamento.',
  `marca` varchar(20) NOT NULL COMMENT 'Marca do equipamento que será avaliado ou reparado.',
  `modelo` varchar(20) NOT NULL COMMENT 'Modelo do equipamento.',
  `imei` int(11) NOT NULL COMMENT 'Número de identificação (IMEI) do aparelho, quando aplicável.',
  `aprovado` varchar(10) DEFAULT NULL COMMENT 'Indica se o orçamento foi aprovado pelo cliente (Sim ou Não).',
  `valor_total` float DEFAULT NULL COMMENT 'Valor total do orçamento, considerando peças e serviços.',
  `data_dia` date DEFAULT NULL COMMENT 'Data de emissão ou criação do orçamento.',
  `status` varchar(15) DEFAULT NULL COMMENT 'Situação atual do orçamento (Pendente, Aprovado, Reprovado, Em andamento ou Finalizado).'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `orcamento`
--

INSERT INTO `orcamento` (`idorcamento`, `cliente_idcliente`, `funcionario_idfuncionario`, `defeito`, `observacoes`, `marca`, `modelo`, `imei`, `aprovado`, `valor_total`, `data_dia`, `status`) VALUES
(2, 2, 1, 'Nao liga', 'Possivel problema na placa', 'Samsung', 'A05S', 987654321, 'Sim', 380, '2026-07-02', 'Aprovado'),
(4, 2, 1, 'Bateria', 'Não esta carregando.', 'APPLE', 'IPHONE 12', 2147483647, 'Sim', 200, '2026-07-03', 'Aprovado'),
(615, 4, 2, 'cd\\aef', 'fwsq', 'Samsung', 'A05S', 2147483647, 'Sim', 520, '2026-08-03', 'Aprovado'),
(618, 11, 9, 'Teste', 'teste', 'Samsung', 'A05S', 2147483647, NULL, 400, '2026-08-03', 'Aguardando'),
(619, 1, 5, 'teste', 'teste', 'Samsung', 'A05S', 2147483647, 'Sim', 0, '2026-08-03', 'Aprovado'),
(620, 5, 1, 'teste', 'teste', 'Apple', 'A05S', 2147483647, 'Sim', 209, '2026-08-04', 'Aprovado'),
(621, 3, 9, 'teste', 'teste', 'Samsung', 'Iphone 12', 383394993, 'Sim', 234, '2026-08-04', 'Aprovado'),
(622, 13, 9, 't', 't', 'Apple', 'A05S', 987654321, 'Sim', 500, '2026-08-04', 'Aprovado'),
(623, 5, 5, 'teste3', 'teste3', 'Apple', 'A05S', 6544578, 'Sim', 700, '2026-08-04', 'Aprovado'),
(624, 3, 2, 'teste', 'teste', 'Samsung', 'A05S', 2147483647, NULL, 100, '2026-08-04', 'Aguardando'),
(625, 5, 10, 'Bateria viciada', 'Trinca na tampa traseira', 'Samsung', 'Redmi Note 11', 2147483647, NULL, 566, '0000-00-00', 'Aguardando'),
(626, 5, 10, 'Tela quebrada', 'Avarias visíveis no aro', 'LG', 'One Macro', 2147483647, 'Sim', 417, '2026-08-05', 'Aprovado'),
(627, 5, 10, 'Bateria viciada', 'Sem riscos na tela', 'LG', 'Moto G60', 2147483647, 'Nao', 1118, '0000-00-00', 'Reprovado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_servico`
--

CREATE TABLE `ordem_servico` (
  `idos` int(11) NOT NULL,
  `numero_os` varchar(20) DEFAULT NULL COMMENT 'Número de controle ou código visível da OS para o cliente.',
  `orcamento_idorcamento` int(11) NOT NULL COMMENT 'Chave estrangeira vinculando a OS ao orçamento de origem (se houver).',
  `cliente_idcliente` int(11) DEFAULT NULL COMMENT 'Chave estrangeira que identifica o cliente dono do equipamento/serviço.',
  `funcionario_idfuncionario` int(11) DEFAULT NULL COMMENT 'Chave estrangeira do funcionário responsável pelo atendimento ou abertura.',
  `data_abertura` date DEFAULT NULL COMMENT 'Data e hora em que a Ordem de Serviço foi aberta.',
  `data_fechamento` date DEFAULT NULL COMMENT 'Data e hora em que a Ordem de Serviço foi concluída/fechada.',
  `garantia_dias` int(11) DEFAULT 90 COMMENT 'Prazo de garantia do serviço prestado (em dias). Padrão: 90 dias.',
  `status_os` varchar(20) DEFAULT 'Aberta' COMMENT 'Situação atual da OS (Ex: Aberta, Em Andamento, Concluída, Cancelada).',
  `defeito_informado` text DEFAULT NULL COMMENT 'Relato do cliente sobre o problema apresentado pelo equipamento.',
  `laudo_tecnico` varchar(255) DEFAULT NULL COMMENT 'Diagnóstico detalhado constatado pelo técnico responsável.',
  `descricao_servico` text DEFAULT NULL COMMENT 'Descrição detalhada dos procedimentos e reparos realizados.',
  `observacoes_os` varchar(255) DEFAULT NULL COMMENT 'Anotações extras ou avisos importantes referentes à OS.',
  `valor_pecas` decimal(10,2) DEFAULT 0.00 COMMENT 'Custo total das peças utilizadas no reparo.',
  `valor_mao_obra` decimal(10,2) DEFAULT 0.00 COMMENT 'Custo referente apenas à mão de obra do serviço.',
  `desconto` decimal(10,2) DEFAULT 0.00 COMMENT 'Valor total concedido de desconto aplicado na OS.',
  `valor_final` decimal(10,2) DEFAULT 0.00 COMMENT 'Valor final a ser pago (Peças + Mão de obra - Desconto).'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ordem_servico`
--

INSERT INTO `ordem_servico` (`idos`, `numero_os`, `orcamento_idorcamento`, `cliente_idcliente`, `funcionario_idfuncionario`, `data_abertura`, `data_fechamento`, `garantia_dias`, `status_os`, `defeito_informado`, `laudo_tecnico`, `descricao_servico`, `observacoes_os`, `valor_pecas`, `valor_mao_obra`, `desconto`, `valor_final`) VALUES
(1, 'OS-20260702-0001', 2, 2, 1, '2026-07-02', NULL, 90, 'Em Andamento', NULL, 'Nao liga', '', 'Possivel problema na placa', 0.00, 0.00, 0.00, 0.00),
(2, 'OS-20260803-0004', 4, 2, 1, '2026-08-03', NULL, 90, 'Em Andamento', NULL, 'xsac', '', '', 0.00, 200.00, 0.00, 200.00),
(3, 'OS-20260803-0615', 615, 4, 2, '2026-08-03', NULL, 90, 'Em Andamento', NULL, 'teste1', '', '', 0.00, 520.00, 0.00, 520.00),
(4, 'OS-20260805-0621', 621, 3, 9, '2026-08-05', NULL, 90, 'Concluído', NULL, 'teste', 'teste', 'teste', 80.00, 234.00, 0.00, 314.00),
(5, 'OS-20260805-0622', 622, 13, 9, '2026-08-05', NULL, 90, 'Concluído', NULL, 'teste', 'teste', 'teste', 40.00, 500.00, 0.00, 540.00),
(9, 'OS-20260805-0620', 620, 5, 1, '2026-08-05', NULL, 90, 'Concluído', NULL, 'teste2', 'teste2', 'teste2', 200.00, 209.00, 0.00, 409.00),
(10, 'OS-20260805-0623', 623, 5, 5, '2026-08-05', NULL, 90, 'Em Andamento', NULL, 'teste1', 'teste3', 'teste3', 200.00, 700.00, 0.00, 900.00),
(11, 'OS-20260805-0619', 619, 1, 5, '2026-08-05', NULL, 90, 'Concluído', NULL, 'teste', 'teste', 'teste', 200.00, 100.00, 0.00, 300.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_servico_itens`
--

CREATE TABLE `ordem_servico_itens` (
  `iditem` int(11) NOT NULL COMMENT 'Identificador único do item da OS (Chave Primária).',
  `os_idos` int(11) NOT NULL COMMENT 'Chave estrangeira vinculando o item à Ordem de Serviço.',
  `tipo_item` enum('PECA','SERVICO') NOT NULL COMMENT 'Indica se o item é uma peça (PECA) ou um serviço (SERVICO).',
  `descricao` varchar(120) NOT NULL COMMENT 'Nome ou descrição detalhada da peça ou do serviço executado.',
  `quantidade` int(11) NOT NULL DEFAULT 1 COMMENT 'Quantidade de itens utilizados. Padrão: 1.',
  `valor_unitario` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor unitário de cada peça ou serviço.',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor total do item (Quantidade x Valor unitário).',
  `observacao` varchar(255) DEFAULT NULL COMMENT 'Observações específicas sobre este item (Opcional).',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Data e hora em que o registro do item foi criado.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `peca`
--

CREATE TABLE `peca` (
  `idpeca` int(11) NOT NULL COMMENT 'Identificador único da peça (Chave Primária, Auto Incremento).',
  `nome_peca` varchar(25) NOT NULL COMMENT 'Nome ou descrição breve da peça (Ex: Tela iPhone, Bateria).',
  `categoria` varchar(15) NOT NULL COMMENT 'Categoria ou tipo de equipamento ao qual a peça pertence.',
  `qtdade_peca` int(11) NOT NULL COMMENT 'Quantidade atual desta peça disponível em estoque.',
  `valor_unit` float NOT NULL COMMENT 'Valor unitário de custo ou de venda da peça.',
  `estoque_min` int(11) DEFAULT NULL COMMENT 'Quantidade mínima em estoque para alerta de reposição.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `peca`
--

INSERT INTO `peca` (`idpeca`, `nome_peca`, `categoria`, `qtdade_peca`, `valor_unit`, `estoque_min`) VALUES
(1, 'Tela Touch iPhone 11 #253', 'Tela', 9, 197.71, 10),
(2, 'Botão Power Moto G9 #674', 'Tela', 57, 315.02, 5),
(6, 'Conector de Carga USB-C #', 'Botões', 85, 301.05, 4),
(7, 'peça', 'Conectores', 9, 5, 9),
(8, 'Botão Power Moto G9 #447', 'Botões', 33, 322.47, 4),
(9, 'Tela Touch iPhone 11 #329', 'Bateria', 83, 202.73, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `peca_orcamento`
--

CREATE TABLE `peca_orcamento` (
  `id_peca_servico` int(11) NOT NULL COMMENT 'Identificador único do registro (Chave Primária, Auto Incremento).',
  `peca_idpeca` int(11) NOT NULL COMMENT 'Chave estrangeira vinculando a peça utilizada (Chave Estrangeira).',
  `orcamento_idorcamento` int(11) NOT NULL COMMENT 'Chave estrangeira vinculando ao orçamento correspondente (Chave Estrangeira).',
  `servico_idservico` int(11) NOT NULL COMMENT 'Chave estrangeira vinculando ao serviço correspondente (Chave Estrangeira).',
  `valor_peca_servico` float DEFAULT NULL COMMENT 'Valor específico da peça ou serviço aplicado neste orçamento.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio`
--

CREATE TABLE `relatorio` (
  `idrelatorio` int(11) NOT NULL COMMENT 'Identificador único do relatório (Chave Primária, Auto Incremento).',
  `nome_relatorio` varchar(50) NOT NULL COMMENT 'Título ou nome descritivo do relatório gerado.',
  `tipo` varchar(20) NOT NULL COMMENT 'Tipo ou categoria do relatório (Ex: Financeiro, Técnico, Estoque).',
  `geracao_data` date NOT NULL COMMENT 'Data em que o relatório foi gerado no sistema.',
  `data_inicio` date NOT NULL COMMENT 'Data de início do período considerado no relatório.',
  `data_fim` date NOT NULL COMMENT 'Data de término do período considerado no relatório.',
  `responsavel` varchar(50) NOT NULL COMMENT 'Nome ou identificação do usuário que gerou o relatório.',
  `exportado` varchar(3) NOT NULL COMMENT 'Indica se o relatório foi exportado (Ex: Sim, Não).',
  `status` varchar(15) NOT NULL COMMENT 'Situação atual do relatório (Ex: Pronto, Processando, Erro).',
  `data_alteracao` datetime NOT NULL COMMENT 'Data e hora da última alteração ou atualização do relatório.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `relatorio`
--

INSERT INTO `relatorio` (`idrelatorio`, `nome_relatorio`, `tipo`, `geracao_data`, `data_inicio`, `data_fim`, `responsavel`, `exportado`, `status`, `data_alteracao`) VALUES
(2, 'Relatório Mensal de Estoque - Julho', 'Estoque', '2026-07-31', '2026-07-01', '2026-07-31', 'Gabriella Galdino', 'Sim', 'Pendente', '0000-00-00 00:00:00'),
(3, 'Relatório de OS', 'Ordem de Serviço', '2026-08-02', '2026-07-01', '2026-07-31', 'Julia Schoepping', 'Sim', 'Pendente', '0000-00-00 00:00:00'),
(4, 'relatorio de serviços', 'Serviços', '2026-08-05', '2026-07-01', '2026-08-04', 'Lucas Henrique Alves', 'Sim', 'Concluído', '2026-08-04 21:59:17'),
(5, 'Relatório de Faturamento Mensal - 616', 'Estoque', '2026-08-05', '2026-07-08', '2026-08-05', 'Carlos Eduardo Silva', 'Não', 'Pendente', '0000-00-00 00:00:00'),
(6, 'Relatório de Produtividade da Equipe - 594', 'Funcionários', '2026-08-05', '2026-07-26', '2026-08-05', 'Carlos Eduardo Silva', 'Não', 'Pendente', '0000-00-00 00:00:00'),
(7, 'Relatório de Produtividade da Equipe - 477', 'Estoque', '2026-08-06', '2026-07-13', '2026-08-05', 'Fernanda Cristina Lima', 'Não', 'Pendente', '0000-00-00 00:00:00'),
(8, 'Relatório de Orçamentos Aprovados - 510', 'Serviços', '2026-08-06', '2026-07-09', '2026-08-05', 'Patrícia Oliveira', 'Não', 'Pendente', '0000-00-00 00:00:00'),
(9, 'Relatório de Controle de Estoque - 712', 'Funcionários', '2026-08-06', '2026-07-13', '2026-08-05', 'Fernanda Cristina Lima', 'Não', 'Pendente', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

CREATE TABLE `servico` (
  `idservico` int(11) NOT NULL COMMENT 'Identificador único do serviço (Chave Primária, Auto Incremento).',
  `nome_servico` varchar(25) NOT NULL COMMENT 'Nome ou título do serviço prestado (Ex: Formatação, Troca de Tela).',
  `descricao_servico` varchar(60) DEFAULT NULL COMMENT 'Detalhes ou o escopo do que é realizado no serviço.',
  `valor` float DEFAULT NULL COMMENT 'Preço cobrado pela execução do serviço.',
  `tempo` time NOT NULL COMMENT 'Tempo estimado necessário para a conclusão do serviço.',
  `status` varchar(10) NOT NULL COMMENT 'Situação do serviço no sistema (Ex: Ativo, Inativo).'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servico`
--

INSERT INTO `servico` (`idservico`, `nome_servico`, `descricao_servico`, `valor`, `tempo`, `status`) VALUES
(1, 'Troca de tela', 'Troca de tela oled e amoled', 150.5, '00:30:00', 'Ativo'),
(2, 'Troca de bateria', 'teste', 200, '02:20:00', 'Inativo'),
(5, 'Troca de Tela - 169', 'Inclui backup e reinstalação de drivers', 359, '01:00:00', 'Inativo'),
(6, 'Formatação de Sistema - 1', 'Remoção completa de poeira e troca de pasta térmica', 166, '00:45:00', 'Inativo');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`idcargos`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`),
  ADD KEY `fk_cliente_cargos` (`cargos_idcargos`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`idfuncionario`),
  ADD KEY `fk_funcionario_cargos` (`cargos_idcargos`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD PRIMARY KEY (`idorcamento`),
  ADD KEY `fk_orcamento_cliente1` (`cliente_idcliente`),
  ADD KEY `fk_orcamento_funcionario1` (`funcionario_idfuncionario`);

--
-- Índices de tabela `ordem_servico`
--
ALTER TABLE `ordem_servico`
  ADD PRIMARY KEY (`idos`);

--
-- Índices de tabela `peca`
--
ALTER TABLE `peca`
  ADD PRIMARY KEY (`idpeca`);

--
-- Índices de tabela `peca_orcamento`
--
ALTER TABLE `peca_orcamento`
  ADD PRIMARY KEY (`id_peca_servico`),
  ADD KEY `fk_peça_has_orcamento_orcamento1` (`orcamento_idorcamento`),
  ADD KEY `fk_peça_has_orcamento_peça1` (`peca_idpeca`),
  ADD KEY `fk_peça_has_orcamento_servico1` (`servico_idservico`);

--
-- Índices de tabela `relatorio`
--
ALTER TABLE `relatorio`
  ADD PRIMARY KEY (`idrelatorio`);

--
-- Índices de tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`idservico`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do cliente. Chave primária da tabela.', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `idfuncionario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do funcionário. Chave primária da tabela.', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único da notificação. Chave primária da tabela.', AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `orcamento`
--
ALTER TABLE `orcamento`
  MODIFY `idorcamento` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do orçamento. Chave primária da tabela.', AUTO_INCREMENT=628;

--
-- AUTO_INCREMENT de tabela `ordem_servico`
--
ALTER TABLE `ordem_servico`
  MODIFY `idos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `peca`
--
ALTER TABLE `peca`
  MODIFY `idpeca` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único da peça (Chave Primária, Auto Incremento).', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `peca_orcamento`
--
ALTER TABLE `peca_orcamento`
  MODIFY `id_peca_servico` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do registro (Chave Primária, Auto Incremento).', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `relatorio`
--
ALTER TABLE `relatorio`
  MODIFY `idrelatorio` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do relatório (Chave Primária, Auto Incremento).', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `idservico` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do serviço (Chave Primária, Auto Incremento).', AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_cargos` FOREIGN KEY (`cargos_idcargos`) REFERENCES `cargos` (`idcargos`);

--
-- Restrições para tabelas `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `fk_funcionario_cargos` FOREIGN KEY (`cargos_idcargos`) REFERENCES `cargos` (`idcargos`);

--
-- Restrições para tabelas `orcamento`
--
ALTER TABLE `orcamento`
  ADD CONSTRAINT `fk_orcamento_cliente1` FOREIGN KEY (`cliente_idcliente`) REFERENCES `cliente` (`idcliente`),
  ADD CONSTRAINT `fk_orcamento_funcionario1` FOREIGN KEY (`funcionario_idfuncionario`) REFERENCES `funcionario` (`idfuncionario`);

--
-- Restrições para tabelas `peca_orcamento`
--
ALTER TABLE `peca_orcamento`
  ADD CONSTRAINT `fk_peça_has_orcamento_orcamento1` FOREIGN KEY (`orcamento_idorcamento`) REFERENCES `orcamento` (`idorcamento`),
  ADD CONSTRAINT `fk_peça_has_orcamento_peça1` FOREIGN KEY (`peca_idpeca`) REFERENCES `peca` (`idpeca`),
  ADD CONSTRAINT `fk_peça_has_orcamento_servico1` FOREIGN KEY (`servico_idservico`) REFERENCES `servico` (`idservico`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
