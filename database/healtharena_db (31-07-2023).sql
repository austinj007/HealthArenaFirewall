-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2023 at 06:04 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `healtharena_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `designation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`, `name`, `designation`) VALUES
('admin101', 'healtharenaservices@gmail.com', 'ee660b13c36f03650d673b30dd69e940206ecfae', 'S. Rojer', 'Firewall Administrator'),
('admin111', 'healtharenaservices@gmail.com', '567f9242fb1dbb3269b5b87247cdc8f1dd1c8984', 'Rojer Billy', 'Firewall Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appo_id` varchar(20) NOT NULL,
  `patient_id` varchar(20) NOT NULL,
  `doc_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `slot` int(11) NOT NULL,
  `patient_desc` varchar(1000) NOT NULL,
  `prescription` varchar(1000) NOT NULL,
  `latest_report` varchar(500) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appo_id`, `patient_id`, `doc_id`, `date`, `slot`, `patient_desc`, `prescription`, `latest_report`, `status`, `datetime`) VALUES
('apt07272023023635', 'janes@1', 'D013', '2023-07-15', 1, 'Test zip-pwd', 'Test zip-pwd presc', 'patient-report-uploads/report-janes@1-apt07272023023635.zip', 1, '2023-07-27 18:09:12'),
('apt07272023032650', 'janes@1', 'D007', '2023-07-09', 5, 'asd', 'asd', 'patient-report-uploads/report-janes@1-apt07272023032650.zip', 1, '2023-07-27 18:56:50'),
('apt07282023114357', 'janes@1', 'D004', '2023-07-27', 1, 'test upload', 'test upload11', 'patient-report-uploads/report-janes@1-apt07282023114357.zip', 1, '2023-07-28 15:13:57'),
('apt07282023115759', 'janes@1', 'D020', '2023-07-20', 1, 'asd', 'asd', 'patient-report-uploads/report-janes@1-apt07282023115759.zip', 1, '2023-07-28 15:27:59'),
('apt07282023122726', 'janes@1', 'D014', '2023-07-06', 1, 'mnvhjv', 'ghfgh', 'patient-report-uploads/report-janes@1-apt07282023122726.zip', 1, '2023-07-28 15:57:26');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `reply_status` tinyint(4) NOT NULL DEFAULT 0,
  `datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `message`, `reply_status`, `datetime`) VALUES
(5, 'Test Contact', 'websiteassignment6@gmail.com', 'test message !!!', 0, '2023-07-31 18:30:41'),
(6, 'Kelly Johnes', 'healtharenaservices@gmail.com\n', 'Test msg1', 1, '2023-07-31 18:30:41'),
(7, 'Website Assignment', 'websiteassignment6@gmail.com', 'test contact2 ', 0, '2023-07-31 18:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `current_admin_sessions`
--

CREATE TABLE `current_admin_sessions` (
  `session_id` varchar(100) NOT NULL,
  `admin_id` varchar(20) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT NULL,
  `ip` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `current_admin_sessions`
--

INSERT INTO `current_admin_sessions` (`session_id`, `admin_id`, `login_time`, `logout_time`, `ip`) VALUES
('5kju4de09em60h97silsdq6lck', 'admin111', '2023-07-31 14:57:18', '2023-07-31 15:33:44', '::1'),
('9ircs9uvhfac0g8afen69hku5l', 'admin111', '2023-07-31 18:17:09', '2023-07-31 18:17:18', '::1'),
('f6ltr7e5nvl56sedjjil6vcklb', 'admin111', '2023-07-31 20:08:37', '2023-07-31 21:33:46', '::1'),
('gqh6vhh20pr726hkbs8jns4dcm', 'admin101', '2023-07-31 18:22:13', '2023-07-31 18:22:32', '::1'),
('tcptgq2plvau0e8sd4srjnpnha', 'admin111', '2023-07-31 18:17:45', '2023-07-31 18:55:19', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `current_sessions`
--

CREATE TABLE `current_sessions` (
  `session_id` varchar(100) NOT NULL,
  `patient_id` varchar(20) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT NULL,
  `ip` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `current_sessions`
--

INSERT INTO `current_sessions` (`session_id`, `patient_id`, `login_time`, `logout_time`, `ip`) VALUES
('92sdf7sj1h86dqo2f6khoha4f0', 'janes@1', '2023-07-27 18:45:25', '2023-07-27 18:45:31', '::1'),
('acgnoukntoi3nmvgf2r39de3n1', 'janes@1', '2023-07-27 18:38:00', '2023-07-27 18:43:13', '::1'),
('bs5ttcmecom41j6324qlltr37a', 'janes@1', '2023-07-28 15:13:34', '2023-07-28 15:26:54', '::1'),
('dsepov86hcatkgscvlg3gpa90l', 'janes@1', '2023-07-28 15:56:25', '2023-07-28 15:58:33', '::1'),
('gdd1ojb86ugjs4cs2lm1l3n3gs', 'janes@1', '2023-07-31 20:15:51', '2023-07-31 20:17:51', '::1'),
('jjeci39m5t6ua0079mr50bjegd', 'janes@1', '2023-07-27 18:43:46', '2023-07-27 18:43:49', '::1'),
('mdkg2v0mg5j96sli7q1ll09tco', 'webs@1', '2023-07-10 18:26:19', '2023-07-10 18:27:54', '::1'),
('mt6mp6l8hcsth7f9dfa58774lp', 'janes@1', '2023-07-27 12:44:45', '2023-07-27 12:44:51', '::1'),
('q1coi495hflp0roa7u9tkr8rvh', 'webs@1', '2023-07-10 18:24:00', '2023-07-10 18:24:05', '::1'),
('urtbfm8fn2i50r03l00s8vf19t', 'janes@1', '2023-07-27 18:46:40', '2023-07-27 18:58:40', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `password` varchar(20) NOT NULL,
  `field` varchar(30) NOT NULL,
  `fees` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `name`, `phone`, `password`, `field`, `fees`, `email`) VALUES
('D001', 'John Doe', '1234567890', '', 'Heart Specialist', 150, 'johndoe@healtharena.com'),
('D002', 'Alice Smith', '9876543210', '', 'Lungs Specialist', 200, 'alicesmith@healtharena.com'),
('D003', 'Michael Johnson', '7890123456', '', 'Skin Specialist', 180, 'michaeljohnson@healtharena.com'),
('D004', 'Emily Brown', '4567890123', '', 'Eyes Specialist', 170, 'emilybrown@healtharena.com'),
('D005', 'David Lee', '2345678901', '', 'Bones Specialist', 190, 'davidlee@healtharena.com'),
('D006', 'Sarah Miller', '6789012345', '', 'Surgery Specialist', 250, 'sarahmiller@healtharena.com'),
('D007', 'Robert Wilson', '3456789012', '', 'General', 120, 'robertwilson@healtharena.com'),
('D008', 'Jennifer Davis', '8901234567', '', 'General', 120, 'jenniferdavis@healtharena.com'),
('D009', 'William Taylor', '5678901234', '', 'General', 120, 'williamtaylor@healtharena.com'),
('D010', 'Linda Anderson', '9012345678', '', 'General', 120, 'lindaanderson@healtharena.com'),
('D011', 'John Smith', '3456789012', '', 'Heart Specialist', 180, 'johnsmith@healtharena.com'),
('D012', 'Sarah Johnson', '8901234567', '', 'Lungs Specialist', 200, 'sarahjohnson@healtharena.com'),
('D013', 'David Williams', '5678901234', '', 'Skin Specialist', 160, 'davidwilliams@healtharena.com'),
('D014', 'Jessica Martinez', '9012345678', '', 'Eyes Specialist', 170, 'jessicamartinez@healtharena.com'),
('D015', 'Christopher Lee', '2345678901', '', 'Bones Specialist', 190, 'christopherlee@healtharena.com'),
('D016', 'Elizabeth Brown', '6789012345', '', 'Surgery Specialist', 220, 'elizabethbrown@healtharena.com'),
('D017', 'William Wilson', '1234509876', '', 'Heart Specialist', 200, 'williamwilson@healtharena.com'),
('D018', 'Mia Davis', '9087654321', '', 'Lungs Specialist', 240, 'miadavis@healtharena.com'),
('D019', 'Michael Taylor', '5432109876', '', 'Skin Specialist', 150, 'michaeltaylor@healtharena.com'),
('D020', 'Sophia Anderson', '8765432109', '', 'Eyes Specialist', 210, 'sophiaanderson@healtharena.com');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `email` varchar(30) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `medical_history` varchar(1000) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `status` varchar(10) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `name`, `password`, `phone`, `email`, `dob`, `gender`, `height`, `weight`, `medical_history`, `created_at`, `login_attempts`, `status`) VALUES
('janes@1', 'Jane S Doe', '$2y$10$tyaQXaCYnq83DzO/ORXiqenRuA9NEGh6K2NNi6ALefY/hiaT8WYtK', '1231231231', 'thetechnologyblues@gmail.com', '2003-06-26', 'Female', 120, 55, 'none', '2023-07-05 18:23:02', 0, 'Active'),
('john@1', 'John Doe', '$2y$10$XymYr.2dqmRtQh3.wFG8MujFzPPsr4KNqpqpdwRKLCG/lCwZPmU8u', '1004054311', 'healtharenaservices@gmail.com', '2023-07-20', 'Male', 150, 75, 'Basic Skin issues', '2023-07-05 17:32:14', 0, 'Active'),
('webs@1', 'Kelly Cox', '$2y$10$PTB4gA.GvaxZcfNlcrWePOnMzjXE.q0sm8XAO5lRbHVmuRI/cIVhK', '1231231231', 'thetechnologyblues@gmail.com', '2000-06-28', 'Male', 123, 34, 'none', '2023-07-10 17:46:36', 0, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `security_config`
--

CREATE TABLE `security_config` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `security_config`
--

INSERT INTO `security_config` (`id`, `type`, `value`, `last_updated`, `updated_by`) VALUES
(5, 'admin_session_time', '30', '2023-07-31 16:00:31', 'admin111'),
(6, 'patient_session_time', '15', '2023-07-31 16:00:31', 'admin101'),
(7, 'max_user_load', '100', '2023-07-31 16:00:31', 'admin101'),
(8, 'max_login_failure', '5', '2023-07-31 16:00:53', 'admin111');

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `slot_id` int(11) NOT NULL,
  `slot_time` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`slot_id`, `slot_time`) VALUES
(1, '10 AM'),
(2, '11 AM'),
(3, '12 PM'),
(4, '01 PM'),
(5, '02 PM'),
(6, '03 PM');

-- --------------------------------------------------------

--
-- Table structure for table `threats`
--

CREATE TABLE `threats` (
  `id` int(11) NOT NULL,
  `patient_id` varchar(20) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appo_id`),
  ADD KEY `doc_fk` (`doc_id`),
  ADD KEY `patient_fk` (`patient_id`),
  ADD KEY `slot_fk` (`slot`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `current_admin_sessions`
--
ALTER TABLE `current_admin_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `csafk` (`admin_id`);

--
-- Indexes for table `current_sessions`
--
ALTER TABLE `current_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `cspfk` (`patient_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security_config`
--
ALTER TABLE `security_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`slot_id`);

--
-- Indexes for table `threats`
--
ALTER TABLE `threats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thpfk` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `security_config`
--
ALTER TABLE `security_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `threats`
--
ALTER TABLE `threats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `doc_fk` FOREIGN KEY (`doc_id`) REFERENCES `doctor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `slot_fk` FOREIGN KEY (`slot`) REFERENCES `slots` (`slot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `current_admin_sessions`
--
ALTER TABLE `current_admin_sessions`
  ADD CONSTRAINT `csafk` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `current_sessions`
--
ALTER TABLE `current_sessions`
  ADD CONSTRAINT `cspfk` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `security_config`
--
ALTER TABLE `security_config`
  ADD CONSTRAINT `security_config_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `threats`
--
ALTER TABLE `threats`
  ADD CONSTRAINT `thpfk` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
