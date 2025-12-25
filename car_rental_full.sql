-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: car_rental
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
-- Table structure for table `car_categories`
--

DROP TABLE IF EXISTS `car_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `car_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) DEFAULT NULL,
  `base_price_per_day` decimal(10,2) DEFAULT NULL,
  `late_fee_per_hour` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_categories`
--

LOCK TABLES `car_categories` WRITE;
/*!40000 ALTER TABLE `car_categories` DISABLE KEYS */;
INSERT INTO `car_categories` VALUES (1,'Sedan',50.00,5.00),(2,'SUV',80.00,8.00),(3,'Luxury',150.00,15.00),(4,'Truck',100.00,10.00);
/*!40000 ALTER TABLE `car_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car_specs`
--

DROP TABLE IF EXISTS `car_specs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `car_specs` (
  `spec_id` int NOT NULL AUTO_INCREMENT,
  `make` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `year` int DEFAULT NULL,
  `engine_size` varchar(20) DEFAULT NULL,
  `transmission_type` varchar(20) DEFAULT NULL,
  `fuel_type` varchar(20) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  PRIMARY KEY (`spec_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `car_specs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `car_categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_specs`
--

LOCK TABLES `car_specs` WRITE;
/*!40000 ALTER TABLE `car_specs` DISABLE KEYS */;
INSERT INTO `car_specs` VALUES (1,'Nissan','Sunny',2023,'1.5L','Automatic','Petrol',1),(2,'Kia','Cerato',2022,'1.6L','Automatic','Petrol',1),(3,'Fiat','Tipo',2023,'1.4L','Automatic','Petrol',1),(4,'Chevrolet','Optra',2022,'1.5L','Automatic','Petrol',1),(5,'Kia','Sportage',2024,'1.6L Turbo','Automatic','Petrol',2),(6,'Mitsubishi','Xpander',2023,'1.5L','Automatic','Petrol',2),(7,'Jeep','Compass',2022,'2.4L','Automatic','Petrol',2),(8,'Audi','A4',2023,'2.0L','Automatic','Petrol',3),(9,'BMW','X5',2022,'3.0L','Automatic','Petrol',3),(10,'Mercedes-Benz','E200',2024,'2.0L Turbo','Automatic','Petrol',3),(11,'Toyota','Hilux',2023,'2.8L Diesel','Automatic','Diesel',4),(12,'Isuzu','D-Max',2022,'3.0L Diesel','Manual','Diesel',4),(13,'Nissan','Sunny',2023,'1.5L','Automatic','Petrol',1),(14,'Kia','Cerato',2022,'1.6L','Automatic','Petrol',1),(15,'Fiat','Tipo',2023,'1.4L','Automatic','Petrol',1),(16,'Chevrolet','Optra',2022,'1.5L','Automatic','Petrol',1),(17,'Kia','Sportage',2024,'1.6L Turbo','Automatic','Petrol',2),(18,'Mitsubishi','Xpander',2023,'1.5L','Automatic','Petrol',2),(19,'Jeep','Compass',2022,'2.4L','Automatic','Petrol',2),(20,'Audi','A4',2023,'2.0L','Automatic','Petrol',3),(21,'BMW','X5',2022,'3.0L','Automatic','Petrol',3),(22,'Mercedes-Benz','E200',2024,'2.0L Turbo','Automatic','Petrol',3),(23,'Toyota','Hilux',2023,'2.8L Diesel','Automatic','Diesel',4),(24,'Isuzu','D-Max',2022,'3.0L Diesel','Manual','Diesel',4);
/*!40000 ALTER TABLE `car_specs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car_status_log`
--

DROP TABLE IF EXISTS `car_status_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `car_status_log` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `car_id` int DEFAULT NULL,
  `status_from` varchar(50) DEFAULT NULL,
  `status_to` varchar(50) DEFAULT NULL,
  `change_timestamp` datetime DEFAULT NULL,
  `employee_id` int DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `car_id` (`car_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `car_status_log_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`),
  CONSTRAINT `car_status_log_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_status_log`
--

LOCK TABLES `car_status_log` WRITE;
/*!40000 ALTER TABLE `car_status_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `car_status_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cars` (
  `car_id` int NOT NULL AUTO_INCREMENT,
  `plate_id` varchar(20) DEFAULT NULL,
  `spec_id` int DEFAULT NULL,
  `current_office_id` int DEFAULT NULL,
  `status` enum('Active','Out of Service','Rented') DEFAULT NULL,
  `current_odometer` int DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`car_id`),
  UNIQUE KEY `plate_id` (`plate_id`),
  KEY `spec_id` (`spec_id`),
  KEY `current_office_id` (`current_office_id`),
  CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`spec_id`) REFERENCES `car_specs` (`spec_id`),
  CONSTRAINT `cars_ibfk_2` FOREIGN KEY (`current_office_id`) REFERENCES `offices` (`office_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` VALUES (52,'CAI-101',4,1,'Active',15000,'White'),(53,'CAI-102',5,1,'Active',27000,'Black'),(54,'CAI-103',6,1,'Rented',32000,'Gray'),(55,'CAI-104',7,1,'Active',21000,'Silver'),(56,'ALX-201',8,2,'Active',18000,'Blue'),(57,'ALX-202',9,2,'Rented',24000,'White'),(58,'ALX-203',10,2,'Out of Service',41000,'Black'),(59,'GIZ-301',11,3,'Active',16000,'Red'),(60,'GIZ-302',12,3,'Active',12000,'White'),(61,'GIZ-303',13,3,'Rented',35000,'Black'),(62,'TRK-401',14,1,'Active',50000,'White'),(63,'TRK-402',15,2,'Active',62000,'Blue');
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `driver_license_number` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `driver_license_number` (`driver_license_number`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'ali@gmail.com','hash123','Ali','Hassan','01011112222','DL123456','Nasr City, Cairo'),(2,'sara@gmail.com','hash456','Sara','Mohamed','01133334444','DL654321','Smouha, Alexandria'),(3,'omar@gmail.com','hash789','Omar','Ahmed','01255556666','DL987654','Dokki, Giza');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `employee_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `office_id` int DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `username` (`username`),
  KEY `office_id` (`office_id`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`office_id`) REFERENCES `offices` (`office_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'admin1','hash_admin_123','Admin','Ahmed Ali',1),(2,'manager1','hash_manager_456','Manager','Sara Mohamed',2),(3,'staff1','hash_staff_789','Staff','Omar Hassan',1);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offices`
--

DROP TABLE IF EXISTS `offices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offices` (
  `office_id` int NOT NULL AUTO_INCREMENT,
  `city` varchar(100) DEFAULT NULL,
  `location_address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`office_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offices`
--

LOCK TABLES `offices` WRITE;
/*!40000 ALTER TABLE `offices` DISABLE KEYS */;
INSERT INTO `offices` VALUES (1,'Cairo','Nasr City','01012345678','cairo@office.com'),(2,'Alexandria','Smouha','01234567890','alex@office.com'),(3,'Giza','Dokki','01198765432','giza@office.com');
/*!40000 ALTER TABLE `offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `reservation_id` int DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `reservation_id` (`reservation_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `car_id` int DEFAULT NULL,
  `pickup_office_id` int DEFAULT NULL,
  `return_office_id` int DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `pickup_date` datetime DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `actual_return_date` datetime DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `reservation_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `customer_id` (`customer_id`),
  KEY `car_id` (`car_id`),
  KEY `pickup_office_id` (`pickup_office_id`),
  KEY `return_office_id` (`return_office_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`),
  CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`pickup_office_id`) REFERENCES `offices` (`office_id`),
  CONSTRAINT `reservations_ibfk_4` FOREIGN KEY (`return_office_id`) REFERENCES `offices` (`office_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'car_rental'
--
/*!50003 DROP PROCEDURE IF EXISTS `GetCarStatusOnDay` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCarStatusOnDay`(IN targetDate DATE)
BEGIN
    SELECT 
        car.plate_id,
        spec.make,
        spec.model,
        CASE 
            -- Check if rented
            WHEN EXISTS (
                SELECT 1 FROM Reservations r 
                WHERE r.car_id = car.car_id 
                AND targetDate BETWEEN r.pickup_date AND r.return_date
                AND r.reservation_status != 'Cancelled'
            ) THEN 'Rented'
            
            -- Check if out of service (maintenance history)
            WHEN (
                SELECT status_to FROM Car_Status_Log log 
                WHERE log.car_id = car.car_id 
                AND log.change_timestamp <= targetDate 
                ORDER BY log.change_timestamp DESC LIMIT 1
            ) = 'Out of Service' THEN 'Out of Service'
            
            -- Default
            ELSE 'Active'
        END AS Status_On_Day
    FROM Cars car
    JOIN Car_Specs spec ON car.spec_id = spec.spec_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetCustomerHistory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCustomerHistory`(IN custID INT)
BEGIN
    SELECT 
        c.customer_id,
        c.first_name,
        c.last_name,
        c.email,

        r.reservation_id,
        r.booking_date,
        r.pickup_date,
        r.return_date,
        r.actual_return_date,
        r.reservation_status,
        r.total_price,

        car.plate_id,
        spec.make,
        spec.model,
        spec.year,

        off.city AS pickup_city
    FROM Reservations r
    JOIN Customers c 
        ON r.customer_id = c.customer_id
    JOIN Cars car 
        ON r.car_id = car.car_id
    JOIN Car_Specs spec 
        ON car.spec_id = spec.spec_id
    JOIN Offices off
        ON r.pickup_office_id = off.office_id
    WHERE c.customer_id = custID
    ORDER BY r.pickup_date DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetDailyPayments` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetDailyPayments`(IN startDate DATE, IN endDate DATE)
BEGIN
    SELECT 
        payment_date,
        SUM(amount) AS Total_Daily_Revenue,
        COUNT(payment_id) AS Transaction_Count
    FROM Payments
    WHERE payment_date BETWEEN startDate AND endDate
    GROUP BY payment_date
    ORDER BY payment_date ASC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetReservationsByPeriod` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetReservationsByPeriod`(IN startDate DATE, IN endDate DATE)
BEGIN
    SELECT 
        r.reservation_id,
        r.pickup_date,
        r.return_date,
        r.reservation_status,
        c.first_name,
        c.last_name,
        c.phone_number,
        car.plate_id,
        spec.model,
        off_pu.city AS pickup_city
    FROM Reservations r
    JOIN Customers c ON r.customer_id = c.customer_id
    JOIN Cars car ON r.car_id = car.car_id
    JOIN Car_Specs spec ON car.spec_id = spec.spec_id
    JOIN Offices off_pu ON r.pickup_office_id = off_pu.office_id
    WHERE r.pickup_date >= startDate AND r.pickup_date <= endDate;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Report_DailyPayments` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Report_DailyPayments`(IN startDate DATE, IN endDate DATE)
BEGIN
    SELECT 
        payment_date,
        COUNT(payment_id) AS transaction_count,
        SUM(amount) AS total_amount
    FROM Payments
    WHERE payment_date BETWEEN startDate AND endDate
    GROUP BY payment_date
    ORDER BY payment_date DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Report_OfficeRevenue` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Report_OfficeRevenue`()
BEGIN
    SELECT 
        off.city AS office_location,
        COUNT(r.reservation_id) AS total_rentals,
        COALESCE(SUM(p.amount), 0) AS total_revenue
    FROM Offices off
    LEFT JOIN Reservations r ON off.office_id = r.pickup_office_id
    LEFT JOIN Payments p ON r.reservation_id = p.reservation_id
    GROUP BY off.office_id, off.city
    ORDER BY total_revenue DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Report_ReservationsByPeriod` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Report_ReservationsByPeriod`(IN startDate DATE, IN endDate DATE)
BEGIN
    SELECT 
        r.reservation_id,
        CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
        car.plate_id,
        spec.model,
        off.city AS pickup_office,
        r.pickup_date,
        r.return_date,
        r.reservation_status,
        r.total_price
    FROM Reservations r
    JOIN Customers c ON r.customer_id = c.customer_id
    JOIN Cars car ON r.car_id = car.car_id
    JOIN Car_Specs spec ON car.spec_id = spec.spec_id
    JOIN Offices off ON r.pickup_office_id = off.office_id
    WHERE r.pickup_date BETWEEN startDate AND endDate
    ORDER BY r.pickup_date DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Report_StatusSummary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Report_StatusSummary`()
BEGIN
    SELECT 
        reservation_status,
        COUNT(*) AS count
    FROM Reservations
    GROUP BY reservation_status;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-23 12:12:49
