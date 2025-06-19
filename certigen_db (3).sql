-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 06:09 AM
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
-- Database: `certigen_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `status` enum('active','revoked') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `api_key`, `status`) VALUES
(1, 'e7f39a6b2c81f00d62a5b17c9a933e0e', 'active'),
(2, '24776dd94abc8a3136b7bc72d3113c8b', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `action` varchar(50) NOT NULL,
  `user` varchar(100) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `status` enum('SUCCESS','ERROR','WARNING','INFO') NOT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `timestamp`, `action`, `user`, `ip`, `status`, `details`) VALUES
(1, '2025-06-18 12:08:51', 'USER_LOGIN', 'admin@example.com', '192.168.1.100', 'SUCCESS', 'User logged in successfully'),
(2, '2025-06-19 00:08:51', 'FILE_UPLOAD', 'user1@example.com', '192.168.1.101', 'SUCCESS', 'Uploaded file \"report.pdf\"'),
(3, '2025-06-19 06:08:51', 'PERMISSION_DENIED', 'user2@example.com', '192.168.1.102', 'ERROR', 'Access denied to admin dashboard'),
(4, '2025-06-19 09:08:51', 'CONFIG_UPDATE', 'admin@example.com', '192.168.1.100', 'SUCCESS', 'Updated system settings'),
(5, '2025-06-19 11:08:51', 'PASSWORD_CHANGE', 'user1@example.com', '192.168.1.101', 'SUCCESS', 'Password changed successfully'),
(6, '2025-06-19 11:38:51', 'INVALID_REQUEST', 'user3@example.com', '192.168.1.103', 'WARNING', 'Invalid API request format'),
(7, '2025-06-19 11:53:51', 'DATA_EXPORT', 'admin@example.com', '192.168.1.100', 'SUCCESS', 'Exported user data to CSV'),
(8, '2025-06-19 12:03:51', 'LOGIN_ATTEMPT', 'unknown', '192.168.1.105', 'ERROR', 'Failed login attempt with invalid credentials');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `cert_id` varchar(255) NOT NULL,
  `certificate_title` varchar(255) NOT NULL,
  `completion_date` date NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `instructor_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `digital_signature_key` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `cert_id`, `certificate_title`, `completion_date`, `recipient_name`, `instructor_name`, `created_at`, `digital_signature_key`, `user_id`, `username`) VALUES
(18, 'CERT-6853185d653c5', 'Appreciation', '2025-06-18', 'Lyka Sebelina', 'Indaleen Quinsayas', '2025-06-18 19:49:49', 'j7gMYViOnglXiAD5tr8ps8sXOx7YvU9OkJSsw4SG3Ru62CE6pETuSDtpWBozAUwQrpFUU9sqPtFQGKhwlBE6ESCvHg91gP4DUiANSoO1SQXzPb6XKCn5oKfNXDbxFPhPLO6SGBpXq14I8w5IeCoMrZDrLw+B1xQZkfJniqwM5CEXWRIm8CcQ108ykoBcRHotpRtgaQwrtwUcmVfR1mR8ir6IzW2fnUb1pIKd1gU4/WyxMh5LuxXw0zsQ1DLmmJyquT1TmBKgwmRBU/WgcjZBsymVLMykIZh2JDzpVZ7T9EOoyHCIhaJMcZ5LDm6L8W55ypULpCipAyg+iCP9wuvkQg==', 6, 'lykasebelina'),
(19, 'CERT-685321d01f21a', 'Recognition', '2025-06-18', 'Nina Astillero', 'Rowell Marquina', '2025-06-18 20:30:08', 'G4m35xmjcKH/UvllCc4GkfMGbXLzEx7dhbQeqRgV1eI75CgVRyCVtaX0VrD7KD3RM+dL2v2gVH4hwOcdfonIHFXLNGdjvLsq4Rx0P10mB6iEicXgLWaBautcz4EfHZKrA2Fwci3rZTPt5FIqeNnRLVK90w9Ysxs0goK7y/fKvQVilyG1C0VSTzLyu6rMfQYXP/svwPeaarPcsPn3R791trfqpiqNePBNBeEzd1hIKS9TORAp2Hyoi662fjFr52U9jRaK/y1MuXprV6QhugzqJAoZxwF0zoeOcDMpqR0iokaAaXuMKhCADxm2ENtkeghWLy505O2ALQYpFhd+f+OC1A==', 7, 'ninaastillero'),
(20, 'CERT-68534f62c1594', 'Ann', '2025-06-19', 'Nina Astillero', 'Michael Miguel', '2025-06-18 23:44:34', 'VJBdntFpQ6yRkm7vVrfQbPQwNIW2HzR9RV7m8qd60AP8XqlIN0GzmfSkoZOy0j8gxZBqqryM30Qp1/L/wNMRF1EkcJGLq7Q0gB3o8CuT/61Jax2/1blFil5IAiEqrUzGWEQP08Y3zIcuzs29EtHSZLOlBNxfoVSfEuwR8I4E13gJac1wJcFPKlCKx3fKMdTkKiaCoVlRZrZK6t4iJyTAi00rEOrd0+sQQqRnIhg2YWbiv1oyNwD7PEHcXpMBTLmmdKYyud9e58TO1M8FusVF5YZPogXunlVBnSFn4exdw5RKfWn6zKHmoJpi83g+ySf+E1EQN8d47pXNEofO2F4sLw==', 7, 'ninaastillero'),
(23, 'CERT-68536400ad330', 'uiuin', '2025-06-19', 'Nina Astillero', 'Michael Miguel', '2025-06-19 01:12:32', 'Gtoy0F4zkfNRskudLAhZn6vJCrR7VSnXvsVGU7XmMkQXgMWMf4DUrp+UfhFJCghV90XDpi7r5FQH3+1/rZDozTtmHa7PQNOFFtLNWlHAhFtEcteNk5PMscAS6FEuUaCJPu8GP/tDsu64jJoePmJsSuqpdHVYaGv2oVRrPd3EdMCZ/H5COKyYKekUJW/oayLZ+B/F0SPV5k8AmxgarNPwc/UkseEduDh0EEtljhw9MW7WxVNB3blnODrBBGVyOuygpmk1RBTifIH+9//v9S1p/3WwA5Ce3+cZ50oNJr8iCeelCokyKRR18BjnktzdqF2nx0xu5nYbunm808zBruvfiA==', 7, 'ninaastillero'),
(24, 'CERT-685364f3bf989', 'uiuin', '2025-06-19', 'Nina Astillero', 'Michael Miguel', '2025-06-19 01:16:35', 'YEeqD4pZtvHs9my3s0nFHW8SSVvb6RS9pvgxkiSqMvWZgA0hpdBut9d5RfMLvxN9Fqb7LyAgVdl6UQZdTgY8PC0vODHXXi1QlcfhEb443bMvvjgGSEMfSeGbLaBp8KAkD3FqRc9Ls64G+FZS/kn9k8r9olxrgZZd00sLcqxSPdTIx6W2GY/hoYSVDWUY57j2thdxRMbZKPO6nz5C0TEikNGEegqrbDbWQvl9KfOePPSUkaXxD1Hyzy3EZNgHMfqSAsMxxL9paJHQEg5if4hrvpQzhKrVFP8Cejsn1ybUjGXetgf3MuxUei3iCNC+MDc9qJuaulpMyZuzdELuKpoGmw==', 7, 'ninaastillero'),
(25, 'CERT-6853657d1abde', 'uiuin', '2025-06-19', 'Nina Astillero', 'Michael Miguel', '2025-06-19 01:18:53', 'R+D3F0QFpzDoy67Oryj5U4pAHFi4L/tj+AJCGJp+tKdsMtacPy0b2IV+9ERgNWxF/k3oLunBI+tAvk29TbeVe/Djt6cxnbKyhCrFZAppzomhahWsZ5epaB4Qkpv/Fi2pcLJ7sb7TRujNoFsEfuJrUIGQAZEKc5GOjM0ZeRf95vNsm7xLnyRNbjf16eos+Bv2kAAuayBtL1cxy/YPjtKN7XBcIeRMO+ENMPSaRjff/3llobPOV8cPKXq5uxoEokAqt2lnu7VkqaWVhvjRZqNpBkEgTsNlpyvI/GXIrzW8XZ1Wkl0/OwIfIfibTCN8mctLoP7GzNmzvzIjsGXSKKj/VQ==', 7, 'ninaastillero'),
(29, 'CERT-6853865cd8cc9', 'Pkhqu', '2025-06-19', 'Nina Astillero', 'Rowell Marquina', '2025-06-19 03:39:08', 'XqESWfRgZuwwPRtpXHIZCWzfUKDpTPzwQeeyqoegWa8SMk5WodvN9HZfbhhZvh6BEB5U1x5xMLF8SS4fXrz4O8/Kce5Z8zQUwVPbbU+NRanIlaE+fmI88YXE0A2Q8ZFLO3U6ouSCp1G+0tr5mc4GMWcUMnkLnJl7uqubmRjOTgezQlcPw33d+TwEU9OXSAX0txlYL3JiLYxl5kqi677leIyBcBY4euDKTCki4jfrHn5osBcU0LCBz/jlAdx4b3zBXEnzApuqGvtRTTJIMRP4NE9A1RSIO0A3NzJw9yBXrW1R8OQSjqu/y2F+7DCZhwrnOzG0dJ9WAHIVJy7RCOeEcA==', NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `prof_users`
-- (See below for the actual view)
--
CREATE TABLE `prof_users` (
`id` int(11)
,`role_id` int(11)
,`username` varchar(50)
,`name` varchar(100)
,`email` varchar(100)
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Professor'),
(3, 'Student');

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_users`
-- (See below for the actual view)
--
CREATE TABLE `student_users` (
`id` int(11)
,`role_id` int(11)
,`username` varchar(50)
,`name` varchar(100)
,`email` varchar(100)
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `role_id`, `created_at`) VALUES
(4, 'eddelacruz', 'Ed Dela Cruz', 'eddelacruz@gmail.com', 'ed123', 2, '2025-06-18 17:14:02'),
(5, 'michaelmiguel', 'Michael Miguel', 'michaelmiguel@gmail.com', 'migs123', 2, '2025-06-18 17:15:31'),
(6, 'lykasebelina', 'Lyka Sebelina', 'lykasebelina@gmail.com', 'lyka123', 3, '2025-06-18 17:16:09'),
(7, 'ninaastillero', 'Nina Astillero', 'ninaastillero@gmail.com', 'nina123', 3, '2025-06-18 17:16:47'),
(8, 'salvevillanueva', 'Salve Villanueva', 'salvevillanueva@gmail.com', 'salve123', 3, '2025-06-18 17:17:27'),
(9, 'jonalynramos', 'Jonalyn Ramos', 'jonalynramos@gmail.com', 'jona123', 3, '2025-06-18 17:18:06'),
(10, 'indaleenquinsayas', 'Indaleen Quinsayas', 'indaleenquinsayas@gmail.com', 'inda123', 2, '2025-06-18 17:18:44'),
(11, 'wellmarquina', 'Rowell Marquina', 'wellmarquina@gmail.com', 'well123', 2, '2025-06-18 17:19:25'),
(12, 'margsevilla', 'Margarita Sevilla', 'margsevilla@gmail.com', 'marg123', 1, '2025-06-18 17:20:02');

-- --------------------------------------------------------

--
-- Structure for view `prof_users`
--
DROP TABLE IF EXISTS `prof_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `prof_users`  AS SELECT `users`.`id` AS `id`, `users`.`role_id` AS `role_id`, `users`.`username` AS `username`, `users`.`name` AS `name`, `users`.`email` AS `email`, `users`.`created_at` AS `created_at` FROM `users` WHERE `users`.`role_id` = 2 ;

-- --------------------------------------------------------

--
-- Structure for view `student_users`
--
DROP TABLE IF EXISTS `student_users`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_users`  AS SELECT `users`.`id` AS `id`, `users`.`role_id` AS `role_id`, `users`.`username` AS `username`, `users`.`name` AS `name`, `users`.`email` AS `email`, `users`.`created_at` AS `created_at` FROM `users` WHERE `users`.`role_id` = 3 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_key` (`api_key`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_timestamp` (`timestamp`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_action` (`action`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_certificate` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `fk_user_certificate` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
