-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 02, 2024 at 05:22 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `divroom`
--

-- --------------------------------------------------------

--
-- Table structure for table `Announcement`
--

CREATE TABLE `Announcement` (
  `announceId` int(11) NOT NULL,
  `announceTitle` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `postDate` datetime NOT NULL,
  `man` int(11) NOT NULL,
  `eventList` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Employee`
--

CREATE TABLE `Employee` (
  `idEmp` int(11) NOT NULL,
  `empName` varchar(255) NOT NULL,
  `departmentName` varchar(255) NOT NULL,
  `no_telp` int(255) NOT NULL,
  `credit_score` int(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Event`
--

CREATE TABLE `Event` (
  `eventId` int(11) NOT NULL,
  `eventTitle` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Manager`
--

CREATE TABLE `Manager` (
  `idManager` int(11) NOT NULL,
  `managerName` varchar(255) NOT NULL,
  `departmentName` varchar(255) NOT NULL,
  `no_telp` int(255) NOT NULL,
  `empList` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Permit`
--

CREATE TABLE `Permit` (
  `permitId` int(11) NOT NULL,
  `permitTitle` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `man` int(255) NOT NULL,
  `empList` int(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Project`
--

CREATE TABLE `Project` (
  `idProject` int(11) NOT NULL,
  `startDate` datetime NOT NULL,
  `Deadline` datetime NOT NULL,
  `projectDetail` varchar(255) NOT NULL,
  `progressBar` int(255) NOT NULL,
  `empList` int(11) NOT NULL,
  `man` int(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Announcement`
--
ALTER TABLE `Announcement`
  ADD PRIMARY KEY (`announceId`),
  ADD KEY `manager_fk_anc` (`man`),
  ADD KEY `event_fk_anc` (`eventList`);

--
-- Indexes for table `Employee`
--
ALTER TABLE `Employee`
  ADD PRIMARY KEY (`idEmp`);

--
-- Indexes for table `Event`
--
ALTER TABLE `Event`
  ADD PRIMARY KEY (`eventId`);

--
-- Indexes for table `Manager`
--
ALTER TABLE `Manager`
  ADD PRIMARY KEY (`idManager`),
  ADD KEY `employee_fk_manager` (`empList`);

--
-- Indexes for table `Permit`
--
ALTER TABLE `Permit`
  ADD PRIMARY KEY (`permitId`),
  ADD KEY `employee_fk_permit` (`empList`),
  ADD KEY `manager_fk_permit` (`man`);

--
-- Indexes for table `Project`
--
ALTER TABLE `Project`
  ADD PRIMARY KEY (`idProject`),
  ADD KEY `employee_fk_project` (`empList`),
  ADD KEY `manager_fk_project` (`man`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Announcement`
--
ALTER TABLE `Announcement`
  MODIFY `announceId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Employee`
--
ALTER TABLE `Employee`
  MODIFY `idEmp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Event`
--
ALTER TABLE `Event`
  MODIFY `eventId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Manager`
--
ALTER TABLE `Manager`
  MODIFY `idManager` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Permit`
--
ALTER TABLE `Permit`
  MODIFY `permitId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Project`
--
ALTER TABLE `Project`
  MODIFY `idProject` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Announcement`
--
ALTER TABLE `Announcement`
  ADD CONSTRAINT `event_fk_anc` FOREIGN KEY (`eventList`) REFERENCES `Event` (`eventId`),
  ADD CONSTRAINT `manager_fk_anc` FOREIGN KEY (`man`) REFERENCES `Manager` (`idManager`);

--
-- Constraints for table `Manager`
--
ALTER TABLE `Manager`
  ADD CONSTRAINT `employee_fk_manager` FOREIGN KEY (`empList`) REFERENCES `Employee` (`idEmp`);

--
-- Constraints for table `Permit`
--
ALTER TABLE `Permit`
  ADD CONSTRAINT `employee_fk_permit` FOREIGN KEY (`empList`) REFERENCES `Employee` (`idEmp`),
  ADD CONSTRAINT `manager_fk_permit` FOREIGN KEY (`man`) REFERENCES `Manager` (`idManager`);

--
-- Constraints for table `Project`
--
ALTER TABLE `Project`
  ADD CONSTRAINT `employee_fk_project` FOREIGN KEY (`empList`) REFERENCES `Employee` (`idEmp`),
  ADD CONSTRAINT `manager_fk_project` FOREIGN KEY (`man`) REFERENCES `Manager` (`idManager`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
