-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 12, 2026 at 09:52 PM
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
-- Database: `yic_course_feedback`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `major` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `major`, `description`) VALUES
(1, 'CS381', 'Web Application Development', 'CS', 'Covers frontend and backend web development concepts with practical application design.'),
(2, 'CS201', 'Programming Fundamentals', 'CS', 'Introduces basic programming concepts, problem solving, and coding logic.'),
(3, 'CS250', 'Database Systems', 'CS', 'Covers database concepts, SQL, tables, relationships, and data management.'),
(4, 'MIS210', 'Systems Analysis', 'MIS', 'Focuses on requirements gathering, system analysis methods, and planning fundamentals.'),
(5, 'MIS330', 'E-Business Systems', 'MIS', 'Introduces online business systems, digital services, and information systems usage.'),
(6, 'HR201', 'Human Resources Management', 'HR', 'Introduces employee management, organizational behavior, and basic HR processes.'),
(7, 'HR310', 'Training and Development', 'HR', 'Covers employee training methods, development plans, and performance improvement.'),
(8, 'CE220', 'Engineering Materials', 'CE', 'Covers construction materials, material properties, and engineering applications.'),
(9, 'ACC101', 'Principles of Accounting', 'Accounting', 'Introduces accounting concepts, recording transactions, and financial statements.'),
(10, 'ACC210', 'Financial Accounting', 'Accounting', 'Covers financial reports, accounting cycles, and financial statement preparation.');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `course_id`, `rating`, `comment`) VALUES
(1, 2, 1, 4, 'Very useful course with clear explanations.'),
(2, 3, 1, 5, 'The course is practical and helpful.'),
(3, 4, 2, 4, 'Good introduction to programming basics.'),
(4, 5, 3, 3, 'The database topics need more examples.'),
(5, 6, 4, 3, 'Systems analysis is useful but needs more practice.'),
(6, 7, 5, 4, 'The course explains e-business ideas clearly.'),
(7, 8, 6, 2, 'The content needs more organization.'),
(8, 9, 7, 3, 'Training topics are good but a little long.'),
(9, 10, 8, 4, 'Engineering materials examples are helpful.'),
(10, 2, 9, 4, 'Accounting concepts are explained well.'),
(13, 12, 1, 5, 'The best course with Dr,mai'),
(15, 13, 2, 3, 'good'),
(16, 13, 10, 5, 'goooood'),
(17, 12, 2, 5, 'easy course');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role`) VALUES
(1, 'Admin User', 'admin', '12345678', 'admin'),
(2, 'Layan Ahmed', 'layan01', 'password01', 'student'),
(3, 'Sara Ali', 'sara02', 'password02', 'student'),
(4, 'Amal Khalid', 'amal03', 'password03', 'student'),
(5, 'Nora Salem', 'nora04', 'password04', 'student'),
(6, 'Reem Hassan', 'reem05', 'password05', 'student'),
(7, 'Abeer Mohammed', 'abeer06', 'password06', 'student'),
(8, 'Raghad Adel', 'raghad07', 'password07', 'student'),
(9, 'Haneen Omar', 'haneen08', 'password08', 'student'),
(10, 'Maha Ibrahim', 'maha09', 'password09', 'student'),
(11, 'JANA', 'jana3232', '123456789', 'student'),
(12, 'arwa hussein', 'arwax3', 'Ar12ar12', 'student'),
(13, 'Layan', 'layan', '12341234', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
