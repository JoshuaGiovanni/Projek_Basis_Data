-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 01:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dw_datamate1`
--

-- --------------------------------------------------------

--
-- Table structure for table `dim_age_group`
--

CREATE TABLE `dim_age_group` (
  `PK_Age_Group_ID` int(11) NOT NULL,
  `Nama_Rentang_Umur` varchar(50) DEFAULT NULL,
  `Penjelasan_Rentang_Umur` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dim_age_group`
--

INSERT INTO `dim_age_group` (`PK_Age_Group_ID`, `Nama_Rentang_Umur`, `Penjelasan_Rentang_Umur`) VALUES
(1, '17-22', 'Remaja Akhir dan Dewasa Awal'),
(2, '23-30', 'Dewasa Awal'),
(3, '31-40', 'Dewasa'),
(4, '41-50', 'Dewasa Menengah'),
(5, '>50', 'Lansia'),
(6, 'Unknown', 'Tanggal lahir tidak tersedia'),
(7, '17-22  ', 'Remaja Akhir dan Dewasa Awal'),
(8, '23-30', 'Dewasa Awal'),
(9, '31-40', 'Dewasa'),
(10, '41-50', 'Dewasa Menengah'),
(11, '>50', 'Lansia'),
(12, 'Unknown', 'Tanggal lahir tidak tersedia'),
(13, '17-22  ', 'Remaja Akhir dan Dewasa Awal'),
(14, '23-30', 'Dewasa Awal'),
(15, '31-40', 'Dewasa'),
(16, '41-50', 'Dewasa Menengah'),
(17, '>50', 'Lansia'),
(18, 'Unknown', 'Tanggal lahir tidak tersedia'),
(19, '17-22  ', 'Remaja Akhir dan Dewasa Awal'),
(20, '23-30', 'Dewasa Awal'),
(21, '31-40', 'Dewasa'),
(22, '41-50', 'Dewasa Menengah'),
(23, '>50', 'Lansia'),
(24, 'Unknown', 'Tanggal lahir tidak tersedia'),
(25, '17-22  ', 'Remaja Akhir dan Dewasa Awal'),
(26, '23-30', 'Dewasa Awal'),
(27, '31-40', 'Dewasa'),
(28, '41-50', 'Dewasa Menengah'),
(29, '>50', 'Lansia'),
(30, 'Unknown', 'Tanggal lahir tidak tersedia'),
(31, '17-22  ', 'Remaja Akhir dan Dewasa Awal'),
(32, '23-30', 'Dewasa Awal'),
(33, '31-40', 'Dewasa'),
(34, '41-50', 'Dewasa Menengah'),
(35, '>50', 'Lansia'),
(36, 'Unknown', 'Tanggal lahir tidak tersedia'),
(37, '17-22  ', 'Remaja Akhir dan Dewasa Awal'),
(38, '23-30', 'Dewasa Awal'),
(39, '31-40', 'Dewasa'),
(40, '41-50', 'Dewasa Menengah'),
(41, '>50', 'Lansia'),
(42, 'Unknown', 'Tanggal lahir tidak tersedia'),
(43, '17-22  ', 'Remaja Akhir dan Dewasa Awal'),
(44, '23-30', 'Dewasa Awal'),
(45, '31-40', 'Dewasa'),
(46, '41-50', 'Dewasa Menengah'),
(47, '>50', 'Lansia'),
(48, 'Unknown', 'Tanggal lahir tidak tersedia'),
(49, '17-22  ', 'Remaja Akhir dan Dewasa Awal'),
(50, '23-30', 'Dewasa Awal'),
(51, '31-40', 'Dewasa'),
(52, '41-50', 'Dewasa Menengah'),
(53, '>50', 'Lansia'),
(54, 'Unknown', 'Tanggal lahir tidak tersedia'),
(55, '17-22  ', 'Remaja Akhir dan Dewasa Awal'),
(56, '23-30', 'Dewasa Awal'),
(57, '31-40', 'Dewasa'),
(58, '41-50', 'Dewasa Menengah'),
(59, '>50', 'Lansia'),
(60, 'Unknown', 'Tanggal lahir tidak tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `dim_client`
--

CREATE TABLE `dim_client` (
  `PK_Client_ID` bigint(20) NOT NULL,
  `Client_Name` varchar(150) DEFAULT NULL,
  `Birth_Date` date DEFAULT NULL,
  `Client_Type` enum('INDIVIDUAL','COMPANY') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dim_client`
--

INSERT INTO `dim_client` (`PK_Client_ID`, `Client_Name`, `Birth_Date`, `Client_Type`) VALUES
(2, 'Dr. Gino Hartmann', '1992-06-26', 'COMPANY'),
(3, 'Orpha Walsh V', '2002-08-11', 'COMPANY'),
(4, 'Nichole Purdy', NULL, 'COMPANY'),
(5, 'Kenton Abshire Sr.', '1978-05-07', 'INDIVIDUAL'),
(6, 'Jammie Kerluke', NULL, 'INDIVIDUAL'),
(18, 'Joshua', NULL, 'INDIVIDUAL');

-- --------------------------------------------------------

--
-- Table structure for table `dim_experience_level`
--

CREATE TABLE `dim_experience_level` (
  `PK_Experience_Level_ID` int(11) NOT NULL,
  `Nama_Level_Pengalaman` varchar(50) DEFAULT NULL,
  `Rentang_Tahun` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dim_experience_level`
--

INSERT INTO `dim_experience_level` (`PK_Experience_Level_ID`, `Nama_Level_Pengalaman`, `Rentang_Tahun`) VALUES
(1, 'Junior', '0-1'),
(2, 'Associate', '2-5'),
(3, 'Mid-Level', '6-10'),
(4, 'Senior', '>10');

-- --------------------------------------------------------

--
-- Table structure for table `dim_service`
--

CREATE TABLE `dim_service` (
  `PK_Service_ID` bigint(20) NOT NULL,
  `Service_Name` varchar(150) DEFAULT NULL,
  `Service_Category` varchar(100) DEFAULT NULL,
  `Price_Min` decimal(12,2) DEFAULT NULL,
  `Price_Max` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dim_service`
--

INSERT INTO `dim_service` (`PK_Service_ID`, `Service_Name`, `Service_Category`, `Price_Min`, `Price_Max`) VALUES
(1, 'Statistical Analysis', 'BI & Visualization', 94.00, 365.00),
(2, 'Machine Learning Model', 'Statistical Analysis', 156.00, 408.00),
(3, 'Predictive Forecasting Model', 'Consultation', 176.00, 412.00),
(4, 'Data Mining & Processing', 'Consultation', 92.00, 295.00),
(5, 'Statistical Analysis', 'ML/AI', 94.00, 247.00),
(6, 'Data Mining & Processing', 'Data Engineering', 200.00, 523.00),
(7, 'Data Analysis & Insights', 'Statistical Analysis', 135.00, 496.00),
(8, 'Interactive Dashboard Creation', 'Consultation', 91.00, 244.00),
(9, 'Machine Learning Model', 'Statistical Analysis', 104.00, 200.00),
(10, 'Machine Learning Model', 'Data Engineering', 89.00, 153.00),
(11, 'Statistical Analysis', 'ML/AI', 83.00, 243.00),
(12, 'Interactive Dashboard Creation', 'Data Engineering', 68.00, 362.00),
(13, 'Data Analysis & Insights', 'Statistical Analysis', 84.00, 402.00),
(14, 'Data Analysis & Insights', 'Data Engineering', 69.00, 287.00),
(15, 'Predictive Forecasting Model', 'Consultation', 115.00, 268.00),
(16, 'Business Intelligence Setup', 'BI & Visualization', 177.00, 271.00),
(17, 'ETL Pipeline Development', 'Data Engineering', 131.00, 378.00),
(18, 'SQL Database Optimization', 'BI & Visualization', 184.00, 542.00),
(19, 'Statistical Analysis', 'BI & Visualization', 148.00, 328.00),
(20, 'ETL Pipeline Development', 'Consultation', 74.00, 131.00);

-- --------------------------------------------------------

--
-- Table structure for table `dim_time`
--

CREATE TABLE `dim_time` (
  `PK_Time_ID` int(11) NOT NULL,
  `Full_Date` date DEFAULT NULL,
  `Day` int(11) DEFAULT NULL,
  `Month` int(11) DEFAULT NULL,
  `Month_Name` varchar(20) DEFAULT NULL,
  `Quarter` int(11) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dim_time`
--

INSERT INTO `dim_time` (`PK_Time_ID`, `Full_Date`, `Day`, `Month`, `Month_Name`, `Quarter`, `Year`) VALUES
(1, '2025-11-10', 10, 11, NULL, 4, 2025),
(2, '2025-08-30', 30, 8, NULL, 3, 2025),
(3, '2025-10-23', 23, 10, NULL, 4, 2025),
(4, '2025-08-24', 24, 8, NULL, 3, 2025),
(5, '2025-10-08', 8, 10, NULL, 4, 2025),
(6, '2025-08-15', 15, 8, NULL, 3, 2025),
(7, '2025-11-12', 12, 11, NULL, 4, 2025),
(8, '2025-10-20', 20, 10, NULL, 4, 2025),
(9, '2025-10-13', 13, 10, NULL, 4, 2025),
(10, '2025-09-24', 24, 9, NULL, 3, 2025),
(11, '2025-10-30', 30, 10, NULL, 4, 2025),
(12, '2025-11-07', 7, 11, NULL, 4, 2025),
(13, '2025-09-12', 12, 9, NULL, 3, 2025),
(14, '2025-09-26', 26, 9, NULL, 3, 2025),
(15, '2025-10-07', 7, 10, NULL, 4, 2025),
(16, '2025-10-12', 12, 10, NULL, 4, 2025),
(17, '2025-08-19', 19, 8, NULL, 3, 2025),
(18, '2025-09-02', 2, 9, NULL, 3, 2025),
(19, '2025-08-31', 31, 8, NULL, 3, 2025),
(20, '2025-10-17', 17, 10, NULL, 4, 2025),
(21, '2025-09-05', 5, 9, NULL, 3, 2025),
(22, '2025-11-01', 1, 11, NULL, 4, 2025),
(23, '2025-10-04', 4, 10, NULL, 4, 2025),
(24, '2025-09-16', 16, 9, NULL, 3, 2025),
(25, '2025-09-17', 17, 9, NULL, 3, 2025),
(26, '2025-11-02', 2, 11, NULL, 4, 2025),
(27, '2025-11-13', 13, 11, NULL, 4, 2025),
(28, '2025-08-27', 27, 8, NULL, 3, 2025),
(29, '2025-12-15', 15, 12, NULL, 4, 2025),
(30, '2025-09-28', 28, 9, NULL, 3, 2025),
(31, '2025-09-23', 23, 9, NULL, 3, 2025),
(32, '2025-09-18', 18, 9, NULL, 3, 2025),
(33, '2025-10-10', 10, 10, NULL, 4, 2025),
(34, '2025-10-19', 19, 10, NULL, 4, 2025),
(35, '2025-10-18', 18, 10, NULL, 4, 2025),
(36, '2025-11-06', 6, 11, NULL, 4, 2025),
(37, '2025-09-19', 19, 9, NULL, 3, 2025);

-- --------------------------------------------------------

--
-- Table structure for table `fact_experience_performance`
--

CREATE TABLE `fact_experience_performance` (
  `FK_Time_ID` int(11) DEFAULT NULL,
  `FK_Service_ID` bigint(20) DEFAULT NULL,
  `FK_Experience_Level_ID` int(11) DEFAULT NULL,
  `Total_Pendapatan` decimal(15,2) DEFAULT NULL,
  `Total_Jumlah_Order` int(11) DEFAULT NULL,
  `Rata_Rata_Rating` decimal(5,2) DEFAULT NULL,
  `Rata_Rata_Penyelesaian_Order` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fact_experience_performance`
--

INSERT INTO `fact_experience_performance` (`FK_Time_ID`, `FK_Service_ID`, `FK_Experience_Level_ID`, `Total_Pendapatan`, `Total_Jumlah_Order`, `Rata_Rata_Rating`, `Rata_Rata_Penyelesaian_Order`) VALUES
(7, 3, 2, 366.22, 1, NULL, NULL),
(9, 5, 3, 218.40, 1, 3.00, NULL),
(12, 6, 2, 300.87, 1, 3.00, NULL),
(14, 7, 2, 493.58, 1, NULL, NULL),
(18, 10, 3, 123.73, 1, 3.00, NULL),
(5, 16, 2, 259.07, 1, NULL, NULL),
(4, 16, 2, 231.70, 1, NULL, NULL),
(29, 11, 3, 100.00, 1, 5.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fact_segment_revenue`
--

CREATE TABLE `fact_segment_revenue` (
  `FK_Time_ID` int(11) DEFAULT NULL,
  `FK_Client_ID` bigint(20) DEFAULT NULL,
  `FK_Age_Group_ID` int(11) DEFAULT NULL,
  `Client_Type` enum('INDIVIDUAL','COMPANY') DEFAULT NULL,
  `Total_Spending` decimal(15,2) DEFAULT NULL,
  `Total_Jumlah_Order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fact_segment_revenue`
--

INSERT INTO `fact_segment_revenue` (`FK_Time_ID`, `FK_Client_ID`, `FK_Age_Group_ID`, `Client_Type`, `Total_Spending`, `Total_Jumlah_Order`) VALUES
(7, 2, 3, 'COMPANY', 366.22, 1),
(9, 5, 4, 'INDIVIDUAL', 218.40, 1),
(12, 2, 3, 'COMPANY', 300.87, 1),
(14, 2, 3, 'COMPANY', 493.58, 1),
(18, NULL, 5, NULL, 123.73, 1),
(5, 5, 4, 'INDIVIDUAL', 259.07, 1),
(4, NULL, 5, NULL, 231.70, 1),
(29, NULL, NULL, NULL, 100.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fact_services_sales`
--

CREATE TABLE `fact_services_sales` (
  `FK_Time_ID` int(11) DEFAULT NULL,
  `FK_Client_ID` bigint(20) DEFAULT NULL,
  `FK_Service_ID` bigint(20) DEFAULT NULL,
  `Order_Count` int(11) DEFAULT NULL,
  `Total_Revenue` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fact_services_sales`
--

INSERT INTO `fact_services_sales` (`FK_Time_ID`, `FK_Client_ID`, `FK_Service_ID`, `Order_Count`, `Total_Revenue`) VALUES
(7, 2, 3, 1, NULL),
(9, 5, 5, 1, NULL),
(12, 2, 6, 1, NULL),
(14, 2, 7, 1, NULL),
(18, 4, 10, 1, NULL),
(5, 5, 16, 1, NULL),
(4, 6, 16, 1, NULL),
(29, 18, 11, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dim_age_group`
--
ALTER TABLE `dim_age_group`
  ADD PRIMARY KEY (`PK_Age_Group_ID`);

--
-- Indexes for table `dim_client`
--
ALTER TABLE `dim_client`
  ADD PRIMARY KEY (`PK_Client_ID`);

--
-- Indexes for table `dim_experience_level`
--
ALTER TABLE `dim_experience_level`
  ADD PRIMARY KEY (`PK_Experience_Level_ID`);

--
-- Indexes for table `dim_service`
--
ALTER TABLE `dim_service`
  ADD PRIMARY KEY (`PK_Service_ID`);

--
-- Indexes for table `dim_time`
--
ALTER TABLE `dim_time`
  ADD PRIMARY KEY (`PK_Time_ID`),
  ADD UNIQUE KEY `Full_Date` (`Full_Date`);

--
-- Indexes for table `fact_experience_performance`
--
ALTER TABLE `fact_experience_performance`
  ADD KEY `FK_Time_ID` (`FK_Time_ID`),
  ADD KEY `FK_Service_ID` (`FK_Service_ID`),
  ADD KEY `FK_Experience_Level_ID` (`FK_Experience_Level_ID`);

--
-- Indexes for table `fact_segment_revenue`
--
ALTER TABLE `fact_segment_revenue`
  ADD KEY `FK_Time_ID` (`FK_Time_ID`),
  ADD KEY `FK_Client_ID` (`FK_Client_ID`),
  ADD KEY `FK_Age_Group_ID` (`FK_Age_Group_ID`);

--
-- Indexes for table `fact_services_sales`
--
ALTER TABLE `fact_services_sales`
  ADD KEY `FK_Time_ID` (`FK_Time_ID`),
  ADD KEY `FK_Client_ID` (`FK_Client_ID`),
  ADD KEY `FK_Service_ID` (`FK_Service_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dim_age_group`
--
ALTER TABLE `dim_age_group`
  MODIFY `PK_Age_Group_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `dim_experience_level`
--
ALTER TABLE `dim_experience_level`
  MODIFY `PK_Experience_Level_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dim_time`
--
ALTER TABLE `dim_time`
  MODIFY `PK_Time_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fact_experience_performance`
--
ALTER TABLE `fact_experience_performance`
  ADD CONSTRAINT `fact_experience_performance_ibfk_1` FOREIGN KEY (`FK_Time_ID`) REFERENCES `dim_time` (`PK_Time_ID`),
  ADD CONSTRAINT `fact_experience_performance_ibfk_2` FOREIGN KEY (`FK_Service_ID`) REFERENCES `dim_service` (`PK_Service_ID`),
  ADD CONSTRAINT `fact_experience_performance_ibfk_3` FOREIGN KEY (`FK_Experience_Level_ID`) REFERENCES `dim_experience_level` (`PK_Experience_Level_ID`);

--
-- Constraints for table `fact_segment_revenue`
--
ALTER TABLE `fact_segment_revenue`
  ADD CONSTRAINT `fact_segment_revenue_ibfk_1` FOREIGN KEY (`FK_Time_ID`) REFERENCES `dim_time` (`PK_Time_ID`),
  ADD CONSTRAINT `fact_segment_revenue_ibfk_2` FOREIGN KEY (`FK_Client_ID`) REFERENCES `dim_client` (`PK_Client_ID`),
  ADD CONSTRAINT `fact_segment_revenue_ibfk_3` FOREIGN KEY (`FK_Age_Group_ID`) REFERENCES `dim_age_group` (`PK_Age_Group_ID`);

--
-- Constraints for table `fact_services_sales`
--
ALTER TABLE `fact_services_sales`
  ADD CONSTRAINT `fact_services_sales_ibfk_1` FOREIGN KEY (`FK_Time_ID`) REFERENCES `dim_time` (`PK_Time_ID`),
  ADD CONSTRAINT `fact_services_sales_ibfk_2` FOREIGN KEY (`FK_Client_ID`) REFERENCES `dim_client` (`PK_Client_ID`),
  ADD CONSTRAINT `fact_services_sales_ibfk_3` FOREIGN KEY (`FK_Service_ID`) REFERENCES `dim_service` (`PK_Service_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
