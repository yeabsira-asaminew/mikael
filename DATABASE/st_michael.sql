-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2025 at 02:31 PM
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
-- Database: `senbet_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `apostolic_category`
--

CREATE TABLE `apostolic_category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apostolic_category`
--

INSERT INTO `apostolic_category` (`id`, `name`) VALUES
(1, 'ሐዋርያው ቅዱስ ጴጥሮስ '),
(2, 'ሐዋርያው ቅዱስ እንድርያስ'),
(3, 'ሐዋርያው ቅዱስ ያዕቆብ ወልደ ዘብዲዎስ'),
(4, 'ሐዋርያው ቅዱስ ዮሐንስ'),
(5, 'ሐዋርያው ቅዱስ ፊልጶስ'),
(6, 'ሐዋርያው ቅዱስ በርተሎሜዎስ'),
(7, 'ሐዋርያው ቅዱስ ቶማስ'),
(8, 'ሐዋርያው ቅዱስ ማቴዎስ'),
(9, 'ሐዋርያው ቅዱስ ያዕቆብ ወልደ እልፍዮስ'),
(10, 'ሐዋርያው ቅዱስ ታዴዎስ'),
(11, 'ሐዋርያው ቅዱስ  ስምዖን ቀነናዊው'),
(12, 'ሐዋርያው ቅዱስ ማቲያስ');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `status` enum('present','absent') NOT NULL,
  `created_date` date NOT NULL DEFAULT current_timestamp(),
  `created_time` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE `auth` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','superadmin','','') NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth`
--

INSERT INTO `auth` (`id`, `email`, `password`, `role`, `last_login`, `created_at`) VALUES
(1, 'superadmin@gmail.com', '$2y$10$SnyWAhfi9Hzg/xnNgj/9HuJjk6rHlq0BRU/4w6AYtih57DNmhdbtO', 'superadmin', NULL, '2025-06-17 14:28:26');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'በእድሜ'),
(2, 'በስርአተ ትምህርት '),
(3, 'በአገልግሎት '),
(4, 'መዘምራን በእድሜ');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `day` varchar(20) DEFAULT NULL,
  `time` time NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) DEFAULT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `mother_name` varchar(255) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `dob` date NOT NULL,
  `pob` varchar(255) DEFAULT NULL,
  `christian_name` varchar(255) NOT NULL,
  `God_father` varchar(255) DEFAULT NULL,
  `repentance_father` varchar(255) DEFAULT NULL,
  `repentance_father_church` varchar(255) DEFAULT NULL,
  `phone1` varchar(20) NOT NULL,
  `phone2` varchar(20) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `apostolic_id` int(11) DEFAULT NULL,
  `age_category_id` int(11) DEFAULT NULL,
  `curriculum_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `choir_id` int(11) DEFAULT NULL,
  `occupation` enum('ተማሪ','ሰራተኛ','ተማሪ እና ሰራተኛ','ስራ ፈላጊ') DEFAULT NULL,
  `education_level` enum('ኬጂ፣ ፕሪፕ','መጀመሪያ ደረጃ','ሁለተኛ ደረጃ','መሰናዶ','ቴክኒክ እና ሙያ(ሌቭል)','ዲፕሎማ','የመውጫ ፈተና የወደቀ','የመጀመሪያ ዲግሪ','ማስተርስ','ፒ.ኤች.ዲ') DEFAULT NULL,
  `academic_field` varchar(255) DEFAULT NULL,
  `workplace` varchar(255) DEFAULT NULL,
  `registration_date` date NOT NULL DEFAULT current_timestamp(),
  `photo` text DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `last_attendance_date` date DEFAULT NULL,
  `last_attendance_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`id`, `name`, `category_id`) VALUES
(1, 'ደቂቅ ህጻናት ', 1),
(2, 'ቂርቆስ ህጻናት', 1),
(3, 'ማዕከላዊያን ', 1),
(4, 'አዳጊ ወጣቶች ', 1),
(5, 'ወጣቶች ', 1),
(6, 'ነባር አባል', 1),
(7, 'ወጣቶች ስርአተ ትምህርት', 2),
(8, '1ኛ', 2),
(9, '2ኛ', 2),
(10, '3ኛ', 2),
(11, '2ኛ', 2),
(12, '5ኛ', 2),
(13, '6ኛ', 2),
(14, '7ኛ', 2),
(15, '8ኛ', 2),
(16, '9ኛ', 2),
(17, '10ኛ', 2),
(18, '11ኛ', 2),
(19, '12ኛ', 2),
(20, 'የሰንበት ትምህርት ቤቱ ጽ/ቤት', 3),
(21, 'ህፃናት እና አዳጊ ወጣቶች', 3),
(22, 'አባላት ጉዳይ', 3),
(23, 'ትምህርት', 3),
(24, 'ስነ ጥበብ', 3),
(25, 'ልማት እና በጎ አድራጎት', 3),
(26, 'ስልጠና እና እቅድ ክትትል', 3),
(27, 'መረጃ እና መዛግብት', 3),
(28, 'ገንዘብ እና ንብረት', 3),
(29, 'ህፃናት መዘምራን', 4),
(30, 'ማእከላዊያን መዘምራን', 4),
(31, 'አዳጊ ወጣቶች መዘምራን', 4),
(32, 'ወጣት መዘምራን', 4),
(33, 'የክብር መዘምራን', 4);

-- --------------------------------------------------------

--
-- Table structure for table `sub_category_schedule`
--

CREATE TABLE `sub_category_schedule` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apostolic_category`
--
ALTER TABLE `apostolic_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student_id` (`student_id`);

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `fk_apostolic_id` (`apostolic_id`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category_id` (`category_id`);

--
-- Indexes for table `sub_category_schedule`
--
ALTER TABLE `sub_category_schedule`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apostolic_category`
--
ALTER TABLE `apostolic_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth`
--
ALTER TABLE `auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `sub_category_schedule`
--
ALTER TABLE `sub_category_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_apostolic_id` FOREIGN KEY (`apostolic_id`) REFERENCES `apostolic_category` (`id`);

--
-- Constraints for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD CONSTRAINT `fk_category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
