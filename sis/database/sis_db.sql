SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `department_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `department_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `department_list` (`id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 'CoEng', 'College of Engineering', 1, 0, '2022-01-27 09:22:31', '2022-01-27 09:33:36'),
(2, 'CoAS', 'College of Arts and Science', 1, 0, '2022-01-27 09:22:54', '2022-01-27 09:33:03'),
(3, 'CoB', 'College of Business', 1, 0, '2022-01-27 09:23:20', '2022-01-27 09:33:11'),
(4, 'CoE', 'College of Education', 1, 0, '2022-01-27 09:25:42', '2022-01-27 09:33:18'),
(5, 'CSSP', 'College of Social Sciences and Philosophy', 1, 0, '2022-01-27 09:26:35', '2022-01-27 09:33:49'),
(6, 'Sample101', 'Deleted Department', 1, 1, '2022-01-27 09:27:17', '2022-01-27 09:27:28');

-- --------------------------------------------------------
-- Table structure for table `course_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `course_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `department_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `course_list` (`id`, `department_id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 2, 'BSIT', 'Bachelor of Science in Information Technology', 1, 0, '2022-01-27 10:03:25', NULL),
(2, 4, 'BEEd', 'Bachelor of Elementary Education', 1, 0, '2022-01-27 10:06:43', NULL),
(3, 4, 'BSEd', 'Bachelor of Secondary Education', 1, 0, '2022-01-27 10:07:21', NULL),
(4, 4, 'MAEd', 'Master of Arts in Education', 1, 0, '2022-01-27 10:07:52', NULL),
(5, 4, 'PhD Educ', 'Doctor of Philosophy in Education', 1, 0, '2022-01-27 10:08:21', NULL),
(6, 1, 'BSCE', 'Bachelor of Science in Civil Engineering', 1, 0, '2022-01-27 10:08:48', NULL),
(7, 1, 'MSCE', 'Master of Science in Civil Engineering', 1, 0, '2022-01-27 10:09:00', NULL),
(8, 1, 'BS ChE', 'Bachelor of Science in Chemical Engineering', 1, 0, '2022-01-27 10:09:35', NULL),
(9, 1, 'MS ChE', 'Master of Science in Chemical Engineering', 1, 0, '2022-01-27 10:10:16', NULL),
(10, 1, 'DEngg ChE', 'Doctor of Engineering (Chemical Engineering)', 1, 0, '2022-01-27 10:10:39', NULL),
(11, 1, 'BSCS', 'Bachelor of Science in Computer Science', 1, 0, '2022-01-27 10:12:23', NULL),
(12, 1, 'MSCS', 'Master of Science in Computer Science', 1, 0, '2022-01-27 10:12:35', NULL);

-- --------------------------------------------------------
-- Table structure for table `student_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `student_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `roll` varchar(100) NOT NULL,
  `firstname` text NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` text NOT NULL,
  `gender` varchar(100) NOT NULL,
  `contact` text NOT NULL,
  `present_address` text NOT NULL,
  `permanent_address` text NOT NULL,
  `dob` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `student_list` (`id`, `roll`, `firstname`, `middlename`, `lastname`, `gender`, `contact`, `present_address`, `permanent_address`, `dob`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, '231415061007', 'Mark', 'D', 'Cooper', 'Male', '09123456789', 'This my sample present address.', 'This my sample permanent address.', '2007-06-23', 1, 0, '2022-01-27 11:14:07', '2022-01-28 08:50:13');

-- --------------------------------------------------------
-- Table structure for table `academic_history`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `academic_history` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `student_id` int(30) NOT NULL,
  `course_id` int(30) NOT NULL,
  `semester` varchar(200) NOT NULL,
  `year` varchar(200) NOT NULL,
  `school_year` text NOT NULL,
  `status` int(10) NOT NULL DEFAULT 1,
  `end_status` tinyint(3) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `academic_history` (`id`, `student_id`, `course_id`, `semester`, `year`, `school_year`, `status`, `end_status`, `date_created`, `date_updated`) VALUES
(1, 1, 11, 'First Semester', '1st Year', '2018-2019', 1, 1, '2022-01-27 13:02:36', '2022-01-27 13:22:31'),
(2, 1, 11, 'Second Semester', '1st Year', '2018-2019', 2, 1, '2022-01-27 13:22:24', '2022-01-27 13:22:44'),
(3, 1, 11, 'Third Semester', '1st Year', '2018-2019', 2, 1, '2022-01-27 13:23:32', NULL),
(5, 1, 11, 'First Semester', '2nd Year', '2019-2020', 2, 1, '2022-01-27 13:28:01', NULL),
(6, 1, 11, 'Second Semester', '2nd Year', '2019-2020', 2, 1, '2022-01-27 13:28:26', NULL),
(7, 1, 11, 'Third Semester', '2nd Year', '2019-2020', 2, 2, '2022-01-27 13:28:52', NULL);

-- --------------------------------------------------------
-- Table structure for table `system_info`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `system_info` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `meta_field` varchar(255) NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `meta_field_idx` (`meta_field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Student Information System'),
(6, 'short_name', 'SIS - PHP'),
(11, 'logo', 'uploads/logo-1643245863.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1643245863.png');

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `username_idx` (`username`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `status`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', NULL, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatar-1.png?v=1639468007', NULL, 1, 1, '2021-01-20 14:02:37', '2021-12-14 15:47:08'),
(8, 'Claire', NULL, 'Blake', 'cblake', '4744ddea876b11dcb1d169fadf494418', 'uploads/avatar-8.png?v=1643185259', NULL, 2, 1, '2022-01-26 16:20:59', '2022-01-26 16:20:59');

SET FOREIGN_KEY_CHECKS = 1;
