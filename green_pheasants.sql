-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2022 at 03:16 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `green_pheasants`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `initiator_name` varchar(255) NOT NULL,
  `developer_name` varchar(255) NOT NULL,
  `initiator_image` varchar(255) NOT NULL,
  `developer_image` varchar(255) NOT NULL,
  `story_by_initiator` text NOT NULL,
  `story_by_developer` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `message`, `created_at`, `updated_at`) VALUES
(1, 'panakj', 'pankaj@mailinator.com', 'asdfghjhgfdsa', '2022-06-10 12:36:38', '2022-06-10 12:36:38'),
(2, 'asacsacd', 'deepak@gmail.com', 'asdfghjhgre', '2022-06-10 12:54:43', '2022-06-10 12:54:43'),
(3, 'DASVSWQ', 'DEEEPAK@gmail.com', 'adssfg', '2022-06-10 12:55:46', '2022-06-10 12:55:46'),
(4, 'sadcas', 'dvffed@gmail.com', 'saesrdtkuyjthg', '2022-06-10 12:57:39', '2022-06-10 12:57:39');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, '1. wie kann ich Sie am besten erreichen', 'Rufen Sie an, schreiben Sie eine emal. Wir sind f&uuml;r Sie da !', 'active', '2020-02-10 13:16:14', '2021-07-08 19:17:30'),
(2, '2. Reparieren Sie alle Fabrikate ?', 'Es gibt nahezu kein Fabrikat und Mosell dass wir nicht repariert haben. Airbag Schulung und Schulung f&uuml;r Arbeit an Elektro Fahrzeugen ist vorhanden.', 'active', '2020-02-10 14:05:57', '2021-07-08 19:16:37'),
(7, '3. Was kostet mich die Reparatur ?', 'Meistens nur der Selbtbehalt', 'active', '2020-02-10 10:58:52', '2021-07-08 19:15:21'),
(9, '4. Wer zahlt den Schaden am Fahrzeug', 'Die Teilkasko Versicherung ist verplichtet den Schaden durch Hagelschlag zu &uuml;bernehmen', 'active', '2020-02-11 15:26:52', '2021-07-08 19:14:24'),
(10, '5. Wo kann man das erlernen ?', 'Bei uns ! Fragen Sie nach Preisen und Terminen', 'active', '2020-02-11 15:50:07', '2021-07-08 19:13:31'),
(11, '6. Wie schnell sind Sie einsatzbereit ?', 'Wenn n&ouml;tig nehmen wir innerhalb von 24 Stunden die Arbeit auf', 'active', '2020-02-11 15:57:15', '2021-07-08 19:12:51'),
(12, '7. Was macht das Hagelteam ?', 'Wir reparieren Hagelsch&auml;den weltweit ! Ohne Einschr&auml;nkung. Von kleinen St&uuml;ckzahlen bis hin zu gro&szlig;en Projekten', 'active', '2020-02-11 16:33:39', '2021-07-08 19:12:04'),
(13, '8. wo sind sie überall tätig', 'Weltweit, keine Einschr&auml;nkung', 'active', '2020-02-11 20:51:37', '2021-07-08 19:11:06');

-- --------------------------------------------------------

--
-- Table structure for table `privacy_policies`
--

CREATE TABLE `privacy_policies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `privacy_policies`
--

INSERT INTO `privacy_policies` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Privacy Policy\r\n', 'At Green Pheasants, accessible from https://www.greenpheasants.com, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by Green Pheasants and how we use it.\r\n\r\nIf you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.\r\n\r\nThis Privacy Policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collect in Green Pheasants. This policy is not applicable to any information collected offline or via channels other than this website. Our Privacy Policy was created with the help of the Free Privacy Policy Generator.\r\n\r\nConsent\r\nBy using our website, you hereby consent to our Privacy Policy and agree to its terms.\r\n\r\nInformation we collect\r\nThe personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information.\r\n\r\nIf you contact us directly, we may receive additional information about you such as your name, email address, phone number, the contents of the message and/or attachments you may send us, and any other information you may choose to provide.\r\n\r\nWhen you register for an Account, we may ask for your contact information, including items such as username, and email address.\r\n\r\nHow we use your information\r\nWe use the information we collect in various ways, including to:\r\n\r\nProvide, operate, and maintain our website\r\nImprove, personalize, and expand our website\r\nUnderstand and analyze how you use our website\r\nDevelop new products, services, features, and functionality\r\nCommunicate with you, either directly or through one of our partners, including for customer service, to provide you with updates and other information relating to the website, and for marketing and promotional purposes\r\nSend you emails\r\nFind and prevent fraud.\r\nLog Files\r\nGreen Pheasants follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this and a part of hosting services\' analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users\' movement on the website, and gathering demographic information.\r\n\r\nCookies and Web Beacons\r\nLike any other website, Green Pheasants uses \'cookies\'. These cookies are used to store information including visitors\' preferences, and the pages on the website that the visitor accessed or visited. The information is used to optimize the users\' experience by customizing our web page content based on visitors\' browser type and/or other information.\r\n\r\nFor more general information on cookies, please read the Cookies article on Generate Privacy Policy website.\r\n\r\nAdvertising Partners Privacy Policies\r\nYou may consult this list to find the Privacy Policy for each of the advertising partners of Green Pheasants.\r\n\r\nThird-party ad servers or ad networks uses technologies like cookies, JavaScript, or Web Beacons that are used in their respective advertisements and links that appear on Green Pheasants, which are sent directly to users\' browser. They automatically receive your IP address when this occurs. These technologies are used to measure the effectiveness of their advertising campaigns and/or to personalize the advertising content that you see on websites that you visit.\r\n\r\nNote that Green Pheasants has no access to or control over these cookies that are used by third-party advertisers.\r\n\r\nThird Party Privacy Policies\r\nGreen Pheasants\'s Privacy Policy does not apply to other advertisers or websites. Thus, we are advising you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. It may include their practices and instructions about how to opt-out of certain options.\r\n\r\nYou can choose to disable cookies through your individual browser options. To know more detailed information about cookie management with specific web browsers, it can be found at the browsers\' respective websites.\r\n\r\nCCPA Privacy Rights (Do Not Sell My Personal Information)\r\nUnder the CCPA, among other rights, California consumers have the right to:\r\n\r\nRequest that a business that collects a consumer\'s personal data disclose the categories and specific pieces of personal data that a business has collected about consumers.\r\nRequest that a business delete any personal data about the consumer that a business has collected.\r\nRequest that a business that sells a consumer\'s personal data, not sell the consumer\'s personal data.\r\nIf you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us.\r\nGDPR Data Protection Rights\r\nWe would like to make sure you are fully aware of all of your data protection rights. Every user is entitled to the following:\r\n\r\nThe right to access – You have the right to request copies of your personal data. We may charge you a small fee for this service.\r\nThe right to rectification – You have the right to request that we correct any information you believe is inaccurate. You also have the right to request that we complete the information you believe is incomplete.\r\nThe right to erasure – You have the right to request that we erase your personal data, under certain conditions.\r\nThe right to restrict processing – You have the right to request that we restrict the processing of your personal data, under certain conditions.\r\nThe right to object to processing – You have the right to object to our processing of your personal data, under certain conditions.\r\nThe right to data portability – You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions.\r\nIf you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us.\r\nChildren\'s Information\r\nAnother part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity.\r\n\r\nGreen Pheasants does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to contact us immediately and we will do our best efforts to promptly remove such information from our records.', '2022-06-08 07:19:08', '2021-07-11 16:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Terms and Conditions\r\n', 'Welcome to Green Pheasants!\r\n\r\nThese terms and conditions outline the rules and regulations for the use of Green Pheasants\'s Website, located at https://www.greenpheasants.com.\r\n\r\nBy accessing this website we assume you accept these terms and conditions. Do not continue to use Green Pheasants if you do not agree to take all of the terms and conditions stated on this page.\r\n\r\nThe following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: \"Client\", \"You\" and \"Your\" refers to you, the person log on this website and compliant to the Company’s terms and conditions. \"The Company\", \"Ourselves\", \"We\", \"Our\" and \"Us\", refers to our Company. \"Party\", \"Parties\", or \"Us\", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client’s needs in respect of provision of the Company’s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same.\r\n\r\nCookies\r\nWe employ the use of cookies. By accessing Green Pheasants, you agreed to use cookies in agreement with the Green Pheasants\'s Privacy Policy.\r\n\r\nMost interactive websites use cookies to let us retrieve the user’s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.\r\n\r\nLicense\r\nUnless otherwise stated, Green Pheasants and/or its licensors own the intellectual property rights for all material on Green Pheasants. All intellectual property rights are reserved. You may access this from Green Pheasants for your own personal use subjected to restrictions set in these terms and conditions.\r\n\r\nYou must not:\r\n\r\nRepublish material from Green Pheasants\r\nSell, rent or sub-license material from Green Pheasants\r\nReproduce, duplicate or copy material from Green Pheasants\r\nRedistribute content from Green Pheasants\r\nThis Agreement shall begin on the date hereof. Our Terms and Conditions were created with the help of the Terms And Conditions Template.\r\n\r\nParts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. Green Pheasants does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of Green Pheasants,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws, Green Pheasants shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.\r\n\r\nGreen Pheasants reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.\r\n\r\nYou warrant and represent that:\r\n\r\nYou are entitled to post the Comments on our website and have all necessary licenses and consents to do so;\r\nThe Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;\r\nThe Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy.\r\nThe Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.\r\nYou hereby grant Green Pheasants a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media.\r\n\r\nHyperlinking to our Content\r\nThe following organizations may link to our Website without prior written approval:\r\n\r\nGovernment agencies;\r\nSearch engines;\r\nNews organizations;\r\nOnline directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses;\r\nSystem wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site.\r\nThese organizations may link to our home page, to publications or to other Website information so long as the link:\r\n\r\n(a) is not in any way deceptive;\r\n\r\n(b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services;\r\n\r\n(c) fits within the context of the linking party’s site.\r\n\r\nWe may consider and approve other link requests from the following types of organizations:\r\n\r\ncommonly-known consumer and/or business information sources;\r\ndot.com community sites;\r\nassociations or other groups representing charities;\r\nonline directory distributors;\r\ninternet portals;\r\naccounting, law and consulting firms; and\r\neducational institutions and trade associations.\r\nThese organizations may link to our home page so long as the link:\r\n\r\nThese organizations may link to our home page, to publications or to other Website information so long as the link:\r\n\r\n(a) is not in any way deceptive;\r\n\r\n(b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services;\r\n\r\n(c) fits within the context of the linking party’s site.\r\n\r\nIf you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to Green Pheasants. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.\r\n\r\nApproved organizations may hyperlink to our Website as follows:\r\n\r\nBy use of our corporate name;\r\nBy use of the uniform resource locator being linked to;\r\nBy use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party’s site.\r\nNo use of Green Pheasants\'s logo or other artwork will be allowed for linking absent a trademark license agreement.\r\n\r\niFrames\r\nWithout prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.\r\n\r\nContent Liability\r\nWe shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.\r\n\r\nYour Privacy\r\nPlease read Privacy Policy\r\n\r\nReservation of Rights\r\nWe reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it’s linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.\r\n\r\nRemoval of links from our website\r\nIf you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.\r\n\r\nWe do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.\r\n\r\nDisclaimer\r\nTo the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:\r\n\r\nlimit or exclude our or your liability for death or personal injury;\r\nlimit or exclude our or your liability for fraud or fraudulent misrepresentation;\r\nlimit any of our or your liabilities in any way that is not permitted under applicable law;\r\nexclude any of our or your liabilities that may not be excluded under applicable law.\r\nThe limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer:\r\n\r\n(a) are subject to the preceding paragraph;\r\n\r\n(b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.\r\n\r\nAs long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.', '2022-06-08 07:20:06', '2021-07-11 16:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_me` int(11) DEFAULT 0 COMMENT '0->false,1->true',
  `subscribe_me` int(11) DEFAULT 0 COMMENT '0->false,1->true',
  `send_recommened_poem` int(11) DEFAULT 0 COMMENT '0->false,1->true',
  `send_notification` int(11) DEFAULT 0 COMMENT '0->false,1->true',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `email`, `password`, `remember_me`, `subscribe_me`, `send_recommened_poem`, `send_notification`, `created_at`, `updated_at`) VALUES
(1, 'qwerty', 'deepak@mailinator.com', '$2y$10$EZNgbNHcL7xq8hCFbcnOsOxZymEk9HsXMz4B.yEaOJYAhWYMOm7PS', 1, 1, 1, 0, '2022-06-10 01:39:21', '2022-06-10 01:39:21'),
(2, 'QSQWD', 'deep1ak@mailinator.com', '$2y$10$Alj5XJQDjKFMieiVu3rk8.MJFtzOt7eYhS7wUMFsfk4404hKGIcZu', 0, 0, 0, 0, '2022-06-10 01:40:38', '2022-06-10 01:40:38'),
(3, 'SADSA', 'deepak@mailinDSAator.com', '$2y$10$wrNYZhkiOesNQN37jMLh.exxe7.LYYhcixrpKQ62.Qfj6IOV3KwLm', 0, 0, 0, 1, '2022-06-10 01:41:15', '2022-06-10 01:41:15'),
(4, 'SADFEW', 'deASDQepak@mailinator.com', '$2y$10$f7EWT59Mf6A4OjmhbNDlpu03oUefguz9SecCeai0SjJ7BXv62/SHK', 0, 1, 0, 0, '2022-06-10 01:44:53', '2022-06-10 01:44:53'),
(5, 'asds', 'deep4fdak@mailinator.com', '$2y$10$NcHR9Y/z9DqeOYinIJytW.48W8YGh6dtfwjUowFiPUMbQw32TOq3S', 0, 1, 1, 0, '2022-06-10 01:56:38', '2022-06-10 01:56:38'),
(6, 'Axssacxsacsa', 'deepakasdssdadsahb6t7@gmail.com', '$2y$10$Rt9W1wxlK.d220LZe.SXzO1PRFeo4WSxxsZ2uP.kmeG1nUShKrStG', 0, 0, 0, 0, '2022-06-10 03:02:20', '2022-06-10 03:02:20'),
(7, 'awdwqe', 'deepak@hmail.com', '$2y$10$vELBCYc20E2Wgyw.UUM/iOsIZwTj76z/VH5ZhKQhc/npGDHLj5sXa', 1, 1, 0, 0, '2022-06-10 03:32:56', '2022-06-10 03:32:56'),
(8, 'awdwqe', 'deepasxaak@hmail.com', '$2y$10$gSIzvvgV9Dj1orEdbsR2weLcl8UsPx3L6cqregYAF/xv.NzOhVlIG', 1, 1, 0, 0, '2022-06-10 03:33:31', '2022-06-10 03:33:31'),
(9, 'awdwqe', 'deepasxaak@gmail.com', '$2y$10$h6OuN789WRODutIt0Iltze9yAe.Trw9CzxAapCBGaMIGCSzJx7sZ6', 1, 1, 0, 0, '2022-06-10 03:33:45', '2022-06-10 03:33:45'),
(10, 'feroz', 'feroz@gmail.com', '$2y$10$2zTNu0BusPL/7Ee0mPaYf.Nh2sB4EOIVqKGEYc3SKKEsfdI.KL6eu', 0, 0, 0, 0, '2022-06-10 03:41:23', '2022-06-10 03:41:23'),
(11, 'ADXSAQ', 'DS@GMAIL.COM', '$2y$10$OulrFTrX4w4AOdAlraJlRO8RBud5hvInS06U087bV0DsgS7eEkvJq', 1, 0, 1, 0, '2022-06-10 03:42:49', '2022-06-10 03:42:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privacy_policies`
--
ALTER TABLE `privacy_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `privacy_policies`
--
ALTER TABLE `privacy_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
