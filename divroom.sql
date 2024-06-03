-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 03, 2024 at 08:58 AM
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
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announceId` int(11) NOT NULL,
  `announceTitle` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `postDate` datetime NOT NULL,
  `man` int(11) NOT NULL,
  `eventList` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `idEmp` int(11) NOT NULL,
  `empName` varchar(255) NOT NULL,
  `departmentName` varchar(255) NOT NULL,
  `no_telp` varchar(255) NOT NULL,
  `credit_score` int(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`idEmp`, `empName`, `departmentName`, `no_telp`, `credit_score`, `status`, `password`) VALUES
(1, 'Richard', 'IT', '081230425719', 50, 'idle', '12345'),
(4, 'Kevin', 'IT', '082223338833', 0, 'idle', '12345'),
(5, 'Kgei', 'IT', '123123123123', 0, 'idle', 'as');

-- --------------------------------------------------------

--
-- Table structure for table `employeeProject`
--

CREATE TABLE `employeeProject` (
  `idEmp` int(11) NOT NULL,
  `idProject` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `eventId` int(11) NOT NULL,
  `eventTitle` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `idManager` int(11) NOT NULL,
  `managerName` varchar(255) NOT NULL,
  `departmentName` varchar(255) NOT NULL,
  `no_telp` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`idManager`, `managerName`, `departmentName`, `no_telp`, `status`, `password`) VALUES
(1, 'Warren', 'IT', '039829473489', 'onDuty', '12345'),
(6, 'James', 'IT', '1231234134', 'onDuty', 'as');

-- --------------------------------------------------------

--
-- Table structure for table `permit`
--

CREATE TABLE `permit` (
  `permitId` int(11) NOT NULL,
  `permitTitle` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `man` int(255) NOT NULL,
  `empList` int(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `idProject` int(11) NOT NULL,
  `startDate` varchar(255) NOT NULL,
  `Deadline` varchar(255) NOT NULL,
  `projectName` varchar(255) NOT NULL,
  `progressBar` int(255) NOT NULL,
  `man` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `idTask` int(11) NOT NULL,
  `taskName` varchar(255) NOT NULL,
  `taskDescription` varchar(255) NOT NULL,
  `progressTask` int(11) NOT NULL,
  `idProject` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announceId`),
  ADD KEY `manager_fk_anc` (`man`),
  ADD KEY `event_fk_anc` (`eventList`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`idEmp`);

--
-- Indexes for table `employeeProject`
--
ALTER TABLE `employeeProject`
  ADD KEY `employeeProject_fk` (`idEmp`),
  ADD KEY `project_fk` (`idProject`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventId`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`idManager`);

--
-- Indexes for table `permit`
--
ALTER TABLE `permit`
  ADD PRIMARY KEY (`permitId`),
  ADD KEY `employee_fk_permit` (`empList`),
  ADD KEY `manager_fk_permit` (`man`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`idProject`),
  ADD KEY `man_fk_project` (`man`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`idTask`),
  ADD KEY `task_idproject_fk` (`idProject`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announceId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `idEmp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `eventId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `idManager` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permit`
--
ALTER TABLE `permit`
  MODIFY `permitId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `idProject` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `idTask` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `event_fk_anc` FOREIGN KEY (`eventList`) REFERENCES `event` (`eventId`),
  ADD CONSTRAINT `manager_fk_anc` FOREIGN KEY (`man`) REFERENCES `manager` (`idManager`);

--
-- Constraints for table `employeeProject`
--
ALTER TABLE `employeeProject`
  ADD CONSTRAINT `employeeProject_fk` FOREIGN KEY (`idEmp`) REFERENCES `employee` (`idEmp`),
  ADD CONSTRAINT `project_fk` FOREIGN KEY (`idProject`) REFERENCES `project` (`idProject`);

--
-- Constraints for table `permit`
--
ALTER TABLE `permit`
  ADD CONSTRAINT `employee_fk_permit` FOREIGN KEY (`empList`) REFERENCES `employee` (`idEmp`),
  ADD CONSTRAINT `manager_fk_permit` FOREIGN KEY (`man`) REFERENCES `manager` (`idManager`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `man_fk_project` FOREIGN KEY (`man`) REFERENCES `manager` (`idManager`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_idproject_fk` FOREIGN KEY (`idProject`) REFERENCES `project` (`idProject`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
