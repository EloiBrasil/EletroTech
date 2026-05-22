-- MySQL dump 10.13  Distrib 8.0.46, for macos15 (x86_64)
--
-- Host: localhost    Database: eletrotech
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary view structure for view `viewmetaseletri`
--

DROP TABLE IF EXISTS `viewmetaseletri`;
/*!50001 DROP VIEW IF EXISTS `viewmetaseletri`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `viewmetaseletri` AS SELECT 
 1 AS `id_eletri`,
 1 AS `nome`,
 1 AS `cpf`,
 1 AS `id_meta`,
 1 AS `mes_meta`,
 1 AS `vlr_meta`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_materiais_ord`
--

DROP TABLE IF EXISTS `view_materiais_ord`;
/*!50001 DROP VIEW IF EXISTS `view_materiais_ord`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_materiais_ord` AS SELECT 
 1 AS `id_ordServ`,
 1 AS `data_os`,
 1 AS `id_eletri`,
 1 AS `nome_eletricista`,
 1 AS `id_prod`,
 1 AS `nome_produto`,
 1 AS `qtd_utilizada`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_eletricista_metas`
--

DROP TABLE IF EXISTS `view_eletricista_metas`;
/*!50001 DROP VIEW IF EXISTS `view_eletricista_metas`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_eletricista_metas` AS SELECT 
 1 AS `id_eletri`,
 1 AS `nome`,
 1 AS `cpf`,
 1 AS `mes_meta`,
 1 AS `vlr_meta`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `viewmetaseletri`
--

/*!50001 DROP VIEW IF EXISTS `viewmetaseletri`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `viewmetaseletri` AS select `e`.`id_eletri` AS `id_eletri`,`e`.`nome` AS `nome`,`e`.`cpf` AS `cpf`,`m`.`id_meta` AS `id_meta`,`m`.`mes_meta` AS `mes_meta`,`m`.`vlr_meta` AS `vlr_meta` from (`tabela_eletricistas` `e` join `tabela_metas` `m` on((`m`.`eletricista_meta` = `e`.`id_eletri`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_materiais_ord`
--

/*!50001 DROP VIEW IF EXISTS `view_materiais_ord`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_materiais_ord` AS select `o`.`id_ordServ` AS `id_ordServ`,`o`.`data_os` AS `data_os`,`e`.`id_eletri` AS `id_eletri`,`e`.`nome` AS `nome_eletricista`,`p`.`id_prod` AS `id_prod`,`p`.`nome` AS `nome_produto`,`m`.`qtd_utilizada` AS `qtd_utilizada` from (((`tabela_eletricistas` `e` join `tabela_ordens_servico` `o` on((`o`.`eletricista_os` = `e`.`id_eletri`))) join `tabela_os_materiais` `m` on((`m`.`id_os` = `o`.`id_ordServ`))) join `tabela_produtos` `p` on((`m`.`id_produto` = `p`.`id_prod`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_eletricista_metas`
--

/*!50001 DROP VIEW IF EXISTS `view_eletricista_metas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_eletricista_metas` AS select `e`.`id_eletri` AS `id_eletri`,`e`.`nome` AS `nome`,`e`.`cpf` AS `cpf`,`m`.`mes_meta` AS `mes_meta`,`m`.`vlr_meta` AS `vlr_meta` from (`tabela_eletricistas` `e` join `tabela_metas` `m` on((`m`.`eletricista_meta` = `e`.`id_eletri`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-22 16:59:18
