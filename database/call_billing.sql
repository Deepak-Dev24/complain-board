-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 07, 2026 at 12:29 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `call_billing`
--

-- --------------------------------------------------------

--
-- Table structure for table `call_analysis`
--

DROP TABLE IF EXISTS `call_analysis`;
CREATE TABLE IF NOT EXISTS `call_analysis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `call_uuid` varchar(64) DEFAULT NULL,
  `complaint_no` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `problem` text,
  `village` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `summary` text,
  `status` enum('NEW','FAILED') DEFAULT 'NEW',
  `transcript` longtext,
  `date_requested` date DEFAULT NULL,
  `transcript_done` tinyint(1) DEFAULT '0',
  `analysis_done` tinyint(1) DEFAULT '0',
  `transcript_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_call_uuid` (`call_uuid`),
  UNIQUE KEY `complaint_no` (`complaint_no`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `call_analysis`
--

INSERT INTO `call_analysis` (`id`, `call_uuid`, `complaint_no`, `name`, `problem`, `village`, `city`, `summary`, `status`, `transcript`, `date_requested`, `transcript_done`, `analysis_done`, `transcript_path`, `created_at`) VALUES
(44, 'b60ba0d0-7de3-123f-c593-02d069b81a19', NULL, NULL, 'Received a call from the police regarding a complaint.', NULL, NULL, 'The caller mentions receiving a call from the police, requesting them to state their problem. No further specific details are provided.', 'NEW', ' Amайjou ji. The police sent us a call on beg you. A Cropi here tell us the problem. Tell the Police...', NULL, 1, 1, 'transcripts/b60ba0d0-7de3-123f-c593-02d069b81a19.txt', '2026-02-07 06:34:01'),
(43, '692b1da9-7df4-123f-c593-02d069b81a19', NULL, 'Rahim', 'Inquiry about doctor\'s appointment', NULL, NULL, 'Rahim inquired about the appointment schedule for a doctor at Susho Tai Hospital. The appointment is confirmed for 9th February, 2021 at 5 o\'clock.', 'NEW', ' Hello, I am Rahim, from Susho Tai Hospital, what do you want to know about the appointment of the doctor? Yes, I want to know about the appointment of the doctor. Please tell me, what day do you want to know about the appointment of the doctor? I want to know about the 9th February. The appointment of the doctor is scheduled for 9th February, 2021. The first lock is 5 o\'clock.', '2021-02-09', 1, 1, 'transcripts/692b1da9-7df4-123f-c593-02d069b81a19.txt', '2026-02-07 06:33:41'),
(42, '9c50cc0e-7d2c-123f-a7b7-02d069b81a19', NULL, 'Aditya Rajnam', 'Incident reported to police', NULL, NULL, 'Aditya Rajnam called to report an incident to the police. The details about the city, village, or date of the incident are not specified in the transcript.', 'NEW', ' Krupack is called to the police importantly. she call. clip I have no other words to say. Yes, I can understand your question. Can you tell me when was this incident? I read it. Yes, it was a incident. Can you tell me what kind of city is this incident? I will have to write it. Yes, it was a incident in the incident. Can you tell me your name? Aditya Rajnam. Yes, Aditya Rajnam. Tell me your mobile number. 9508949406. I am asking you. Your mobile number 9508949406 is correct. Yes, it is correct. Thank you. Now, please tell me, what is your mobile number or mobile number in the cases?', NULL, 1, 1, 'transcripts/9c50cc0e-7d2c-123f-a7b7-02d069b81a19.txt', '2026-02-07 06:33:28'),
(41, 'ec2ef412-7d2b-123f-a7b7-02d069b81a19', NULL, NULL, 'Caller reports issues with people getting drunk.', NULL, 'Ghatna', 'The caller contacted the police helpline to report disturbances caused by people getting drunk. The city mentioned in the call is Ghatna. Limited other information was provided by the caller.', 'NEW', ' Hello, you have called in the police department 150 helpline. Krupe, tell us the problem of the police. We are very small here and there are many small holes in the sky. So we have to talk to you about this. Yes, you don\'t know. First be calm. I am listening to you. Krupe, tell us the problem of the police. We are getting drunk. Yes, your secret is coming. Can you give your knowledge about your problems? Yes, I am getting drunk. Yes, I understand. Can you tell us that this was in the city of Ghatna.', NULL, 1, 1, 'transcripts/ec2ef412-7d2b-123f-a7b7-02d069b81a19.txt', '2026-02-07 06:32:36'),
(40, '1b9cc4f1-7d27-123f-a6b7-02d069b81a19', NULL, 'Rahim', 'Wants to book a doctor\'s appointment', NULL, 'Lucknow', 'Rahim from Susho Thai Hospital in Lucknow is requesting a doctor\'s appointment. The appointment is being sought for February 26, 2021. No other specific issues or complaint number are mentioned.', 'NEW', ' Hello, I am Rahim, from Susho Thai Hospital Lucknow, do you want the appointment of the doctor? Yes, I want the appointment of the doctor. Please tell me, do you want the appointment of the doctor? I want it for tomorrow. Tomorrow\'s date is 6th February 26, 2021. This is the appointment of the 5th of the 5th of the day. Do you want to book it?', '2021-02-26', 1, 1, 'transcripts/1b9cc4f1-7d27-123f-a6b7-02d069b81a19.txt', '2026-02-07 06:32:15'),
(37, '14d4147b-7d02-123f-a6b7-02d069b81a19', NULL, 'Rahim Sushotai', 'Doctor appointment requested', NULL, 'Lucknow', 'Rahim Sushotai from Lucknow called to request a doctor\'s appointment. The appointment is scheduled for February 26, 2021. The time slot available is between 5 p.m. and 8 p.m.', 'NEW', ' Hello, I am Rahim Sushotai from Hospital Lucknow. Do you want the appointment of the doctor? Yes, I want the appointment of the doctor. Then tell me, what do you want the appointment of the case? I want the appointment tomorrow. Tomorrow\'s date is 6th February 26, 2021. From 5 p.m. to 8 p.m., the appointment is scheduled. What time do you want the appointment? I want the appointment of the case. Okay, 6th February 26, 2021 will be booked. Pripyat will be booked.', '2021-02-26', 1, 1, 'transcripts/14d4147b-7d02-123f-a6b7-02d069b81a19.txt', '2026-02-07 06:31:09'),
(38, '15bc8ee2-7d27-123f-a6b7-02d069b81a19', NULL, NULL, 'Appointment issue', NULL, NULL, 'There was an appointment scheduled from 5 a.m., but there is some uncertainty about whether the appointment will be taken for this season. The speaker is seeking clarification regarding the scheduling.', 'NEW', ' soıldz had a appointment from 5 a.m so, will you take your appointment for this season?', NULL, 1, 1, 'transcripts/15bc8ee2-7d27-123f-a6b7-02d069b81a19.txt', '2026-02-07 06:31:46'),
(39, 'b3a50fb5-7d25-123f-a6b7-02d069b81a19', NULL, 'Navati', 'Request for doctor\'s appointment', NULL, 'Lucknow', 'Navati is calling Sushotai Hospital in Lucknow to request a doctor\'s appointment. She is seeking to schedule the appointment for February 26, 2021. The conversation confirms the date and her request for a doctor\'s appointment.', 'NEW', ' What is your name? I am Navati. I am speaking. Sushotai Hospital Lucknow. Do you want to be a doctor\'s appointment? Yes, I want to be a doctor\'s appointment. Then tell me, what do you want to be an appointment? I want to be a servant\'s patient. On Saturday, February 26, 2021, 5th birthday appointment is scheduled. Do you want to be an appointment for this time? I want to be an 8th birthday. On Saturday, February 26, 2021, 10th birthday appointment is scheduled. Do you want to be an appointment for this time? Yes, I will be a doctor\'s appointment.', '2021-02-26', 1, 1, 'transcripts/b3a50fb5-7d25-123f-a6b7-02d069b81a19.txt', '2026-02-07 06:32:02'),
(36, '71d64d24-7d1c-123f-a6b7-02d069b81a19', NULL, 'Rahim', 'Doctor\'s appointment request', NULL, 'Lucknow', 'Rahim requested to book a doctor\'s appointment at a hospital in Lucknow. The appointment was scheduled for 9th February, 2266 at 5 o\'clock.', 'NEW', ' Hello, I am Rahim. I am Sushotai from hospital Lucknow. Do you want the appointment of the doctor? Yes, I want the doctor\'s appointment. Pripyat, tell me, do you want the appointment of the doctor? I want the appointment of the doctor. 9th February. 9th February, 2,266. The appointment is scheduled. It is time. 5 o\'clock. Do you want to book the appointment at this time?', '2266-02-09', 1, 1, 'transcripts/71d64d24-7d1c-123f-a6b7-02d069b81a19.txt', '2026-02-07 06:30:54'),
(45, 'a498df65-7df4-123f-c593-02d069b81a19', NULL, NULL, 'Conflict related to a divorce incident in society; police involvement reported.', NULL, NULL, 'The caller reports a conflict in their community related to a divorce, which led to police intervention. They express frustration and seek assistance regarding the ongoing situation.', 'NEW', ' You have called the police in 150 helpline. Krupe, tell us the problem of the police. My problem is that we are getting very angry in society. Yes, you don\'t know. First, calm down. I am hearing your story. Krupe, tell us the situation. What is the problem? There is a divorce in society. There are other people. They caught him and the police came. Yes, I can understand your problem. When did you get this incident? It is the problem. Yes, thank you. Did anyone get any problem or any other problem?', NULL, 1, 1, 'transcripts/a498df65-7df4-123f-c593-02d069b81a19.txt', '2026-02-07 06:34:17');

-- --------------------------------------------------------

--
-- Table structure for table `call_records`
--

DROP TABLE IF EXISTS `call_records`;
CREATE TABLE IF NOT EXISTS `call_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `call_uuid` varchar(64) NOT NULL,
  `call_date` datetime DEFAULT NULL,
  `direction` varchar(20) DEFAULT NULL,
  `from_number` varchar(30) DEFAULT NULL,
  `to_number` varchar(30) DEFAULT NULL,
  `billsec` int DEFAULT '0',
  `bill_minutes` int DEFAULT '0',
  `rate_per_min` decimal(5,2) DEFAULT '3.00',
  `amount` decimal(10,2) DEFAULT '0.00',
  `status` varchar(20) DEFAULT NULL,
  `paid` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `recording_url` text,
  `recording_path` varchar(255) DEFAULT NULL,
  `recording_downloaded` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_call_uuid` (`call_uuid`),
  KEY `user_id` (`user_id`),
  KEY `call_date` (`call_date`)
) ENGINE=MyISAM AUTO_INCREMENT=413 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `call_records`
--

INSERT INTO `call_records` (`id`, `user_id`, `call_uuid`, `call_date`, `direction`, `from_number`, `to_number`, `billsec`, `bill_minutes`, `rate_per_min`, `amount`, `status`, `paid`, `created_at`, `recording_url`, `recording_path`, `recording_downloaded`) VALUES
(411, 1, 'f68461a2-7b9a-123f-f5ab-02d069b81a19', '2026-02-03 12:01:50', 'inbound', '+919999668250', '08071387150', 109, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(410, 1, 'e126054b-7b9b-123f-f5ab-02d069b81a19', '2026-02-03 12:08:23', 'inbound', '+919999668250', '08071387150', 161, 3, 3.00, 9.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(409, 1, '2e34abbc-7b9e-123f-f5ab-02d069b81a19', '2026-02-03 12:24:51', 'inbound', '+919999668250', '08071387150', 11, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(408, 1, '668b3fed-7b9e-123f-f5ab-02d069b81a19', '2026-02-03 12:26:26', 'inbound', '+919999668250', '08071387150', 59, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(407, 1, '67f95b23-7bc0-123f-f5ab-02d069b81a19', '2026-02-03 16:29:51', 'inbound', '+919999668250', '08071387150', 76, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(406, 1, 'c4dcb61e-7bcd-123f-f5ab-02d069b81a19', '2026-02-03 18:05:31', 'inbound', '+919999668250', '08071387150', 14, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(405, 1, '99a35b94-7c32-123f-799e-02d069b81a19', '2026-02-04 06:07:17', 'inbound', '+919999668250', '08071387150', 117, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(404, 1, 'ad1eea7c-7c34-123f-799e-02d069b81a19', '2026-02-04 06:22:09', 'inbound', '+919999668250', '08071387150', 115, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(403, 1, '17d3504b-7c36-123f-799e-02d069b81a19', '2026-02-04 06:32:17', 'inbound', '+919999668250', '08071387150', 120, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(402, 1, '78eadba5-7c36-123f-799e-02d069b81a19', '2026-02-04 06:35:00', 'inbound', '+919999668250', '08071387150', 97, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(401, 1, '8b36fd34-7c3a-123f-799e-02d069b81a19', '2026-02-04 07:04:09', 'inbound', '+919999668250', '08071387150', 110, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(400, 1, '6406e143-7c3b-123f-799e-02d069b81a19', '2026-02-04 07:10:13', 'inbound', '+919999668250', '08071387150', 51, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(399, 1, '314bd62a-7c3d-123f-799e-02d069b81a19', '2026-02-04 07:23:07', 'inbound', '+919999668250', '08071387150', 100, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(398, 1, '82efa1e8-7c3e-123f-799e-02d069b81a19', '2026-02-04 07:32:33', 'inbound', '+919559949227', '08071387150', 118, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(397, 1, '440f99ff-7c3f-123f-799e-02d069b81a19', '2026-02-04 07:37:57', 'inbound', '+919559949227', '08071387150', 52, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(396, 1, '6b2b8bc8-7c3f-123f-799e-02d069b81a19', '2026-02-04 07:39:03', 'inbound', '+919559949227', '08071387150', 95, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(395, 1, '2942525c-7c40-123f-799e-02d069b81a19', '2026-02-04 07:44:22', 'inbound', '+919999668250', '08071387150', 31, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(394, 1, 'f0ef4985-7c40-123f-799e-02d069b81a19', '2026-02-04 07:49:57', 'inbound', '+919508949406', '08071387150', 114, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(393, 1, '3b8034e5-7c5f-123f-7a9e-02d069b81a19', '2026-02-04 11:26:47', 'inbound', '+919999668250', '08071387150', 140, 3, 3.00, 9.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(392, 1, '750a55ff-7c60-123f-7a9e-02d069b81a19', '2026-02-04 11:35:33', 'inbound', '+919508949406', '08071387150', 95, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(391, 1, '1b7c5ce2-7c91-123f-7a9e-02d069b81a19', '2026-02-04 17:23:48', 'inbound', '+919999668250', '08071387150', 121, 3, 3.00, 9.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(390, 1, '2e31ee30-7c92-123f-7a9e-02d069b81a19', '2026-02-04 17:31:29', 'inbound', '+919999668250', '08071387150', 113, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(388, 1, 'cc7a2870-7cfc-123f-a6b7-02d069b81a19', '2026-02-05 06:14:41', 'inbound', '+919999668250', '08071387150', 101, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(389, 1, '1277b079-7cfc-123f-a6b7-02d069b81a19', '2026-02-05 06:09:29', 'inbound', '+919999668250', '08071387150', 89, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0),
(386, 1, '71d64d24-7d1c-123f-a6b7-02d069b81a19', '2026-02-05 10:01:13', 'inbound', '+919999668250', '08071387150', 39, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:11', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/71d64d24-7d1c-123f-a6b7-02d069b81a19.wav', 'recordings/71d64d24-7d1c-123f-a6b7-02d069b81a19.wav', 1),
(387, 1, '14d4147b-7d02-123f-a6b7-02d069b81a19', '2026-02-05 06:52:30', 'inbound', '+919999668250', '08071387150', 51, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:12', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/14d4147b-7d02-123f-a6b7-02d069b81a19.wav', 'recordings/14d4147b-7d02-123f-a6b7-02d069b81a19.wav', 1),
(383, 1, '15bc8ee2-7d27-123f-a6b7-02d069b81a19', '2026-02-05 11:17:23', 'inbound', '+919999668250', '08071387150', 36, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:09', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/15bc8ee2-7d27-123f-a6b7-02d069b81a19.wav', 'recordings/15bc8ee2-7d27-123f-a6b7-02d069b81a19.wav', 1),
(384, 1, 'b3a50fb5-7d25-123f-a6b7-02d069b81a19', '2026-02-05 11:07:29', 'inbound', '+919999668250', '08071387150', 50, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:10', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/b3a50fb5-7d25-123f-a6b7-02d069b81a19.wav', 'recordings/b3a50fb5-7d25-123f-a6b7-02d069b81a19.wav', 1),
(385, 1, 'dPJVaHkfxicap7HevyXcpM5gjLJ', '2026-02-05 11:01:06', 'outbound', '+918071387150', '+919999668250', 49, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:11', NULL, NULL, 0),
(381, 1, 'woU6PsF7iDKMHxLvEMWfbPzVkwF', '2026-02-05 11:19:01', 'outbound', '+918071387150', '+919999668250', 52, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:08', NULL, NULL, 0),
(382, 1, '1b9cc4f1-7d27-123f-a6b7-02d069b81a19', '2026-02-05 11:17:32', 'inbound', '+919559949227', '08071387150', 33, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:08', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/1b9cc4f1-7d27-123f-a6b7-02d069b81a19.wav', 'recordings/1b9cc4f1-7d27-123f-a6b7-02d069b81a19.wav', 1),
(380, 1, 'ec2ef412-7d2b-123f-a7b7-02d069b81a19', '2026-02-05 11:52:00', 'inbound', '+919999668250', '08071387150', 60, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:07', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/ec2ef412-7d2b-123f-a7b7-02d069b81a19.wav', 'recordings/ec2ef412-7d2b-123f-a7b7-02d069b81a19.wav', 1),
(379, 1, '9c50cc0e-7d2c-123f-a7b7-02d069b81a19', '2026-02-05 11:56:56', 'inbound', '+919508949406', '08071387150', 101, 2, 3.00, 6.00, 'Answered', 0, '2026-02-07 06:30:06', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/9c50cc0e-7d2c-123f-a7b7-02d069b81a19.wav', 'recordings/9c50cc0e-7d2c-123f-a7b7-02d069b81a19.wav', 1),
(377, 1, '692b1da9-7df4-123f-c593-02d069b81a19', '2026-02-06 11:47:09', 'inbound', '+919999668250', '08071387150', 32, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:05', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/692b1da9-7df4-123f-c593-02d069b81a19.wav', 'recordings/692b1da9-7df4-123f-c593-02d069b81a19.wav', 1),
(378, 1, 'b60ba0d0-7de3-123f-c593-02d069b81a19', '2026-02-06 09:47:37', 'inbound', '+919999668250', '08071387150', 15, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:06', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/b60ba0d0-7de3-123f-c593-02d069b81a19.wav', 'recordings/b60ba0d0-7de3-123f-c593-02d069b81a19.wav', 1),
(376, 1, 'a498df65-7df4-123f-c593-02d069b81a19', '2026-02-06 11:48:49', 'inbound', '+919999668250', '08071387150', 59, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:03', 'https://media.vobiz.ai/v1/Account/MA_HBHNNLV7/Recording/a498df65-7df4-123f-c593-02d069b81a19.wav', 'recordings/a498df65-7df4-123f-c593-02d069b81a19.wav', 1),
(412, 1, '8c0c252a-7b99-123f-f5ab-02d069b81a19', '2026-02-03 11:51:41', 'inbound', '+919999668250', '08071387150', 35, 1, 3.00, 3.00, 'Answered', 0, '2026-02-07 06:30:13', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `vobiz_auth_id` varchar(100) NOT NULL,
  `vobiz_auth_token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
CREATE TABLE IF NOT EXISTS `wallets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT '0.00',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
