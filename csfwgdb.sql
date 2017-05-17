-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2017 at 03:07 AM
-- Server version: 5.7.14
-- PHP Version: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `csfwgdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `ann_id` int(11) NOT NULL,
  `ann_title` varchar(255) NOT NULL COMMENT 'to store the title of the organizations public announcements',
  `ann_post` varchar(1200) NOT NULL COMMENT 'to store the posts of the organizations public announcements',
  `ann_date` date NOT NULL COMMENT 'to store the date of the  organizations public announcements',
  `member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='to store the organizations public announcements';

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
  `archive_id` int(11) NOT NULL,
  `archive_path` varchar(255) DEFAULT NULL,
  `member_id` int(11) NOT NULL,
  `submit_date` date DEFAULT NULL,
  `archive_disc` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='to store the group submissions';

--
-- Dumping data for table `archive`
--

INSERT INTO `archive` (`archive_id`, `archive_path`, `member_id`, `submit_date`, `archive_disc`) VALUES
(1, 'files/archive/member1/JodyOctober20161.pdf', 1, '2016-10-23', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `due_payment`
--

CREATE TABLE `due_payment` (
  `payment_id` int(11) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_year` int(11) DEFAULT NULL,
  `payment_vouch` int(11) DEFAULT NULL,
  `member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `due_payment`
--

INSERT INTO `due_payment` (`payment_id`, `payment_date`, `payment_year`, `payment_vouch`, `member_id`) VALUES
(1, '2017-04-16', 2017, 0, 1),
(2, '2017-04-16', 2017, 3, 1),
(3, '2017-04-16', 2017, 1, 2),
(4, '2017-04-16', 2016, 1, 2),
(5, '2017-04-16', 2016, 1, 6),
(6, '2017-04-16', 2015, 0, 2),
(7, '2017-04-16', 2018, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_title` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `repeat_type` int(11) DEFAULT NULL,
  `repeat_end` date DEFAULT NULL,
  `event_type` varchar(45) DEFAULT NULL,
  `event_discl` varchar(1500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_title`, `event_date`, `repeat_type`, `repeat_end`, `event_type`, `event_discl`) VALUES
(1, 'fatmas birthday', '1900-12-19', 4, NULL, NULL, NULL),
(2, '4th of jully', '1900-07-04', 4, NULL, NULL, NULL),
(3, 'mustafa birthday', '1971-06-22', 4, NULL, NULL, NULL),
(4, 'father day', '1900-06-19', 4, NULL, NULL, NULL),
(5, 'Cinco di mayo', '1900-05-05', 4, NULL, NULL, NULL),
(6, 'christmas', '1900-12-25', 4, NULL, NULL, NULL),
(7, 'book launch party', '2016-11-12', 0, NULL, NULL, NULL),
(8, 'remnants and resulition', '2015-12-07', 0, NULL, NULL, NULL),
(9, 'pigs fly', '2016-07-28', 0, NULL, NULL, NULL),
(10, 'free pizza night', '2016-06-26', 0, NULL, NULL, NULL),
(11, 'andy book launch', '2016-07-12', 0, NULL, NULL, NULL),
(12, 'jasons lecture', '2016-05-11', 0, NULL, NULL, NULL),
(13, 'pikes peek confrince', '2016-05-16', 1, '2016-05-18', NULL, NULL),
(14, 'mustafa vacation', '2016-06-19', 1, '2016-06-25', NULL, NULL),
(15, 'nano rimo', '2016-11-01', 1, '2016-11-30', NULL, NULL),
(16, 'writing contest', '2016-07-07', 1, '2016-07-12', NULL, NULL),
(17, 'hump day', '2015-05-06', 2, NULL, NULL, NULL),
(18, 'flash fiction', '2015-05-08', 2, NULL, NULL, NULL),
(19, 'check in day', '2015-05-04', 2, NULL, NULL, NULL),
(20, 'get out and move day', '2015-05-09', 2, '2016-07-28', NULL, NULL),
(21, 'kill your favorite', '2015-05-20', 3, '2015-09-20', NULL, NULL),
(22, 'pick your writing goal', '2016-05-01', 3, NULL, NULL, NULL),
(23, 'word caunt day', '2016-06-30', 3, NULL, NULL, NULL),
(24, 'monday group', '2015-05-25', 6, NULL, NULL, NULL),
(25, 'tuesday group', '2015-05-26', 6, NULL, NULL, NULL),
(26, 'saturday group', '2015-05-30', 6, NULL, NULL, NULL),
(27, 'sunday group', '2015-05-31', 6, NULL, NULL, NULL),
(28, 'workshop', '2015-05-12', 5, NULL, NULL, NULL),
(29, 'brainstorming session', '2015-05-07', 5, NULL, NULL, NULL),
(30, NULL, NULL, NULL, NULL, NULL, NULL),
(31, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events_repeat_type`
--

CREATE TABLE `events_repeat_type` (
  `repeat_id` int(11) NOT NULL,
  `event_repeat_type` varchar(30) DEFAULT NULL COMMENT 'to store the type of repeating event'
) ENGINE=InnoDB AVG_ROW_LENGTH=4096 DEFAULT CHARSET=utf8 COMMENT='to store the different types of repeating for events';

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL COMMENT 'to store the name of the group',
  `group_officer` int(11) DEFAULT NULL COMMENT 'to store the id of the member who is assigned as the officer to this group',
  `group_description` varchar(1200) DEFAULT NULL COMMENT 'to store a description of the group',
  `weekday_id` int(11) NOT NULL,
  `group_pic` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='to store info about the groups';

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `group_officer`, `group_description`, `weekday_id`, `group_pic`) VALUES
(1, 'Sunday group', 1, '			jhkjk					', 1, 'images/groupPics/group1.jpg'),
(2, 'Wednesday group', 2, NULL, 4, 'images/groupPics/group2.jpg'),
(3, 'Saturday group', 3, NULL, 7, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group_member_list`
--

CREATE TABLE `group_member_list` (
  `group_list_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8 COMMENT='to store the list of the groups members';

--
-- Dumping data for table `group_member_list`
--

INSERT INTO `group_member_list` (`group_list_id`, `member_id`, `group_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 1),
(6, 6, 2),
(7, 7, 2),
(8, 8, 3),
(9, 9, 3),
(10, 10, 3),
(11, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_news`
--

CREATE TABLE `group_news` (
  `group_news_id` int(11) NOT NULL,
  `group_news_title` varchar(255) NOT NULL COMMENT 'to store the title of the group news posts',
  `group_news_post` varchar(1200) NOT NULL COMMENT 'to store the group news posts',
  `group_news_date` date NOT NULL COMMENT 'to store the date of the group news posts',
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8 COMMENT='to store the group news posts';

-- --------------------------------------------------------

--
-- Table structure for table `log_in`
--

CREATE TABLE `log_in` (
  `log_id` int(11) NOT NULL,
  `log_un` char(64) NOT NULL COMMENT 'to store username',
  `log_pw` char(64) NOT NULL COMMENT 'to store password',
  `member_id` int(11) NOT NULL,
  `log_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='to store log in info';

--
-- Dumping data for table `log_in`
--

INSERT INTO `log_in` (`log_id`, `log_un`, `log_pw`, `member_id`, `log_token`) VALUES
(1, 'jody@gmail.com', '$2y$10$kp5XztH2AtgHjCki0.CpLurE4XrBTJEd/vEWn0mEkTyNuP2/7CXZ.', 1, NULL),
(2, 'henry@gmail.com', 'password', 2, NULL),
(3, 'katherine@gmail.com', 'password', 3, NULL),
(4, 'kurt@gmail.com', 'password', 4, NULL),
(5, 'wilfred@gmail.com', 'password', 5, NULL),
(6, 'becky@gmail.com', 'password', 6, NULL),
(7, 'billie@gmail.com', 'password', 7, NULL),
(8, 'beth@gmail.com', 'password', 8, NULL),
(9, 'nadine@gmail.com', 'password', 9, NULL),
(10, 'tyler@gmail.com', 'password', 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `first_nm` varchar(25) NOT NULL COMMENT 'to store the members first name',
  `last_nm` varchar(25) NOT NULL COMMENT 'to store the members last name',
  `email` varchar(50) DEFAULT NULL COMMENT 'to store the members email address',
  `rank_id` int(11) NOT NULL COMMENT 'stores the rank id from rank table'
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8 COMMENT='this is to store a list of the members and their info';

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `first_nm`, `last_nm`, `email`, `rank_id`) VALUES
(1, 'Jody', 'Morgan', 'jody@gmail.com', 1),
(2, 'Henry', 'Olson', 'henry@gmail.com', 2),
(3, 'Katherine', 'Douglas', 'katherine@gmail.com', 2),
(4, 'Kurt', 'Frazier', 'kurt@gmail.com', 3),
(5, 'Wilfred', 'Hines', 'wilfred@gmail.com', 3),
(6, 'Becky', 'Moore', 'becky@gmail.com', 3),
(7, 'Billie', 'Barber', 'billie@gmail.com', 3),
(8, 'Beth', 'Cunningham', 'beth@gmail.com', 3),
(9, 'Nadine', 'Wallace', 'nadine@gmail.com', 3),
(10, 'Tyler', 'Hawkins', 'tyler@gmail.com', 3);

-- --------------------------------------------------------

--
-- Table structure for table `member_profile`
--

CREATE TABLE `member_profile` (
  `profile_id` int(11) NOT NULL,
  `member_phone` varchar(12) DEFAULT NULL,
  `member_address` varchar(100) DEFAULT NULL COMMENT 'member street address',
  `member_city` varchar(45) DEFAULT NULL,
  `member_state` varchar(18) DEFAULT NULL,
  `member_zip` varchar(30) DEFAULT NULL,
  `member_privacy` tinyint(3) DEFAULT '3' COMMENT 'privicy level 1 everybody can see 2 only loged in members can see 3 only officers can see',
  `member_id` int(11) NOT NULL,
  `member_pic` varchar(45) DEFAULT NULL,
  `member_qt` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `member_profile`
--

INSERT INTO `member_profile` (`profile_id`, `member_phone`, `member_address`, `member_city`, `member_state`, `member_zip`, `member_privacy`, `member_id`, `member_pic`, `member_qt`) VALUES
(1, '555-555-5555', '223 joans st 111', 'colorado springs', 'CO', '80910', 3, 1, 'images/profilePics/member1.jpg', 'have fun also stuff yes fun fun j\n'),
(2, NULL, NULL, NULL, NULL, NULL, 3, 2, NULL, NULL),
(3, NULL, NULL, NULL, NULL, NULL, 3, 3, NULL, NULL),
(4, NULL, NULL, NULL, NULL, NULL, 3, 4, NULL, NULL),
(5, NULL, NULL, NULL, NULL, NULL, 3, 5, NULL, NULL),
(6, NULL, NULL, NULL, NULL, NULL, 3, 6, NULL, NULL),
(7, NULL, NULL, NULL, NULL, NULL, 3, 7, NULL, NULL),
(8, NULL, NULL, NULL, NULL, NULL, 3, 8, NULL, NULL),
(9, NULL, NULL, NULL, NULL, NULL, 3, 9, NULL, NULL),
(10, NULL, NULL, NULL, NULL, NULL, 3, 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE `ranks` (
  `rank_id` int(11) NOT NULL,
  `rank_name` varchar(20) NOT NULL COMMENT 'to store a list of all the possible ranks of members'
) ENGINE=InnoDB AVG_ROW_LENGTH=4096 DEFAULT CHARSET=utf8 COMMENT='this is to store a list of the possible ranks';

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`rank_id`, `rank_name`) VALUES
(1, 'Admin'),
(4, 'Alumni'),
(5, 'Inactive'),
(3, 'Member'),
(2, 'Officer');

-- --------------------------------------------------------

--
-- Table structure for table `resources_list`
--

CREATE TABLE `resources_list` (
  `resource_id` int(11) NOT NULL,
  `resource_path` varchar(255) DEFAULT NULL,
  `resource_title` varchar(24) DEFAULT NULL,
  `resource_discription` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `resources_list`
--

INSERT INTO `resources_list` (`resource_id`, `resource_path`, `resource_title`, `resource_discription`) VALUES
(4, 'files/resources/Demon On the Outside.pdf', 'Demon On the Outside.pdf', ' another test');

-- --------------------------------------------------------

--
-- Table structure for table `weekdays`
--

CREATE TABLE `weekdays` (
  `weekday_id` int(11) NOT NULL,
  `weekday` varchar(10) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=2340 DEFAULT CHARSET=utf8 COMMENT='to store a list of the days of the week';

--
-- Dumping data for table `weekdays`
--

INSERT INTO `weekdays` (`weekday_id`, `weekday`) VALUES
(6, 'Friday'),
(2, 'Monday'),
(7, 'Saturday'),
(1, 'Sunday'),
(5, 'Thursday'),
(3, 'Tuesday'),
(4, 'Wednesday');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`ann_id`),
  ADD KEY `FK_ann_tbl_members_tbl_member_id` (`member_id`);

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`archive_id`),
  ADD KEY `fk_archive_members1_idx` (`member_id`);

--
-- Indexes for table `due_payment`
--
ALTER TABLE `due_payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `payment_id_UNIQUE` (`payment_id`),
  ADD KEY `fk_due_payment_members1_idx` (`member_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `events_repeat_type`
--
ALTER TABLE `events_repeat_type`
  ADD PRIMARY KEY (`repeat_id`),
  ADD UNIQUE KEY `UK_events_repeat_type_tbl_even` (`event_repeat_type`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `FK_group_tbl_members_tbl_member_id` (`group_officer`),
  ADD KEY `fk_groups_weekdays1_idx` (`weekday_id`);

--
-- Indexes for table `group_member_list`
--
ALTER TABLE `group_member_list`
  ADD PRIMARY KEY (`group_list_id`),
  ADD KEY `FK_group_list_tbl_members_tbl_member_id` (`group_list_id`),
  ADD KEY `fk_group_listl_members_member_id` (`member_id`),
  ADD KEY `fk_group_member_list_tbl_group_tbl1_idx` (`group_id`);

--
-- Indexes for table `group_news`
--
ALTER TABLE `group_news`
  ADD PRIMARY KEY (`group_news_id`),
  ADD KEY `FK_group_news_group_tbl_group_id` (`group_id`);

--
-- Indexes for table `log_in`
--
ALTER TABLE `log_in`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_log_in_members1_idx` (`member_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `FK_members_tbl_ranks_tbl_rank_name` (`member_id`),
  ADD KEY `fk_members_ranks1_idx` (`rank_id`);

--
-- Indexes for table `member_profile`
--
ALTER TABLE `member_profile`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `fk_member_profile_members1_idx` (`member_id`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`rank_id`),
  ADD UNIQUE KEY `UK_ranks_tbl_rank_name` (`rank_name`);

--
-- Indexes for table `resources_list`
--
ALTER TABLE `resources_list`
  ADD PRIMARY KEY (`resource_id`);

--
-- Indexes for table `weekdays`
--
ALTER TABLE `weekdays`
  ADD PRIMARY KEY (`weekday_id`),
  ADD UNIQUE KEY `UK_weekdays_tbl_weekday` (`weekday`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `ann_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `due_payment`
--
ALTER TABLE `due_payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `events_repeat_type`
--
ALTER TABLE `events_repeat_type`
  MODIFY `repeat_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `group_member_list`
--
ALTER TABLE `group_member_list`
  MODIFY `group_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `group_news`
--
ALTER TABLE `group_news`
  MODIFY `group_news_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log_in`
--
ALTER TABLE `log_in`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `member_profile`
--
ALTER TABLE `member_profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
  MODIFY `rank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `resources_list`
--
ALTER TABLE `resources_list`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `weekdays`
--
ALTER TABLE `weekdays`
  MODIFY `weekday_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `FK_ann_tbl_members_tbl_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

--
-- Constraints for table `archive`
--
ALTER TABLE `archive`
  ADD CONSTRAINT `fk_archive_members1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `due_payment`
--
ALTER TABLE `due_payment`
  ADD CONSTRAINT `fk_due_payment_members1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `FK_group_tbl_members_tbl_member_id` FOREIGN KEY (`group_officer`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `fk_groups_weekdays1` FOREIGN KEY (`weekday_id`) REFERENCES `weekdays` (`weekday_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `group_member_list`
--
ALTER TABLE `group_member_list`
  ADD CONSTRAINT `fk_group_listl_members_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `fk_group_member_list_tbl_group_tbl1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `group_news`
--
ALTER TABLE `group_news`
  ADD CONSTRAINT `FK_group_news_group_tbl_group_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`);

--
-- Constraints for table `log_in`
--
ALTER TABLE `log_in`
  ADD CONSTRAINT `fk_log_in_members1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ranks` FOREIGN KEY (`rank_id`) REFERENCES `ranks` (`rank_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `member_profile`
--
ALTER TABLE `member_profile`
  ADD CONSTRAINT `fk_member_profile_members1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
