-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 05, 2016 at 11:03 PM
-- Server version: 5.5.42-37.1-log
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mycodebu_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_auto_responder`
--

DROP TABLE IF EXISTS `lh_abstract_auto_responder`;
CREATE TABLE IF NOT EXISTS `lh_abstract_auto_responder` (
  `id` int(11) NOT NULL,
  `siteaccess` varchar(3) NOT NULL,
  `wait_message` varchar(250) NOT NULL,
  `wait_timeout` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `dep_id` int(11) NOT NULL,
  `repeat_number` int(11) NOT NULL DEFAULT '1',
  `timeout_message` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_browse_offer_invitation`
--

DROP TABLE IF EXISTS `lh_abstract_browse_offer_invitation`;
CREATE TABLE IF NOT EXISTS `lh_abstract_browse_offer_invitation` (
  `id` int(11) NOT NULL,
  `siteaccess` varchar(10) NOT NULL,
  `time_on_site` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `callback_content` longtext NOT NULL,
  `lhc_iframe_content` tinyint(4) NOT NULL,
  `custom_iframe_url` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `executed_times` int(11) NOT NULL,
  `url` varchar(250) NOT NULL,
  `active` int(11) NOT NULL,
  `has_url` int(11) NOT NULL,
  `is_wildcard` int(11) NOT NULL,
  `referrer` varchar(250) NOT NULL,
  `priority` varchar(250) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `unit` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_email_template`
--

DROP TABLE IF EXISTS `lh_abstract_email_template`;
CREATE TABLE IF NOT EXISTS `lh_abstract_email_template` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `from_name` varchar(150) NOT NULL,
  `from_name_ac` tinyint(4) NOT NULL,
  `from_email` varchar(150) NOT NULL,
  `from_email_ac` tinyint(4) NOT NULL,
  `user_mail_as_sender` tinyint(4) NOT NULL,
  `content` text NOT NULL,
  `subject` varchar(250) NOT NULL,
  `bcc_recipients` varchar(200) NOT NULL,
  `subject_ac` tinyint(4) NOT NULL,
  `reply_to` varchar(150) NOT NULL,
  `reply_to_ac` tinyint(4) NOT NULL,
  `recipient` varchar(150) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_abstract_email_template`
--

INSERT INTO `lh_abstract_email_template` (`id`, `name`, `from_name`, `from_name_ac`, `from_email`, `from_email_ac`, `user_mail_as_sender`, `content`, `subject`, `bcc_recipients`, `subject_ac`, `reply_to`, `reply_to_ac`, `recipient`) VALUES
(1, 'Send mail to user', 'Live Helper Chat', 0, '', 0, 0, 'Dear {user_chat_nick},\r\n\r\n{additional_message}\r\n\r\nLive Support response:\r\n{messages_content}\r\n\r\nSincerely,\r\nLive Support Team\r\n', '{name_surname} has responded to your request', '', 1, '', 1, ''),
(2, 'Support request from user', '', 0, '', 0, 0, 'Hello,\r\n\r\nUser request data:\r\nName: {name}\r\nEmail: {email}\r\nPhone: {phone}\r\nDepartment: {department}\r\nCountry: {country}\r\nCity: {city}\r\nIP: {ip}\r\n\r\nMessage:\r\n{message}\r\n\r\nAdditional data, if any:\r\n{additional_data}\r\n\r\nURL of page from which user has send request:\r\n{url_request}\r\n\r\nLink to chat if any:\r\n{prefillchat}\r\n\r\nSincerely,\r\nLive Support Team', 'Support request from user', '', 0, '', 0, 'admin@ecm-games.com'),
(3, 'User mail for himself', 'Live Helper Chat', 0, '', 0, 0, 'Dear {user_chat_nick},\r\n\r\nTranscript:\r\n{messages_content}\r\n\r\nSincerely,\r\nLive Support Team\r\n', 'Chat transcript', '', 0, '', 0, ''),
(4, 'New chat request', 'Live Helper Chat', 0, '', 0, 0, 'Hello,\r\n\r\nUser request data:\r\nName: {name}\r\nEmail: {email}\r\nPhone: {phone}\r\nDepartment: {department}\r\nCountry: {country}\r\nCity: {city}\r\nIP: {ip}\r\n\r\nMessage:\r\n{message}\r\n\r\nURL of page from which user has send request:\r\n{url_request}\r\n\r\nClick to accept chat automatically\r\n{url_accept}\r\n\r\nSincerely,\r\nLive Support Team', 'New chat request', '', 0, '', 0, 'admin@ecm-games.com'),
(5, 'Chat was closed', 'Live Helper Chat', 0, '', 0, 0, 'Hello,\r\n\r\n{operator} has closed a chat\r\nName: {name}\r\nEmail: {email}\r\nPhone: {phone}\r\nDepartment: {department}\r\nCountry: {country}\r\nCity: {city}\r\nIP: {ip}\r\n\r\nMessage:\r\n{message}\r\n\r\nAdditional data, if any:\r\n{additional_data}\r\n\r\nURL of page from which user has send request:\r\n{url_request}\r\n\r\nSincerely,\r\nLive Support Team', 'Chat was closed', '', 0, '', 0, ''),
(6, 'New FAQ question', 'Live Helper Chat', 0, '', 0, 0, 'Hello,\r\n\r\nNew FAQ question\r\nEmail: {email}\r\n\r\nQuestion:\r\n{question}\r\n\r\nQuestion URL:\r\n{url_question}\r\n\r\nURL to answer a question:\r\n{url_request}\r\n\r\nSincerely,\r\nLive Support Team', 'New FAQ question', '', 0, '', 0, ''),
(7, 'New unread message', 'Live Helper Chat', 0, '', 0, 0, 'Hello,\r\n\r\nUser request data:\r\nName: {name}\r\nEmail: {email}\r\nPhone: {phone}\r\nDepartment: {department}\r\nCountry: {country}\r\nCity: {city}\r\nIP: {ip}\r\n\r\nMessage:\r\n{message}\r\n\r\nURL of page from which user has send request:\r\n{url_request}\r\n\r\nClick to accept chat automatically\r\n{url_accept}\r\n\r\nSincerely,\r\nLive Support Team', 'New chat request', '', 0, '', 0, 'admin@ecm-games.com'),
(8, 'Filled form', 'Live Helper Chat', 0, '', 0, 0, 'Hello,\r\n\r\nUser has filled a form\r\nForm name - {form_name}\r\nUser IP - {ip}\r\nDownload filled data - {url_download}\r\nIdentifier - {identifier}\r\nView filled data - {url_view}\r\n\r\n {content} \r\n\r\nSincerely,\r\nLive Support Team', 'Filled form - {form_name}', '', 0, '', 0, 'admin@ecm-games.com'),
(9, 'Chat was accepted', 'Live Helper Chat', 0, '', 0, 0, 'Hello,\r\n\r\nOperator {user_name} has accepted a chat [{chat_id}]\r\n\r\nUser request data:\r\nName: {name}\r\nEmail: {email}\r\nPhone: {phone}\r\nDepartment: {department}\r\nCountry: {country}\r\nCity: {city}\r\nIP: {ip}\r\n\r\nMessage:\r\n{message}\r\n\r\nURL of page from which user has send request:\r\n{url_request}\r\n\r\nClick to accept chat automatically\r\n{url_accept}\r\n\r\nSincerely,\r\nLive Support Team', 'Chat was accepted [{chat_id}]', '', 0, '', 0, 'admin@ecm-games.com'),
(10, 'Permission request', 'Live Helper Chat', 0, '', 0, 0, 'Hello,\r\n\r\nOperator {user} has requested these permissions\n\r\n{permissions}\r\n\r\nSincerely,\r\nLive Support Team', 'Permission request from {user}', '', 0, '', 0, 'admin@ecm-games.com');

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_form`
--

DROP TABLE IF EXISTS `lh_abstract_form`;
CREATE TABLE IF NOT EXISTS `lh_abstract_form` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `content` longtext NOT NULL,
  `recipient` varchar(250) NOT NULL,
  `active` int(11) NOT NULL,
  `name_attr` varchar(250) NOT NULL,
  `intro_attr` varchar(250) NOT NULL,
  `xls_columns` text NOT NULL,
  `pagelayout` varchar(200) NOT NULL,
  `post_content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_form_collected`
--

DROP TABLE IF EXISTS `lh_abstract_form_collected`;
CREATE TABLE IF NOT EXISTS `lh_abstract_form_collected` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `ip` varchar(250) NOT NULL,
  `identifier` varchar(250) NOT NULL,
  `content` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_proactive_chat_invitation`
--

DROP TABLE IF EXISTS `lh_abstract_proactive_chat_invitation`;
CREATE TABLE IF NOT EXISTS `lh_abstract_proactive_chat_invitation` (
  `id` int(11) NOT NULL,
  `siteaccess` varchar(10) NOT NULL,
  `time_on_site` int(11) NOT NULL,
  `pageviews` int(11) NOT NULL,
  `message` text NOT NULL,
  `message_returning` text NOT NULL,
  `executed_times` int(11) NOT NULL,
  `dep_id` int(11) NOT NULL,
  `hide_after_ntimes` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `operator_ids` varchar(100) NOT NULL,
  `wait_message` varchar(250) NOT NULL,
  `timeout_message` varchar(250) NOT NULL,
  `message_returning_nick` varchar(250) NOT NULL,
  `referrer` varchar(250) NOT NULL,
  `wait_timeout` int(11) NOT NULL,
  `show_random_operator` int(11) NOT NULL,
  `operator_name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `requires_email` int(11) NOT NULL,
  `requires_username` int(11) NOT NULL,
  `requires_phone` int(11) NOT NULL,
  `repeat_number` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_product`
--

DROP TABLE IF EXISTS `lh_abstract_product`;
CREATE TABLE IF NOT EXISTS `lh_abstract_product` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `disabled` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `departament_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_rest_api_key`
--

DROP TABLE IF EXISTS `lh_abstract_rest_api_key`;
CREATE TABLE IF NOT EXISTS `lh_abstract_rest_api_key` (
  `id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_survey`
--

DROP TABLE IF EXISTS `lh_abstract_survey`;
CREATE TABLE IF NOT EXISTS `lh_abstract_survey` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `max_stars` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_survey_item`
--

DROP TABLE IF EXISTS `lh_abstract_survey_item`;
CREATE TABLE IF NOT EXISTS `lh_abstract_survey_item` (
  `id` bigint(20) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `stars` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ftime` int(11) NOT NULL,
  `dep_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_widget_theme`
--

DROP TABLE IF EXISTS `lh_abstract_widget_theme`;
CREATE TABLE IF NOT EXISTS `lh_abstract_widget_theme` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `name_company` varchar(250) NOT NULL,
  `onl_bcolor` varchar(10) NOT NULL,
  `bor_bcolor` varchar(10) NOT NULL DEFAULT 'e3e3e3',
  `text_color` varchar(10) NOT NULL,
  `online_image` varchar(250) NOT NULL,
  `online_image_path` varchar(250) NOT NULL,
  `offline_image` varchar(250) NOT NULL,
  `offline_image_path` varchar(250) NOT NULL,
  `logo_image` varchar(250) NOT NULL,
  `logo_image_path` varchar(250) NOT NULL,
  `need_help_image` varchar(250) NOT NULL,
  `header_background` varchar(10) NOT NULL,
  `need_help_tcolor` varchar(10) NOT NULL,
  `need_help_bcolor` varchar(10) NOT NULL,
  `need_help_border` varchar(10) NOT NULL,
  `need_help_close_bg` varchar(10) NOT NULL,
  `need_help_hover_bg` varchar(10) NOT NULL,
  `need_help_close_hover_bg` varchar(10) NOT NULL,
  `need_help_image_path` varchar(250) NOT NULL,
  `custom_status_css` text NOT NULL,
  `custom_container_css` text NOT NULL,
  `custom_widget_css` text NOT NULL,
  `need_help_header` varchar(250) NOT NULL,
  `need_help_text` varchar(250) NOT NULL,
  `online_text` varchar(250) NOT NULL,
  `offline_text` varchar(250) NOT NULL,
  `widget_border_color` varchar(10) NOT NULL,
  `copyright_image` varchar(250) NOT NULL,
  `copyright_image_path` varchar(250) NOT NULL,
  `widget_copyright_url` varchar(250) NOT NULL,
  `show_copyright` int(11) NOT NULL DEFAULT '1',
  `explain_text` text NOT NULL,
  `intro_operator_text` varchar(250) NOT NULL,
  `operator_image` varchar(250) NOT NULL,
  `operator_image_path` varchar(250) NOT NULL,
  `minimize_image` varchar(250) NOT NULL,
  `minimize_image_path` varchar(250) NOT NULL,
  `restore_image` varchar(250) NOT NULL,
  `restore_image_path` varchar(250) NOT NULL,
  `close_image` varchar(250) NOT NULL,
  `close_image_path` varchar(250) NOT NULL,
  `popup_image` varchar(250) NOT NULL,
  `popup_image_path` varchar(250) NOT NULL,
  `support_joined` varchar(250) NOT NULL,
  `support_closed` varchar(250) NOT NULL,
  `pending_join` varchar(250) NOT NULL,
  `noonline_operators` varchar(250) NOT NULL,
  `noonline_operators_offline` varchar(250) NOT NULL,
  `hide_close` int(11) NOT NULL,
  `hide_popup` int(11) NOT NULL,
  `show_need_help` int(11) NOT NULL DEFAULT '1',
  `show_need_help_timeout` int(11) NOT NULL DEFAULT '24',
  `header_height` int(11) NOT NULL,
  `header_padding` int(11) NOT NULL,
  `widget_border_width` int(11) NOT NULL,
  `show_voting` tinyint(1) NOT NULL DEFAULT '1',
  `department_title` varchar(250) NOT NULL,
  `department_select` varchar(250) NOT NULL,
  `buble_visitor_background` varchar(250) NOT NULL,
  `buble_visitor_title_color` varchar(250) NOT NULL,
  `buble_visitor_text_color` varchar(250) NOT NULL,
  `buble_operator_background` varchar(250) NOT NULL,
  `buble_operator_title_color` varchar(250) NOT NULL,
  `buble_operator_text_color` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_canned_msg`
--

DROP TABLE IF EXISTS `lh_canned_msg`;
CREATE TABLE IF NOT EXISTS `lh_canned_msg` (
  `id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `fallback_msg` text NOT NULL,
  `title` varchar(250) NOT NULL,
  `explain` varchar(250) NOT NULL,
  `position` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `delay` int(11) NOT NULL,
  `auto_send` tinyint(1) NOT NULL,
  `attr_int_1` int(11) NOT NULL,
  `attr_int_2` int(11) NOT NULL,
  `attr_int_3` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_chat`
--

DROP TABLE IF EXISTS `lh_chat`;
CREATE TABLE IF NOT EXISTS `lh_chat` (
  `id` int(11) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `status_sub` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `referrer` text NOT NULL,
  `session_referrer` text NOT NULL,
  `chat_variables` text NOT NULL,
  `remarks` text NOT NULL,
  `ip` varchar(100) NOT NULL,
  `dep_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_status` int(11) NOT NULL DEFAULT '0',
  `user_closed_ts` int(11) NOT NULL DEFAULT '0',
  `support_informed` int(11) NOT NULL DEFAULT '0',
  `unread_messages_informed` int(11) NOT NULL DEFAULT '0',
  `reinform_timeout` int(11) NOT NULL DEFAULT '0',
  `email` varchar(100) NOT NULL,
  `country_code` varchar(100) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `unanswered_chat` int(11) NOT NULL,
  `user_typing` int(11) NOT NULL,
  `user_typing_txt` varchar(50) NOT NULL,
  `operator_typing` int(11) NOT NULL,
  `operator_typing_id` int(11) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `has_unread_messages` int(11) NOT NULL,
  `last_user_msg_time` int(11) NOT NULL,
  `fbst` tinyint(1) NOT NULL,
  `online_user_id` int(11) NOT NULL,
  `last_msg_id` int(11) NOT NULL,
  `additional_data` text NOT NULL,
  `timeout_message` varchar(250) NOT NULL,
  `user_tz_identifier` varchar(50) NOT NULL,
  `lat` varchar(10) NOT NULL,
  `lon` varchar(10) NOT NULL,
  `city` varchar(100) NOT NULL,
  `operation` text NOT NULL,
  `operation_admin` varchar(200) NOT NULL,
  `chat_locale` varchar(10) NOT NULL,
  `chat_locale_to` varchar(10) NOT NULL,
  `mail_send` int(11) NOT NULL,
  `screenshot_id` int(11) NOT NULL,
  `wait_time` int(11) NOT NULL,
  `wait_timeout` int(11) NOT NULL,
  `wait_timeout_send` int(11) NOT NULL,
  `wait_timeout_repeat` int(11) NOT NULL,
  `chat_duration` int(11) NOT NULL,
  `tslasign` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `chat_initiator` int(11) NOT NULL,
  `transfer_timeout_ts` int(11) NOT NULL,
  `transfer_timeout_ac` int(11) NOT NULL,
  `transfer_if_na` int(11) NOT NULL,
  `na_cb_executed` int(11) NOT NULL,
  `nc_cb_executed` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_chat`
--

INSERT INTO `lh_chat` (`id`, `nick`, `status`, `status_sub`, `time`, `user_id`, `hash`, `referrer`, `session_referrer`, `chat_variables`, `remarks`, `ip`, `dep_id`, `product_id`, `user_status`, `user_closed_ts`, `support_informed`, `unread_messages_informed`, `reinform_timeout`, `email`, `country_code`, `country_name`, `unanswered_chat`, `user_typing`, `user_typing_txt`, `operator_typing`, `operator_typing_id`, `phone`, `has_unread_messages`, `last_user_msg_time`, `fbst`, `online_user_id`, `last_msg_id`, `additional_data`, `timeout_message`, `user_tz_identifier`, `lat`, `lon`, `city`, `operation`, `operation_admin`, `chat_locale`, `chat_locale_to`, `mail_send`, `screenshot_id`, `wait_time`, `wait_timeout`, `wait_timeout_send`, `wait_timeout_repeat`, `chat_duration`, `tslasign`, `priority`, `chat_initiator`, `transfer_timeout_ts`, `transfer_timeout_ac`, `transfer_if_na`, `na_cb_executed`, `nc_cb_executed`) VALUES
(1, 'Роман', 1, 0, 1465961764, 1, 'beb205a22b488db1f136176d38d94069fd7c0826', '//mycodebusters.com/games/', '', '', '', '109.87.201.130', 1, 0, 1, 1465966463, 1, 0, 0, '', 'ua', 'Ukraine', 0, 1465966458, 'Visitor has left the chat!', 0, 0, '', 0, 1465963400, 0, 1, 5, '', '', 'Europe/Helsinki', '', '', '', '', '', '', '', 0, 0, 25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(2, 'asdf', 0, 0, 1465970854, 0, 'dca25147d93f479e28ce83c8de8e3c91e321cb70', '//mycodebusters.com/games/index.php/login/signup', '//mycodebusters.com/games/', '', '', '91.195.91.160', 1, 0, 1, 1465970880, 1, 0, 0, '', 'ua', 'Ukraine', 1, 1465970871, 'http://mycodebusters.com/games/index.php/login/sig', 0, 0, '', 0, 0, 0, 4, 6, '', '', 'Europe/Helsinki', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(3, 'aaa', 1, 0, 1469786143, 1, '40fe1bbf21889b5064a897653b7ecfa2e2f09d73', '//mycodebusters.com/games/', '', '', '', '94.248.48.13', 1, 0, 1, 1469786893, 1, 0, 0, '', 'ua', 'Ukraine', 0, 1469786888, 'Visitor has left the chat!', 0, 0, '', 0, 0, 0, 7, 9, '', '', 'Europe/Moscow', '', '', '', '', '', '', '', 0, 0, 13, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lh_chatbox`
--

DROP TABLE IF EXISTS `lh_chatbox`;
CREATE TABLE IF NOT EXISTS `lh_chatbox` (
  `id` int(11) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_chat_accept`
--

DROP TABLE IF EXISTS `lh_chat_accept`;
CREATE TABLE IF NOT EXISTS `lh_chat_accept` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `ctime` int(11) NOT NULL,
  `wused` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_chat_archive_range`
--

DROP TABLE IF EXISTS `lh_chat_archive_range`;
CREATE TABLE IF NOT EXISTS `lh_chat_archive_range` (
  `id` int(11) NOT NULL,
  `range_from` int(11) NOT NULL,
  `range_to` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_chat_blocked_user`
--

DROP TABLE IF EXISTS `lh_chat_blocked_user`;
CREATE TABLE IF NOT EXISTS `lh_chat_blocked_user` (
  `id` int(11) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `datets` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_chat_config`
--

DROP TABLE IF EXISTS `lh_chat_config`;
CREATE TABLE IF NOT EXISTS `lh_chat_config` (
  `identifier` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `explain` varchar(250) NOT NULL,
  `hidden` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_chat_config`
--

INSERT INTO `lh_chat_config` (`identifier`, `value`, `type`, `explain`, `hidden`) VALUES
('tracked_users_cleanup', '160', 0, 'How many days keep records of online users.', 0),
('list_online_operators', '0', 0, 'List online operators.', 0),
('voting_days_limit', '7', 0, 'How many days voting widget should not be expanded after last show', 0),
('track_online_visitors', '1', 0, 'Enable online site visitors tracking', 0),
('pro_active_invite', '1', 0, 'Is pro active chat invitation active. Online users tracking also has to be enabled', 0),
('customer_company_name', 'Live Helper Chat', 0, 'Your company name - visible in bottom left corner', 0),
('customer_site_url', 'http://livehelperchat.com', 0, 'Your site URL address', 0),
('smtp_data', 'a:5:{s:4:"host";s:0:"";s:4:"port";s:2:"25";s:8:"use_smtp";i:0;s:8:"username";s:0:"";s:8:"password";s:0:"";}', 0, 'SMTP configuration', 1),
('chatbox_data', 'a:6:{i:0;b:0;s:20:"chatbox_auto_enabled";i:0;s:19:"chatbox_secret_hash";s:9:"inmcx48iw";s:20:"chatbox_default_name";s:7:"Chatbox";s:17:"chatbox_msg_limit";i:50;s:22:"chatbox_default_opname";s:7:"Manager";}', 0, 'Chatbox configuration', 1),
('start_chat_data', 'a:23:{i:0;b:0;s:21:"name_visible_in_popup";b:1;s:27:"name_visible_in_page_widget";b:1;s:19:"name_require_option";s:8:"required";s:22:"email_visible_in_popup";b:0;s:28:"email_visible_in_page_widget";b:0;s:20:"email_require_option";s:8:"required";s:24:"message_visible_in_popup";b:1;s:30:"message_visible_in_page_widget";b:1;s:22:"message_require_option";s:8:"required";s:22:"phone_visible_in_popup";b:0;s:28:"phone_visible_in_page_widget";b:0;s:20:"phone_require_option";s:8:"required";s:21:"force_leave_a_message";b:0;s:29:"offline_name_visible_in_popup";b:1;s:35:"offline_name_visible_in_page_widget";b:1;s:27:"offline_name_require_option";s:8:"required";s:30:"offline_phone_visible_in_popup";b:0;s:36:"offline_phone_visible_in_page_widget";b:0;s:28:"offline_phone_require_option";s:8:"required";s:32:"offline_message_visible_in_popup";b:1;s:38:"offline_message_visible_in_page_widget";b:1;s:30:"offline_message_require_option";s:8:"required";}', 0, '', 1),
('application_name', 'a:6:{s:3:"eng";s:31:"Live Helper Chat - live support";s:3:"lit";s:26:"Live Helper Chat - pagalba";s:3:"hrv";s:0:"";s:3:"esp";s:0:"";s:3:"por";s:0:"";s:10:"site_admin";s:31:"Live Helper Chat - live support";}', 1, 'Support application name, visible in browser title.', 0),
('track_footprint', '0', 0, 'Track users footprint. For this also online visitors tracking should be enabled', 0),
('pro_active_limitation', '-1', 0, 'Pro active chats invitations limitation based on pending chats, (-1) do not limit, (0,1,n+1) number of pending chats can be for invitation to be shown.', 0),
('pro_active_show_if_offline', '0', 0, 'Should invitation logic be executed if there is no online operators', 0),
('export_hash', 'l6kmbp0ae', 0, 'Chats export secret hash', 0),
('message_seen_timeout', '24', 0, 'Proactive message timeout in hours. After how many hours proactive chat mesasge should be shown again.', 0),
('reopen_chat_enabled', '1', 0, 'Reopen chat functionality enabled', 0),
('ignorable_ip', '', 0, 'Which ip should be ignored in online users list, separate by comma', 0),
('run_departments_workflow', '0', 0, 'Should cronjob run departments transfer workflow, even if user leaves a chat', 0),
('geo_location_data', 'a:3:{s:4:"zoom";i:4;s:3:"lat";s:7:"49.8211";s:3:"lng";s:7:"11.7835";}', 0, '', 1),
('xmp_data', 'a:14:{i:0;b:0;s:4:"host";s:15:"talk.google.com";s:6:"server";s:9:"gmail.com";s:8:"resource";s:6:"xmpphp";s:4:"port";s:4:"5222";s:7:"use_xmp";i:0;s:8:"username";s:0:"";s:8:"password";s:0:"";s:11:"xmp_message";s:78:"New chat request [{chat_id}]\r\n{messages}\r\nClick to accept a chat\r\n{url_accept}";s:10:"recipients";s:0:"";s:20:"xmp_accepted_message";s:69:"{user_name} has accepted a chat [{chat_id}]\r\n{messages}\r\n{url_accept}";s:16:"use_standard_xmp";i:0;s:15:"test_recipients";s:0:"";s:21:"test_group_recipients";s:0:"";}', 0, 'XMP data', 1),
('run_unaswered_chat_workflow', '0', 0, 'Should cronjob run unanswered chats workflow and execute unaswered chats callback, 0 - no, any other number bigger than 0 is a minits how long chat have to be not accepted before executing callback.', 0),
('disable_popup_restore', '0', 0, 'Disable option in widget to open new window. Restore icon will be hidden', 0),
('accept_tos_link', '#', 0, 'Change to your site Terms of Service', 0),
('hide_button_dropdown', '0', 0, 'Hide close button in dropdown', 0),
('on_close_exit_chat', '0', 0, 'On chat close exit chat', 0),
('product_enabled_module', '0', 0, 'Product module is enabled', 1),
('paidchat_data', '', 0, 'Paid chat configuration', 1),
('file_configuration', 'a:7:{i:0;b:0;s:5:"ft_op";s:43:"gif|jpe?g|png|zip|rar|xls|doc|docx|xlsx|pdf";s:5:"ft_us";s:26:"gif|jpe?g|png|doc|docx|pdf";s:6:"fs_max";i:2048;s:18:"active_user_upload";b:0;s:16:"active_op_upload";b:1;s:19:"active_admin_upload";b:1;}', 0, 'Files configuration item', 1),
('accept_chat_link_timeout', '300', 0, 'How many seconds chat accept link is valid. Set 0 to force login all the time manually.', 0),
('session_captcha', '0', 0, 'Use session captcha. LHC have to be installed on the same domain or subdomain.', 0),
('sync_sound_settings', 'a:16:{i:0;b:0;s:12:"repeat_sound";i:1;s:18:"repeat_sound_delay";i:5;s:10:"show_alert";b:0;s:22:"new_chat_sound_enabled";b:1;s:31:"new_message_sound_admin_enabled";b:1;s:30:"new_message_sound_user_enabled";b:1;s:14:"online_timeout";d:300;s:22:"check_for_operator_msg";d:10;s:21:"back_office_sinterval";d:10;s:22:"chat_message_sinterval";d:3.5;s:20:"long_polling_enabled";b:0;s:30:"polling_chat_message_sinterval";d:1.5;s:29:"polling_back_office_sinterval";d:5;s:18:"connection_timeout";i:30;s:28:"browser_notification_message";b:0;}', 0, '', 1),
('sound_invitation', '1', 0, 'Play sound on invitation to chat.', 0),
('explicit_http_mode', '', 0, 'Please enter explicit http mode. Either http: or https:, do not forget : at the end.', 0),
('track_domain', '', 0, 'Set your domain to enable user tracking across different domain subdomains.', 0),
('max_message_length', '500', 0, 'Maximum message length in characters', 0),
('need_help_tip', '1', 0, 'Show need help tooltip?', 0),
('need_help_tip_timeout', '24', 0, 'Need help tooltip timeout, after how many hours show again tooltip?', 0),
('use_secure_cookie', '0', 0, 'Use secure cookie, check this if you want to force SSL all the time', 0),
('faq_email_required', '0', 0, 'Is visitor e-mail required for FAQ', 0),
('disable_print', '0', 0, 'Disable chat print', 0),
('hide_disabled_department', '1', 0, 'Hide disabled department widget', 0),
('disable_send', '0', 0, 'Disable chat transcript send', 0),
('ignore_user_status', '0', 0, 'Ignore users online statuses and use departments online hours', 0),
('bbc_button_visible', '1', 0, 'Show BB Code button', 0),
('allow_reopen_closed', '1', 0, 'Allow user to reopen closed chats?', 0),
('reopen_as_new', '1', 0, 'Reopen closed chat as new? Otherwise it will be reopened as active.', 0),
('default_theme_id', '0', 0, 'Default theme ID.', 1),
('translation_data', 'a:6:{i:0;b:0;s:19:"translation_handler";s:4:"bing";s:19:"enable_translations";b:0;s:14:"bing_client_id";s:0:"";s:18:"bing_client_secret";s:0:"";s:14:"google_api_key";s:0:"";}', 0, 'Translation data', 1),
('disable_html5_storage', '1', 0, 'Disable HMTL5 storage, check it if your site is switching between http and https', 0),
('automatically_reopen_chat', '1', 0, 'Automatically reopen chat on widget open', 0),
('autoclose_timeout', '0', 0, 'Automatic chats closing. 0 - disabled, n > 0 time in minutes before chat is automatically closed', 0),
('autopurge_timeout', '0', 0, 'Automatic chats purging. 0 - disabled, n > 0 time in minutes before chat is automatically deleted', 0),
('update_ip', '127.0.0.1', 0, 'Which ip should be allowed to update DB by executing http request, separate by comma?', 0),
('track_if_offline', '0', 0, 'Track online visitors even if there is no online operators', 0),
('min_phone_length', '8', 0, 'Minimum phone number length', 0),
('mheight', '', 0, 'Messages box height', 0),
('dashboard_order', 'online_operators,departments_stats|pending_chats,unread_chats,transfered_chats|active_chats,closed_chats', 0, 'Home page dashboard widgets order', 0),
('banned_ip_range', '', 0, 'Which ip should not be allowed to chat', 0),
('suggest_leave_msg', '1', 0, 'Suggest user to leave a message then user chooses offline department', 0),
('checkstatus_timeout', '0', 0, 'Interval between chat status checks in seconds, 0 disabled.', 0),
('show_language_switcher', '0', 0, 'Show users option to switch language at widget', 0),
('sharing_auto_allow', '0', 0, 'Do not ask permission for users to see their screen', 0),
('sharing_nodejs_enabled', '0', 0, 'NodeJs support enabled', 0),
('sharing_nodejs_path', '', 0, 'socket.io path, optional', 0),
('online_if', '0', 0, '', 0),
('track_mouse_activity', '0', 0, 'Should mouse movement be tracked as activity measure, if not checked only basic events would be tracked', 0),
('track_activity', '0', 0, 'Track users activity on site?', 0),
('autologin_data', 'a:3:{i:0;b:0;s:11:"secret_hash";s:16:"please_change_me";s:7:"enabled";i:0;}', 0, 'Autologin configuration data', 1),
('sharing_nodejs_secure', '0', 0, 'Connect to NodeJs in https mode', 0),
('disable_js_execution', '1', 0, 'Disable JS execution in Co-Browsing operator window', 0),
('sharing_nodejs_socket_host', '', 0, 'Host where NodeJs is running', 0),
('hide_right_column_frontpage', '0', 0, 'Hide right column in frontpage', 0),
('front_tabs', 'dashboard,online_users,online_map', 0, 'Home page tabs order', 0),
('speech_data', 'a:3:{i:0;b:0;s:8:"language";i:7;s:7:"dialect";s:5:"en-US";}', 1, '', 1),
('sharing_nodejs_sllocation', 'https://cdn.socket.io/socket.io-1.1.0.js', 0, 'Location of SocketIO JS library', 0),
('track_is_online', '0', 0, 'Track is user still on site, chat status checks also has to be enabled', 0),
('show_languages', 'eng,lit,hrv,esp,por,nld,ara,ger,pol,rus,ita,fre,chn,cse,nor,tur,vnm,idn,sve,per,ell,dnk,rou,bgr,tha,geo,fin,alb', 0, 'Between what languages user should be able to switch', 0),
('geoadjustment_data', 'a:8:{i:0;b:0;s:18:"use_geo_adjustment";b:0;s:13:"available_for";s:0:"";s:15:"other_countries";s:6:"custom";s:8:"hide_for";s:0:"";s:12:"other_status";s:7:"offline";s:11:"rest_status";s:6:"hidden";s:12:"apply_widget";i:0;}', 0, 'Geo adjustment settings', 1),
('geo_data', 'a:5:{i:0;b:0;s:21:"geo_detection_enabled";i:1;s:22:"geo_service_identifier";s:8:"max_mind";s:23:"max_mind_detection_type";s:7:"country";s:22:"max_mind_city_location";s:37:"var/external/geoip/GeoLite2-City.mmdb";}', 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lh_chat_file`
--

DROP TABLE IF EXISTS `lh_chat_file`;
CREATE TABLE IF NOT EXISTS `lh_chat_file` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `upload_name` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `online_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_chat_online_user`
--

DROP TABLE IF EXISTS `lh_chat_online_user`;
CREATE TABLE IF NOT EXISTS `lh_chat_online_user` (
  `id` int(11) NOT NULL,
  `vid` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `current_page` text NOT NULL,
  `page_title` varchar(250) NOT NULL,
  `referrer` text NOT NULL,
  `chat_id` int(11) NOT NULL,
  `invitation_seen_count` int(11) NOT NULL,
  `invitation_id` int(11) NOT NULL,
  `last_visit` int(11) NOT NULL,
  `first_visit` int(11) NOT NULL,
  `total_visits` int(11) NOT NULL,
  `pages_count` int(11) NOT NULL,
  `tt_pages_count` int(11) NOT NULL,
  `invitation_count` int(11) NOT NULL,
  `last_check_time` int(11) NOT NULL,
  `dep_id` int(11) NOT NULL,
  `user_agent` varchar(250) NOT NULL,
  `notes` varchar(250) NOT NULL,
  `user_country_code` varchar(50) NOT NULL,
  `user_country_name` varchar(50) NOT NULL,
  `visitor_tz` varchar(50) NOT NULL,
  `operator_message` text NOT NULL,
  `operator_user_proactive` varchar(100) NOT NULL,
  `operator_user_id` int(11) NOT NULL,
  `message_seen` int(11) NOT NULL,
  `message_seen_ts` int(11) NOT NULL,
  `user_active` int(11) NOT NULL,
  `lat` varchar(10) NOT NULL,
  `lon` varchar(10) NOT NULL,
  `city` varchar(100) NOT NULL,
  `reopen_chat` int(11) NOT NULL,
  `time_on_site` int(11) NOT NULL,
  `tt_time_on_site` int(11) NOT NULL,
  `requires_email` int(11) NOT NULL,
  `requires_username` int(11) NOT NULL,
  `requires_phone` int(11) NOT NULL,
  `screenshot_id` int(11) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `operation` text NOT NULL,
  `online_attr_system` text NOT NULL,
  `operation_chat` text NOT NULL,
  `online_attr` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_chat_online_user`
--

INSERT INTO `lh_chat_online_user` (`id`, `vid`, `ip`, `current_page`, `page_title`, `referrer`, `chat_id`, `invitation_seen_count`, `invitation_id`, `last_visit`, `first_visit`, `total_visits`, `pages_count`, `tt_pages_count`, `invitation_count`, `last_check_time`, `dep_id`, `user_agent`, `notes`, `user_country_code`, `user_country_name`, `visitor_tz`, `operator_message`, `operator_user_proactive`, `operator_user_id`, `message_seen`, `message_seen_ts`, `user_active`, `lat`, `lon`, `city`, `reopen_chat`, `time_on_site`, `tt_time_on_site`, `requires_email`, `requires_username`, `requires_phone`, `screenshot_id`, `identifier`, `operation`, `online_attr_system`, `operation_chat`, `online_attr`) VALUES
(1, 'zwjbm8q9lhr5j9fpkfr', '109.87.201.130', 'http://mycodebusters.com/games/index.php/user/page/1/4', 'Магазин игровой валюты', '', 1, 0, 0, 1466014746, 1465961589, 2, 83, 217, 0, 1466014746, 1, 'Mozilla/5.0 (Windows NT 6.0; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0', '', 'ua', 'Ukraine', 'Europe/Helsinki', '', '', 0, 1, 1465961764, 1, '', '', '', 0, 13163, 28893, 0, 0, 0, 0, '', '', '', '', ''),
(2, 'wt9qm7l6bnc8kqzlfuv9', '109.87.201.130', 'http://mycodebusters.com/games/index.php/user/page/0/4', 'Магазин игровой валюты', '', 0, 0, 0, 1466305798, 1465963637, 6, 2, 71, 0, 1466305798, 0, 'Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36', '', 'ua', 'Ukraine', 'Europe/Helsinki', '', '', 0, 0, 0, 1, '', '', '', 0, 24, 6740, 0, 0, 0, 0, '', '', '', '', ''),
(3, '1ramy9j9ysp0jez5b485', '5.164.114.155', 'http://mycodebusters.com/games/', 'Магазин игровой валюты', '', 0, 0, 0, 1465964033, 1465964033, 1, 1, 1, 0, 1465964033, 0, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET4.0C; .NET4.0E)', '', 'ru', 'Russia', 'Europe/Moscow', '', '', 0, 0, 0, 1, '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', '', ''),
(4, '3xsrihhao2vptoqeo2e1', '91.195.91.160', 'http://mycodebusters.com/games/index.php/menu/page/guarantee', 'Магазин игровой валюты', '', 2, 0, 0, 1465970928, 1465970800, 1, 9, 9, 0, 1465970928, 1, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36', '', 'ua', 'Ukraine', 'Europe/Helsinki', '', '', 0, 1, 1465970854, 1, '', '', '', 0, 139, 139, 0, 0, 0, 0, '', '', '', '', ''),
(5, 'wvyodtkpbuw1twqhurnp', '31.170.148.112', 'http://mycodebusters.com/games/index.php/menu/page/news', '', '//mycodebusters.com/games/index.php/menu/page/garant', 0, 0, 0, 1469438310, 1465996639, 4, 0, 14, 0, 1469438310, 0, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36 OPR/38.0.2220.41', '', 'ua', 'Ukraine', '', '', '', 0, 0, 0, 1, '', '', '', 0, 0, 716, 0, 0, 0, 0, '', '', '', '', ''),
(6, 'xymhjosdkabpl1qqfibe', '93.76.126.64', 'http://mycodebusters.com/games/index.php/menu/page/contact', '', '', 0, 0, 0, 1471861827, 1466007746, 3, 0, 10, 0, 1471861827, 0, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36', '', 'ua', 'Ukraine', 'Europe/Helsinki', '', '', 0, 0, 0, 1, '', '', '', 0, 0, 909, 0, 0, 0, 0, '', '', '', '', ''),
(7, 'afwd68qoczdwnh4j8sfj', '109.87.201.130', 'http://mycodebusters.com/games/index.php/games/edit/17', 'Магазин игровой валюты', '', 3, 0, 0, 1469786514, 1466009473, 6, 2, 36, 0, 1469786514, 1, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36', '', 'ua', 'Ukraine', 'Europe/Moscow', '', '', 0, 1, 1469786143, 1, '', '', '', 0, 403, 2598, 0, 0, 0, 0, '', '', '', '', ''),
(8, '3gggs2x0dpormhnroawn', '109.87.201.130', 'http://mycodebusters.com/games/index.php/games/edit/8', 'Магазин игровой валюты', '//mycodebusters.com/games/index.php/menu/page/about', 0, 0, 0, 1466311356, 1466309862, 1, 4, 4, 0, 1466311356, 0, 'Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36 OPR/36.0.2130.65', '', 'ua', 'Ukraine', 'Europe/Helsinki', '', '', 0, 0, 0, 1, '', '', '', 0, 1494, 1494, 0, 0, 0, 0, '', '', '', '', ''),
(9, '4gwx43t9by8971hueh', '94.248.15.23', 'http://mycodebusters.com/games/index.php/user/page/1/4', 'Магазин игровой валюты', '', 0, 0, 0, 1466322268, 1466322169, 1, 6, 6, 0, 1466322268, 0, 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko', '', 'ua', 'Ukraine', 'Europe/Moscow', '', '', 0, 0, 0, 1, '', '', '', 0, 99, 99, 0, 0, 0, 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `lh_chat_online_user_footprint`
--

DROP TABLE IF EXISTS `lh_chat_online_user_footprint`;
CREATE TABLE IF NOT EXISTS `lh_chat_online_user_footprint` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `online_user_id` int(11) NOT NULL,
  `page` varchar(250) NOT NULL,
  `vtime` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_chat_paid`
--

DROP TABLE IF EXISTS `lh_chat_paid`;
CREATE TABLE IF NOT EXISTS `lh_chat_paid` (
  `id` int(11) NOT NULL,
  `hash` varchar(250) NOT NULL,
  `chat_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_cobrowse`
--

DROP TABLE IF EXISTS `lh_cobrowse`;
CREATE TABLE IF NOT EXISTS `lh_cobrowse` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `online_user_id` int(11) NOT NULL,
  `mtime` int(11) NOT NULL,
  `url` varchar(250) NOT NULL,
  `initialize` longtext NOT NULL,
  `modifications` longtext NOT NULL,
  `finished` tinyint(1) NOT NULL,
  `w` int(11) NOT NULL,
  `wh` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_departament`
--

DROP TABLE IF EXISTS `lh_departament`;
CREATE TABLE IF NOT EXISTS `lh_departament` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `xmpp_recipients` text NOT NULL,
  `xmpp_group_recipients` text NOT NULL,
  `priority` int(11) NOT NULL,
  `sort_priority` int(11) NOT NULL,
  `department_transfer_id` int(11) NOT NULL,
  `transfer_timeout` int(11) NOT NULL,
  `disabled` int(11) NOT NULL,
  `hidden` int(11) NOT NULL,
  `delay_lm` int(11) NOT NULL,
  `max_active_chats` int(11) NOT NULL,
  `max_timeout_seconds` int(11) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `mod` tinyint(1) NOT NULL,
  `tud` tinyint(1) NOT NULL,
  `wed` tinyint(1) NOT NULL,
  `thd` tinyint(1) NOT NULL,
  `frd` tinyint(1) NOT NULL,
  `sad` tinyint(1) NOT NULL,
  `sud` tinyint(1) NOT NULL,
  `nc_cb_execute` tinyint(1) NOT NULL,
  `na_cb_execute` tinyint(1) NOT NULL,
  `inform_unread` tinyint(1) NOT NULL,
  `active_balancing` tinyint(1) NOT NULL,
  `visible_if_online` tinyint(1) NOT NULL,
  `start_hour` int(2) NOT NULL,
  `end_hour` int(2) NOT NULL,
  `inform_close` int(11) NOT NULL,
  `inform_unread_delay` int(11) NOT NULL,
  `inform_options` varchar(250) NOT NULL,
  `online_hours_active` tinyint(1) NOT NULL,
  `inform_delay` int(11) NOT NULL,
  `attr_int_1` int(11) NOT NULL,
  `attr_int_2` int(11) NOT NULL,
  `attr_int_3` int(11) NOT NULL,
  `active_chats_counter` int(11) NOT NULL,
  `pending_chats_counter` int(11) NOT NULL,
  `closed_chats_counter` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_departament`
--

INSERT INTO `lh_departament` (`id`, `name`, `email`, `xmpp_recipients`, `xmpp_group_recipients`, `priority`, `sort_priority`, `department_transfer_id`, `transfer_timeout`, `disabled`, `hidden`, `delay_lm`, `max_active_chats`, `max_timeout_seconds`, `identifier`, `mod`, `tud`, `wed`, `thd`, `frd`, `sad`, `sud`, `nc_cb_execute`, `na_cb_execute`, `inform_unread`, `active_balancing`, `visible_if_online`, `start_hour`, `end_hour`, `inform_close`, `inform_unread_delay`, `inform_options`, `online_hours_active`, `inform_delay`, `attr_int_1`, `attr_int_2`, `attr_int_3`, `active_chats_counter`, `pending_chats_counter`, `closed_chats_counter`) VALUES
(1, 'IT', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lh_faq`
--

DROP TABLE IF EXISTS `lh_faq`;
CREATE TABLE IF NOT EXISTS `lh_faq` (
  `id` int(11) NOT NULL,
  `question` varchar(250) NOT NULL,
  `answer` text NOT NULL,
  `url` varchar(250) NOT NULL,
  `email` varchar(50) NOT NULL,
  `identifier` varchar(10) NOT NULL,
  `active` int(11) NOT NULL,
  `has_url` tinyint(1) NOT NULL,
  `is_wildcard` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_forgotpasswordhash`
--

DROP TABLE IF EXISTS `lh_forgotpasswordhash`;
CREATE TABLE IF NOT EXISTS `lh_forgotpasswordhash` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `created` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_group`
--

DROP TABLE IF EXISTS `lh_group`;
CREATE TABLE IF NOT EXISTS `lh_group` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `disabled` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_group`
--

INSERT INTO `lh_group` (`id`, `name`, `disabled`) VALUES
(1, 'Administrators', 0),
(2, 'Operators', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lh_grouprole`
--

DROP TABLE IF EXISTS `lh_grouprole`;
CREATE TABLE IF NOT EXISTS `lh_grouprole` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_grouprole`
--

INSERT INTO `lh_grouprole` (`id`, `group_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `lh_groupuser`
--

DROP TABLE IF EXISTS `lh_groupuser`;
CREATE TABLE IF NOT EXISTS `lh_groupuser` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_groupuser`
--

INSERT INTO `lh_groupuser` (`id`, `group_id`, `user_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lh_msg`
--

DROP TABLE IF EXISTS `lh_msg`;
CREATE TABLE IF NOT EXISTS `lh_msg` (
  `id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `time` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `name_support` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_msg`
--

INSERT INTO `lh_msg` (`id`, `msg`, `time`, `chat_id`, `user_id`, `name_support`) VALUES
(1, 'У меня есть вопрос ....', 1465961764, 1, 0, ''),
(2, 'Admin User Принял чат!', 1465961789, 1, -1, ''),
(3, 'Я слушаю Вас ...', 1465961817, 1, 1, 'Admin User'),
(4, 'Как стать партнером?', 1465961833, 1, 0, ''),
(5, 'sa', 1465963400, 1, 0, ''),
(6, 'asfasfdasfsdf', 1465970854, 2, 0, ''),
(7, 'ddghfdf', 1469786144, 3, 0, ''),
(8, 'Admin User Принял чат!', 1469786156, 3, -1, ''),
(9, 'asdefwe', 1469786161, 3, 1, 'Admin User');

-- --------------------------------------------------------

--
-- Table structure for table `lh_question`
--

DROP TABLE IF EXISTS `lh_question`;
CREATE TABLE IF NOT EXISTS `lh_question` (
  `id` int(11) NOT NULL,
  `question` varchar(250) NOT NULL,
  `location` varchar(250) NOT NULL,
  `active` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `is_voting` int(11) NOT NULL,
  `question_intro` text NOT NULL,
  `revote` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_question_answer`
--

DROP TABLE IF EXISTS `lh_question_answer`;
CREATE TABLE IF NOT EXISTS `lh_question_answer` (
  `id` int(11) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `ctime` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_question_option`
--

DROP TABLE IF EXISTS `lh_question_option`;
CREATE TABLE IF NOT EXISTS `lh_question_option` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_name` varchar(250) NOT NULL,
  `priority` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_question_option_answer`
--

DROP TABLE IF EXISTS `lh_question_option_answer`;
CREATE TABLE IF NOT EXISTS `lh_question_option_answer` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `ip` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_role`
--

DROP TABLE IF EXISTS `lh_role`;
CREATE TABLE IF NOT EXISTS `lh_role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_role`
--

INSERT INTO `lh_role` (`id`, `name`) VALUES
(1, 'Administrators'),
(2, 'Operators');

-- --------------------------------------------------------

--
-- Table structure for table `lh_rolefunction`
--

DROP TABLE IF EXISTS `lh_rolefunction`;
CREATE TABLE IF NOT EXISTS `lh_rolefunction` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `function` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_rolefunction`
--

INSERT INTO `lh_rolefunction` (`id`, `role_id`, `module`, `function`) VALUES
(1, 1, '*', '*'),
(2, 2, 'lhuser', 'selfedit'),
(3, 2, 'lhuser', 'changeonlinestatus'),
(4, 2, 'lhuser', 'changeskypenick'),
(5, 2, 'lhuser', 'personalcannedmsg'),
(6, 2, 'lhuser', 'change_visibility_list'),
(7, 2, 'lhuser', 'see_assigned_departments'),
(8, 2, 'lhuser', 'canseedepartmentstats'),
(9, 2, 'lhchat', 'use'),
(10, 2, 'lhchat', 'chattabschrome'),
(11, 2, 'lhchat', 'singlechatwindow'),
(12, 2, 'lhchat', 'allowopenremotechat'),
(13, 2, 'lhchat', 'allowchattabs'),
(14, 2, 'lhchat', 'use_onlineusers'),
(15, 2, 'lhchat', 'take_screenshot'),
(16, 2, 'lhfront', 'use'),
(17, 2, 'lhsystem', 'use'),
(18, 2, 'lhtranslation', 'use'),
(19, 2, 'lhchat', 'allowblockusers'),
(20, 2, 'lhsystem', 'generatejs'),
(21, 2, 'lhsystem', 'changelanguage'),
(22, 2, 'lhchat', 'allowredirect'),
(23, 2, 'lhchat', 'allowtransfer'),
(24, 2, 'lhchat', 'administratecannedmsg'),
(25, 2, 'lhchat', 'sees_all_online_visitors'),
(26, 2, 'lhpermission', 'see_permissions'),
(27, 2, 'lhquestionary', 'manage_questionary'),
(28, 2, 'lhfaq', 'manage_faq'),
(29, 2, 'lhchatbox', 'manage_chatbox'),
(30, 2, 'lhbrowseoffer', 'manage_bo'),
(31, 2, 'lhxml', '*'),
(32, 2, 'lhcobrowse', 'browse'),
(33, 2, 'lhfile', 'use_operator'),
(34, 2, 'lhfile', 'file_delete_chat'),
(35, 2, 'lhstatistic', 'use'),
(36, 2, 'lhspeech', 'changedefaultlanguage'),
(37, 2, 'lhspeech', 'use'),
(38, 2, 'lhspeech', 'change_chat_recognition');

-- --------------------------------------------------------

--
-- Table structure for table `lh_speech_chat_language`
--

DROP TABLE IF EXISTS `lh_speech_chat_language`;
CREATE TABLE IF NOT EXISTS `lh_speech_chat_language` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `dialect` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_speech_language`
--

DROP TABLE IF EXISTS `lh_speech_language`;
CREATE TABLE IF NOT EXISTS `lh_speech_language` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_speech_language`
--

INSERT INTO `lh_speech_language` (`id`, `name`) VALUES
(1, 'Afrikaans'),
(2, 'Bahasa Indonesia'),
(3, 'Bahasa Melayu'),
(4, 'Català'),
(5, 'Čeština'),
(6, 'Deutsch'),
(7, 'English'),
(8, 'Español'),
(9, 'Euskara'),
(10, 'Français'),
(11, 'Galego'),
(12, 'Hrvatski'),
(13, 'IsiZulu'),
(14, 'Íslenska'),
(15, 'Italiano'),
(16, 'Magyar'),
(17, 'Nederlands'),
(18, 'Norsk bokmål'),
(19, 'Polski'),
(20, 'Português'),
(21, 'Română'),
(22, 'Slovenčina'),
(23, 'Suomi'),
(24, 'Svenska'),
(25, 'Türkçe'),
(26, 'български'),
(27, 'Pусский'),
(28, 'Српски'),
(29, '한국어'),
(30, '中文'),
(31, '日本語'),
(32, 'Lingua latīna');

-- --------------------------------------------------------

--
-- Table structure for table `lh_speech_language_dialect`
--

DROP TABLE IF EXISTS `lh_speech_language_dialect`;
CREATE TABLE IF NOT EXISTS `lh_speech_language_dialect` (
  `id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `lang_name` varchar(100) NOT NULL,
  `lang_code` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_speech_language_dialect`
--

INSERT INTO `lh_speech_language_dialect` (`id`, `language_id`, `lang_name`, `lang_code`) VALUES
(1, 1, 'Afrikaans', 'af-ZA'),
(2, 2, 'Bahasa Indonesia', 'id-ID'),
(3, 3, 'Bahasa Melayu', 'ms-MY'),
(4, 4, 'Català', 'ca-ES'),
(5, 5, 'Čeština', 'cs-CZ'),
(6, 6, 'Deutsch', 'de-DE'),
(7, 7, 'Australia', 'en-AU'),
(8, 7, 'Canada', 'en-CA'),
(9, 7, 'India', 'en-IN'),
(10, 7, 'New Zealand', 'en-NZ'),
(11, 7, 'South Africa', 'en-ZA'),
(12, 7, 'United Kingdom', 'en-GB'),
(13, 7, 'United States', 'en-US'),
(14, 8, 'Argentina', 'es-AR'),
(15, 8, 'Bolivia', 'es-BO'),
(16, 8, 'Chile', 'es-CL'),
(17, 8, 'Colombia', 'es-CO'),
(18, 8, 'Costa Rica', 'es-CR'),
(19, 8, 'Ecuador', 'es-EC'),
(20, 8, 'El Salvador', 'es-SV'),
(21, 8, 'España', 'es-ES'),
(22, 8, 'Estados Unidos', 'es-US'),
(23, 8, 'Guatemala', 'es-GT'),
(24, 8, 'Honduras', 'es-HN'),
(25, 8, 'México', 'es-MX'),
(26, 8, 'Nicaragua', 'es-NI'),
(27, 8, 'Panamá', 'es-PA'),
(28, 8, 'Paraguay', 'es-PY'),
(29, 8, 'Perú', 'es-PE'),
(30, 8, 'Puerto Rico', 'es-PR'),
(31, 8, 'República Dominicana', 'es-DO'),
(32, 8, 'Uruguay', 'es-UY'),
(33, 8, 'Venezuela', 'es-VE'),
(34, 9, 'Euskara', 'eu-ES'),
(35, 10, 'Français', 'fr-FR'),
(36, 11, 'Galego', 'gl-ES'),
(37, 12, 'Hrvatski', 'hr_HR'),
(38, 13, 'IsiZulu', 'zu-ZA'),
(39, 14, 'Íslenska', 'is-IS'),
(40, 15, 'Italia', 'it-IT'),
(41, 15, 'Svizzera', 'it-CH'),
(42, 16, 'Magyar', 'hu-HU'),
(43, 17, 'Nederlands', 'nl-NL'),
(44, 18, 'Norsk bokmål', 'nb-NO'),
(45, 19, 'Polski', 'pl-PL'),
(46, 20, 'Brasil', 'pt-BR'),
(47, 20, 'Portugal', 'pt-PT'),
(48, 21, 'Română', 'ro-RO'),
(49, 22, 'Slovenčina', 'sk-SK'),
(50, 23, 'Suomi', 'fi-FI'),
(51, 24, 'Svenska', 'sv-SE'),
(52, 25, 'Türkçe', 'tr-TR'),
(53, 26, 'български', 'bg-BG'),
(54, 27, 'Pусский', 'ru-RU'),
(55, 28, 'Српски', 'sr-RS'),
(56, 29, '한국어', 'ko-KR'),
(57, 30, '普通话 (中国大陆)', 'cmn-Hans-CN'),
(58, 30, '普通话 (香港)', 'cmn-Hans-HK'),
(59, 30, '中文 (台灣)', 'cmn-Hant-TW'),
(60, 30, '粵語 (香港)', 'yue-Hant-HK'),
(61, 31, '日本語', 'ja-JP'),
(62, 32, 'Lingua latīna', 'la');

-- --------------------------------------------------------

--
-- Table structure for table `lh_transfer`
--

DROP TABLE IF EXISTS `lh_transfer`;
CREATE TABLE IF NOT EXISTS `lh_transfer` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `dep_id` int(11) NOT NULL,
  `transfer_user_id` int(11) NOT NULL,
  `from_dep_id` int(11) NOT NULL,
  `transfer_to_user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_userdep`
--

DROP TABLE IF EXISTS `lh_userdep`;
CREATE TABLE IF NOT EXISTS `lh_userdep` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dep_id` int(11) NOT NULL,
  `last_activity` int(11) NOT NULL,
  `hide_online` int(11) NOT NULL,
  `last_accepted` int(11) NOT NULL,
  `active_chats` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_userdep`
--

INSERT INTO `lh_userdep` (`id`, `user_id`, `dep_id`, `last_activity`, `hide_online`, `last_accepted`, `active_chats`) VALUES
(1, 1, 0, 0, 0, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `lh_users`
--

DROP TABLE IF EXISTS `lh_users`;
CREATE TABLE IF NOT EXISTS `lh_users` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `time_zone` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `filepath` varchar(200) NOT NULL,
  `filename` varchar(200) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `departments_ids` varchar(100) NOT NULL,
  `chat_nickname` varchar(100) NOT NULL,
  `xmpp_username` varchar(200) NOT NULL,
  `session_id` varchar(40) NOT NULL,
  `skype` varchar(50) NOT NULL,
  `disabled` tinyint(4) NOT NULL,
  `hide_online` tinyint(1) NOT NULL,
  `all_departments` tinyint(1) NOT NULL,
  `invisible_mode` tinyint(1) NOT NULL,
  `rec_per_req` tinyint(1) NOT NULL,
  `active_chats_counter` int(11) NOT NULL,
  `closed_chats_counter` int(11) NOT NULL,
  `pending_chats_counter` int(11) NOT NULL,
  `attr_int_1` int(11) NOT NULL,
  `attr_int_2` int(11) NOT NULL,
  `attr_int_3` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_users`
--

INSERT INTO `lh_users` (`id`, `username`, `password`, `email`, `time_zone`, `name`, `surname`, `filepath`, `filename`, `job_title`, `departments_ids`, `chat_nickname`, `xmpp_username`, `session_id`, `skype`, `disabled`, `hide_online`, `all_departments`, `invisible_mode`, `rec_per_req`, `active_chats_counter`, `closed_chats_counter`, `pending_chats_counter`, `attr_int_1`, `attr_int_2`, `attr_int_3`) VALUES
(1, 'admin@ecm-games.com', '$2y$10$yN7KEtNKeyaQ0XYXk4uMXu3a3xYmEnuqva0e9HAoBMlOR7ElV8YeC', 'admin@ecm-games.com', '', 'Admin', 'User', '', '', '', '0', '', '', '', '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lh_users_remember`
--

DROP TABLE IF EXISTS `lh_users_remember`;
CREATE TABLE IF NOT EXISTS `lh_users_remember` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mtime` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_users_setting`
--

DROP TABLE IF EXISTS `lh_users_setting`;
CREATE TABLE IF NOT EXISTS `lh_users_setting` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_users_setting`
--

INSERT INTO `lh_users_setting` (`id`, `user_id`, `identifier`, `value`) VALUES
(1, 1, 'user_language', 'ru_RU'),
(2, 1, 'enable_pending_list', '1'),
(3, 1, 'enable_active_list', '1'),
(4, 1, 'enable_close_list', '0'),
(5, 1, 'enable_unread_list', '1'),
(6, 1, 'new_user_bn', '0'),
(7, 1, 'new_user_sound', '0'),
(8, 1, 'o_department', '0'),
(9, 1, 'ouser_timeout', '3600'),
(10, 1, 'oupdate_timeout', '10'),
(11, 1, 'omax_rows', '50'),
(12, 1, 'ogroup_by', 'none'),
(13, 1, 'omap_depid', '0'),
(14, 1, 'omap_mtimeout', '30'),
(15, 1, 'dwo', ''),
(16, 1, 'new_chat_sound', '1'),
(17, 1, 'chat_message', '1'),
(18, 1, 'show_all_pending', '1');

-- --------------------------------------------------------

--
-- Table structure for table `lh_users_setting_option`
--

DROP TABLE IF EXISTS `lh_users_setting_option`;
CREATE TABLE IF NOT EXISTS `lh_users_setting_option` (
  `identifier` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  `attribute` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_users_setting_option`
--

INSERT INTO `lh_users_setting_option` (`identifier`, `class`, `attribute`) VALUES
('chat_message', '', ''),
('new_chat_sound', '', ''),
('enable_pending_list', '', ''),
('enable_active_list', '', ''),
('enable_close_list', '', ''),
('new_user_bn', '', ''),
('new_user_sound', '', ''),
('oupdate_timeout', '', ''),
('ouser_timeout', '', ''),
('o_department', '', ''),
('omax_rows', '', ''),
('ogroup_by', '', ''),
('omap_depid', '', ''),
('omap_mtimeout', '', ''),
('dwo', '', ''),
('enable_unread_list', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lh_abstract_auto_responder`
--
ALTER TABLE `lh_abstract_auto_responder`
  ADD PRIMARY KEY (`id`), ADD KEY `siteaccess_position` (`siteaccess`,`position`);

--
-- Indexes for table `lh_abstract_browse_offer_invitation`
--
ALTER TABLE `lh_abstract_browse_offer_invitation`
  ADD PRIMARY KEY (`id`), ADD KEY `active` (`active`), ADD KEY `identifier` (`identifier`);

--
-- Indexes for table `lh_abstract_email_template`
--
ALTER TABLE `lh_abstract_email_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lh_abstract_form`
--
ALTER TABLE `lh_abstract_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lh_abstract_form_collected`
--
ALTER TABLE `lh_abstract_form_collected`
  ADD PRIMARY KEY (`id`), ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `lh_abstract_proactive_chat_invitation`
--
ALTER TABLE `lh_abstract_proactive_chat_invitation`
  ADD PRIMARY KEY (`id`), ADD KEY `time_on_site_pageviews_siteaccess_position` (`time_on_site`,`pageviews`,`siteaccess`,`identifier`,`position`), ADD KEY `identifier` (`identifier`), ADD KEY `dep_id` (`dep_id`);

--
-- Indexes for table `lh_abstract_product`
--
ALTER TABLE `lh_abstract_product`
  ADD PRIMARY KEY (`id`), ADD KEY `departament_id` (`departament_id`);

--
-- Indexes for table `lh_abstract_rest_api_key`
--
ALTER TABLE `lh_abstract_rest_api_key`
  ADD PRIMARY KEY (`id`), ADD KEY `api_key` (`api_key`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lh_abstract_survey`
--
ALTER TABLE `lh_abstract_survey`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lh_abstract_survey_item`
--
ALTER TABLE `lh_abstract_survey_item`
  ADD PRIMARY KEY (`id`), ADD KEY `survey_id` (`survey_id`), ADD KEY `chat_id` (`chat_id`), ADD KEY `user_id` (`user_id`), ADD KEY `dep_id` (`dep_id`), ADD KEY `ftime` (`ftime`);

--
-- Indexes for table `lh_abstract_widget_theme`
--
ALTER TABLE `lh_abstract_widget_theme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lh_canned_msg`
--
ALTER TABLE `lh_canned_msg`
  ADD PRIMARY KEY (`id`), ADD KEY `department_id` (`department_id`), ADD KEY `attr_int_1` (`attr_int_1`), ADD KEY `attr_int_2` (`attr_int_2`), ADD KEY `attr_int_3` (`attr_int_3`), ADD KEY `position_title` (`position`,`title`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lh_chat`
--
ALTER TABLE `lh_chat`
  ADD PRIMARY KEY (`id`), ADD KEY `status_user_id` (`status`,`user_id`), ADD KEY `user_id` (`user_id`), ADD KEY `unanswered_chat` (`unanswered_chat`), ADD KEY `online_user_id` (`online_user_id`), ADD KEY `dep_id` (`dep_id`), ADD KEY `product_id` (`product_id`), ADD KEY `has_unread_messages_dep_id_id` (`has_unread_messages`,`dep_id`,`id`), ADD KEY `status_dep_id_id` (`status`,`dep_id`,`id`), ADD KEY `status_dep_id_priority_id` (`status`,`dep_id`,`priority`,`id`), ADD KEY `status_priority_id` (`status`,`priority`,`id`);

--
-- Indexes for table `lh_chatbox`
--
ALTER TABLE `lh_chatbox`
  ADD PRIMARY KEY (`id`), ADD KEY `identifier` (`identifier`);

--
-- Indexes for table `lh_chat_accept`
--
ALTER TABLE `lh_chat_accept`
  ADD PRIMARY KEY (`id`), ADD KEY `hash` (`hash`);

--
-- Indexes for table `lh_chat_archive_range`
--
ALTER TABLE `lh_chat_archive_range`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lh_chat_blocked_user`
--
ALTER TABLE `lh_chat_blocked_user`
  ADD PRIMARY KEY (`id`), ADD KEY `ip` (`ip`);

--
-- Indexes for table `lh_chat_config`
--
ALTER TABLE `lh_chat_config`
  ADD PRIMARY KEY (`identifier`);

--
-- Indexes for table `lh_chat_file`
--
ALTER TABLE `lh_chat_file`
  ADD PRIMARY KEY (`id`), ADD KEY `chat_id` (`chat_id`), ADD KEY `online_user_id` (`online_user_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lh_chat_online_user`
--
ALTER TABLE `lh_chat_online_user`
  ADD PRIMARY KEY (`id`), ADD KEY `vid` (`vid`), ADD KEY `dep_id` (`dep_id`), ADD KEY `last_visit_dep_id` (`last_visit`,`dep_id`);

--
-- Indexes for table `lh_chat_online_user_footprint`
--
ALTER TABLE `lh_chat_online_user_footprint`
  ADD PRIMARY KEY (`id`), ADD KEY `chat_id_vtime` (`chat_id`,`vtime`), ADD KEY `online_user_id` (`online_user_id`);

--
-- Indexes for table `lh_chat_paid`
--
ALTER TABLE `lh_chat_paid`
  ADD PRIMARY KEY (`id`), ADD KEY `hash` (`hash`), ADD KEY `chat_id` (`chat_id`);

--
-- Indexes for table `lh_cobrowse`
--
ALTER TABLE `lh_cobrowse`
  ADD PRIMARY KEY (`id`), ADD KEY `chat_id` (`chat_id`), ADD KEY `online_user_id` (`online_user_id`);

--
-- Indexes for table `lh_departament`
--
ALTER TABLE `lh_departament`
  ADD PRIMARY KEY (`id`), ADD KEY `identifier` (`identifier`), ADD KEY `attr_int_1` (`attr_int_1`), ADD KEY `attr_int_2` (`attr_int_2`), ADD KEY `attr_int_3` (`attr_int_3`), ADD KEY `disabled_hidden` (`disabled`,`hidden`), ADD KEY `sort_priority_name` (`sort_priority`,`name`), ADD KEY `oha_sh_eh` (`online_hours_active`,`start_hour`,`end_hour`);

--
-- Indexes for table `lh_faq`
--
ALTER TABLE `lh_faq`
  ADD PRIMARY KEY (`id`), ADD KEY `active` (`active`), ADD KEY `active_url` (`active`,`url`), ADD KEY `has_url` (`has_url`), ADD KEY `identifier` (`identifier`), ADD KEY `is_wildcard` (`is_wildcard`);

--
-- Indexes for table `lh_forgotpasswordhash`
--
ALTER TABLE `lh_forgotpasswordhash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lh_group`
--
ALTER TABLE `lh_group`
  ADD PRIMARY KEY (`id`), ADD KEY `disabled` (`disabled`);

--
-- Indexes for table `lh_grouprole`
--
ALTER TABLE `lh_grouprole`
  ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`role_id`,`group_id`), ADD KEY `group_id_primary` (`group_id`);

--
-- Indexes for table `lh_groupuser`
--
ALTER TABLE `lh_groupuser`
  ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`group_id`), ADD KEY `user_id` (`user_id`), ADD KEY `group_id_2` (`group_id`,`user_id`);

--
-- Indexes for table `lh_msg`
--
ALTER TABLE `lh_msg`
  ADD PRIMARY KEY (`id`), ADD KEY `chat_id_id` (`chat_id`,`id`);

--
-- Indexes for table `lh_question`
--
ALTER TABLE `lh_question`
  ADD PRIMARY KEY (`id`), ADD KEY `priority` (`priority`), ADD KEY `active_priority` (`active`,`priority`);

--
-- Indexes for table `lh_question_answer`
--
ALTER TABLE `lh_question_answer`
  ADD PRIMARY KEY (`id`), ADD KEY `ip` (`ip`), ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `lh_question_option`
--
ALTER TABLE `lh_question_option`
  ADD PRIMARY KEY (`id`), ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `lh_question_option_answer`
--
ALTER TABLE `lh_question_option_answer`
  ADD PRIMARY KEY (`id`), ADD KEY `question_id` (`question_id`), ADD KEY `ip` (`ip`);

--
-- Indexes for table `lh_role`
--
ALTER TABLE `lh_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lh_rolefunction`
--
ALTER TABLE `lh_rolefunction`
  ADD PRIMARY KEY (`id`), ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `lh_speech_chat_language`
--
ALTER TABLE `lh_speech_chat_language`
  ADD PRIMARY KEY (`id`), ADD KEY `chat_id` (`chat_id`);

--
-- Indexes for table `lh_speech_language`
--
ALTER TABLE `lh_speech_language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lh_speech_language_dialect`
--
ALTER TABLE `lh_speech_language_dialect`
  ADD PRIMARY KEY (`id`), ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `lh_transfer`
--
ALTER TABLE `lh_transfer`
  ADD PRIMARY KEY (`id`), ADD KEY `dep_id` (`dep_id`), ADD KEY `transfer_user_id_dep_id` (`transfer_user_id`,`dep_id`), ADD KEY `transfer_to_user_id` (`transfer_to_user_id`);

--
-- Indexes for table `lh_userdep`
--
ALTER TABLE `lh_userdep`
  ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `last_activity_hide_online_dep_id` (`last_activity`,`hide_online`,`dep_id`), ADD KEY `dep_id` (`dep_id`);

--
-- Indexes for table `lh_users`
--
ALTER TABLE `lh_users`
  ADD PRIMARY KEY (`id`), ADD KEY `hide_online` (`hide_online`), ADD KEY `rec_per_req` (`rec_per_req`), ADD KEY `email` (`email`), ADD KEY `xmpp_username` (`xmpp_username`);

--
-- Indexes for table `lh_users_remember`
--
ALTER TABLE `lh_users_remember`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lh_users_setting`
--
ALTER TABLE `lh_users_setting`
  ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`,`identifier`);

--
-- Indexes for table `lh_users_setting_option`
--
ALTER TABLE `lh_users_setting_option`
  ADD PRIMARY KEY (`identifier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lh_abstract_auto_responder`
--
ALTER TABLE `lh_abstract_auto_responder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_abstract_browse_offer_invitation`
--
ALTER TABLE `lh_abstract_browse_offer_invitation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_abstract_email_template`
--
ALTER TABLE `lh_abstract_email_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `lh_abstract_form`
--
ALTER TABLE `lh_abstract_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_abstract_form_collected`
--
ALTER TABLE `lh_abstract_form_collected`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_abstract_proactive_chat_invitation`
--
ALTER TABLE `lh_abstract_proactive_chat_invitation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_abstract_product`
--
ALTER TABLE `lh_abstract_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_abstract_rest_api_key`
--
ALTER TABLE `lh_abstract_rest_api_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_abstract_survey`
--
ALTER TABLE `lh_abstract_survey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_abstract_survey_item`
--
ALTER TABLE `lh_abstract_survey_item`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_abstract_widget_theme`
--
ALTER TABLE `lh_abstract_widget_theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_canned_msg`
--
ALTER TABLE `lh_canned_msg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_chat`
--
ALTER TABLE `lh_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `lh_chatbox`
--
ALTER TABLE `lh_chatbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_chat_accept`
--
ALTER TABLE `lh_chat_accept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_chat_archive_range`
--
ALTER TABLE `lh_chat_archive_range`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_chat_blocked_user`
--
ALTER TABLE `lh_chat_blocked_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_chat_file`
--
ALTER TABLE `lh_chat_file`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_chat_online_user`
--
ALTER TABLE `lh_chat_online_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `lh_chat_online_user_footprint`
--
ALTER TABLE `lh_chat_online_user_footprint`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_chat_paid`
--
ALTER TABLE `lh_chat_paid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_cobrowse`
--
ALTER TABLE `lh_cobrowse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_departament`
--
ALTER TABLE `lh_departament`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lh_faq`
--
ALTER TABLE `lh_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_forgotpasswordhash`
--
ALTER TABLE `lh_forgotpasswordhash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_group`
--
ALTER TABLE `lh_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `lh_grouprole`
--
ALTER TABLE `lh_grouprole`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `lh_groupuser`
--
ALTER TABLE `lh_groupuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lh_msg`
--
ALTER TABLE `lh_msg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `lh_question`
--
ALTER TABLE `lh_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_question_answer`
--
ALTER TABLE `lh_question_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_question_option`
--
ALTER TABLE `lh_question_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_question_option_answer`
--
ALTER TABLE `lh_question_option_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_role`
--
ALTER TABLE `lh_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `lh_rolefunction`
--
ALTER TABLE `lh_rolefunction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `lh_speech_chat_language`
--
ALTER TABLE `lh_speech_chat_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_speech_language`
--
ALTER TABLE `lh_speech_language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `lh_speech_language_dialect`
--
ALTER TABLE `lh_speech_language_dialect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `lh_transfer`
--
ALTER TABLE `lh_transfer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_userdep`
--
ALTER TABLE `lh_userdep`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lh_users`
--
ALTER TABLE `lh_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `lh_users_remember`
--
ALTER TABLE `lh_users_remember`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lh_users_setting`
--
ALTER TABLE `lh_users_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
