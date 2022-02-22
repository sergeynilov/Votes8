-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Мар 13 2019 г., 17:52
-- Версия сервера: 5.7.25-0ubuntu0.18.04.2
-- Версия PHP: 7.2.15-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Votes`
--

-- --------------------------------------------------------

--
-- Структура таблицы `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` int(11) DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`) VALUES
(1, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2018-10-25 10:08:39'),
(2, 'admin@mail.com', 'Failed login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, NULL, 'failed_login', '', '2018-10-25 11:13:43'),
(3, 'admin@mail.com', 'Failed login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, NULL, 'failed_login', '', '2018-10-25 11:13:47'),
(4, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2018-10-25 11:13:49'),
(5, 'test', 'Testing email was sent ret = ', -1, NULL, -1, 'Email Testing', '0', '2018-10-25 11:13:50'),
(6, 'JackParrot', 'Successful registration from ip 127.0.0.1 with \'JackParrot\' username, \'admin@mail.commm\' email ', NULL, NULL, 45, 'successful_user_registration', '', '2018-10-25 11:22:21'),
(7, 'Admin', 'Admin voted not correctly on \'Who Framed Roger Rabbit ?\' vote ', 2, NULL, 5, 'vote_selected', '0', '2018-10-25 11:24:05'),
(8, 'Admin', 'Admin set quiz quality 5 on \'Who Framed Roger Rabbit ?\' vote ', 2, NULL, 5, 'set_quiz_quality', '5', '2018-10-25 11:24:12'),
(9, 'Admin', 'Admin voted correctly on \'Who Framed Roger Rabbit ?\' vote ', 2, NULL, 5, 'vote_selected', '1', '2018-10-25 11:24:18'),
(10, 'Admin', 'Admin voted not correctly on \'Which fictional city is the home of Batman ?\' vote ', 4, NULL, 5, 'vote_selected', '0', '2018-10-25 11:24:45'),
(11, 'Admin', 'Admin set quiz quality 3 on \'Which fictional city is the home of Batman ?\' vote ', 4, NULL, 5, 'set_quiz_quality', '3', '2018-10-25 11:24:48'),
(12, 'Admin', 'Admin voted correctly on \'Which fictional city is the home of Batman ?\' vote ', 4, NULL, 5, 'vote_selected', '1', '2018-10-25 11:25:01'),
(13, 'JackParrot', 'Successful activation from ip 127.0.0.1 with \'JackParrot\' username, \'admin@mail.commm\' email ', NULL, NULL, 45, 'successful_user_activation', '', '2018-10-25 11:30:03'),
(14, 'JackParroda', 'Successful registration from ip 127.0.0.1 with \'JackParroda\' username, \'JackParroda@mail.com\' email ', NULL, NULL, 46, 'successful_user_activation', '', '2018-10-25 11:32:37'),
(15, 'JackParroda', 'Successful activation from ip 127.0.0.1 with \'JackParroda\' username, \'JackParroda@mail.com\' email ', NULL, NULL, 46, 'successful_user_registration', '', '2018-10-25 11:33:10'),
(16, 'Admiuterneferzer', 'Contact Us was sent from ip 127.0.0.1 with \'Admiuterneferzer\' username, \'Admiuterneferzer@mail.com\' email ', 13, NULL, NULL, 'successful_user_contact_us_sent', '', '2018-10-25 11:39:31'),
(17, 'Admin', 'Admin voted correctly on \'Which is the tallest mammal?\' vote ', 14, NULL, 5, 'vote_selected', '1', '2018-10-25 11:40:15'),
(18, 'Admin', 'Admin set quiz quality 3 on \'Which is the tallest mammal?\' vote ', 14, NULL, 5, 'set_quiz_quality', '3', '2018-10-25 11:40:17'),
(19, 'Admin', 'Admin voted not correctly on \'Which is the tallest mammal?\' vote ', 14, NULL, 5, 'vote_selected', '0', '2018-10-25 11:40:24'),
(20, 'admin@mail.com', 'Failed login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, NULL, 'failed_login', '', '2018-10-25 11:40:50'),
(21, 'adggmin@mail.comgg', 'Failed login from ip 127.0.0.1 with \'adggmin@mail.comgg\' email ', NULL, NULL, NULL, 'failed_login', '', '2018-10-25 11:40:57'),
(22, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-02-26 07:23:00'),
(23, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-02-26 08:58:29'),
(24, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-02-26 11:02:46'),
(25, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-02-27 08:16:39'),
(26, 'votes_demo@votes.com', 'Failed login from ip 127.0.0.1 with \'votes_demo@votes.com\' email ', NULL, NULL, NULL, 'failed_login', '', '2019-02-27 12:45:36'),
(27, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-02-27 12:45:39'),
(28, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-02-28 04:18:20'),
(29, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-03-10 12:07:13'),
(30, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-03-11 06:13:49'),
(31, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-03-11 11:01:47'),
(32, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-03-11 14:45:23'),
(33, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-03-12 11:28:00'),
(34, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-03-13 10:57:39'),
(36, 'JackParrot', 'Successful registration from ip 127.0.0.1 with \'JackParrot\' username, \'admin@mail.commm\' email ', NULL, NULL, 43, 'successful_user_activation', '', '2019-03-13 15:17:36'),
(37, 'admin@mail.com', 'Successful login from ip 127.0.0.1 with \'admin@mail.com\' email ', NULL, NULL, 5, 'successful_login', '1', '2019-03-13 15:30:16');

-- --------------------------------------------------------

--
-- Структура таблицы `banners`
--

CREATE TABLE `banners` (
  `id` int(10) UNSIGNED NOT NULL,
  `text` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(10) UNSIGNED NOT NULL,
  `view_type` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `banners`
--

INSERT INTO `banners` (`id`, `text`, `url`, `active`, `ordering`, `view_type`, `created_at`) VALUES
(1, 'PHP site', 'http://php.net/', 1, 1, 2, '2019-02-22 07:16:12'),
(2, 'Mysql site', 'https://www.mysql.com/', 1, 2, 1, '2019-02-22 07:16:12'),
(3, 'Vuejs site', 'https://vuejs.org/', 1, 3, 2, '2019-02-22 07:16:12'),
(4, 'Our Sponsor', 'our_sponsor.com', 1, 4, 3, '2019-02-22 07:16:12');

-- --------------------------------------------------------

--
-- Структура таблицы `chats`
--

CREATE TABLE `chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creator_id` int(10) UNSIGNED NOT NULL,
  `status` enum('A','C') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT ' A=>Active, C=>Closed',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `chats`
--

INSERT INTO `chats` (`id`, `name`, `description`, `creator_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Greeting all employees!', 'Greeting all employees! and Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.', 1, 'A', '2019-02-22 07:16:14', NULL),
(3, 'Greeting all employees!55', 'Greeting all employees!', 5, 'C', '2019-02-26 15:19:28', '2019-02-26 13:19:38'),
(4, '111111', '<ul> <li>111111</li> <li>222<strong>22</strong>2</li> <li>33333333</li> <li>4444<em>44</em>4</li> </ul>', 5, 'C', '2019-02-27 15:56:13', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `chats_last_visited`
--

CREATE TABLE `chats_last_visited` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `chat_id` int(10) UNSIGNED NOT NULL,
  `visited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `chat_id` int(10) UNSIGNED NOT NULL,
  `is_top` tinyint(1) NOT NULL DEFAULT '0',
  `text` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_type` enum('T','U') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'T' COMMENT ' N=>Text added , U=>Files uploaded',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_at_by_user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `user_id`, `chat_id`, `is_top`, `text`, `message_type`, `created_at`, `updated_at`, `updated_at_by_user_id`) VALUES
(1, 1, 1, 1, ' That is first/top message on \"Greeting all employees!\" chan and Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.', 'T', '2019-02-22 07:16:15', NULL, NULL),
(2, 3, 1, 0, 'That is next message on \"Greeting all employees!\" ', 'T', '2019-02-22 07:16:15', NULL, NULL),
(3, 5, 1, 0, 'That is third message on \"Greeting all employees!\" ', 'T', '2019-02-22 07:16:15', NULL, NULL),
(4, 2, 1, 0, 'One message on \"Greeting all employees!\" ', 'T', '2019-02-22 07:16:15', NULL, NULL),
(6, 5, 1, 1, ' One more top message\" ', 'T', '2019-02-22 07:16:15', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `chat_participants`
--

CREATE TABLE `chat_participants` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('M','W','R') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'R' COMMENT ' ''M''=>''Manage this chat'', ''W'' => ''Can write messages'', ''R'' => ''Can only read'' ',
  `chat_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `chat_participants`
--

INSERT INTO `chat_participants` (`id`, `user_id`, `status`, `chat_id`, `created_at`) VALUES
(1, 1, 'R', 1, '2019-02-22 07:16:16'),
(2, 5, 'M', 1, '2019-02-22 07:16:16'),
(3, 2, 'W', 1, '2019-02-22 07:16:16');

-- --------------------------------------------------------

--
-- Структура таблицы `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(10) UNSIGNED NOT NULL,
  `acceptor_id` int(10) UNSIGNED DEFAULT NULL,
  `author_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `accepted` tinyint(1) NOT NULL DEFAULT '0',
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `contact_us`
--

INSERT INTO `contact_us` (`id`, `acceptor_id`, `author_name`, `author_email`, `message`, `accepted`, `accepted_at`, `created_at`) VALUES
(1, 5, 'Marisa Murphy', 'marisa.murphy@votes_site.com', 'Nulla eius et exercitationem. Voluptas provident autem rem totam rem. Est id atque necessitatibus ut. Perspiciatis cum quod perferendis quidem suscipit qui.\n\nItaque consequatur laborum dolorem quia reiciendis officia. Animi nostrum similique aperiam expedita fuga amet in impedit. Commodi officiis quae quisquam deleniti vel optio voluptas. Voluptas ex natus eveniet in assumenda aliquid.\n\nDolores enim itaque doloremque cupiditate dolorem quos reprehenderit excepturi. In dolorem ratione beatae molestiae vero. Cumque totam magni dolore et ab laboriosam.', 1, '2019-02-26 13:20:25', '2019-02-05 09:02:53'),
(2, 5, 'Jocelyn Witting', 'jocelyn.witting@votes_site.com', 'Et eum voluptate et maiores. Cum inventore incidunt eum quis. Occaecati voluptatem ipsum eos laudantium repellat. Sit ea enim aut quas aut facere voluptatibus. Illum unde aut hic quis rerum inventore.\n\nSit illum distinctio officiis in. Velit necessitatibus deserunt provident rerum. Culpa qui repellat dicta sint ipsam delectus. Excepturi optio repellat nisi consequatur pariatur.\n\nVoluptatem facere sit exercitationem numquam impedit quia. Molestiae aut officiis quod consequuntur. Veniam deleniti molestiae molestiae eveniet laborum veniam ipsam. Eligendi tenetur blanditiis porro aut velit. Et omnis non excepturi quas aspernatur enim deserunt.', 1, '2019-01-24 04:01:07', '2019-01-24 04:01:07'),
(4, 1, 'Elsa Howell', 'elsa.howell@votes_site.com', 'Debitis aut dolor illo aperiam quisquam numquam nihil corporis. Explicabo veritatis placeat voluptas nam. Dignissimos ad saepe delectus dignissimos atque ullam.\n\nEst id dolores aut rerum laudantium qui. Quisquam ut non eum dolor molestiae dolore nihil. Dolorum neque aut illo omnis et. Autem sint nobis excepturi aliquam quos consequatur non.\n\nExcepturi omnis nesciunt vel. Impedit sit ab explicabo quaerat.', 1, '2019-02-21 01:02:46', '2019-02-21 01:02:46'),
(6, 5, 'Clifford Rutherford', 'clifford.rutherford@votes_site.com', 'Aut in qui omnis facere rerum voluptatem ut. Quidem consectetur corrupti omnis ratione inventore sint molestiae. Quo aliquam qui laborum soluta.\n\nDebitis consequatur vel fugit eum. Ducimus nesciunt dolorum aut aperiam voluptatem est. Labore enim consectetur et qui temporibus ut. Aliquam ut repellat nobis dolorem rem qui.\n\nExplicabo cupiditate quasi voluptate ea consequuntur modi. Beatae non sed aperiam libero impedit. Odit est asperiores cum delectus totam harum illo.', 1, '2019-02-14 10:02:15', '2019-02-14 10:02:15'),
(7, NULL, 'Jermaine Becker', 'jermaine.becker@votes_site.com', 'Voluptas aliquid distinctio a dolorem sint fuga accusamus asperiores. Non omnis ut laborum. Qui aut libero accusantium eum exercitationem a. Enim velit tempore dicta recusandae. Quae saepe hic tempora sed hic quod porro.\n\nVoluptatem quaerat optio deserunt quas quasi repudiandae pariatur. Fugit minus omnis sit velit debitis cum nam. Cupiditate totam suscipit aperiam assumenda vitae. Excepturi velit tempora numquam ut dignissimos commodi.\n\nEt qui non sapiente error deleniti ea beatae. Suscipit sint voluptatem eos autem placeat. Repellat id soluta enim accusantium illo.', 0, NULL, '2019-01-26 03:01:39'),
(8, 3, 'Domenic Gleason', 'domenic.gleason@votes_site.com', 'Doloremque molestiae rerum iste amet. Ullam iste et quaerat eos quia sit. Qui corporis deserunt voluptatibus laudantium nihil enim.\n\nEt modi architecto aperiam non sunt et. Expedita necessitatibus quisquam nesciunt eum. Nam culpa laboriosam corrupti aut et repellendus ut. Modi ipsa aut eum eos.\n\nCorrupti eos consequuntur non est laboriosam. Ipsam consectetur sit omnis aut ab saepe. Magnam nulla et illo excepturi temporibus. Facilis autem assumenda aut rerum tempora quo cum.', 1, '2019-02-02 03:02:19', '2019-02-02 03:02:19'),
(9, NULL, 'Clementine Davis', 'clementine.davis@votes_site.com', 'Eligendi et voluptatem harum praesentium sapiente optio. Sed voluptate recusandae veniam ut quo. Esse nesciunt optio aspernatur mollitia iste.\n\nQuisquam recusandae aut nisi quia. Ut illum ab iusto rem et. Velit et asperiores expedita et.\n\nOmnis qui deserunt voluptatum qui aut vero. Temporibus voluptatem iste excepturi quasi molestiae libero omnis ut. Et voluptatum ex occaecati non facilis saepe. Sunt corporis est itaque qui omnis deserunt.', 0, NULL, '2019-02-21 03:02:43'),
(10, NULL, 'Kaleigh Zboncak', 'kaleigh.zboncak@votes_site.com', 'Harum quo nihil aliquam fugiat. Nam repellat est et autem quis harum veniam. Nostrum sit sit expedita ut. Aut debitis eius a tempora modi saepe. Qui perferendis laboriosam eligendi minus.\n\nDelectus beatae sunt quia nesciunt reiciendis doloribus neque. Dolor ipsa vel molestiae nobis qui et mollitia. Natus voluptatem illo sit quidem. Unde id aliquid commodi omnis.\n\nOmnis qui quaerat exercitationem modi doloribus dignissimos amet. Similique ipsam ut tempore. Sunt quis aut sunt eos laborum asperiores. A itaque vitae est ut ratione.', 0, NULL, '2019-01-31 23:02:31');

-- --------------------------------------------------------

--
-- Структура таблицы `cron_notifications`
--

CREATE TABLE `cron_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `cron_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cron_object_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cron_notifications`
--

INSERT INTO `cron_notifications` (`id`, `cron_type`, `cron_object_id`, `created_at`) VALUES
(1, 'new_contact_us', 1, '2019-02-05 09:02:53'),
(2, 'new_user', 32, '2019-02-05 09:02:53'),
(3, 'new_contact_us', 7, '2019-01-26 03:01:39'),
(4, 'new_user', 38, '2019-01-26 03:01:39'),
(5, 'new_contact_us', 9, '2019-02-21 03:02:43'),
(6, 'new_user', 40, '2019-02-21 03:02:43'),
(7, 'new_contact_us', 10, '2019-01-31 23:02:31'),
(8, 'new_user', 41, '2019-01-31 23:02:31');

-- --------------------------------------------------------

--
-- Структура таблицы `downloads`
--

CREATE TABLE `downloads` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creator_id` int(10) UNSIGNED NOT NULL,
  `preview_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `downloads`
--

INSERT INTO `downloads` (`id`, `title`, `file`, `description`, `creator_id`, `preview_image`, `mime`, `size`, `created_at`, `updated_at`) VALUES
(1, 'Our Services', 'our-services.doc', '<b>Our Services</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua \n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>...', 3, 'preview_image_our-services.jpg', NULL, '11kb', '2019-03-11 14:50:48', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `external_news_importing`
--

CREATE TABLE `external_news_importing` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `import_image` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `external_news_importing`
--

INSERT INTO `external_news_importing` (`id`, `title`, `url`, `status`, `import_image`, `created_at`, `updated_at`) VALUES
(1, 'BBC Health', 'http://feeds.bbci.co.uk/news/health/rss.xml', 1, 0, '2019-02-22 07:16:18', NULL),
(2, 'BBC Education', 'http://feeds.bbci.co.uk/news/education/rss.xml', 0, 0, '2019-02-22 07:16:18', NULL),
(3, 'CNN Top Stories', 'http://rss.cnn.com/rss/edition.rss', 1, 0, '2019-02-22 07:16:18', NULL),
(4, 'CNN World', 'http://rss.cnn.com/rss/edition_world.rss', 1, 0, '2019-02-22 07:16:18', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Admin', 'Administrator has access to all parts of backend.', '2019-02-22 07:15:53'),
(2, 'Manager', 'Manager has access to all votes, page contents, contact us of backend...', '2019-02-22 07:15:53'),
(3, 'User', 'User has access to all pages of frontend and his profile...', '2019-02-22 07:15:53');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(53, '2014_10_12_000000_create_users_table', 1),
(54, '2014_10_12_100000_create_password_resets_table', 1),
(55, '2015_05_06_194030_create_youtube_access_tokens_table', 1),
(56, '2018_01_01_145312_create_settings_table', 1),
(57, '2018_07_13_051201_create_vote_categories_table', 1),
(58, '2018_07_13_150151_create_votes_table', 1),
(59, '2018_07_13_152716_create_vote_items_table', 1),
(60, '2018_07_14_083752_create_users_groups_table', 1),
(61, '2018_08_09_113432_create_vote_item_users_results_table', 1),
(62, '2018_09_02_115059_create_site_subscriptions_table', 1),
(63, '2018_09_04_140212_create_users_site_subscriptions_table', 1),
(64, '2018_09_26_104240_create_quiz_quality_results_table', 1),
(65, '2018_09_26_145148_create_contact_us_table', 1),
(66, '2018_10_23_101632_create_activity_log_table', 1),
(67, '2018_11_01_150912_create_banners_table', 1),
(68, '2018_11_05_120035_create_search_results_table', 1),
(69, '2018_11_10_105950_create_page_contents_table', 1),
(70, '2018_11_21_131726_settings_table_switch_elastic_automation_on', 1),
(71, '2018_11_21_164111_users_table_add_provider_fields', 1),
(72, '2018_11_24_130352_create_chats_table', 1),
(73, '2018_11_24_130404_create_chat_messages_table', 1),
(74, '2018_11_24_130754_create_chat_participants_table', 1),
(75, '2018_11_24_130807_create_chats_last_visited_table', 1),
(76, '2018_12_04_120422_create_page_content_images_table', 1),
(77, '2018_12_17_044922_create_websockets_statistics_entries_table', 1),
(78, '2019_01_08_121124_external_news_importing_table', 1),
(79, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(80, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(81, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(82, '2016_06_01_000004_create_oauth_clients_table', 2),
(83, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2),
(95, '2019_03_10_130708_create_settings_text_table', 3),
(96, '2019_03_10_151355_create_downloads_table', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `page_contents`
--

CREATE TABLE `page_contents` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(260) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_shortly` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creator_id` int(10) UNSIGNED NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_homepage` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `page_type` enum('N','E','P','B') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT ' N=>Our News, E=>External News,  P=>Page, B=>Blog',
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `page_contents`
--

INSERT INTO `page_contents` (`id`, `title`, `slug`, `content`, `content_shortly`, `creator_id`, `is_featured`, `is_homepage`, `published`, `page_type`, `image`, `source_type`, `source_url`, `created_at`, `updated_at`) VALUES
(1, 'About', 'about', '<b>About</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua \n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>...', NULL, 3, 0, 0, 1, 'P', 'about.jpg', NULL, NULL, '2019-02-22 05:16:13', NULL),
(2, 'Contact Us', 'contact-us', '<b>Contact Us</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua \n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt </p> ', NULL, 5, 0, 0, 1, 'P', 'contact.png', NULL, NULL, '2019-02-22 05:16:13', NULL),
(3, 'Security privacy', 'security-privacy', '<b>Security privacy</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua \n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>...', NULL, 3, 0, 1, 1, 'P', 'security-privacy.png', NULL, NULL, '2019-02-22 05:16:13', NULL),
(4, 'Warranty and service', 'warranty-and-service', '<b>Warranty and service</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua \n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>...', NULL, 3, 0, 1, 0, 'P', 'warranty_and_service.jpg', NULL, NULL, '2019-02-22 05:16:13', NULL),
(5, 'This Must Have Device Can Save & Protect ALL Your Photos & Videos In ONE Click', 'this-must-have-device-can-save-protect-all-your-photos-videos-in-one-click', 'Don’t trust your photos to the cloud? You\'re not alone. Most people worry that they will lose their precious photos and videos because they\'re not backed up.\n\nIf you\'re like most of us you have a lot of photos and videos on your computer that aren\'t backed up anywhere. You\'ve probably thought about doing it but haven\'t done it yet because a professional backup will cost too much money and you\'re not sure how to do it yourself.\n\nAnd keeping all your photos and videos in \"the cloud\" isn\'t always safe either. You have to pay storage fees every month and someone could always hack in and either delete or expose all your private memories.\n\nDoing a manual backup to the Cloud isn\'t easy either. It can take 5-6 hours to go through each folder manually and upload your photos and videos one at a time. It\'s a nightmare!\n\nThe worst part is - you have to repeat that 5-6 hour process every week or month or you\'ll probably forget or give up after a while and go back to not backing up at all.\n\nLuckily, a brand new innovative little device can solve all of these problems for you. With it, you can backup and protect all of your photos, videos and memories in one click of a button. You click one button and it will do the rest for you!\n\nWhat is it?\nIt\'s called the PhotoStick. A tiny little USB drive that uses the latest innovative technology to go through you computer, grab all of your photos, videos and memories and save them to USB in one click. It works well with both Mac and Windows computers, so everyone can use it.\n\nThe PhotoStick works so fast, it saves you hours of searching, digging and saving your photos and videos to back them up. You just click the button and it does it for you.', 'A brand new gadget can instantly back up all of your photos and videos in one click. It\'s incredibly fast and easy and right now you can get it for only $29 (25% off retail price).', 2, 0, 1, 1, 'N', 'photostick-mobile.jpg', 'Yahoo news', 'https://smartlifeguides.com/lp/photostick/7.php?cep=rSPTuLws51PavVEbRZ06dRPhiBEQuidqkWBk7sNhXWvAXxHbcNx_yXl3OeBuj_8hcjlCvPQBhrKRtNfiusca', '2019-02-22 05:16:13', NULL),
(6, 'Donald Trump Dragged For Skipping WWI Cemetery Visit Due To Rain', 'donald-trump-dragged-for-skipping-wwi-cemetery-visit-due-to-rain', 'President Donald Trump traveled thousands of miles to Paris on Friday to participate in a number of events commemorating the 100th anniversary of the end of World War I.\n\nBut a bit of rain on Saturday kept him from one of the solemn gatherings ― and his no-show prompted a chorus of scorn.\n\nTrump skipped a planned visit to the Aisne-Marne American Cemetery, with a White House statement citing “scheduling and logistical difficulties caused by the weather” for the decision.\n\nThe cemetery is the final resting place of many of the 1,800 American soldiers killed in the battle of Belleau Wood.\n\nIn his place, Trump sent a delegation led by White House chief of staff John Kelly, who traveled in a small motorcade to the cemetery that is about an hour’s drive from Paris. \n\nTrump’s absence earned him harsh criticism from a range of current and former U.S. and European officials over social media.\n\nDavid Frum, a one-time speechwriter for President George W. Bush, voiced astonishment in a series of tweets, writing, “It’s incredible that a president would travel to France for this significant anniversary ― and then remain in his hotel room watching TV rather than pay in person his respects to the Americans who gave their lives in France for the victory gained 100 years ago tomorrow.”\n\nIt remains unclear how the weather impeded the president’s plans.\n\nA helicopter ride aboard Marine One may have been too risky. Been the weather did not deter French President Emmanuel Macron and German Chancellor Angela Merkel on Saturday from making a trip by car to a town in northern France where the Allies and Germany signed the Armistice ending the war, the BBC noted. Macron and Merkel unveiled a commemorative plaque at the event.\n\nBritish Parliament member Nicholas Soames ― a grandson of Winston Churchill ― reacted to Trump’s absence at the cemetery event by calling the U.S. president “pathetic.”\n\nSoldiers “died with their face to the foe and that pathetic inadequate [Trump] couldn’t even defy the weather to pay his respects to The Fallen,” Soames wrote on Twitter. ', 'President Donald Trump traveled thousands of miles to Paris on Friday to participate in a number of events commemorating the 100th anniversary of the end of World War I.', 4, 1, 1, 1, 'N', 'f3e055d3568dbacfb68746e56ad47535.jpg', 'Yahoo news', 'https://www.yahoo.com/news/donald-trump-dragged-skipping-wwi-195053461.html', '2019-02-22 05:16:13', NULL),
(7, 'California fires: The questions we are left with', 'california-fires-the-questions-we-are-left-with', '<h3>Are all the people on the list missing?</h3>\n<p>That isn\'t clear. Butte County Sheriff\'s Investigations Sgt. Steve Collins told CNN that authorities are working from a list of people whose loved ones have called in welfare checks for them or have reported them missing. From there, he says, officers go out to confirm if their structure is still standing or if the loved one who called in has heard from them.\nRescue workers sift through rubble in search of human remains at a burned property in Paradise, California on November 14, 2018.\nRescue workers sift through rubble in search of human remains at a burned property in Paradise, California on November 14, 2018.\nThe process is made even more difficult, Butte County Sheriff and Coroner Kory Honea said, by difficulty communicating with the displaced.\n\"There are a lot of people displaced and we\'re finding a lot of people don\'t know we are looking for them,\" Honea said. If people find their own or loved ones\' names on the list at the Butte County Sheriff\'s ffice\'s website, they should call the sheriff\'s office, Honea said.</p>\n<p>Are the people unaccounted for mostly seniors?\nThe list of the missing includes people of all ages. But it also has a lot of seniors, with one as old as 101.\nAt least 73 of the 103 people listed as missing in the Butte County city Paradise are over the age of 65, according to CNN affiliate KSTU.\nDo officials have a final death toll?</p>\n<p>The number of fatalities from the fires has gone up almost daily as officials continue to search.\nMany factors make it difficult for officials to reach a final death toll. One, according to Collins, is simply that there is still so much left to search.\n\"It\'s not just the area,\" said Collins. \"It\'s the number of homes, the number of trailers, the multi-story buildings. All of that changes the complexity of this operation.\"\nThen, he said, anthropologists must identify remains as human.</p>\n<p>Search and rescue workers search for human remains at a trailer park burned out from the Camp Fire.\nAnd once authorities know they have found human remains, they reach another obstacle in identifying the victims.\nHonea has invited relatives of the missing to visit the sheriff\'s office in Oroville, California so authorities can collect DNA samples from them. The DNA will be used to help identify fire victims, Honea said</p>.', 'More than a week into three different California fires, authorities are pushing search and recovery efforts. And now, with unprecedented devastation across the state, many questions remain as to what happens now.', 2, 0, 1, 1, 'N', 'california-wildfires.jpg', 'CNN', 'https://edition.cnn.com/2018/11/18/us/questions-california-fire-search/index.html', '2019-02-22 05:16:13', NULL),
(9, 'Nursing homes to be phased out in the next 20 years - Jim Daly', 'nursing-homes-to-be-phased-out-in-the-next-20-years--jim-daly', '<p>Nursing homes to be phased out in the next 20 years - Jim Daly Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.</p>\n<p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.</p>\n<p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.</p>\n<p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.</p>', '', 1, 1, 1, 1, 'N', 'lthBudget.jpg', 'independent.ie', 'https://www.independent.ie/irish-news/health/nursing-homes-to-be-phased-out-in-the-next-20-years-jim-daly-37539557.html', '2019-02-22 05:16:13', NULL),
(10, '\'Saturday Night Live\' parodies Fox News trying to explain away blue midterm wave', 'saturday-night-live-parodies-fox-news-trying-to-explain-away-blue-midterm-wave', '<p>More fake news about the midterm blues. \"Saturday Night Live\" took to the airwaves to poke fun at conspiracy theories about voter fraud following the midterms, using a parody of conservative commentator Laura Ingraham’s Fox News show.</p>\n\n<p>The sketch featured Kate McKinnon as Ingraham teasing an upcoming segment about how \"celebrities in California are whining about some tiny wildfires, while our heroic president is under constant attack — from rain.\"</p>\n\n<p>Cecily Strong played firebrand Fox News host Judge Jeanine Pirro warning about what McKinnon’s Ingraham called \"rampant voter fraud that allowed Democrats to literally steal the election.\"</p>\n\n<p>\"Some have claimed that suburban women revolted against the Republican Party — but doesn’t it feel more true that all Hispanics voted twice?\" McKinnon’s Ingraham said. \"You can’t dismiss that idea simply because it isn’t true and sounds insane.\"</p>\n\n\n<p>More \"feel facts\" designed to pander to the stereotypical Fox News crowd followed, like \"Santa is Jesus\'s dad,\" \"blackface is a compliment\" and \"If the Earth is so warm, then why are my feet cold?\"</p>\n\n<p>Strong’s Pirro offered up examples that included one person being able to impersonate many — holding up Eddie Murphy’s \"Nutty Professor II: The Klumps\" — and a \"huge increase in what people call stacking, where multiple children will stack on top of each other, under a trench coat and then vote as an adult.\"</p>\n\n<p>The sketch also lampooned parody advertisers on the faux-Fox show, including a fashion catheter company, the manufacturer of baptism kits for dogs and a brand of \"whites only\" eggs — \"it\'s just egg whites, and it’s just for us.\" (A parody of a derided real-life \"vape god\" segment also made an appearance.)</p>\n\n<p>\"SNL\" was hosted by Steve Carell, the boss from the hit show \"The Office,\" who returned to host for the third time.</p>\n', '\"You can’t dismiss that idea simply because it isn’t true and sounds insane,\" Kate McKinnon as Laura Ingraham said of voter-fraud claims.', 4, 0, 1, 1, 'N', '', 'nbcnews', 'https://www.nbcnews.com/pop-culture/tv/saturday-night-live-parodies-fox-news-trying-explain-away-blue-n937596', '2019-02-22 05:16:13', NULL),
(11, 'Terms of service', 'terms-of-service', '<b>Terms of service</b> sed do <b>eiusmod</b>  tempor incididunt ut <b>labore</b> et dolore magna aliqua \n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>\n <p>Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>...', NULL, 3, 0, 1, 1, 'P', 'terms-of-service.png', NULL, NULL, '2019-02-22 05:16:13', NULL),
(12, 'Antibiotic resistance plan to fight \'urgent\' global threat', 'antibiotic-resistance-plan-to-fight-urgent-global-threat', 'Ministers want drug firms to develop new antibiotics and to stop people overusing existing ones.', 'Ministers want drug firms to develop new antibiotics and to stop people overusing existing ones.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46973641', '2019-01-23 23:31:57', NULL),
(13, '\'Super poo donors\' wanted', 'super-poo-donors-wanted', 'Some people appear to have extra special poo, rich in good bugs, that could help mend sick patients.', 'Some people appear to have extra special poo, rich in good bugs, that could help mend sick patients.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46944631', '2019-01-23 23:12:01', NULL),
(14, 'Muffin or apple: Do you know what you \'should\' be eating?', 'muffin-or-apple-do-you-know-what-you-should-be-eating', 'A nine-year analysis of the UK National Diet and Nutrition Survey shows mixed results.', 'A nine-year analysis of the UK National Diet and Nutrition Survey shows mixed results.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46980211', '2019-01-23 19:47:03', NULL),
(15, 'Iceland still selling own-brand palm oil products despite pledge', 'iceland-still-selling-own-brand-palm-oil-products-despite-pledge', 'The retailer promised to remove palm oil from all of its own products by the end of 2018.', 'The retailer promised to remove palm oil from all of its own products by the end of 2018.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-46969920', '2019-01-23 12:53:56', NULL),
(16, 'St George\'s Hospital: 250 cardiac unit deaths to be reviewed', 'st-george-s-hospital-250-cardiac-unit-deaths-to-be-reviewed', 'Complex heart operations were moved out of St George\'s Hospital last year to allow for improvements.', 'Complex heart operations were moved out of St George\'s Hospital last year to allow for improvements.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-england-london-46974762', '2019-01-23 12:39:17', NULL),
(17, 'How diet is changing - the good and the bad', 'how-diet-is-changing-the-good-and-the-bad', 'Children are turning their backs on sugary drinks but all age groups eat too few vegetables, a survey suggests.', 'Children are turning their backs on sugary drinks but all age groups eat too few vegetables, a survey suggests.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46972101', '2019-01-23 10:28:10', NULL),
(18, 'Conjoined twins Marieme and Ndeye Ndiaye are living in the UK', 'conjoined-twins-marieme-and-ndeye-ndiaye-are-living-in-the-uk', 'Conjoined twins Marieme and Ndye Ndiaye have an uncertain future after moving from Senegal to the UK.', 'Conjoined twins Marieme and Ndye Ndiaye have an uncertain future after moving from Senegal to the UK.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-wales-46964519', '2019-01-23 09:20:47', NULL),
(19, 'Global health research money reaches \'record high\'', 'global-health-research-money-reaches-record-high', 'An annual survey has found money invested in this area reached its highest level yet in 2017.', 'An annual survey has found money invested in this area reached its highest level yet in 2017.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46951262', '2019-01-23 08:39:30', NULL),
(20, 'Young at higher risk of asthma attack, says charity', 'young-at-higher-risk-of-asthma-attack-says-charity', 'Amy Pay, 27, says she didn\'t take her asthma seriously until she nearly ended up in hospital.', 'Amy Pay, 27, says she didn\'t take her asthma seriously until she nearly ended up in hospital.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46964708', '2019-01-23 05:38:25', NULL),
(21, 'Teen one of first UK proton-beam NHS patients', 'teen-one-of-first-uk-proton-beam-nhs-patients', 'Manchester\'s Christie hospital has the UK\'s first dedicated NHS treatment centre for the pioneering treatment.', 'Manchester\'s Christie hospital has the UK\'s first dedicated NHS treatment centre for the pioneering treatment.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46958785', '2019-01-22 23:03:28', NULL),
(22, 'How virtual reality can help you manage pain', 'how-virtual-reality-can-help-you-manage-pain', 'A company in Israel is using virtual reality computer games to help patients deal with chronic pain.', 'A company in Israel is using virtual reality computer games to help patients deal with chronic pain.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/business-46964729', '2019-01-22 22:18:24', NULL),
(23, 'Child\'s death linked to Glasgow hospital pigeon infection', 'child-s-death-linked-to-glasgow-hospital-pigeon-infection', 'The fungus from bird droppings was a \"contributing factor\" in the death of the youngster at a Glasgow hospital.', 'The fungus from bird droppings was a \"contributing factor\" in the death of the youngster at a Glasgow hospital.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-scotland-glasgow-west-46953707', '2019-01-22 15:58:32', NULL),
(24, 'Instagram \'helped kill my daughter\'', 'instagram-helped-kill-my-daughter', 'After Molly Russell took her own life, her family found distressing material through her Instagram account.', 'After Molly Russell took her own life, her family found distressing material through her Instagram account.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-46966009', '2019-01-22 15:38:13', NULL),
(25, 'Pigeon droppings health risk - should you worry?', 'pigeon-droppings-health-risk-should-you-worry', 'A child\'s death at a hospital in Glasgow has been linked to an infection spread by bird droppings.', 'A child\'s death at a hospital in Glasgow has been linked to an infection spread by bird droppings.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46964702', '2019-01-22 15:04:53', NULL),
(26, '\'Brazilian butt lift\' inquest: Fat clot killed woman', 'brazilian-butt-lift-inquest-fat-clot-killed-woman', 'Leah Cambridge, 29, from Leeds, died after travelling to a hospital in Turkey for cosmetic surgery.', 'Leah Cambridge, 29, from Leeds, died after travelling to a hospital in Turkey for cosmetic surgery.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-england-leeds-46961322', '2019-01-22 10:41:31', NULL),
(27, 'Disability hate crime: Katie Price backed by MPs over online abuse', 'disability-hate-crime-katie-price-backed-by-mps-over-online-abuse', 'The model is calling for new laws to protect disabled people online after her son Harvey was trolled.', 'The model is calling for new laws to protect disabled people online after her son Harvey was trolled.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-politics-46951232', '2019-01-22 08:51:53', NULL),
(28, 'NHS Wales agency staff costs spiral to cover vacancies', 'nhs-wales-agency-staff-costs-spiral-to-cover-vacancies', 'Auditor general Adrian Crompton says he hopes NHS Wales can \"continue to bring down and control\" costs.', 'Auditor general Adrian Crompton says he hopes NHS Wales can \"continue to bring down and control\" costs.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-wales-46949253', '2019-01-22 00:05:22', NULL),
(29, 'Turtle meat - the ultimate survival diet?', 'turtle-meat-the-ultimate-survival-diet', 'How one family stayed alive while shipwrecked and adrift on the Pacific Ocean.', 'How one family stayed alive while shipwrecked and adrift on the Pacific Ocean.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46671224', '2019-01-21 22:24:11', NULL),
(30, 'The teen Primark model with vitiligo', 'the-teen-primark-model-with-vitiligo', 'Kaiden Williams, 13, recently featured in a campaign for the clothes store and is inspiring others.', 'Kaiden Williams, 13, recently featured in a campaign for the clothes store and is inspiring others.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-england-birmingham-46950832', '2019-01-21 22:07:36', NULL),
(31, 'Being awake during brain surgery: \'You are hovering in between worlds\'', 'being-awake-during-brain-surgery-you-are-hovering-in-between-worlds', 'Jazz musician Musa Manzini was kept awake for part of his surgery to have a tumour removed.', 'Jazz musician Musa Manzini was kept awake for part of his surgery to have a tumour removed.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/world-africa-46952242', '2019-01-21 22:06:26', NULL),
(32, 'Is it OK to take the pill every day without a break?', 'is-it-ok-to-take-the-pill-every-day-without-a-break', 'Women can avoid monthly bleeding if they run oral contraceptive pill packets back to back - but is it safe?', 'Women can avoid monthly bleeding if they run oral contraceptive pill packets back to back - but is it safe?', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46952694', '2019-01-21 18:22:49', NULL),
(33, 'How Bournemouth\'s \'streaming\' nurses ease A&amp;E pressure', 'how-bournemouth-s-streaming-nurses-ease-a-amp-e-pressure', 'A Bournemouth hospital is stationing a nurse at its A&amp;E entrance to redirect non-emergency cases.', 'A Bournemouth hospital is stationing a nurse at its A&amp;E entrance to redirect non-emergency cases.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46910965', '2019-01-21 16:19:36', NULL),
(34, 'Sickle cell: Call The Midwife shines spotlight on disease', 'sickle-cell-call-the-midwife-shines-spotlight-on-disease', 'Sickle cell carriers took to social media to praise the show\'s portrayal of the genetic disease.', 'Sickle cell carriers took to social media to praise the show\'s portrayal of the genetic disease.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-46947432', '2019-01-21 15:04:00', NULL),
(35, 'Gosport hospital deaths: \'My father’s life was ended by someone else\'', 'gosport-hospital-deaths-my-father-s-life-was-ended-by-someone-else', 'David Huntington told BBC Panorama of his anger over his father’s death at the Gosport War Memorial Hospital.', 'David Huntington told BBC Panorama of his anger over his father’s death at the Gosport War Memorial Hospital.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-46941622', '2019-01-21 13:19:18', NULL),
(36, 'Ant McPartlin sent support from ADHD community', 'ant-mcpartlin-sent-support-from-adhd-community', 'The TV presenter says he \"found out stuff about me I hadn\'t addressed for years\".', 'The TV presenter says he \"found out stuff about me I hadn\'t addressed for years\".', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/entertainment-arts-46946582', '2019-01-21 10:11:47', NULL),
(37, 'He Jiankui: China condemns \'baby gene editing\' scientist', 'he-jiankui-china-condemns-baby-gene-editing-scientist', 'Investigators say He Jiankui sought \"fame and fortune\" with his claim to have edited baby genes.', 'Investigators say He Jiankui sought \"fame and fortune\" with his claim to have edited baby genes.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/world-asia-46943593', '2019-01-21 10:10:04', NULL),
(38, 'Why are so many people still dying from snake bites?', 'why-are-so-many-people-still-dying-from-snake-bites', 'Most of the world\'s population lives near venomous snakes - but some are at greater risk than others.', 'Most of the world\'s population lives near venomous snakes - but some are at greater risk than others.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/world-45332002', '2019-01-21 00:14:32', NULL),
(39, 'Women\'s frozen eggs \'should be stored for longer\'', 'women-s-frozen-eggs-should-be-stored-for-longer', 'The 10-year limit for keeping a woman\'s frozen eggs is arbitrary and does not reflect current technology, campaigners say.', 'The 10-year limit for keeping a woman\'s frozen eggs is arbitrary and does not reflect current technology, campaigners say.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46923329', '2019-01-20 23:23:44', NULL),
(40, 'Night shifts: The toll they can take on your life', 'night-shifts-the-toll-they-can-take-on-your-life', 'As more of us work through the night, one man finds out how 30 years of shifts has affected his life.', 'As more of us work through the night, one man finds out how 30 years of shifts has affected his life.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-england-northamptonshire-46920989', '2019-01-20 10:04:54', NULL),
(41, 'The pain of Tourette\'s: \'I\'m always covered in bruises\'', 'the-pain-of-tourette-s-i-m-always-covered-in-bruises', 'Charity Tourettes Action has discovered that patients are facing long delays in diagnosis.', 'Charity Tourettes Action has discovered that patients are facing long delays in diagnosis.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46905085', '2019-01-20 00:08:43', NULL),
(42, 'Gosport hospital deaths: Evidence \'strong enough to bring charges\'', 'gosport-hospital-deaths-evidence-strong-enough-to-bring-charges', 'More than 450 patients died after being prescribed painkillers at Gosport War Memorial Hospital.', 'More than 450 patients died after being prescribed painkillers at Gosport War Memorial Hospital.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-england-hampshire-46924754', '2019-01-19 23:44:02', NULL),
(43, 'Carter Cookson: Baby who needed new heart dies', 'carter-cookson-baby-who-needed-new-heart-dies', 'His parents, who lost their first son in 2013, say Carter Cookson has \"gained his angel wings\".', 'His parents, who lost their first son in 2013, say Carter Cookson has \"gained his angel wings\".', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/uk-england-tyne-46935826', '2019-01-19 21:27:46', NULL),
(44, '\'Why I wanted a tattoo on my mastectomy scar\'', 'why-i-wanted-a-tattoo-on-my-mastectomy-scar', 'Kris Hallenga was diagnosed with terminal cancer, but beat the odds. Her scar is part of her body art.', 'Kris Hallenga was diagnosed with terminal cancer, but beat the odds. Her scar is part of her body art.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/stories-46785690', '2019-01-18 22:47:44', NULL),
(45, 'What is the right age to lose your virginity?', 'what-is-the-right-age-to-lose-your-virginity', 'More than a third of women and a quarter of men think they got it wrong, research suggests.', 'More than a third of women and a quarter of men think they got it wrong, research suggests.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46794269', '2019-01-14 22:40:23', NULL),
(46, 'The breakthroughs that could save our lives', 'the-breakthroughs-that-could-save-our-lives', 'From reversing blindness and paralysis to new treatments for cancer and infertility.', 'From reversing blindness and paralysis to new treatments for cancer and infertility.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-46597605', '2018-12-29 01:02:39', NULL),
(47, 'Check NHS cancer, A&amp;E, ops and mental health targets in your area', 'check-nhs-cancer-a-amp-e-ops-and-mental-health-targets-in-your-area', 'Use our tracker to check whether your local services are meeting waiting-time targets for cancer, routine operations, A&amp;E and mental health treatment.', 'Use our tracker to check whether your local services are meeting waiting-time targets for cancer, routine operations, A&amp;E and mental health treatment.', 5, 0, 1, 1, 'E', NULL, 'BBC Health', 'https://www.bbc.co.uk/news/health-41483322', '2018-12-13 08:59:21', NULL),
(48, 'Carlos Ghosn has resigned as head of Renault, French government says', 'carlos-ghosn-has-resigned-as-head-of-renault-french-government-says', 'Carlos Ghosn has quit as chairman and CEO of Renault, according to the French government.', 'Carlos Ghosn has quit as chairman and CEO of Renault, according to the French government.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/24/business/carlos-ghosn-renault-ceo/index.html', '2019-01-24 05:17:10', NULL),
(49, 'Trapped for hours, they used a group chat to share their fears as terrorists roamed the halls', 'trapped-for-hours-they-used-a-group-chat-to-share-their-fears-as-terrorists-roamed-the-halls', 'It had been hours since terrorists burst into a hotel complex in Kenya, and you could feel the desperation in the air.', 'It had been hours since terrorists burst into a hotel complex in Kenya, and you could feel the desperation in the air.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/24/africa/kenya-terror-attack-whatsapp-group-help-trnd/index.html', '2019-01-24 05:15:16', NULL),
(50, '5 people shot dead in hostage situation at Florida bank', '5-people-shot-dead-in-hostage-situation-at-florida-bank', 'Highlands County Commissioner Don Elwell said that multiple people have been shot in a \"hostage situation\" at a SunTrust Bank in Sebring, Florida.', 'Highlands County Commissioner Don Elwell said that multiple people have been shot in a \"hostage situation\" at a SunTrust Bank in Sebring, Florida.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/us/sebring-florida-bank-incident/index.html', '2019-01-24 04:39:02', NULL),
(51, 'Witness testifies he, El Chapo\'s wife and sons helped coordinate kingpin\'s last prison escape', 'witness-testifies-he-el-chapo-s-wife-and-sons-helped-coordinate-kingpin-s-last-prison-escape', 'While Joaquin \"El Chapo\" Guzman sat in a Mexican maximum security prison in 2015, his sons, a former cartel associate and even his wife worked together to coordinate details of his final escape, according to the former associate\'s testimony.', 'While Joaquin \"El Chapo\" Guzman sat in a Mexican maximum security prison in 2015, his sons, a former cartel associate and even his wife worked together to coordinate details of his final escape, according to the former associate\'s testimony.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/24/us/el-chapo-escape-wife-sons/index.html', '2019-01-24 04:12:46', NULL),
(52, 'Chinese-Australian writer detained', 'chinese-australian-writer-detained', 'Chinese-Australian writer Yang Hengjun has been detained by Chinese authorities, the Australian Department of Foreign Affairs and Trade said on Wednesday.', 'Chinese-Australian writer Yang Hengjun has been detained by Chinese authorities, the Australian Department of Foreign Affairs and Trade said on Wednesday.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/asia/australia-china-yang-hengjun-intl/index.html', '2019-01-24 04:05:44', NULL),
(53, 'US President agrees not to deliver the annual address until after the shutdown  ends', 'us-president-agrees-not-to-deliver-the-annual-address-until-after-the-shutdown-ends', 'White House officials were caught off guard Wednesday by House Speaker Nancy Pelosi formally disinviting President Donald Trump from giving his State of the Union address from the House chamber, leaving them scrambling for a response.', 'White House officials were caught off guard Wednesday by House Speaker Nancy Pelosi formally disinviting President Donald Trump from giving his State of the Union address from the House chamber, leaving them scrambling for a response.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/politics/white-house-state-of-the-union-off-guard/index.html', '2019-01-24 03:38:22', NULL),
(54, 'Cohen delays House testimony, citing threats from Trump', 'cohen-delays-house-testimony-citing-threats-from-trump', 'President Donald Trump\'s former lawyer Michael Cohen announced he is postponing his public congressional testimony that was scheduled for February 7, citing \"ongoing threats against his family.\"', 'President Donald Trump\'s former lawyer Michael Cohen announced he is postponing his public congressional testimony that was scheduled for February 7, citing \"ongoing threats against his family.\"', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/politics/michael-cohen-testimony-postponed/index.html', '2019-01-24 03:36:53', NULL),
(55, 'S Korean prosecutor jailed in #MeToo case', 's-korean-prosecutor-jailed-in-metoo-case', 'A former top prosecutor whose actions helped kick off South Korea\'s #MeToo movement has been jailed.', 'A former top prosecutor whose actions helped kick off South Korea\'s #MeToo movement has been jailed.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/asia/south-korea-metoo-intl/index.html', '2019-01-24 03:04:08', NULL),
(56, 'Microsoft search engine Bing is blocked in China', 'microsoft-search-engine-bing-is-blocked-in-china', 'Microsoft\'s Bing search engine has run into trouble in China.', 'Microsoft\'s Bing search engine has run into trouble in China.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/tech/bing-microsoft-china/index.html', '2019-01-24 02:35:28', NULL),
(57, 'With the population in free fall, Japan\'s military turns to women to fill the ranks', 'with-the-population-in-free-fall-japan-s-military-turns-to-women-to-fill-the-ranks', 'The first time 22-year-old Akiko Hirayama stepped on board a navy ship, she was transfixed by the hardware.', 'The first time 22-year-old Akiko Hirayama stepped on board a navy ship, she was transfixed by the hardware.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/asia/japan-self-defense-force-recruitment-intl/index.html', '2019-01-24 00:20:44', NULL),
(58, 'White House confirms President Trump sent letter to Kim Jong Un', 'white-house-confirms-president-trump-sent-letter-to-kim-jong-un', 'The White House confirmed Wednesday that President Donald Trump sent a letter to North Korean leader Kim Jong Un.', 'The White House confirmed Wednesday that President Donald Trump sent a letter to North Korean leader Kim Jong Un.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/politics/trump-kim-jong-un-letter/index.html', '2019-01-23 23:46:03', NULL),
(59, 'The shutdown\'s hitting Smithsonian hard. It\'s losing $1 million a week', 'the-shutdown-s-hitting-smithsonian-hard-it-s-losing-1-million-a-week', 'The Smithsonian museums have been closed since January 2 because of the government shutdown.', 'The Smithsonian museums have been closed since January 2 because of the government shutdown.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/us/smithsonian-shutdown-losses-trnd/index.html', '2019-01-23 23:39:15', NULL),
(60, 'America mocks and dehumanizes natives at every turn', 'america-mocks-and-dehumanizes-natives-at-every-turn', 'Let\'s be absolutely clear about something here: Whatever else may have been said about it or our country\'s reactions to it, the racist disrespect of Nathan Phillips, a Native American elder, by Nick Sandmann and his MAGA-hat clad classmates of Covington Catholic High School at the Lincoln Memorial is nothing new. In fact, it\'s quite the common thing. In this increasingly depraved society, that kind of behavior is often encouraged or excused as just good ol\' American fun.', 'Let\'s be absolutely clear about something here: Whatever else may have been said about it or our country\'s reactions to it, the racist disrespect of Nathan Phillips, a Native American elder, by Nick Sandmann and his MAGA-hat clad classmates of Coving ...', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/opinions/america-mocks-dehumanizes-natives-at-every-turn/index.html', '2019-01-23 22:54:03', NULL),
(61, 'Climate change may impact how many boys are born', 'climate-change-may-impact-how-many-boys-are-born', 'Global warming will have a variety of effects on our planet, yet it may also directly impact our human biology, research suggests.', 'Global warming will have a variety of effects on our planet, yet it may also directly impact our human biology, research suggests.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/health/climate-change-infant-sex-ratio-intl/index.html', '2019-01-23 22:40:42', NULL),
(62, 'BuzzFeed to cut 15% of staff in new round of layoffs', 'buzzfeed-to-cut-15-of-staff-in-new-round-of-layoffs', 'BuzzFeed is preparing to lay off about 15% of its employees. The coming contraction is the latest example of a media company making cutbacks in a difficult operating environment.', 'BuzzFeed is preparing to lay off about 15% of its employees. The coming contraction is the latest example of a media company making cutbacks in a difficult operating environment.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/media/buzzfeed-layoffs/index.html', '2019-01-23 22:24:10', NULL),
(63, 'Trump dared Pelosi to cancel his State of the Union speech. So she did.', 'trump-dared-pelosi-to-cancel-his-state-of-the-union-speech-so-she-did', 'President Donald Trump will NOT be giving his State of the Union speech on the House floor next Tuesday.', 'President Donald Trump will NOT be giving his State of the Union speech on the House floor next Tuesday.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/politics/donald-trump-nancy-pelosi/index.html', '2019-01-23 22:19:01', NULL),
(64, 'Harris Wofford: What an admirable life', 'harris-wofford-what-an-admirable-life', 'To write an appreciation that fully captures Harris Wofford\'s amazing life is too big a task for this space. His canvas is too large, his story too sweeping, his causes and accomplishments too vast.', 'To write an appreciation that fully captures Harris Wofford\'s amazing life is too big a task for this space. His canvas is too large, his story too sweeping, his causes and accomplishments too vast.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/opinions/harris-wofford-admirable-life-begala/index.html', '2019-01-23 21:50:31', NULL),
(65, 'One serving of fried chicken a day linked to 13% higher risk of death: study', 'one-serving-of-fried-chicken-a-day-linked-to-13-higher-risk-of-death-study', 'A regular serving of fried chicken or fish is associated with a higher risk of death from any cause except cancer, according to a new study done in postmenopausal women in the United States.', 'A regular serving of fried chicken or fish is associated with a higher risk of death from any cause except cancer, according to a new study done in postmenopausal women in the United States.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/health/fried-food-fish-chicken-higher-risk-of-death-intl/index.html', '2019-01-23 21:30:12', NULL),
(66, 'Simon Cowell has plans for this 6-year-old DJ', 'simon-cowell-has-plans-for-this-6-year-old-dj', 'A six-year-old South African DJ received a standing ovation on America\'s Got Talent show after he wowed judges with his skills.', 'A six-year-old South African DJ received a standing ovation on America\'s Got Talent show after he wowed judges with his skills.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/africa/south-african-young-dj/index.html', '2019-01-23 20:41:04', NULL),
(67, 'A married gay man is running for US president. That\'s a big deal.', 'a-married-gay-man-is-running-for-us-president-that-s-a-big-deal', 'South Bend Mayor Pete Buttigieg entered the 2020 race for president on Wednesday, announcing his intentions with a video featuring scenes of him and his husband, Chasten, cooking and playing with their dog, Buddy.', 'South Bend Mayor Pete Buttigieg entered the 2020 race for president on Wednesday, announcing his intentions with a video featuring scenes of him and his husband, Chasten, cooking and playing with their dog, Buddy.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/politics/pete-buttigieg-gay-running-for-president/index.html', '2019-01-23 20:39:56', NULL),
(68, 'Boeing\'s first autonomous air taxi flight ends in fewer than 60 seconds', 'boeing-s-first-autonomous-air-taxi-flight-ends-in-fewer-than-60-seconds', 'Everyone has to start somewhere, and air taxis are no exception.', 'Everyone has to start somewhere, and air taxis are no exception.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/tech/boeing-flying-car/index.html', '2019-01-23 20:08:36', NULL);
INSERT INTO `page_contents` (`id`, `title`, `slug`, `content`, `content_shortly`, `creator_id`, `is_featured`, `is_homepage`, `published`, `page_type`, `image`, `source_type`, `source_url`, `created_at`, `updated_at`) VALUES
(69, 'Pelosi: Republicans, take back your party', 'pelosi-republicans-take-back-your-party', 'While addressing the Conference of Mayors, Speaker of the House Nancy Pelosi said she was concerned about President Donald Trump using government shutdowns in the future if he doesn\'t get his way.', 'While addressing the Conference of Mayors, Speaker of the House Nancy Pelosi said she was concerned about President Donald Trump using government shutdowns in the future if he doesn\'t get his way.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/videos/politics/2019/01/23/nancy-pelosi-trump-sotu-address-shutdown-response-vpx.cnn', '2019-01-23 18:29:46', NULL),
(70, 'This collision made life possible on Earth, study says', 'this-collision-made-life-possible-on-earth-study-says', 'Earth didn\'t exactly start out ready to support life, but scientists now have a better idea of how the essential elements for life ended up on our planet, according to a study published Wednesday in the journal Science Advances.', 'Earth didn\'t exactly start out ready to support life, but scientists now have a better idea of how the essential elements for life ended up on our planet, according to a study published Wednesday in the journal Science Advances.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/world/earth-collision-life-elements/index.html', '2019-01-23 17:52:40', NULL),
(71, 'Where survivors of sexual abuse are sued by the perpetrators', 'where-survivors-of-sexual-abuse-are-sued-by-the-perpetrators', 'As teenage girls, they raced down snow-covered Alpine slopes in pursuit of winning Olympic gold. They were revered by millions of Austrians who cheered them as national heroes, their best athletes, the pride of a small nation.', 'As teenage girls, they raced down snow-covered Alpine slopes in pursuit of winning Olympic gold. They were revered by millions of Austrians who cheered them as national heroes, their best athletes, the pride of a small nation.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/europe/austria-skiers-sexual-assault-intl/index.html', '2019-01-23 16:49:58', NULL),
(72, 'Why Anne Hathaway gave up drinking', 'why-anne-hathaway-gave-up-drinking', 'Actress Anne Hathaway talked about why she has stopped drinking, during an interview on \"The Ellen DeGeneres Show.\"', 'Actress Anne Hathaway talked about why she has stopped drinking, during an interview on \"The Ellen DeGeneres Show.\"', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/videos/entertainment/2019/01/23/anne-hathaway-alcohol-stop-drinking-comment-ellen-vpx.hln', '2019-01-23 16:43:49', NULL),
(73, 'China to Davos: Stop freaking out about our economy', 'china-to-davos-stop-freaking-out-about-our-economy', 'China has a message for the Davos crowd: Fear about an economic slowdown is overblown.', 'China has a message for the Davos crowd: Fear about an economic slowdown is overblown.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/business/china-economy-davos/index.html', '2019-01-23 16:40:30', NULL),
(74, 'Nigerian officer tells gay people to leave country or face prosecution', 'nigerian-officer-tells-gay-people-to-leave-country-or-face-prosecution', 'A high-ranking Nigerian policewoman has told gay people living in the country to leave or risk prosecution.', 'A high-ranking Nigerian policewoman has told gay people living in the country to leave or risk prosecution.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/health/nigeria-police-gay-prosecution-warning/index.html', '2019-01-23 16:24:05', NULL),
(75, 'Missing footballer\'s \'last goodbye\' tweet gathers added poignancy', 'missing-footballer-s-last-goodbye-tweet-gathers-added-poignancy', 'Emiliano Sala wanted to say goodbye one final time.', 'Emiliano Sala wanted to say goodbye one final time.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/football/emiliano-sala-plane-nantes-cardiff-spt-intl/index.html', '2019-01-23 16:08:40', NULL),
(76, 'US House panel investigating White House security clearances', 'us-house-panel-investigating-white-house-security-clearances', 'The House Oversight Committee on Wednesday announced it was launching a \"wide-ranging\" probe into the White House\'s handling of its security clearance process, kicking off one of the first high-profile investigations into the Trump administration by the new, Democratic-controlled chamber.', 'The House Oversight Committee on Wednesday announced it was launching a \"wide-ranging\" probe into the White House\'s handling of its security clearance process, kicking off one of the first high-profile investigations into the Trump administration by ...', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/politics/house-oversight-committee-investigation-security-clearances/index.html', '2019-01-23 15:29:43', NULL),
(77, 'Audience strips down for naked play in Paris', 'audience-strips-down-for-naked-play-in-paris', 'Actors are known for baring their souls on stage. But the cast of a new French play went a step further -- and so did their audience.', 'Actors are known for baring their souls on stage. But the cast of a new French play went a step further -- and so did their audience.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/travel/article/paris-naked-play-scli-intl/index.html', '2019-01-23 15:25:57', NULL),
(78, 'Passengers say man made flight attendants undress and clean him', 'passengers-say-man-made-flight-attendants-undress-and-clean-him', 'A Taiwanese airline says it may ban an overweight passenger who allegedly forced members of an all-female cabin crew to undress him in the lavatory and then assist him in the bathroom.', 'A Taiwanese airline says it may ban an overweight passenger who allegedly forced members of an all-female cabin crew to undress him in the lavatory and then assist him in the bathroom.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/asia/taiwan-eva-air-incident-intl/index.html', '2019-01-23 13:10:04', NULL),
(79, '\'Exquisite torture\' for Woods continues', 'exquisite-torture-for-woods-continues', 'New year, new Tiger Woods.', 'New year, new Tiger Woods.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/sport/tiger-woods-pga-tour-torrey-pines-golf-spt-intl/index.html', '2019-01-23 11:24:09', NULL),
(80, 'Ad accused of \'whitewashing\' tennis star', 'ad-accused-of-whitewashing-tennis-star', 'One of Naomi Osaka\'s Japanese sponsors has apologized after releasing a advertisement which has been widely accused of \"whitewashing\" the tennis star\'s skin tone.', 'One of Naomi Osaka\'s Japanese sponsors has apologized after releasing a advertisement which has been widely accused of \"whitewashing\" the tennis star\'s skin tone.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/tennis/nissin-naomi-osaka-skin-tone-intl/index.html', '2019-01-23 11:14:22', NULL),
(81, 'Boeing 747 to be sunk for divers to explore', 'boeing-747-to-be-sunk-for-divers-to-explore', 'The Persian gulf country of Bahrain is hoping to attract divers from across the globe with a new \"underwater theme park\" --  an expansive diving site spanning 100,000 square meters with some unusual features.', 'The Persian gulf country of Bahrain is hoping to attract divers from across the globe with a new \"underwater theme park\" --  an expansive diving site spanning 100,000 square meters with some unusual features.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/travel/article/boeing-jet-underwater-park-bahrain/index.html', '2019-01-23 10:57:16', NULL),
(82, 'Formula E driver back in the game after \'long time of suffering\'', 'formula-e-driver-back-in-the-game-after-long-time-of-suffering', 'Formula E driver Antonio Felix Da Costa says the feelgood factor in the paddock has helped him out of the doldrums and back into form after a four-year winless drought.', 'Formula E driver Antonio Felix Da Costa says the feelgood factor in the paddock has helped him out of the doldrums and back into form after a four-year winless drought.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/motorsport/antonio-felix-da-costa-formula-e-spt-intl/index.html', '2019-01-23 10:53:46', NULL),
(83, 'Brexit opens possibility of \'far-right drift into extreme right-wing terrorism\' ...', 'brexit-opens-possibility-of-far-right-drift-into-extreme-right-wing-terrorism', 'The divisive atmosphere surrounding Brexit could be exploited by right-wing groups, the UK\'s most senior counter-terrorism police chief has warned.', 'The divisive atmosphere surrounding Brexit could be exploited by right-wing groups, the UK\'s most senior counter-terrorism police chief has warned.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/uk/far-right-radicalism-brexit-police-gbr-intl/index.html', '2019-01-23 10:14:28', NULL),
(84, 'Serena Williams loses at Australian Open', 'serena-williams-loses-at-australian-open', 'Serena Williams\' last two losses at grand slams couldn\'t have been more dramatic.', 'Serena Williams\' last two losses at grand slams couldn\'t have been more dramatic.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/22/tennis/australian-open-serena-williams-osaka-tennis-intl-spt/index.html', '2019-01-23 09:24:03', NULL),
(85, 'Online influencers threatened with jail time over posts', 'online-influencers-threatened-with-jail-time-over-posts', 'Under a threat of potential jail time, a group of British online influencers including Ellie Goulding, Rita Ora and Zoella have agreed to change how they post online, after their social media profiles were investigated by an advertising watchdog.', 'Under a threat of potential jail time, a group of British online influencers including Ellie Goulding, Rita Ora and Zoella have agreed to change how they post online, after their social media profiles were investigated by an advertising watchdog.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/uk/influencers-online-posts-investigation-scli-gbr-intl/index.html', '2019-01-23 08:57:02', NULL),
(86, 'What\'s really different about the Oscar nominations', 'what-s-really-different-about-the-oscar-nominations', 'An African king broke the superhero barrier for a best picture nomination while nods for producing, directing, cinematography and original screenplay all went to the same Mexican director. And for the first time ever, an African-American was nominated for an Oscar in production design.', 'An African king broke the superhero barrier for a best picture nomination while nods for producing, directing, cinematography and original screenplay all went to the same Mexican director. And for the first time ever, an African-American was nominate ...', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/22/opinions/oscar-nominations-diversity-seymour/index.html', '2019-01-23 07:51:31', NULL),
(87, 'The hidden pitfalls of popular diets you just resolved to follow', 'the-hidden-pitfalls-of-popular-diets-you-just-resolved-to-follow', 'If you\'ve resolved to eat healthier at one point or another, you may have been enticed by popular nutrition trends, like organic or gluten-free eating, or even vegetarianism.', 'If you\'ve resolved to eat healthier at one point or another, you may have been enticed by popular nutrition trends, like organic or gluten-free eating, or even vegetarianism.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/health/diet-pitfalls-food-drayer/index.html', '2019-01-23 07:48:12', NULL),
(88, 'D&amp;G ad campaign \'almost ruined my career\'', 'd-amp-g-ad-campaign-almost-ruined-my-career', 'The Chinese model who starred in a series of controversial Dolce &amp; Gabbana videos has said the controversy surrounding the campaign almost ruined her career.', 'The Chinese model who starred in a series of controversial Dolce &amp; Gabbana videos has said the controversy surrounding the campaign almost ruined her career.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/style/article/dolce-and-gabbana-model-zuo-ye/index.html', '2019-01-23 05:28:45', NULL),
(89, 'Macau syndicate smuggled $4.4 billion out of China, police say', 'macau-syndicate-smuggled-4-4-billion-out-of-china-police-say', 'Chinese authorities say they have busted an underground money-smuggling ring used to launder more than $4.4 billion through the Asian gambling hub of Macau.', 'Chinese authorities say they have busted an underground money-smuggling ring used to launder more than $4.4 billion through the Asian gambling hub of Macau.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/23/asia/macau-currency-bust-intl/index.html', '2019-01-23 03:53:51', NULL),
(90, 'US university to cover Christopher Columbus murals', 'us-university-to-cover-christopher-columbus-murals', 'More than 130 years after their debut at the ceremonial entrance to the University of Notre Dame\'s Main Building, murals illustrating the life of Christopher Columbus will soon be covered up.', 'More than 130 years after their debut at the ceremonial entrance to the University of Notre Dame\'s Main Building, murals illustrating the life of Christopher Columbus will soon be covered up.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/style/article/university-of-notre-dame-christopher-columbus-murals-trnd-style/index.html', '2019-01-23 00:23:46', NULL),
(91, 'Famed \'bikini hiker\' dies in mountain fall', 'famed-bikini-hiker-dies-in-mountain-fall', 'Gigi Wu, a Taiwanese internet star famous for hiking mountains, has been found dead after falling into a gorge 30 meters deep (100 feet), the island\'s state media reported.', 'Gigi Wu, a Taiwanese internet star famous for hiking mountains, has been found dead after falling into a gorge 30 meters deep (100 feet), the island\'s state media reported.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/22/asia/taiwan-gigi-wu-death-intl/index.html', '2019-01-22 16:56:39', NULL),
(92, 'Harms of aspirin may outweigh the benefits', 'harms-of-aspirin-may-outweigh-the-benefits', 'Aspirin, a mild pain reliever, is one of the most familiar medicines in the world. One increasingly common use of this popular medicine, though, may not be safe for some older adults, a new analysis of existing research suggests.', 'Aspirin, a mild pain reliever, is one of the most familiar medicines in the world. One increasingly common use of this popular medicine, though, may not be safe for some older adults, a new analysis of existing research suggests.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/2019/01/22/health/aspirin-heart-trouble-study/index.html', '2019-01-22 14:00:33', NULL),
(93, '$5 billion artificial island resort takes shape', '5-billion-artificial-island-resort-takes-shape', 'Four kilometers off the Dubai coastline lies Europe. Or a version of it, at least.', 'Four kilometers off the Dubai coastline lies Europe. Or a version of it, at least.', 5, 0, 1, 1, 'E', NULL, 'CNN Top Stories', 'https://www.cnn.com/travel/article/the-heart-of-europe-the-world-dubai/index.html', '2019-01-22 01:52:14', NULL),
(94, 'Goodbye, old iPhone: This could be 40X better', 'goodbye-old-iphone-this-could-be-40x-better', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/2ljBSpruuXg\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '170821105452-cnnpartnerimage-fool-applestore-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/2ljBSpruuXg/iphone-tsunami-advert', '2019-01-24 07:47:30', NULL),
(95, 'Shinzo Abe took the poor man\'s route to Davos', 'shinzo-abe-took-the-poor-man-s-route-to-davos', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/pPKlxzzUih8\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '190124124515-shinzo-abe-train-davos-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/pPKlxzzUih8/2FTaH3U', '2019-01-24 07:47:30', NULL),
(96, 'Know someone who is an everyday hero? Tell us here.', 'know-someone-who-is-an-everyday-hero-tell-us-here', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/JAAqBYOIFSc\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '171211143828-03-young-wonder-sidney-keys-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/JAAqBYOIFSc/', '2019-01-24 07:47:30', NULL),
(97, 'Jack Ma: \'Today the world is full of suspicion\'', 'jack-ma-today-the-world-is-full-of-suspicion', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/ah-r-5X7Q1s\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '190124124117-jack-ma-wef-2019-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/ah-r-5X7Q1s/2CJU8Vc', '2019-01-24 07:47:30', NULL),
(98, 'Venezuelan opposition leader declares himself acting president amid massive marches', 'venezuelan-opposition-leader-declares-himself-acting-president-amid-massive-marches', 'Scores of people are expected to take to the streets of Venezuela on Wednesday in a revitalized effort against President Nicolas Maduro and his government.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/8dZk0R1VzqE\" height=\"1\" width=\"1\" alt=\"\">\n', 'Scores of people are expected to take to the streets of Venezuela on Wednesday in a revitalized effort against President Nicolas Maduro and his government.\n', 5, 0, 1, 1, 'E', '190123101314-venezuela-opposition-demostration-caracas-getty-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/8dZk0R1VzqE/index.html', '2019-01-24 07:47:30', NULL),
(99, 'Zimbabwean music legend Oliver Mtukudzi has died', 'zimbabwean-music-legend-oliver-mtukudzi-has-died', 'One of Zimbabwe\'s most celebrated musician Oliver Mtukudzi died Wednesday of an undisclosed ailment, a former colleague has said.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/y6Hnoh1wHjM\" height=\"1\" width=\"1\" alt=\"\">\n', 'One of Zimbabwe\'s most celebrated musician Oliver Mtukudzi died Wednesday of an undisclosed ailment, a former colleague has said.\n', 5, 0, 1, 1, 'E', '130108102610-tuku-1-vertical-large-gallery.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/y6Hnoh1wHjM/index.html', '2019-01-24 07:47:31', NULL),
(100, 'Emiliano Sala\'s family plead with rescuers to continue their search', 'emiliano-sala-s-family-plead-with-rescuers-to-continue-their-search', 'British police searching for missing Argentine soccer player Emiliano Sala say they are prioritizing their search based on the possibility that Sala and the other person on board the aircraft, which went missing over the English Channel, have landed on water and made it onto the life raft which was on board the plane.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/0Iw8GUpmbsU\" height=\"1\" width=\"1\" alt=\"\">\n', 'British police searching for missing Argentine soccer player Emiliano Sala say they are prioritizing their search based on the possibility that Sala and the other person on board the aircraft, which went missing over the English Channel, have landed ...', 5, 0, 1, 1, 'E', '190122161006-emiliano-sala-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/0Iw8GUpmbsU/index.html', '2019-01-24 07:47:31', NULL),
(101, 'Nigerian police officer tells gays: Leave country or face prosecution', 'nigerian-police-officer-tells-gays-leave-country-or-face-prosecution', 'A high-ranking Nigerian policewoman has warned gay people living in the country to leave or risk criminal prosecution.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/SRjVaaM62T8\" height=\"1\" width=\"1\" alt=\"\">\n', 'A high-ranking Nigerian policewoman has warned gay people living in the country to leave or risk criminal prosecution.\n', 5, 0, 1, 1, 'E', '190123150315-dolapo-badmos-twitter-profile-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/SRjVaaM62T8/index.html', '2019-01-24 07:47:31', NULL),
(102, 'Crypto was hot at Davos last year. Now, not so much', 'crypto-was-hot-at-davos-last-year-now-not-so-much', 'When the global business elite last gathered in Davos for their annual shindig, one bitcoin was worth $10,000. This year, a unit of the cryptocurrency buys just $3,560.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/3Yfcz4aT1Uk\" height=\"1\" width=\"1\" alt=\"\">\n', 'When the global business elite last gathered in Davos for their annual shindig, one bitcoin was worth $10,000. This year, a unit of the cryptocurrency buys just $3,560.\n', 5, 0, 1, 1, 'E', '180927091832-01-bitcoin-illustration-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/3Yfcz4aT1Uk/index.html', '2019-01-24 07:47:31', NULL),
(103, 'Chef José Andrés serves free meals to furloughed federal workers in DC', 'chef-jose-andres-serves-free-meals-to-furloughed-federal-workers-in-dc', 'In the past few years renowned chef José Andrés and his nonprofit have served millions of free meals to needy Americans after such natural disasters as Hurricane Maria and the California wildfires.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/IBOiQ49Ywiw\" height=\"1\" width=\"1\" alt=\"\">\n', 'In the past few years renowned chef José Andrés and his nonprofit have served millions of free meals to needy Americans after such natural disasters as Hurricane Maria and the California wildfires.\n', 5, 0, 1, 1, 'E', '180612095738-02-jose-andres-feeding-guatemala-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/IBOiQ49Ywiw/index.html', '2019-01-24 07:47:31', NULL),
(104, 'How to keep a healthy lifestyle while traveling for work', 'how-to-keep-a-healthy-lifestyle-while-traveling-for-work', 'These tips can help you clear your head, lower your heart rate and keep you focused.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/Q1HKHHnhwq8\" height=\"1\" width=\"1\" alt=\"\">\n', 'These tips can help you clear your head, lower your heart rate and keep you focused.\n', 5, 0, 1, 1, 'E', '190117104913-businessman-on-bike-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/Q1HKHHnhwq8/index.html', '2019-01-24 07:47:31', NULL),
(105, 'Youngest member of Nehru-Gandhi dynasty fires up Indian politics', 'youngest-member-of-nehru-gandhi-dynasty-fires-up-indian-politics', 'Priyanka Gandhi Vadra -- the youngest member of India\'s Nehru-Gandhi political dynasty -- took on a formal leadership role in the main opposition Congress Party Wednesday, injecting a burst of enthusiasm among its ranks as the country gears up for general elections.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/bgB1prj-I-U\" height=\"1\" width=\"1\" alt=\"\">\n', 'Priyanka Gandhi Vadra -- the youngest member of India\'s Nehru-Gandhi political dynasty -- took on a formal leadership role in the main opposition Congress Party Wednesday, injecting a burst of enthusiasm among its ranks as the country gears up for ge ...', 5, 0, 1, 1, 'E', '190123134507-02-priyanka-gandhi-vadra-file-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/bgB1prj-I-U/index.html', '2019-01-24 07:47:32', NULL),
(106, 'Japanese sponsor accused of \'whitewashing\' tennis star Naomi Osaka', 'japanese-sponsor-accused-of-whitewashing-tennis-star-naomi-osaka', 'One of Naomi Osaka\'s Japanese sponsors has apologized after releasing a advertisement which has been widely accused of \"whitewashing\" the tennis star\'s skin tone.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/kPhurkxAF1o\" height=\"1\" width=\"1\" alt=\"\">\n', 'One of Naomi Osaka\'s Japanese sponsors has apologized after releasing a advertisement which has been widely accused of \"whitewashing\" the tennis star\'s skin tone.\n', 5, 0, 1, 1, 'E', '190123153803-20190123-nissin-tennis-split-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/kPhurkxAF1o/index.html', '2019-01-24 07:47:32', NULL),
(107, 'Macau syndicate smuggled $4.4 billion out of China, police say', 'macau-syndicate-smuggled-4-4-billion-out-of-china-police-say-1', 'Chinese authorities say they have busted an underground money-smuggling ring used to launder more than $4.4 billion through the Asian gambling hub of Macau.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/JYRtKlOy4oY\" height=\"1\" width=\"1\" alt=\"\">\n', 'Chinese authorities say they have busted an underground money-smuggling ring used to launder more than $4.4 billion through the Asian gambling hub of Macau.\n', 5, 0, 1, 1, 'E', '190123110642-01-macau-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/JYRtKlOy4oY/index.html', '2019-01-24 07:47:32', NULL),
(108, 'Boateng to Barca?! Five of the strangest January transfers', 'boateng-to-barca-five-of-the-strangest-january-transfers', 'Kevin-Prince Boateng signing for Barcelona in the January transfer window caught many people by surprise.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/D3AG2JDG3fI\" height=\"1\" width=\"1\" alt=\"\">\n', 'Kevin-Prince Boateng signing for Barcelona in the January transfer window caught many people by surprise.\n', 5, 0, 1, 1, 'E', '190122140358-kevin-prince-boateng-barcelona-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/D3AG2JDG3fI/index.html', '2019-01-24 07:47:32', NULL),
(109, 'Fairytale of New York? New club\'s plan to win over Big Apple', 'fairytale-of-new-york-new-club-s-plan-to-win-over-big-apple', 'Bringing professional rugby to the Big Apple has been no mean feat. So when Rugby United New York takes to the field for its first competitive game in Major League Rugby, away to San Diego Legion on January 27, there will be a smile on one man\'s face.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/x6qN13q8SLQ\" height=\"1\" width=\"1\" alt=\"\">\n', 'Bringing professional rugby to the Big Apple has been no mean feat. So when Rugby United New York takes to the field for its first competitive game in Major League Rugby, away to San Diego Legion on January 27, there will be a smile on one man\'s face ...', 5, 0, 1, 1, 'E', '190122093153-new-york-skyline-tease-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/x6qN13q8SLQ/index.html', '2019-01-24 07:47:32', NULL),
(110, 'Star\'s favorite hotel gets $280 million facelift', 'star-s-favorite-hotel-gets-280-million-facelift', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/trYnHtIUSX8\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '190108172203-11-hotel-de-paris-monaco-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/trYnHtIUSX8/index.html', '2019-01-24 07:47:33', NULL),
(111, 'Georgia has rugby on its mind ahead of World Cup', 'georgia-has-rugby-on-its-mind-ahead-of-world-cup', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/8pcr9MlEssE\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '190121103651-georgia-rugby-fan-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/8pcr9MlEssE/index.html', '2019-01-24 07:47:33', NULL),
(112, 'Virgin reveals adults-only cruise ship', 'virgin-reveals-adults-only-cruise-ship', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/vZsCMCnf7D4\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '190121143109-virgin-voyages-scarlet-lady-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/vZsCMCnf7D4/index.html', '2019-01-24 07:47:34', NULL),
(113, 'Pacquiao challenges Mayweather to a rematch', 'pacquiao-challenges-mayweather-to-a-rematch', 'It was almost three years ago that Manny Pacquiao and Floyd Mayweather fought in the \"Fight of the Century\" and now the 40-year-old Pacquiao wants to avenge his 2015 defeat.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/0QLpN4qXHTg\" height=\"1\" width=\"1\" alt=\"\">\n', 'It was almost three years ago that Manny Pacquiao and Floyd Mayweather fought in the \"Fight of the Century\" and now the 40-year-old Pacquiao wants to avenge his 2015 defeat.\n', 5, 0, 1, 1, 'E', '190120182246-manny-pacquiao-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/0QLpN4qXHTg/index.html', '2019-01-24 07:47:34', NULL),
(114, 'Lindsey Vonn may have competed in her last race', 'lindsey-vonn-may-have-competed-in-her-last-race', 'It is a tale of two Americans at opposite ends of their glorious careers.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/gq_mJv520U8\" height=\"1\" width=\"1\" alt=\"\">\n', 'It is a tale of two Americans at opposite ends of their glorious careers.\n', 5, 0, 1, 1, 'E', '181010145755-lindsey-vonn-skiing-val-disere-celebration-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/gq_mJv520U8/index.html', '2019-01-24 07:47:35', NULL),
(115, 'Pretty Italian town sells homes for a dollar', 'pretty-italian-town-sells-homes-for-a-dollar', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/gBnC8F8_XUs\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '190115110801-sambuca-tease-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/gBnC8F8_XUs/index.html', '2019-01-24 07:47:35', NULL),
(116, 'Rare photos reveal life in 19th-century China', 'rare-photos-reveal-life-in-19th-century-china', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/twZ3Gyv1BHI\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '190108155156-loewentheil-collection-tease-1-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/twZ3Gyv1BHI/index.html', '2019-01-24 07:47:36', NULL),
(117, 'A man\'s mission to find a kidney donor for his wife goes viral', 'a-man-s-mission-to-find-a-kidney-donor-for-his-wife-goes-viral', 'It was a Friday afternoon like any other. I ate lunch, dropped off my dry cleaning, then stopped by my local Target to pick up some prescriptions.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/5lCevJ0zcds\" height=\"1\" width=\"1\" alt=\"\">\n', 'It was a Friday afternoon like any other. I ate lunch, dropped off my dry cleaning, then stopped by my local Target to pick up some prescriptions.\n', 5, 0, 1, 1, 'E', '181227143432-raymond-thompson-kidney-transplant-sign-trnd-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/5lCevJ0zcds/index.html', '2019-01-24 07:47:37', NULL),
(118, 'Unseen photos of America\'s early \'working girls\'', 'unseen-photos-of-america-s-early-working-girls', 'Women in sexual professions have always distinguished themselves from other women, from the mores of the time, by pushing the boundaries of style. The most celebrated concubines and courtesans in history set the trends in their respective courts. The great dames of burlesque -- Sally Rand, Gypsy Rose Lee -- boasted a signature style on- and offstage, reflecting broader-than-life personalities.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/yLl_BXhu6jE\" height=\"1\" width=\"1\" alt=\"\">\n', 'Women in sexual professions have always distinguished themselves from other women, from the mores of the time, by pushing the boundaries of style. The most celebrated concubines and courtesans in history set the trends in their respective courts. The ...', 5, 0, 1, 1, 'E', '181123164600-working-girls-tease-3-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/yLl_BXhu6jE/index.html', '2019-01-24 07:47:37', NULL),
(119, 'Jam-packed island is running out of space', 'jam-packed-island-is-running-out-of-space', '<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/MVPnJTwWLlk\" height=\"1\" width=\"1\" alt=\"\">', '', 5, 0, 1, 1, 'E', '180806133431-santa-cruz-island-aerial-turquoise-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/MVPnJTwWLlk/index.html', '2019-01-24 07:47:38', NULL),
(120, '11 ads that captured the spirit of the \'90s', '11-ads-that-captured-the-spirit-of-the-90s', 'If \"Hasta la vista, baby,\" \"You had me at hello,\" and \"Yada, yada, yada\" mean nothing to you, chances are you were not around in the 1990s, a decade whose legacy has yet to fully crystallize in our collective imagination. (These are popular catchphrases from \"Terminator,\" \"Jerry Maguire\" and \"Seinfeld.\")<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/IPm5xgrC1MI\" height=\"1\" width=\"1\" alt=\"\">\n', 'If \"Hasta la vista, baby,\" \"You had me at hello,\" and \"Yada, yada, yada\" mean nothing to you, chances are you were not around in the 1990s, a decade whose legacy has yet to fully crystallize in our collective imagination. (These are popular catchphra ...', 5, 0, 1, 1, 'E', '180606133340-mi-all-merican-ads-90s-hc-p561-1804181643-id-1187562-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/IPm5xgrC1MI/index.html', '2019-01-24 07:47:38', NULL),
(121, 'The Instagram hoax that fooled thousands', 'the-instagram-hoax-that-fooled-thousands', 'Argentinian-born artist Amalia Ulman\'s \"Excellences and Perfections\" performance happened only four years ago, but in the digital age, it feels like much longer.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/WvQ2d0fg-XQ\" height=\"1\" width=\"1\" alt=\"\">\n', 'Argentinian-born artist Amalia Ulman\'s \"Excellences and Perfections\" performance happened only four years ago, but in the digital age, it feels like much longer.\n', 5, 0, 1, 1, 'E', '180330110111-amalia-ulman-selfie-tease-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/WvQ2d0fg-XQ/index.html', '2019-01-24 07:47:38', NULL),
(122, 'How the Soviet \'Concordski\' crashed and burned', 'how-the-soviet-concordski-crashed-and-burned', 'When the Soviet rival to the Concorde made its first foreign appearance at the Paris Air Show in 1971, everyone was impressed. In the heated race to develop a supersonic passenger jet, it was the USSR who got off to a head start.<img src=\"http://feeds.feedburner.com/~r/rss/edition_world/~4/Jx7glrCUuaY\" height=\"1\" width=\"1\" alt=\"\">\n', 'When the Soviet rival to the Concorde made its first foreign appearance at the Paris Air Show in 1971, everyone was impressed. In the heated race to develop a supersonic passenger jet, it was the USSR who got off to a head start.\n', 5, 0, 1, 1, 'E', '170814141450-gettyimages-89965054-super-169.jpg', 'CNN World', 'http://rss.cnn.com/~r/rss/edition_world/~3/Jx7glrCUuaY/index.html', '2019-01-24 07:47:39', NULL),
(123, 'GOP allies Graham, McCarthy defend Trump against Pelosi in battle over State of the Union address', 'gop-allies-graham-mccarthy-defend-trump-against-pelosi-in-battle-over-state-of-the-union-address', 'It was unclear exactly what prompted President Trump\'s decision Wednesday night to postpone his State of the Union address, but earlier in the evening it seemed that two of his closest Republican allies -- Sen. Lindsey Graham and House Minority Leader Kevin McCarthy -- were standing by the president in his battle with House Speaker Nancy Pelosi regarding plans for the speech.  ', 'It was unclear exactly what prompted President Trump\'s decision Wednesday night to postpone his State of the Union address, but earlier in the evening it seemed that two of his closest Republican allies -- Sen. Lindsey Graham and House Minority Lead ...', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/politics/gop-allies-say-pelosi-acting-like-a-dictator-and-a-nightmare-for-dems-for-canceling-state-of-the-union-during-shutdown', '2019-01-24 05:44:05', NULL),
(124, 'Most expensive home in US – dubbed ‘billionaires’ bunker’ – purchased for $238 million', 'most-expensive-home-in-us-dubbed-billionaires-bunker-purchased-for-238-million', 'A hedgefunder has just bought the most expensive home in US history — a $238 million four-story condo on Central Park South in a building dubbed the billionaires’ bunker.”', 'A hedgefunder has just bought the most expensive home in US history — a $238 million four-story condo on Central Park South in a building dubbed the billionaires’ bunker.”', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/real-estate/most-expensive-home-in-us-dubbed-billionaires-bunker-purchased-for-238-million', '2019-01-24 05:43:51', NULL),
(125, 'Indiana superintendent charged with fraud for allegedly using own insurance to help ill student', 'indiana-superintendent-charged-with-fraud-for-allegedly-using-own-insurance-to-help-ill-student', 'An Indiana school superintendent is facing fraud charges for allegedly seeking medical treatment for a student using her son’s name.', 'An Indiana school superintendent is facing fraud charges for allegedly seeking medical treatment for a student using her son’s name.', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/us/indiana-school-superintendent-allegedly-seeks-treatment-for-student-under-sons-name', '2019-01-24 05:33:43', NULL),
(126, 'Oregon family files $250G lawsuit, claims neighbor masturbated on 14-year-old’s clothes: report', 'oregon-family-files-250g-lawsuit-claims-neighbor-masturbated-on-14-year-old-s-clothes-report', 'The family of an Oregon teenager has filed a $250,000 lawsuit against a 21-year-old for allegedly sneaking into their apartment and masturbating’s on the teen’s clothes, according to a Wednesday report.', 'The family of an Oregon teenager has filed a $250,000 lawsuit against a 21-year-old for allegedly sneaking into their apartment and masturbating’s on the teen’s clothes, according to a Wednesday report.', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/us/oregon-family-files-250g-lawsuit-claims-neighbor-masturbated-on-14-year-olds-clothes-report', '2019-01-24 05:22:27', NULL),
(127, 'Alaska dad, son sentenced for killing mother bear, \'shrieking\' newborn cubs', 'alaska-dad-son-sentenced-for-killing-mother-bear-shrieking-newborn-cubs', 'An Alaska man and his son have been sentenced Tuesday to jail for the poaching of a mother black bear and point-blank slaughter of her two cubs after their illegal actions were caught in a research camera.', 'An Alaska man and his son have been sentenced Tuesday to jail for the poaching of a mother black bear and point-blank slaughter of her two cubs after their illegal actions were caught in a research camera.', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/us/father-son-sentenced-to-jail-for-poaching-point-blank-slaughter-of-mother-bear-2-cubs', '2019-01-24 04:37:39', NULL),
(128, 'Ohio\'s new governor says he\'ll sign \'heartbeat\' abortion bill that Kasich vetoed', 'ohio-s-new-governor-says-he-ll-sign-heartbeat-abortion-bill-that-kasich-vetoed', 'Ohio Gov. Mike DeWine said Wednesday that he will “absolutely” sign a controversial “heartbeat bill” that former Gov. John Kasich vetoed last month. The proposal would make abortions illegal once a fetal heartbeat can be detected.', 'Ohio Gov. Mike DeWine said Wednesday that he will “absolutely” sign a controversial “heartbeat bill” that former Gov. John Kasich vetoed last month. The proposal would make abortions illegal once a fetal heartbeat can be detected.', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/politics/ohios-new-governor-says-hell-sign-abortion-bill-that-kasich-vetoed', '2019-01-24 04:29:52', NULL),
(129, 'Army vet\'s Trump T-shirt made fellow gym patrons feel \'uncomfortable,\' report says', 'army-vet-s-trump-t-shirt-made-fellow-gym-patrons-feel-uncomfortable-report-says', 'The owner of a gym in Missouri asked an Army veteran to not wear a Trump T-shirt during his next visit, saying the shirt was making other gym patrons feel \"uncomfortable,\" according to reports.', 'The owner of a gym in Missouri asked an Army veteran to not wear a Trump T-shirt during his next visit, saying the shirt was making other gym patrons feel \"uncomfortable,\" according to reports.', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/us/gym-owner-told-veteran-not-to-wear-trump-t-shirt-calling-it-racist-inappropriate-report', '2019-01-24 04:16:25', NULL),
(130, 'Laura Ingraham rips Trump’s decision to postpone State of the Union', 'laura-ingraham-rips-trump-s-decision-to-postpone-state-of-the-union', 'Laura Ingraham, the host of Fox News’ \"The Ingraham Angle,\" called out President Trump for delaying his State of the Union address until after the partial government shutdown is over.', 'Laura Ingraham, the host of Fox News’ \"The Ingraham Angle,\" called out President Trump for delaying his State of the Union address until after the partial government shutdown is over.', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/politics/laura-ingraham-rips-trumps-decision-to-postpone-state-of-the-union', '2019-01-24 04:02:55', NULL),
(131, 'Kamala Harris says president should ‘open up’ Trump Tower to federal workers furloughed by shutdown', 'kamala-harris-says-president-should-open-up-trump-tower-to-federal-workers-furloughed-by-shutdown', 'U.S. Sen. Kamala Harris, a California Democrat who has declared her 2020 presidential candidacy, has challenged President Trump to use his tower in New York City as shelter for furloughed federal workers during the partial government shutdown.', 'U.S. Sen. Kamala Harris, a California Democrat who has declared her 2020 presidential candidacy, has challenged President Trump to use his tower in New York City as shelter for furloughed federal workers during the partial government shutdown.', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/politics/kamala-harris-says-president-should-open-up-trump-tower-to-federal-workers-furloughed-by-shutdown', '2019-01-24 03:46:47', NULL),
(132, 'National Shrine confirms report that Native American activist allegedly tried to disrupt Mass', 'national-shrine-confirms-report-that-native-american-activist-allegedly-tried-to-disrupt-mass', 'Officials at the Basilica of the National Shrine of the Immaculate Conception in Washington, D.C., on Wednesday confirmed earlier reports that a Native American rights activist led several dozen people in an attempt to enter the shrine during a Saturday evening Mass.', 'Officials at the Basilica of the National Shrine of the Immaculate Conception in Washington, D.C., on Wednesday confirmed earlier reports that a Native American rights activist led several dozen people in an attempt to enter the shrine during a Sat ...', 5, 0, 1, 1, 'E', NULL, 'FOX News', 'https://www.foxnews.com/us/national-shrine-says-nathan-phillips-allegedly-tried-to-disrupt-mass', '2019-01-24 03:09:41', NULL),
(133, 'Aboutj', 'aboutj', 'kkkkkkk', 'Aboutj', 5, 0, 1, 0, 'E', 'palyfull.jpeg', '111111', '22222', '2019-02-26 15:30:14', '2019-02-26 13:30:14'),
(134, 'tttttt', 'tttttt', 'tttttttttttttttt', 'tttttt', 5, 0, 0, 0, 'E', NULL, NULL, 'mmmmmmm', '2019-02-27 13:17:35', '2019-02-27 13:17:35'),
(135, '111111', '111111', '<ul> <li>aaa</li> <li>bbb</li> <li>ccccc</li> <li><em><strong>3333</strong></em>333</li> <li>4444</li> <li>55<em>55555</em>55  </li> </ul>', '22222222', 5, 0, 0, 1, 'N', NULL, '1234444', '123', '2019-02-27 15:50:12', '2019-02-27 15:50:12');

-- --------------------------------------------------------

--
-- Структура таблицы `page_content_images`
--

CREATE TABLE `page_content_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `page_content_id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT '0',
  `is_video` tinyint(1) NOT NULL DEFAULT '0',
  `video_width` smallint(6) DEFAULT NULL,
  `video_height` smallint(6) DEFAULT NULL,
  `info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `page_content_images`
--

INSERT INTO `page_content_images` (`id`, `page_content_id`, `filename`, `is_main`, `is_video`, `video_width`, `video_height`, `info`, `created_at`) VALUES
(1, 1, 'your_vote.jpg', 1, 0, NULL, NULL, 'Site slogan image', '2019-02-22 07:16:17'),
(2, 1, 'our_boss.jpg', 0, 0, NULL, NULL, 'Our boss photo', '2019-02-22 07:16:17'),
(3, 1, 'our_main_manager.jpg', 0, 0, NULL, NULL, 'Our main manager', '2019-02-22 07:16:17'),
(4, 1, 'video_demo.mp4', 0, 1, 720, 404, 'video demo Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua.', '2019-02-22 07:16:17'),
(5, 2, 'office_building.jpeg', 1, 0, NULL, NULL, 'Office building', '2019-02-22 07:16:17'),
(6, 2, 'video.mp4', 0, 1, 200, 110, 'Our Office video Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua', '2019-02-22 07:16:17'),
(7, 133, 'thinking.jpg', 1, 0, NULL, NULL, ',,,,,', '2019-02-26 15:30:14');

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `quiz_quality_results`
--

CREATE TABLE `quiz_quality_results` (
  `id` int(10) UNSIGNED NOT NULL,
  `vote_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `quiz_quality_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `quiz_quality_results`
--

INSERT INTO `quiz_quality_results` (`id`, `vote_id`, `user_id`, `quiz_quality_id`, `created_at`) VALUES
(1, 1, 1, 4, '2019-01-27 02:01:39'),
(2, 2, 1, 5, '2019-02-02 07:02:39'),
(4, 4, 1, 5, '2019-02-13 02:02:01'),
(6, 6, 1, 1, '2019-02-07 08:02:55'),
(10, 10, 1, 1, '2019-02-21 01:02:14'),
(15, 15, 1, 2, '2019-02-16 05:02:07'),
(16, 16, 1, 1, '2019-01-25 08:01:08'),
(19, 19, 1, 5, '2019-02-06 02:02:35'),
(20, 1, 2, 5, '2019-02-07 07:02:54'),
(21, 2, 2, 5, '2019-02-05 02:02:13'),
(23, 4, 2, 2, '2019-01-23 09:01:45'),
(25, 6, 2, 1, '2019-01-26 03:01:23'),
(29, 10, 2, 1, '2019-02-08 10:02:43'),
(34, 15, 2, 1, '2019-02-09 04:02:25'),
(35, 16, 2, 2, '2019-02-08 00:02:41'),
(38, 19, 2, 5, '2019-01-29 09:01:38'),
(39, 1, 3, 5, '2019-02-12 09:02:53'),
(40, 2, 3, 5, '2019-01-28 00:01:55'),
(42, 4, 3, 3, '2019-01-24 02:01:40'),
(44, 6, 3, 2, '2019-01-27 01:01:17'),
(48, 10, 3, 2, '2019-01-25 01:01:36'),
(53, 15, 3, 1, '2019-01-31 02:01:25'),
(54, 16, 3, 4, '2019-02-06 03:02:44'),
(57, 19, 3, 3, '2019-02-09 10:02:21'),
(58, 1, 4, 4, '2019-02-06 08:02:49'),
(59, 2, 4, 5, '2019-02-15 01:02:12'),
(61, 4, 4, 1, '2019-02-03 08:02:57'),
(63, 6, 4, 3, '2019-02-21 23:02:39'),
(67, 10, 4, 2, '2019-02-11 06:02:55'),
(72, 15, 4, 1, '2019-02-20 01:02:44'),
(73, 16, 4, 2, '2019-02-02 03:02:23'),
(76, 19, 4, 5, '2019-02-12 23:02:53'),
(77, 1, 5, 5, '2019-02-06 05:02:35'),
(78, 2, 5, 5, '2019-01-27 08:01:46'),
(80, 4, 5, 3, '2019-01-23 23:01:49'),
(82, 6, 5, 1, '2019-02-12 00:02:35'),
(86, 10, 5, 5, '2019-02-14 06:02:25'),
(91, 15, 5, 2, '2019-01-26 09:01:08'),
(92, 16, 5, 4, '2019-02-06 05:02:10'),
(95, 19, 5, 1, '2019-02-05 05:02:05'),
(115, 1, 7, 5, '2019-02-17 06:02:56'),
(116, 2, 7, 5, '2019-02-18 06:02:01'),
(118, 4, 7, 2, '2019-02-20 08:02:08'),
(120, 6, 7, 2, '2019-02-05 05:02:16'),
(124, 10, 7, 4, '2019-02-08 03:02:51'),
(129, 15, 7, 1, '2019-01-24 05:01:24'),
(130, 16, 7, 1, '2019-02-07 06:02:00'),
(133, 19, 7, 3, '2019-02-10 08:02:29'),
(134, 1, 8, 5, '2019-02-09 06:02:37'),
(135, 2, 8, 4, '2019-01-30 04:01:53'),
(137, 4, 8, 5, '2019-01-31 01:01:26'),
(139, 6, 8, 3, '2019-02-07 01:02:24'),
(143, 10, 8, 3, '2019-02-17 01:02:55'),
(148, 15, 8, 2, '2019-02-10 23:02:58'),
(149, 16, 8, 3, '2019-01-24 09:01:10'),
(152, 19, 8, 4, '2019-02-09 05:02:28'),
(153, 1, 9, 5, '2019-02-14 06:02:59'),
(154, 2, 9, 5, '2019-02-12 02:02:55'),
(156, 4, 9, 3, '2019-01-27 23:01:18'),
(158, 6, 9, 2, '2019-02-11 01:02:03'),
(162, 10, 9, 4, '2019-02-19 07:02:08'),
(167, 15, 9, 1, '2019-02-19 00:02:23'),
(168, 16, 9, 2, '2019-02-06 08:02:23'),
(171, 19, 9, 5, '2019-02-19 01:02:52'),
(172, 1, 10, 5, '2019-02-10 09:02:03'),
(173, 2, 10, 5, '2019-02-11 02:02:21'),
(175, 4, 10, 2, '2019-02-21 02:02:54'),
(177, 6, 10, 1, '2019-02-08 10:02:14'),
(181, 10, 10, 2, '2019-01-28 01:01:43'),
(186, 15, 10, 1, '2019-02-21 09:02:35'),
(187, 16, 10, 5, '2019-02-17 04:02:28'),
(190, 19, 10, 1, '2019-02-10 06:02:40'),
(191, 1, 11, 5, '2019-01-31 02:01:20'),
(192, 2, 11, 5, '2019-01-23 10:01:57'),
(194, 4, 11, 1, '2019-01-24 03:01:44'),
(196, 6, 11, 4, '2019-02-14 10:02:50'),
(200, 10, 11, 1, '2019-02-01 10:02:19'),
(205, 15, 11, 1, '2019-02-20 10:02:23'),
(206, 16, 11, 4, '2019-02-06 05:02:02'),
(209, 19, 11, 3, '2019-01-25 05:01:39'),
(210, 1, 12, 5, '2019-02-14 06:02:14'),
(211, 2, 12, 4, '2019-01-27 04:01:46'),
(213, 4, 12, 2, '2019-02-18 10:02:30'),
(215, 6, 12, 5, '2019-02-16 05:02:36'),
(219, 10, 12, 4, '2019-01-25 01:01:03'),
(224, 15, 12, 1, '2019-02-01 01:02:37'),
(225, 16, 12, 4, '2019-02-10 05:02:09'),
(228, 19, 12, 3, '2019-01-30 06:01:04'),
(229, 1, 13, 4, '2019-02-01 23:02:24'),
(230, 2, 13, 5, '2019-02-04 00:02:53'),
(232, 4, 13, 2, '2019-02-09 09:02:43'),
(234, 6, 13, 1, '2019-02-09 02:02:53'),
(238, 10, 13, 4, '2019-02-01 08:02:40'),
(243, 15, 13, 1, '2019-02-17 05:02:23'),
(244, 16, 13, 2, '2019-01-23 09:01:22'),
(247, 19, 13, 4, '2019-02-12 10:02:06'),
(248, 1, 14, 4, '2019-02-10 10:02:57'),
(249, 2, 14, 5, '2019-02-02 01:02:32'),
(251, 4, 14, 3, '2019-02-20 09:02:34'),
(253, 6, 14, 2, '2019-01-26 01:01:13'),
(257, 10, 14, 4, '2019-01-25 00:01:36'),
(262, 15, 14, 1, '2019-02-11 06:02:06'),
(263, 16, 14, 4, '2019-02-14 23:02:25'),
(266, 19, 14, 2, '2019-02-18 09:02:31'),
(267, 1, 15, 5, '2019-02-10 01:02:52'),
(268, 2, 15, 4, '2019-02-13 04:02:36'),
(270, 4, 15, 1, '2019-02-13 09:02:50'),
(272, 6, 15, 3, '2019-02-05 07:02:20'),
(276, 10, 15, 5, '2019-01-24 09:01:52'),
(281, 15, 15, 1, '2019-01-22 07:01:51'),
(282, 16, 15, 3, '2019-02-01 02:02:47'),
(285, 19, 15, 3, '2019-01-29 05:01:02'),
(286, 1, 16, 5, '2019-02-15 10:02:43'),
(287, 2, 16, 4, '2019-02-13 05:02:19'),
(289, 4, 16, 4, '2019-01-25 09:01:59'),
(291, 6, 16, 5, '2019-02-02 09:02:25'),
(295, 10, 16, 4, '2019-02-14 04:02:29'),
(300, 15, 16, 2, '2019-02-07 02:02:37'),
(301, 16, 16, 4, '2019-02-08 06:02:43'),
(304, 19, 16, 2, '2019-02-06 06:02:17'),
(305, 1, 17, 5, '2019-02-10 03:02:29'),
(306, 2, 17, 5, '2019-02-21 05:02:55'),
(308, 4, 17, 4, '2019-01-23 23:01:38'),
(310, 6, 17, 2, '2019-02-06 08:02:27'),
(314, 10, 17, 5, '2019-02-07 04:02:53'),
(319, 15, 17, 1, '2019-02-01 04:02:04'),
(320, 16, 17, 2, '2019-01-31 06:01:42'),
(323, 19, 17, 5, '2019-02-14 04:02:33'),
(324, 1, 18, 5, '2019-01-25 09:01:05'),
(325, 2, 18, 5, '2019-01-29 07:01:24'),
(327, 4, 18, 2, '2019-02-11 23:02:03'),
(329, 6, 18, 4, '2019-02-03 08:02:42'),
(333, 10, 18, 3, '2019-02-05 07:02:26'),
(338, 15, 18, 1, '2019-02-02 00:02:03'),
(339, 16, 18, 1, '2019-01-26 23:01:58'),
(342, 19, 18, 4, '2019-01-30 23:01:16'),
(343, 1, 19, 5, '2019-01-23 23:01:55'),
(344, 2, 19, 5, '2019-02-08 01:02:25'),
(346, 4, 19, 2, '2019-01-23 06:01:30'),
(348, 6, 19, 4, '2019-02-05 08:02:43'),
(352, 10, 19, 1, '2019-01-30 07:01:52'),
(357, 15, 19, 1, '2019-01-25 09:01:24'),
(358, 16, 19, 3, '2019-02-16 06:02:32'),
(361, 19, 19, 2, '2019-02-01 01:02:03'),
(362, 1, 20, 5, '2019-02-03 02:02:16'),
(363, 2, 20, 5, '2019-02-16 05:02:16'),
(365, 4, 20, 1, '2019-01-23 06:01:53'),
(367, 6, 20, 4, '2019-02-17 09:02:33'),
(371, 10, 20, 4, '2019-02-06 07:02:30'),
(376, 15, 20, 1, '2019-02-20 04:02:56'),
(377, 16, 20, 2, '2019-01-26 01:01:59'),
(380, 19, 20, 1, '2019-02-12 07:02:57'),
(381, 1, 21, 5, '2019-02-14 02:02:08'),
(382, 2, 21, 4, '2019-02-20 03:02:35'),
(384, 4, 21, 4, '2019-02-01 04:02:42'),
(386, 6, 21, 4, '2019-02-10 04:02:10'),
(390, 10, 21, 3, '2019-01-29 00:01:16'),
(395, 15, 21, 2, '2019-02-02 06:02:14'),
(396, 16, 21, 5, '2019-02-04 10:02:39'),
(399, 19, 21, 1, '2019-01-22 03:01:33'),
(400, 1, 22, 5, '2019-02-16 23:02:12'),
(401, 2, 22, 5, '2019-02-15 00:02:16'),
(403, 4, 22, 1, '2019-02-11 04:02:02'),
(405, 6, 22, 5, '2019-02-10 03:02:09'),
(409, 10, 22, 1, '2019-02-20 09:02:46'),
(414, 15, 22, 1, '2019-01-23 03:01:29'),
(415, 16, 22, 3, '2019-01-30 01:01:51'),
(418, 19, 22, 4, '2019-02-06 07:02:20'),
(419, 1, 23, 5, '2019-02-18 23:02:05'),
(420, 2, 23, 5, '2019-01-25 10:01:22'),
(422, 4, 23, 2, '2019-02-06 03:02:03'),
(424, 6, 23, 4, '2019-02-02 07:02:37'),
(428, 10, 23, 1, '2019-02-10 04:02:01'),
(433, 15, 23, 1, '2019-02-01 08:02:03'),
(434, 16, 23, 5, '2019-02-10 02:02:17'),
(437, 19, 23, 4, '2019-01-25 06:01:58'),
(438, 1, 24, 5, '2019-01-29 02:01:59'),
(439, 2, 24, 5, '2019-01-25 04:01:00'),
(441, 4, 24, 3, '2019-02-10 01:02:14'),
(443, 6, 24, 2, '2019-02-06 07:02:12'),
(447, 10, 24, 5, '2019-01-27 09:01:20'),
(452, 15, 24, 1, '2019-02-17 09:02:51'),
(453, 16, 24, 4, '2019-02-06 03:02:21'),
(456, 19, 24, 5, '2019-01-22 07:01:24'),
(457, 1, 25, 5, '2019-01-30 09:01:15'),
(458, 2, 25, 5, '2019-02-18 03:02:12'),
(460, 4, 25, 4, '2019-02-05 03:02:56'),
(462, 6, 25, 3, '2019-02-01 10:02:09'),
(466, 10, 25, 5, '2019-01-31 23:02:01'),
(471, 15, 25, 1, '2019-01-30 03:01:08'),
(472, 16, 25, 5, '2019-02-11 05:02:19'),
(475, 19, 25, 2, '2019-02-16 07:02:29'),
(476, 1, 26, 5, '2019-01-22 01:01:45'),
(477, 2, 26, 5, '2019-02-04 04:02:12'),
(479, 4, 26, 1, '2019-02-20 06:02:06'),
(481, 6, 26, 5, '2019-01-27 00:01:27'),
(485, 10, 26, 3, '2019-02-16 07:02:44'),
(490, 15, 26, 1, '2019-01-29 10:01:45'),
(491, 16, 26, 4, '2019-02-22 03:02:22'),
(494, 19, 26, 1, '2019-02-17 23:02:41'),
(495, 1, 27, 5, '2019-02-09 09:02:39'),
(496, 2, 27, 4, '2019-02-13 02:02:23'),
(498, 4, 27, 2, '2019-02-05 08:02:10'),
(500, 6, 27, 5, '2019-02-13 07:02:20'),
(504, 10, 27, 3, '2019-02-20 05:02:22'),
(509, 15, 27, 1, '2019-02-11 10:02:02'),
(510, 16, 27, 5, '2019-02-06 08:02:32'),
(513, 19, 27, 5, '2019-02-15 09:02:24'),
(514, 1, 28, 5, '2019-02-11 05:02:48'),
(515, 2, 28, 5, '2019-02-18 04:02:52'),
(517, 4, 28, 2, '2019-02-08 06:02:26'),
(519, 6, 28, 2, '2019-01-29 04:01:31'),
(523, 10, 28, 4, '2019-01-30 08:01:02'),
(528, 15, 28, 1, '2019-01-30 06:01:21'),
(529, 16, 28, 5, '2019-02-08 05:02:01'),
(532, 19, 28, 2, '2019-01-25 01:01:38'),
(533, 1, 29, 5, '2019-01-27 23:01:52'),
(534, 2, 29, 5, '2019-01-30 09:01:43'),
(536, 4, 29, 5, '2019-02-15 23:02:34'),
(538, 6, 29, 1, '2019-02-07 23:02:58'),
(542, 10, 29, 1, '2019-02-19 10:02:05'),
(547, 15, 29, 1, '2019-02-18 05:02:18'),
(548, 16, 29, 2, '2019-02-02 09:02:00'),
(551, 19, 29, 2, '2019-02-15 03:02:14'),
(552, 1, 30, 5, '2019-02-11 00:02:47'),
(553, 2, 30, 5, '2019-02-07 00:02:14'),
(555, 4, 30, 2, '2019-02-12 07:02:47'),
(557, 6, 30, 2, '2019-02-09 08:02:42'),
(561, 10, 30, 2, '2019-02-10 03:02:16'),
(566, 15, 30, 1, '2019-02-15 23:02:25'),
(567, 16, 30, 4, '2019-02-21 09:02:18'),
(570, 19, 30, 2, '2019-02-02 01:02:07'),
(571, 1, 31, 4, '2019-02-11 00:02:16'),
(572, 2, 31, 5, '2019-01-27 03:01:22'),
(574, 4, 31, 1, '2019-02-03 05:02:32'),
(576, 6, 31, 5, '2019-02-13 06:02:05'),
(580, 10, 31, 5, '2019-01-25 01:01:12'),
(585, 15, 31, 1, '2019-02-06 08:02:29'),
(586, 16, 31, 3, '2019-02-10 03:02:03'),
(589, 19, 31, 1, '2019-01-31 07:01:25');

-- --------------------------------------------------------

--
-- Структура таблицы `search_results`
--

CREATE TABLE `search_results` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `found_results` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `search_results`
--

INSERT INTO `search_results` (`id`, `user_id`, `text`, `found_results`, `created_at`) VALUES
(1, 15, 'soccer', 0, '2019-02-07 09:02:05'),
(2, 4, 'cartoon dog', 0, '2019-02-06 04:02:56'),
(3, 14, 'Everest', 3, '2019-02-18 23:02:56'),
(4, NULL, 'environment', 0, '2019-02-17 09:02:32'),
(5, NULL, 'hook', 0, '2019-02-17 07:02:51'),
(6, 26, 'Batman', 1, '2019-02-12 23:02:01'),
(7, 16, 'comedy', 5, '2019-02-10 09:02:30'),
(8, NULL, 'cartoon dog', 0, '2019-01-26 07:01:08'),
(9, NULL, 'Everest', 1, '2019-02-09 23:02:47'),
(10, 19, 'stand up', 0, '2019-01-26 02:01:02'),
(11, 10, 'environment', 0, '2019-01-23 04:01:55'),
(12, NULL, 'hook', 0, '2019-02-17 10:02:57'),
(13, NULL, 'Batman', 3, '2019-01-22 01:01:25'),
(14, 12, 'theatrical', 5, '2019-02-19 00:02:05'),
(15, 24, 'cartoon dog', 4, '2019-01-25 07:01:05'),
(16, 5, 'stand up', 0, '2019-02-03 03:02:30'),
(17, NULL, 'movie Jaws', 5, '2019-02-21 04:02:46'),
(18, NULL, 'environment', 0, '2019-01-22 02:01:09'),
(19, 1, 'mountain', 2, '2019-02-19 09:02:36'),
(20, NULL, 'Batman', 4, '2019-01-22 06:01:36'),
(21, NULL, 'Tragedy', 2, '2019-01-27 05:01:55'),
(22, NULL, 'soccer', 0, '2019-02-18 02:02:08'),
(23, NULL, 'cartoon dog', 0, '2019-02-11 10:02:38'),
(24, NULL, 'Everest', 3, '2019-02-01 00:02:37'),
(25, 30, 'stand up', 0, '2019-02-17 00:02:43'),
(26, 33, 'movie Jaws', 1, '2019-02-17 02:02:03'),
(27, NULL, 'hook', 0, '2019-02-18 08:02:52'),
(28, 20, 'Batman', 1, '2019-02-12 23:02:42'),
(29, NULL, 'comedy', 3, '2019-02-14 05:02:21'),
(30, 37, 'soccer', 0, '2019-02-06 09:02:54'),
(31, 33, 'theatrical', 4, '2019-02-10 23:02:08'),
(32, 2, 'cartoon dog', 5, '2019-02-05 02:02:25'),
(33, NULL, 'Everest', 5, '2019-02-20 05:02:40'),
(34, 4, 'stand up', 0, '2019-02-20 09:02:19'),
(35, 40, 'mountain', 0, '2019-02-14 03:02:58'),
(36, NULL, 'Batman', 1, '2019-02-05 01:02:18');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Select & Vote', '2019-02-22 07:15:48', '2019-03-12 15:13:11'),
(2, 'copyright_text', '© 2018 - 2019 All rights reserved', '2019-02-22 07:15:48', '2019-03-12 15:13:11'),
(3, 'elastic_automation', 'Y', '2019-02-22 07:15:48', NULL),
(4, 'site_heading', 'Make your choice!', '2019-02-22 07:15:48', '2019-03-12 15:13:11'),
(5, 'site_subheading', 'Vote\'em all !', '2019-02-22 07:15:48', '2019-03-12 15:13:11'),
(6, 'contact_us_email', 'vote@vote.com', '2019-02-22 07:15:48', '2019-03-12 15:13:11'),
(7, 'contact_us_phone', 'Chicago, US', '2019-02-22 07:15:48', '2019-03-12 15:13:11'),
(8, 'home_page_ref_items_per_pagination', '888', '2019-02-22 07:15:48', '2019-03-12 15:23:25'),
(9, 'backend_per_page', '20', '2019-02-22 07:15:48', NULL),
(10, 'news_per_page', '20', '2019-02-22 07:15:48', '2019-03-12 15:23:25'),
(11, 'noreply_email', 'noreply@make_votes.com', '2019-02-22 07:15:48', '2019-03-12 15:13:11'),
(12, 'support_signature', '<p>Best Regards,<br />Support of Select & Vote Team</p> <p><em><strong>11111</strong></em>1</p> <p>22222</p> <ul> <li>33333</li> <li>444444</li> <li>55555</li> </ul>', '2019-02-22 07:15:48', '2019-03-12 15:13:11'),
(13, 'userRegistrationFiles', 'rules-of-our-site.pdf; terms.doc ; our-services.doc ;  slogan_1.jpg;  ; our_prices.ods', '2019-02-22 07:15:48', NULL),
(20, 'verification_token_days_expired', '9', '2019-03-11 06:17:10', '2019-03-12 13:38:02'),
(21, 'allow_facebook_authorization', 'Y', '2019-03-11 06:17:10', '2019-03-12 13:38:02'),
(22, 'allow_google_authorization', 'Y', '2019-03-11 11:23:34', '2019-03-12 13:38:02'),
(23, 'allow_github_authorization', 'Y', '2019-03-12 13:38:02', '2019-03-12 13:38:02');

-- --------------------------------------------------------

--
-- Структура таблицы `settings_text`
--

CREATE TABLE `settings_text` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `settings_text`
--

INSERT INTO `settings_text` (`id`, `name`, `text`, `created_at`, `updated_at`) VALUES
(1, 'account_register_details_text', '<p>When you are registering at our site we need to say : lorem ipsum dolor sit amet, consecteturadipiscing elit, sed do eiusmod tempor incididunt ut labore etdolore magna aliqua. Ut enim ad minim ... 0000000099999999b  111111<br />22222<br />• 33333<br />• 444444<br />• 55555</p>', '2019-03-11 14:50:47', '2019-03-12 15:23:25'),
(2, 'account_register_avatar_text', '<p>Selecting avatar for your account lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore etdolore magna aliqua. Ut enim... 111111<br />22222<br />• 33333<br />• 444444<br />• 55555</p>', '2019-03-11 14:50:47', '2019-03-12 15:23:25'),
(3, 'account_register_subscriptions_text', '<p>Selecting news subscriptions for your account lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore etdolore magna aliqua. Ut enim ad ... </p> <p>QWERTY</p> <p>AAA</p> <p>BBB</p> <p>CCC</p>', '2019-03-11 14:50:47', '2019-03-12 15:23:25'),
(4, 'account_register_confirm_text', '<p>Creating account lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt dolore magna aliqua. Ut veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea...</p> <p>QWERTY</p> <p>AAA</p> <p>BBB</p> <p>CCC FFFFFFFFFF</p>', '2019-03-11 14:50:47', '2019-03-12 15:23:25'),
(5, 'account_contacts_us_text', '<p>Please, send your message for us and the administration of our site would answer soon. We would be glad to get from you new quiz idea.</p> <p>QWERTY</p> <p>AAA</p> <p>BBB</p> <p>CCC GGGGGGGGGGGG</p>', '2019-03-11 14:50:47', '2019-03-12 15:23:25');

-- --------------------------------------------------------

--
-- Структура таблицы `site_subscriptions`
--

CREATE TABLE `site_subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `vote_category_id` int(10) UNSIGNED DEFAULT NULL,
  `mailchimp_list_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailchimp_list_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `site_subscriptions`
--

INSERT INTO `site_subscriptions` (`id`, `name`, `active`, `vote_category_id`, `mailchimp_list_name`, `mailchimp_list_id`, `created_at`) VALUES
(1, 'News of site', 1, NULL, 'Votes Newsletters : News of site', 'f33474b1c9', '2019-02-22 07:16:03'),
(2, 'Classic literature', 1, 1, 'Votes Newsletters : Classic Literature', 'a6cd2a60e6', '2019-02-22 07:16:03'),
(3, 'Movie&Cartoons', 1, 2, 'Votes Newsletters : Movie&Cartoons', '087acbf04b', '2019-02-22 07:16:03'),
(6, 'Subscribers', 1, 5, NULL, 'a6cd2a60e6', '2019-02-22 07:16:03'),
(8, 'Subscribers99999', 0, 2, NULL, NULL, '2019-02-26 14:54:41');

-- --------------------------------------------------------

--
-- Структура таблицы `taggables`
--

CREATE TABLE `taggables` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `taggable_id` int(10) UNSIGNED NOT NULL,
  `taggable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `taggables`
--

INSERT INTO `taggables` (`id`, `tag_id`, `taggable_id`, `taggable_type`) VALUES
(1, 1, 1, 'App\\Vote'),
(2, 2, 1, 'App\\Vote'),
(3, 3, 1, 'App\\Vote'),
(4, 4, 1, 'App\\Vote'),
(5, 5, 2, 'App\\Vote'),
(6, 6, 2, 'App\\Vote'),
(7, 7, 2, 'App\\Vote'),
(8, 8, 2, 'App\\Vote'),
(9, 9, 2, 'App\\Vote'),
(10, 10, 2, 'App\\Vote'),
(13, 7, 4, 'App\\Vote'),
(14, 6, 4, 'App\\Vote'),
(24, 19, 10, 'App\\Vote'),
(25, 20, 10, 'App\\Vote'),
(33, 26, 15, 'App\\Vote'),
(34, 7, 15, 'App\\Vote'),
(35, 9, 15, 'App\\Vote'),
(36, 3, 16, 'App\\Vote'),
(37, 25, 16, 'App\\Vote'),
(41, 8, 19, 'App\\Vote'),
(42, 7, 19, 'App\\Vote');

-- --------------------------------------------------------

--
-- Структура таблицы `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` json NOT NULL,
  `slug` json NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_column` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `type`, `order_column`, `created_at`, `updated_at`) VALUES
(1, '{\"en\": \"Hamlet\"}', '{\"en\": \"hamlet\"}', 'votesTagType', 1, '2019-02-22 05:15:50', '2019-02-22 05:15:50'),
(2, '{\"en\": \"William Shakespeare\"}', '{\"en\": \"william-shakespeare\"}', 'votesTagType', 2, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(3, '{\"en\": \"Drama\"}', '{\"en\": \"drama\"}', 'votesTagType', 3, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(4, '{\"en\": \"Theater\"}', '{\"en\": \"theater\"}', 'votesTagType', 4, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(5, '{\"en\": \"Cartoon\"}', '{\"en\": \"cartoon\"}', 'votesTagType', 5, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(6, '{\"en\": \"Animated fantasy\"}', '{\"en\": \"animated-fantasy\"}', 'votesTagType', 6, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(7, '{\"en\": \"Hollywood\"}', '{\"en\": \"hollywood\"}', 'votesTagType', 7, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(8, '{\"en\": \"Film\"}', '{\"en\": \"film\"}', 'votesTagType', 8, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(9, '{\"en\": \"Movie director\"}', '{\"en\": \"movie-director\"}', 'votesTagType', 9, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(10, '{\"en\": \"Robert Zemeckis\"}', '{\"en\": \"robert-zemeckis\"}', 'votesTagType', 10, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(11, '{\"en\": \"Calendar\"}', '{\"en\": \"calendar\"}', 'votesTagType', 11, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(12, '{\"en\": \"Year\"}', '{\"en\": \"year\"}', 'votesTagType', 12, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(13, '{\"en\": \"France\"}', '{\"en\": \"france\"}', 'votesTagType', 13, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(14, '{\"en\": \"Middle Ages\"}', '{\"en\": \"middle-ages\"}', 'votesTagType', 14, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(15, '{\"en\": \"Ancient Rome\"}', '{\"en\": \"ancient-rome\"}', 'votesTagType', 15, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(16, '{\"en\": \"Ancient History\"}', '{\"en\": \"ancient-history\"}', 'votesTagType', 16, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(18, '{\"en\": \"Interesting Places\"}', '{\"en\": \"interesting-places\"}', 'votesTagType', 18, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(19, '{\"en\": \"Fictional characters\"}', '{\"en\": \"fictional-characters\"}', 'votesTagType', 19, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(20, '{\"en\": \"Fairy\"}', '{\"en\": \"fairy\"}', 'votesTagType', 20, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(21, '{\"en\": \"Solar System\"}', '{\"en\": \"solar-system\"}', 'votesTagType', 21, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(22, '{\"en\": \"Earth\"}', '{\"en\": \"earth\"}', 'votesTagType', 22, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(23, '{\"en\": \"Mythology\"}', '{\"en\": \"mythology\"}', 'votesTagType', 23, '2019-02-22 05:15:51', '2019-02-22 05:15:51'),
(24, '{\"en\": \"Religion\"}', '{\"en\": \"religion\"}', 'votesTagType', 24, '2019-02-22 05:15:52', '2019-02-22 05:15:52'),
(25, '{\"en\": \"Animals\"}', '{\"en\": \"animals\"}', 'votesTagType', 25, '2019-02-22 05:15:52', '2019-02-22 05:15:52'),
(26, '{\"en\": \"Thriller\"}', '{\"en\": \"thriller\"}', 'votesTagType', 26, '2019-02-22 05:15:52', '2019-02-22 05:15:52'),
(32, '{\"en\": \"ff\"}', '{\"en\": \"ff\"}', 'votesTagType', 55, '2019-03-11 22:00:00', '2019-03-11 22:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `tag_details`
--

CREATE TABLE `tag_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tag_details`
--

INSERT INTO `tag_details` (`id`, `tag_id`, `image`, `description`) VALUES
(1, 1, 'hamlet.jpg', 'The Tragedy of <i><i>Hamlet</i>, Prince of Denmark</i>, often shortened to <i>Hamlet</i> (/ˈhæmlɪt/), is a tragedy written by <i><i>William Shakespeare</i></i> at an uncertain date between 1599 and 1602. Set in Denmark, the play dramatises the revenge <i>Prince <i>Hamlet</i></i> is called to wreak upon his uncle, Claudius, by the ghost of <i><i>Hamlet</i>\'s</i> father, King <i>Hamlet</i>. Claudius had murdered his own brother and seized the throne, also marrying his deceased brother\'s widow.\n\n<i>Hamlet</i> is Shakespeare\'s longest play, and is considered among the most powerful and influential works of world literature, with a story capable of \"seemingly endless retelling and adaptation by others\". It was one of Shakespeare\'s most popular works during his lifetime, and still ranks among his most performed, topping the performance list of the Royal Shakespeare Company and its predecessors in Stratford-upon-Avon since 1879. It has inspired many other writers—from Johann Wolfgang von Goethe and Charles Dickens to James Joyce and Iris Murdoch—and has been described as \"the world\'s most filmed story after Cinderella\".\n\nThe story of Shakespeare\'s <i>Hamlet</i> was derived from the legend of Amleth, preserved by 13th-century chronicler Saxo Grammaticus in his Gesta Danorum, as subsequently retold by the 16th-century scholar François de Belleforest. Shakespeare may also have drawn on an earlier Elizabethan play known today as the Ur-<i>Hamlet</i>, though some scholars believe he himself wrote the Ur-<i>Hamlet</i>, later revising it to create the version of <i>Hamlet</i> we now have. He almost certainly wrote his version of the title role for his fellow actor, Richard Burbage, the leading tragedian of Shakespeare\'s time. In the 400 years since its inception, the role has been performed by numerous highly acclaimed actors in each successive century.\n\nThree different early versions of the play are extant: the First Quarto (Q1, 1603); the Second Quarto (Q2, 1604); and the First Folio (F1, 1623). Each version includes lines and entire scenes missing from the others. The play\'s structure and depth of characterisation have inspired much critical scrutiny. One such example is the centuries-old debate about <i><i>Hamlet</i>\'s</i> hesitation to kill his uncle, which some see as merely a plot device to prolong the action, but which others argue is a dramatisation of the complex philosophical and ethical issues that surround cold-blooded murder, calculated revenge, and thwarted desire. More recently, psychoanalytic critics have examined <i><i>Hamlet</i>\'s</i> unconscious desires, while feminist critics have re-evaluated and attempted to rehabilitate the often maligned characters of Ophelia and Gertrude.'),
(2, 2, 'shakespeare.jpg', '<i>William Shakespeare</i> (26 April 1564 (baptised) – 23 April 1616)[a] was an English poet, playwright and actor, widely regarded as both the greatest writer in the English language and the world\'s pre-eminent dramatist. He is often called England\'s national poet and the \"Bard of Avon\". His extant works, including collaborations, consist of approximately 39 plays,[c] 154 sonnets, two long narrative poems, and a few other verses, some of uncertain authorship. His plays have been translated into every major living language and are performed more often than those of any other playwright.\n\nShakespeare was born and raised in Stratford-upon-Avon, Warwickshire. At the age of 18, he married Anne Hathaway, with whom he had three children: Susanna and twins Hamnet and Judith. Sometime between 1585 and 1592, he began a successful career in London as an actor, writer, and part-owner of a playing company called the Lord Chamberlain\'s Men, later known as the King\'s Men. At age 49 (around 1613), he appears to have retired to Stratford, where he died three years later. Few records of Shakespeare\'s private life survive; this has stimulated considerable speculation about such matters as his physical appearance, his sexuality, his religious beliefs, and whether the works attributed to him were written by others. Such theories are often criticised for failing to adequately note the fact that few records survive of most commoners of the period.\n\nShakespeare produced most of his known works between 1589 and 1613. His early plays were primarily comedies and histories and are regarded as some of the best work ever produced in these genres. Then, until about 1608, he wrote mainly tragedies, among them <i>Hamlet</i>, Othello, King Lear, and Macbeth, all considered to be among the finest works in the English language. In the last phase of his life, he wrote tragicomedies (also known as romances) and collaborated with other playwrights.\n\nMany of his plays were published in editions of varying quality and accuracy in his lifetime. However, in 1623, two fellow actors and friends of Shakespeare\'s, John Heminges and Henry Condell, published a more definitive text known as the First Folio, a posthumous collected edition of Shakespeare\'s dramatic works that included all but two of the plays now recognised as his. The volume was prefaced with a poem by Ben Jonson, in which the poet presciently hails the playwright in a now-famous quote as \"not of an age, but for all time\".\n\nThroughout the 20th and 21st centuries, Shakespeare\'s works have been continually adapted and rediscovered by new movements in scholarship and performance. His plays remain highly popular and are constantly studied, performed, and reinterpreted through various cultural and political contexts around the world.'),
(3, 3, 'drama.png', 'In reference to film and television, drama is a genre of narrative fiction (or semi-fiction) intended to be more serious than humorous in tone. Drama of this kind is usually qualified with additional terms that specify its particular subgenre, such as \"police crime drama\", \"political drama\", \"legal drama\", \"historical period drama\", \"domestic drama\", or \"comedy-drama\". These terms tend to indicate a particular setting or subject-matter, or else they qualify the otherwise serious tone of a drama with elements that encourage a broader range of moods.\n\nAll forms of cinema or television that involve fictional stories are forms of drama in the broader sense if their storytelling is achieved by means of actors who represent (mimesis) characters. In this broader sense, drama is a mode distinct from novels, short stories, and narrative poetry or songs. In the modern era before the birth of cinema or television, \"drama\" came to be used within the theatre as a generic term to describe a type of play that was neither a comedy nor a tragedy. It is this narrower sense that the film and television industries, along with film studies, adopted. \"Radio drama\" has been used in both senses—originally transmitted in a live performance, it has also been used to describe the more high-brow and serious end of the dramatic output of radio.'),
(4, 4, 'theater.jpg', 'Theatre or theater is a collaborative form of fine art that uses live performers, typically actors or actresses, to present the experience of a real or imagined event before a live audience in a specific place, often a stage. The performers may communicate this experience to the audience through combinations of gesture, speech, song, music, and dance. Elements of art, such as painted scenery and stagecraft such as lighting are used to enhance the physicality, presence and immediacy of the experience. The specific place of the performance is also named by the word \"theatre\" as derived from the Ancient Greek θέατρον (théatron, \"a place for viewing\"), itself from θεάομαι (theáomai, \"to see\", \"to watch\", \"to observe\").\n\nModern Western theatre comes, in large measure, from the theatre of ancient Greece, from which it borrows technical terminology, classification into genres, and many of its themes, stock characters, and plot elements. Theatre artist Patrice Pavis defines theatricality, theatrical language, stage writing and the specificity of theatre as synonymous expressions that differentiate theatre from the other performing arts, literature and the arts in general.\n\nModern theatre includes performances of plays and musical theatre. The art forms of ballet and opera are also theatre and use many conventions such as acting, costumes and staging. They were influential to the development of musical theatre; see those articles for more information.'),
(5, 5, 'cartoon.png', 'A cartoon is a type of illustration, possibly animated, typically in a non-realistic or semi-realistic style. The specific meaning has evolved over time, but the modern usage usually refers to either: an image or series of images intended for satire, caricature, or humor; or a motion picture that relies on a sequence of illustrations for its animation. Someone who creates cartoons in the first sense is called a cartoonist, and in the second sense they are usually called an animator.\n\nThe concept originated in the Middle Ages, and first described a preparatory drawing for a piece of art, such as a painting, fresco, tapestry, or stained glass window. In the 19th century, it came to refer – ironically at first – to humorous illustrations in magazines and newspapers. In the early 20th century, it began to refer to animated films which resembled print cartoons.'),
(6, 6, 'tranimated-fantasy.jpg', 'Animated fantasy Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt... Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt... Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt... Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt... Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt...'),
(7, 7, 'hollywood.jpg', '<b>Hollywood</b> is a neighborhood in the central region of Los Angeles, California, notable as the home of the U.S. film industry, including several of its historic studios. Its name has come to be a shorthand reference for the industry and the people associated with it.\n\n<b>Hollywood</b> was a small community in 1870 and was incorporated as a municipality in 1903. It was consolidated with the city of Los Angeles in 1910 and soon thereafter a prominent film industry emerged, eventually becoming the most recognizable film industry in the world.'),
(8, 8, 'Berlin_Wintergarten.jpg', 'A film, also called a movie, motion picture, moving picture, theatrical film, or photoplay, is a series of still images that, when shown on a screen, create the illusion of moving images. (See the glossary of motion picture terms.)\n\nThis optical illusion causes the audience to perceive continuous motion between separate objects viewed in rapid succession. The process of filmmaking is both an art and an industry. A film is created by photographing actual scenes with a motion-picture camera, by photographing drawings or miniature models using traditional animation techniques, by means of CGI and computer animation, or by a combination of some or all of these techniques, and other visual effects.\n\nThe word \"cinema\", short for cinematography, is often used to refer to filmmaking and the film industry, and to the art of filmmaking itself. The contemporary definition of cinema is the art of simulating experiences to communicate ideas, stories, perceptions, feelings, beauty or atmosphere by the means of recorded or programmed moving images along with other sensory stimulations.\n\nFilms were originally recorded onto plastic film through a photochemical process and then shown through a movie projector onto a large screen. Contemporary films are now often fully digital through the entire process of production, distribution, and exhibition, while films recorded in a photochemical form traditionally included an analogous optical soundtrack (a graphic recording of the spoken words, music and other sounds that accompany the images which runs along a portion of the film exclusively reserved for it, and is not projected).\n\nFilms are cultural artifacts created by specific cultures. They reflect those cultures, and, in turn, affect them. Film is considered to be an important art form, a source of popular entertainment, and a powerful medium for educating—or indoctrinating—citizens. The visual basis of film gives it a universal power of communication. Some films have become popular worldwide attractions through the use of dubbing or subtitles to translate the dialog into other languages.\n\nThe individual images that make up a film are called frames. In the projection of traditional celluloid films, a rotating shutter causes intervals of darkness as each frame, in turn, is moved into position to be projected, but the viewer does not notice the interruptions because of an effect known as persistence of vision, whereby the eye retains a visual image for a fraction of a second after its source disappears. The perception of motion is due to a psychological effect called the phi phenomenon.\n\nThe name \"film\" originates from the fact that photographic film (also called film stock) has historically been the medium for recording and displaying motion pictures. Many other terms exist for an individual motion-picture, including picture, picture show, moving picture, photoplay, and flick. The most common term in the United States is movie, while in Europe film is preferred. Common terms for the field in general include the big screen, the silver screen, the movies, and cinema; the last of these is commonly used, as an overarching term, in scholarly texts and critical essays. In early years, the word sheet was sometimes used instead of screen.'),
(9, 9, 'Alfred_Hitchcock.jpg', 'A film director is a person who directs the making of a film. A film director controls a film\'s artistic and dramatic aspects and visualizes the screenplay (or script) while guiding the technical crew and actors in the fulfillment of that vision. The director has a key role in choosing the cast members, production design, and the creative aspects of filmmaking. Under European Union law, the director is viewed as the author of the film.\n\nThe film director gives direction to the cast and crew and creates an overall vision through which a film eventually becomes realized, or noticed. Directors need to be able to mediate differences in creative visions and stay within the boundaries of the film\'s budget.\n\nThere are many pathways to becoming a film director. Some film directors started as screenwriters, cinematographers, film editors or actors. Other film directors have attended a film school. Directors use different approaches. Some outline a general plotline and let the actors improvise dialogue, while others control every aspect, and demand that the actors and crew follow instructions precisely. Some directors also write their own screenplays or collaborate on screenplays with long-standing writing partners. Some directors edit or appear in their films, or compose the music score for their films.'),
(10, 10, 'Robert_Zemeckis.jpg', 'Robert Lee Zemeckis (/zəˈmɛkɪs/; born May 14, 1952) is an American filmmaker frequently credited as an innovator in visual effects. He first came to public attention in the 1980s as the director of Romancing the Stone (1984) and the science-fiction comedy Back to the Future film trilogy, as well as the live-action/animated comedy Who Framed Roger Rabbit (1988). In the 1990s he directed Death Becomes Her and then diversified into more dramatic fare, including 1994\'s Forrest Gump, for which he won an Academy Award for Best Director. The film itself won Best Picture. The films he has directed have ranged across a wide variety of genres, for both adults and families.\n\nZemeckis\'s films are characterized by an interest in state-of-the-art special effects, including the early use of the insertion of computer graphics into live-action footage in Back to the Future Part II (1989) and Forrest Gump, and the pioneering performance capture techniques seen in The Polar Express (2004), Monster House (2006), Beowulf (2007) and A Christmas Carol (2009). Though Zemeckis has often been pigeonholed as a director interested only in special effects, his work has been defended by several critics including David Thomson, who wrote that \"No other contemporary director has used special effects to more dramatic and narrative purpose.\"'),
(11, 11, 'calender.jpg', 'A calendar is a system of organizing days for social, religious, commercial or administrative purposes. This is done by giving names to periods of time, typically days, weeks, months and years. A date is the designation of a single, specific day within such a system. A calendar is also a physical record (often paper) of such a system. A calendar can also mean a list of planned events, such as a court calendar or a partly or fully chronological list of documents, such as a calendar of wills.\n\nPeriods in a calendar (such as years and months) are usually, though not necessarily, synchronised with the cycle of the sun or the moon. The most common type of pre-modern calendar was the lunisolar calendar, a lunar calendar that occasionally adds one intercalary month to remain synchronised with the solar year over the long term.\n\nThe term calendar is taken from calendae, the term for the first day of the month in the Roman calendar, related to the verb calare \"to call out\", referring to the \"calling\" of the new moon when it was first seen. Latin calendarium meant \"account book, register\" (as accounts were settled and debts were collected on the calends of each month). The Latin term was adopted in Old French as calendier and from there in Middle English as calender by the 13th century (the spelling calendar is early modern).'),
(12, 12, '', 'A year is the orbital period of the Earth moving in its orbit around the Sun. Due to the Earth\'s axial tilt, the course of a year sees the passing of the seasons, marked by change in weather, the hours of daylight, and, consequently, vegetation and soil fertility. The current year is 2018.\n\nIn temperate and subpolar regions around the planet, four seasons are generally recognized: spring, summer, autumn, and winter. In tropical and subtropical regions, several geographical sectors do not present defined seasons; but in the seasonal tropics, the annual wet and dry seasons are recognized and tracked.\n\nA calendar year is an approximation of the number of days of the Earth\'s orbital period as counted in a given calendar. The Gregorian calendar, or modern calendar, presents its calendar year to be either a common year of 365 days or a leap year of 366 days, as do the Julian calendars; see below. For the Gregorian calendar, the average length of the calendar year (the mean year) across the complete leap cycle of 400 years is 365.2425 days. The ISO standard ISO 80000-3, Annex C, supports the symbol a (for Latin annus) to represent a year of either 365 or 366 days. In English, the abbreviations y and yr are commonly used.\n\nIn astronomy, the Julian year is a unit of time; it is defined as 365.25 days of exactly 86,400 seconds (SI base unit), totalling exactly 31,557,600 seconds in the Julian astronomical year.\n\nThe word year is also used for periods loosely associated with, but not identical to, the calendar or astronomical year, such as the seasonal year, the fiscal year, the academic year, etc. Similarly, year can mean the orbital period of any planet; for example, a Martian year and a Venusian year are examples of the time a planet takes to transit one complete orbit. The term can also be used in reference to any long period or cycle, such as the Great Year.'),
(13, 13, 'Flag_of_France.png', 'France (French: [fʁɑ̃s]), officially the French Republic (French: République française; French pronunciation: ​[ʁepyblik fʁɑ̃sɛz]), is a country whose territory consists of metropolitan France in Western Europe and several overseas regions and territories.[XIII] The metropolitan area of France extends from the Mediterranean Sea to the English Channel and the North Sea, and from the Rhine to the Atlantic Ocean. The overseas territories include French Guiana in South America and several islands in the Atlantic, Pacific and Indian oceans. The country\'s 18 integral regions (five of which are situated overseas) span a combined area of 643,801 square kilometres (248,573 sq mi) and a total population of 67.3 million (as of October 2018). France, a sovereign state, is a unitary semi-presidential republic with its capital in Paris, the country\'s largest city and main cultural and commercial centre. Other major urban areas include Lyon, Marseille, Toulouse, Bordeaux, Lille and Nice.\n\nDuring the Iron Age, what is now metropolitan France was inhabited by the Gauls, a Celtic people. Rome annexed the area in 51 BC, holding it until the arrival of Germanic Franks in 476, who formed the Kingdom of France. France emerged as a major European power in the Late Middle Ages following its victory in the Hundred Years\' War (1337 to 1453). During the Renaissance, French culture flourished and a global colonial empire was established, which by the 20th century would become the second largest in the world. The 16th century was dominated by religious civil wars between Catholics and Protestants (Huguenots). France became Europe\'s dominant cultural, political, and military power in the 17th century under Louis XIV. In the late 18th century, the French Revolution overthrew the absolute monarchy, established one of modern history\'s earliest republics, and saw the drafting of the Declaration of the Rights of Man and of the Citizen, which expresses the nation\'s ideals to this day.\n\nIn the 19th century, Napoleon took power and established the First French Empire. His subsequent Napoleonic Wars shaped the course of continental Europe. Following the collapse of the Empire, France endured a tumultuous succession of governments culminating with the establishment of the French Third Republic in 1870. France was a major participant in World War I, from which it emerged victorious, and was one of the Allies in World War II, but came under occupation by the Axis powers in 1940. Following liberation in 1944, a Fourth Republic was established and later dissolved in the course of the Algerian War. The Fifth Republic, led by Charles de Gaulle, was formed in 1958 and remains today. Algeria and nearly all the other colonies became independent in the 1960s and typically retained close economic and military connections with France.\n\nFrance has long been a global centre of art, science, and philosophy. It hosts the world\'s fourth-largest number of UNESCO World Heritage Sites and is the leading tourist destination, receiving around 83 million foreign visitors annually. France is a developed country with the world\'s seventh-largest economy by nominal GDP, and tenth-largest by purchasing power parity. In terms of aggregate household wealth, it ranks fourth in the world. France performs well in international rankings of education, health care, life expectancy, and human development. France is considered a great power in global affairs, being one of the five permanent members of the United Nations Security Council with the power to veto and an official nuclear-weapon state. It is a leading member state of the European Union and the Eurozone, and a member of the Group of 7, North Atlantic Treaty Organization (NATO), Organisation for Economic Co-operation and Development (OECD), the World Trade Organization (WTO), and La Francophonie.'),
(14, 14, '350px-Europe_map_450.png', 'The Cross of Mathilde, a crux gemmata made for Mathilde, Abbess of Essen (973–1011), who is shown kneeling before the Virgin and Child in the enamel plaque. The figure of Christ is slightly later. Probably made in Cologne or Essen, the cross demonstrates several medieval techniques: cast figurative sculpture, filigree, enamelling, gem polishing and setting, and the reuse of Classical cameos and engraved gems.\nIn the history of Europe, the Middle Ages (or Medieval period) lasted from the 5th to the 15th century. It began with the fall of the Western Roman Empire and merged into the Renaissance and the Age of Discovery. The Middle Ages is the middle period of the three traditional divisions of Western history: classical antiquity, the medieval period, and the modern period. The medieval period is itself subdivided into the Early, High, and Late Middle Ages.\n\nPopulation decline, counterurbanisation, invasion, and movement of peoples, which had begun in Late Antiquity, continued in the Early Middle Ages. The large-scale movements of the Migration Period, including various Germanic peoples, formed new kingdoms in what remained of the Western Roman Empire. In the 7th century, North Africa and the Middle East—once part of the Byzantine Empire—came under the rule of the Umayyad Caliphate, an Islamic empire, after conquest by Muhammad\'s successors. Although there were substantial changes in society and political structures, the break with classical antiquity was not complete. The still-sizeable Byzantine Empire, Rome\'s direct continuation, survived in the Eastern Mediterranean and remained a major power. The empire\'s law code, the Corpus Juris Civilis or \"Code of Justinian\", was rediscovered in Northern Italy in 1070 and became widely admired later in the Middle Ages. In the West, most kingdoms incorporated the few extant Roman institutions. Monasteries were founded as campaigns to Christianise pagan Europe continued. The Franks, under the Carolingian dynasty, briefly established the Carolingian Empire during the later 8th and early 9th century. It covered much of Western Europe but later succumbed to the pressures of internal civil wars combined with external invasions: Vikings from the north, Magyars from the east, and Saracens from the south.\n\nDuring the High Middle Ages, which began after 1000, the population of Europe increased greatly as technological and agricultural innovations allowed trade to flourish and the Medieval Warm Period climate change allowed crop yields to increase. Manorialism, the organisation of peasants into villages that owed rent and labour services to the nobles, and feudalism, the political structure whereby knights and lower-status nobles owed military service to their overlords in return for the right to rent from lands and manors, were two of the ways society was organised in the High Middle Ages. The Crusades, first preached in 1095, were military attempts by Western European Christians to regain control of the Holy Land from Muslims. Kings became the heads of centralised nation-states, reducing crime and violence but making the ideal of a unified Christendom more distant. Intellectual life was marked by scholasticism, a philosophy that emphasised joining faith to reason, and by the founding of universities. The theology of Thomas Aquinas, the paintings of Giotto, the poetry of Dante and Chaucer, the travels of Marco Polo, and the Gothic architecture of cathedrals such as Chartres are among the outstanding achievements toward the end of this period and into the Late Middle Ages.\n\nThe Late Middle Ages was marked by difficulties and calamities including famine, plague, and war, which significantly diminished the population of Europe; between 1347 and 1350, the Black Death killed about a third of Europeans. Controversy, heresy, and the Western Schism within the Catholic Church paralleled the interstate conflict, civil strife, and peasant revolts that occurred in the kingdoms. Cultural and technological developments transformed European society, concluding the Late Middle Ages and beginning the early modern period.'),
(15, 15, 'Roman_Republic_Empire_map.gif', 'In historiography, ancient Rome is Roman civilization from the founding of the city of Rome in the 8th century BC to the collapse of the Western Roman Empire in the 5th century AD, encompassing the Roman Kingdom, Roman Republic and Roman Empire until the fall of the western empire. The term is sometimes used to refer only to the kingdom and republic periods, excluding the subsequent empire.\n\nThe civilization began as an Italic settlement in the Italian peninsula, dating from the 8th century BC, that grew into the city of Rome and which subsequently gave its name to the empire over which it ruled and to the widespread civilisation the empire developed. The Roman empire expanded to become one of the largest empires in the ancient world, though still ruled from the city, with an estimated 50 to 90 million inhabitants (roughly 20% of the world\'s population) and covering 5.0 million square kilometres at its height in AD 117.\n\nIn its many centuries of existence, the Roman state evolved from a monarchy to a Classical Republic and then to an increasingly autocratic empire. Through conquest and assimilation, it eventually dominated the Mediterranean region, Western Europe, Asia Minor, North Africa, and parts of Northern and Eastern Europe. It is often grouped into classical antiquity together with ancient Greece, and their similar cultures and societies are known as the Greco-Roman world.\n\nAncient Roman civilisation has contributed to modern government, law, politics, engineering, art, literature, architecture, technology, warfare, religion, language, and society. Rome professionalised and expanded its military and created a system of government called res publica, the inspiration for modern republics such as the United States and France. It achieved impressive technological and architectural feats, such as the construction of an extensive system of aqueducts and roads, as well as the construction of large monuments, palaces, and public facilities.\n\nBy the end of the Republic (27 BC), Rome had conquered the lands around the Mediterranean and beyond: its domain extended from the Atlantic to Arabia and from the mouth of the Rhine to North Africa. The Roman Empire emerged with the end of the Republic and the dictatorship of Augustus Caesar. 721 years of Roman-Persian Wars started in 92 BC with their first war against Parthia. It would become the longest conflict in human history, and have major lasting effects and consequences for both empires. Under Trajan, the Empire reached its territorial peak. Republican mores and traditions started to decline during the imperial period, with civil wars becoming a prelude common to the rise of a new emperor. Splinter states, such as the Palmyrene Empire, would temporarily divide the Empire during the crisis of the 3rd century.\n\nPlagued by internal instability and attacked by various migrating peoples, the western part of the empire broke up into independent \"barbarian\" kingdoms in the 5th century. This splintering is a landmark historians use to divide the ancient period of universal history from the pre-medieval \"Dark Ages\" of Europe. The eastern part of the empire endured through the 5th century and remained a power throughout the \"Dark Ages\" and medieval times until its fall in 1453 AD. Although the citizens of the empire made no distinction, the empire is most commonly referred to as the \"Byzantine Empire\" by modern historians during the Middle Ages to differentiate between the state of antiquity and the nation it grew into.'),
(16, 16, 'Ancient_cities_of_Sumer.jpg', 'Ancient history as a term refers to the aggregate of past events from the beginning of writing and recorded human history and extending as far as the post-classical history. The phrase may be used either to refer to the period of time or the academic discipline.\n\nThe span of recorded history is roughly 5,000 years, beginning with Sumerian Cuneiform script, the oldest discovered form of coherent writing from the protoliterate period around the 30th century BC. Ancient History covers all continents inhabited by humans in the 3,000 BC - 500 AD period.\n\nThe broad term Ancient History is not to be confused with Classical Antiquity. The term classical antiquity is often used to refer to Western History in the Ancient Mediterranean from the beginning of recorded Greek history in 776 BC (First Olympiad). This roughly coincides with the traditional date of the founding of Rome in 753 BC, the beginning of the history of ancient Rome, and the beginning of the Archaic period in Ancient Greece.\n\nThe academic term \"history\" is additionally not to be confused with colloquial references to times past. History is fundamentally the study of the past through documents, and can be either scientific (archaeology) or humanistic (history through language).\n\nAlthough the ending date of ancient history is disputed, some Western scholars use the fall of the Western Roman Empire in 476 AD (the most used), the closure of the Platonic Academy in 529 AD, the death of the emperor Justinian I in 565 AD, the coming of Islam or the rise of Charlemagne as the end of ancient and Classical European history. Outside of Europe the 450-500 time frame for the end of ancient times has had difficulty as a transition date from Ancient to Post-Classical times.\n\nDuring the time period of \'Ancient History\' starting roughly from 3000 B.C world population was already exponentially increasing due to the Neolithic Revolution which was in full progress. According to HYDE estimates from the Netherlands world population increased exponentially in this period. At 10,000 BC in Prehistory world population had stood at 2 million, rising to 45 million by 3,000 B.C. By the rise of the Iron Age in 1,000 BC that population had risen to 72 million. By the end of the period in 500 AD world population stood possibly at 209 million.'),
(18, 18, 'interesting_places.png', 'Looking for a more unusual travel destination this year? Check out these photos of some unbelievably amazing places in the world; we challenge you to read on without reaching for your passport...'),
(19, 19, 'fictional_characters.jpg', 'A character (sometimes known as a fictional character) is a person or other being in a narrative (such as a novel, play, television series, film, or video game). The character may be entirely fictional or based on a real-life person, in which case the distinction of a \"fictional\" versus \"real\" character may be made. Derived from the ancient Greek word χαρακτήρ, the English word dates from the Restoration, although it became widely used after its appearance in Tom Jones in 1749. From this, the sense of \"a part played by an actor\" developed. Character, particularly when enacted by an actor in the theatre or cinema, involves \"the illusion of being a human person\". In literature, characters guide readers through their stories, helping them to understand plots and ponder themes. Since the end of the 18th century, the phrase \"in character\" has been used to describe an effective impersonation by an actor. Since the 19th century, the art of creating characters, as practiced by actors or writers, has been called characterisation.\n\nA character who stands as a representative of a particular class or group of people is known as a type. Types include both stock characters and those that are more fully individualised. The characters in Henrik Ibsen\'s Hedda Gabler (1891) and August Strindberg\'s Miss Julie (1888), for example, are representative of specific positions in the social relations of class and gender, such that the conflicts between the characters reveal ideological conflicts.\n\nThe study of a character requires an analysis of its relations with all of the other characters in the work. The individual status of a character is defined through the network of oppositions (proairetic, pragmatic, linguistic, proxemic) that it forms with the other characters. The relation between characters and the action of the story shifts historically, often miming shifts in society and its ideas about human individuality, self-determination, and the social order.'),
(20, 20, 'SophieAndersonTakethefairfaceofWoman.jpg', 'A fairy (also fata, fay, fey, fae, fair folk; from faery, faerie, \"realm of the fays\") is a type of mythical being or legendary creature in European folklore (and particularly Celtic, Slavic, German, English, and French folklore), a form of spirit, often described as metaphysical, supernatural, or preternatural.\n\nMyths and stories about fairies do not have a single origin, but are rather a collection of folk beliefs from disparate sources. Various folk theories about the origins of fairies include casting them as either demoted angels or demons in a Christian tradition, as minor deities in pre-Christian Pagan belief systems, as spirits of the dead, as prehistoric precursors to humans, or as elementals.\n\nThe label of fairy has at times applied only to specific magical creatures with human appearance, small stature, magical powers, and a penchant for trickery. At other times it has been used to describe any magical creature, such as goblins and gnomes. Fairy has at times been used as an adjective, with a meaning equivalent to \"enchanted\" or \"magical\".\n\nA recurring motif of legends about fairies is the need to ward off fairies using protective charms. Common examples of such charms include church bells, wearing clothing inside out, four-leaf clover, and food. Fairies were also sometimes thought to haunt specific locations, and to lead travelers astray using will-o\'-the-wisps. Before the advent of modern medicine, fairies were often blamed for sickness, particularly tuberculosis and birth deformities.\n\nIn addition to their folkloric origins, fairies were a common feature of Renaissance literature and Romantic art, and were especially popular in the United Kingdom during the Victorian and Edwardian eras. The Celtic Revival also saw fairies established as a canonical part of Celtic cultural heritage.'),
(21, 21, 'Planets.png', 'The Solar System[a] is the gravitationally bound system of the Sun and the objects that orbit it, either directly or indirectly,[b] including the eight planets and five dwarf planets as defined by the International Astronomical Union (IAU). Of the objects that orbit the Sun directly, the largest eight are the planets,[c] with the remainder being smaller objects, such as dwarf planets and small Solar System bodies. Of the objects that orbit the Sun indirectly—the moons—two are larger than the smallest planet, Mercury.[d]\n\nThe Solar System formed 4.6 billion years ago from the gravitational collapse of a giant interstellar molecular cloud. The vast majority of the system\'s mass is in the Sun, with the majority of the remaining mass contained in Jupiter. The four smaller inner planets, Mercury, Venus, Earth and Mars, are terrestrial planets, being primarily composed of rock and metal. The four outer planets are giant planets, being substantially more massive than the terrestrials. The two largest, Jupiter and Saturn, are gas giants, being composed mainly of hydrogen and helium; the two outermost planets, Uranus and Neptune, are ice giants, being composed mostly of substances with relatively high melting points compared with hydrogen and helium, called volatiles, such as water, ammonia and methane. All eight planets have almost circular orbits that lie within a nearly flat disc called the ecliptic.\n\nThe Solar System also contains smaller objects.[e] The asteroid belt, which lies between the orbits of Mars and Jupiter, mostly contains objects composed, like the terrestrial planets, of rock and metal. Beyond Neptune\'s orbit lie the Kuiper belt and scattered disc, which are populations of trans-Neptunian objects composed mostly of ices, and beyond them a newly discovered population of sednoids. Within these populations are several dozen to possibly tens of thousands of objects large enough that they have been rounded by their own gravity. Such objects are categorized as dwarf planets. Identified dwarf planets include the asteroid Ceres and the trans-Neptunian objects Pluto and Eris.[e] In addition to these two regions, various other small-body populations, including comets, centaurs and interplanetary dust clouds, freely travel between regions. Six of the planets, at least four of the dwarf planets, and many of the smaller bodies are orbited by natural satellites,[f] usually termed \"moons\" after the Moon. Each of the outer planets is encircled by planetary rings of dust and other small objects.\n\nThe solar wind, a stream of charged particles flowing outwards from the Sun, creates a bubble-like region in the interstellar medium known as the heliosphere. The heliopause is the point at which pressure from the solar wind is equal to the opposing pressure of the interstellar medium; it extends out to the edge of the scattered disc. The Oort cloud, which is thought to be the source for long-period comets, may also exist at a distance roughly a thousand times further than the heliosphere. The Solar System is located in the Orion Arm, 26,000 light-years from the center of the Milky Way.'),
(22, 22, 'SouthAmerica.jpg', 'Earth is the third planet from the Sun and the only astronomical object known to harbor life. According to radiometric dating and other sources of evidence, Earth formed over 4.5 billion years ago. Earth\'s gravity interacts with other objects in space, especially the Sun and the Moon, Earth\'s only natural satellite. Earth revolves around the Sun in 365.26 days, a period known as an Earth year. During this time, Earth rotates about its axis about 366.26 times.[n 5]\n\nEarth\'s axis of rotation is tilted with respect to its orbital plane, producing seasons on Earth. The gravitational interaction between Earth and the Moon causes ocean tides, stabilizes Earth\'s orientation on its axis, and gradually slows its rotation. Earth is the densest planet in the Solar System and the largest of the four terrestrial planets.\n\nEarth\'s lithosphere is divided into several rigid tectonic plates that migrate across the surface over periods of many millions of years. About 71% of Earth\'s surface is covered with water, mostly by oceans. The remaining 29% is land consisting of continents and islands that together have many lakes, rivers and other sources of water that contribute to the hydrosphere. The majority of Earth\'s polar regions are covered in ice, including the Antarctic ice sheet and the sea ice of the Arctic ice pack. Earth\'s interior remains active with a solid iron inner core, a liquid outer core that generates the Earth\'s magnetic field, and a convecting mantle that drives plate tectonics.\n\nWithin the first billion years of Earth\'s history, life appeared in the oceans and began to affect the Earth\'s atmosphere and surface, leading to the proliferation of aerobic and anaerobic organisms. Some geological evidence indicates that life may have arisen as much as 4.1 billion years ago. Since then, the combination of Earth\'s distance from the Sun, physical properties, and geological history have allowed life to evolve and thrive. In the history of the Earth, biodiversity has gone through long periods of expansion, occasionally punctuated by mass extinction events. Over 99% of all species that ever lived on Earth are extinct. Estimates of the number of species on Earth today vary widely; most species have not been described. Over 7.6 billion humans live on Earth and depend on its biosphere and natural resources for their survival. Humans have developed diverse societies and cultures; politically, the world has about 200 sovereign states.'),
(23, 23, 'mythology.png', 'Myth is a folklore genre consisting of narratives that play a fundamental role in society, such as foundational tales. The main characters in myths are usually gods, demigods or supernatural humans. Myths are often endorsed by rulers and priests and are closely linked to religion or spirituality. In fact, many societies group their myths, legends and history together, considering myths to be true accounts of their remote past. Creation myths particularly, take place in a primordial age when the world had not achieved its later form. Other myths explain how a society\'s customs, institutions and taboos were established and sanctified. There is a complex relationship between recital of myths and enactment of rituals.\n\nThe study of myth began in ancient history. Rival classes of the Greek myths by Euhemerus, Plato and Sallustius were developed by the Neoplatonists and later revived by Renaissance mythographers. Today, the study of myth continues in a wide variety of academic fields, including folklore studies, philology, and psychology. The term mythology may either refer to the study of myths in general, or a body of myths regarding a particular subject. The academic comparisons of bodies of myth is known as comparative mythology.'),
(24, 24, 'Open_Torah_and_pointer.jpg', 'Religion may be defined as a cultural system of designated behaviors and practices, worldviews, texts, sanctified places, prophecies, ethics, or organizations, that relates humanity to supernatural, transcendental, or spiritual elements. However, there is no scholarly consensus over what precisely constitutes a religion.\n\nDifferent religions may or may not contain various elements ranging from the divine, sacred things, faith, a supernatural being or supernatural beings or \"some sort of ultimacy and transcendence that will provide norms and power for the rest of life\". Religious practices may include rituals, sermons, commemoration or veneration (of deities), sacrifices, festivals, feasts, trances, initiations, funerary services, matrimonial services, meditation, prayer, music, art, dance, public service, or other aspects of human culture. Religions have sacred histories and narratives, which may be preserved in sacred scriptures, and symbols and holy places, that aim mostly to give a meaning to life. Religions may contain symbolic stories, which are sometimes said by followers to be true, that have the side purpose of explaining the origin of life, the universe, and other things. Traditionally, faith, in addition to reason, has been considered a source of religious beliefs.\n\nThere are an estimated 10,000 distinct religions worldwide, but about 84% of the world\'s population is affiliated with one of the five largest religion groups, namely Christianity, Islam, Hinduism, Buddhism or forms of folk religion. The religiously unaffiliated demographic includes those who do not identify with any particular religion, atheists and agnostics. While the religiously unaffiliated have grown globally, many of the religiously unaffiliated still have various religious beliefs.\n\nThe study of religion encompasses a wide variety of academic disciplines, including theology, comparative religion and social scientific studies. Theories of religion offer various explanations for the origins and workings of religion, including the ontological foundations of religious being and belief.');
INSERT INTO `tag_details` (`id`, `tag_id`, `image`, `description`) VALUES
(25, 25, 'Animal_diversity.png', 'Animals are multicellular eukaryotic organisms that form the biological kingdom Animalia. With few exceptions, animals consume organic material, breathe oxygen, are able to move, reproduce sexually, and grow from a hollow sphere of cells, the blastula, during embryonic development. Over 1.5 million living animal species have been described—of which around 1 million are insects—but it has been estimated there are over 7 million animal species in total. Animals range in length from 8.5 millionths of a metre to 33.6 metres (110 ft) and have complex interactions with each other and their environments, forming intricate food webs. The study of animals is called zoology.\n\nMost living animal species are in the Bilateria, a clade whose members have a bilaterally symmetric body plan. The Bilateria include the protostomes—in which many groups of invertebrates are found, such as nematodes, arthropods, and molluscs—and the deuterostomes, containing the echinoderms and chordates (including the vertebrates). Life forms interpreted as early animals were present in the Ediacaran biota of the late Precambrian. Many modern animal phyla became clearly established in the fossil record as marine species during the Cambrian explosion which began around 542 million years ago. 6,331 groups of genes common to all living animals have been identified; these may have arisen from a single common ancestor that lived 650 million years ago.\n\nAristotle divided animals into those with blood and those without. Carl Linnaeus created the first hierarchical biological classification for animals in 1758 with his Systema Naturae, which Jean-Baptiste Lamarck expanded into 14 phyla by 1809. In 1874, Ernst Haeckel divided the animal kingdom into the multicellular Metazoa (now synonymous with Animalia) and the Protozoa, single-celled organisms no longer considered animals. In modern times, the biological classification of animals relies on advanced techniques, such as molecular phylogenetics, which are effective at demonstrating the evolutionary relationships between animal taxa.\n\nHumans make use of many other animal species for food, including meat, milk, and eggs; for materials, such as leather and wool; as pets; and as working animals for power and transport. Dogs have been used in hunting, while many terrestrial and aquatic animals are hunted for sport. Non-human animals have appeared in art from the earliest times and are featured in mythology and religion.'),
(26, 26, 'RebeccaTrailer.jpg', 'Thriller film, also known as suspense film or suspense thriller, is a broad film genre that involves excitement and suspense in the audience. The suspense element, found in most films\' plots, is particularly exploited by the filmmaker in this genre. Tension is created by delaying what the audience sees as inevitable, and is built through situations that are menacing or where escape seems impossible.\n\nThe cover-up of important information from the viewer, and fight and chase scenes are common methods. Life is typically threatened in thriller film, such as when the protagonist does not realize that they are entering a dangerous situation. Thriller films\' characters conflict with each other or with an outside force, which can sometimes be abstract. The protagonist is usually set against a problem, such as an escape, a mission, or a mystery.\n\nThriller films are typically hybridized with other genres; hybrids commonly including: action thrillers, adventure thrillers, fantasy and science fiction thrillers. Thriller films also share a close relationship with horror films, both eliciting tension. In plots about crime, thriller films focus less on the criminal or the detective and more on generating suspense. Common themes include, terrorism, political conspiracy, pursuit and romantic triangles leading to murder.\n\nIn 2001, the American Film Institute made its selection of the top 100 greatest American \"heart-pounding\" and \"adrenaline-inducing\" films of all time. The 400 nominated films had to be American-made films whose thrills have \"enlivened and enriched America\'s film heritage\". AFI also asked jurors to consider \"the total adrenaline-inducing impact of a film\'s artistry and craft\".'),
(32, 32, NULL, '<p>tt</p>');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('N','A','I') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT ' N => New(Waiting activation), A=>Active, I=>Inactive',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  `avatar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_photo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `sex` enum('M','F') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT ' M => Male, F=>Female',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `remember_token`, `status`, `verified`, `verification_token`, `first_name`, `last_name`, `phone`, `website`, `activated_at`, `avatar`, `provider_name`, `provider_id`, `full_photo`, `notes`, `sex`, `created_at`, `updated_at`) VALUES
(1, 'Shawn Hadray', 'ShawnHadray@makevote.site.com', '$2y$10$49c/4FKDUp7koE0pMQDEneOCqaVKh/CHXGfcIeHDdKNe7MdBJqNi.', NULL, 'N', 1, NULL, 'Shawn------=========', 'Hadray----', '987-7543-916', 'shawn-hadray-make-vote.site.com', '2019-02-22 07:15:47', 'shawn_hadray.jpg', NULL, NULL, 'shawn_hadray_full.jpg', 'Some notes on <strong>Shawn Hadray</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.', 'M', '2019-02-22 07:15:47', '2019-02-27 15:55:16'),
(2, 'Lisa Longred', 'LisaLongred@makevote.site.com', '$2y$10$nQoGdcsisxg6Ake5d2OVV.xTyrbh1SxIvKAejZDUVW7wEHFe7jfAe', NULL, 'A', 1, NULL, 'Lisa', 'Longreddddd', '987-7543-916', 'lisalongred-make-vote.site.com', '2019-02-22 07:15:48', 'LisaLongred_avatar.jpg', NULL, NULL, 'LisaLongred.jpg', 'Some notes on <strong>Lisa Longred</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. \n            lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.', 'F', '2019-02-22 07:15:48', '2019-02-27 13:16:24'),
(3, 'Tony Black', 'TonyBlack@makevote.site.com', '$2y$10$6FlnI738ZUwYd7M95Y/08eoLVMCR4nyyHtfdSerugIAaApng9zYRS', NULL, 'A', 1, NULL, 'Tony', 'Black', '247-159-0976', 'tony-black-make-vote.site.com', '2019-02-22 07:15:48', 'tony_black.jpg', NULL, NULL, 'tony_black_full_photo.jpg', 'Some notes on <strong>Tony Black</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. \n            lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.', 'M', '2019-02-22 07:15:48', NULL),
(4, 'Martha Lang', 'MarthaLang@makevote.site.com', '$2y$10$wL7.NHj3HG6QUY8TakeOQO4A5YSLrJgU9DCPgRglszPCdXDGY6wCu', NULL, 'I', 1, NULL, 'Martha', 'Lang', '247-541-7172', 'martha-lang-make-vote.site.com', '2019-02-22 07:15:48', 'Martha_lang_avatar.jpg', NULL, NULL, 'Martha_lang.jpg', 'Some notes on <strong>Martha Lang</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. \n            lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.', 'F', '2019-02-22 07:15:48', NULL),
(5, 'Admin', 'admin@mail.com', '$2y$10$gkxyaiaYvadOnde350OmpupYNn1r2CWJJSfzJZMesRMltAAcXXThG', 'WNsr9fuGbAYHTLCIX19zJ1HlmMFN4aBPYaRHLptVhUkGm30KyRNTyGDuZwzB', 'A', 1, NULL, 'Rad', 'Soang', '247-541-7172', 'rad-soang-make_vote.site.com', '2019-02-22 07:15:48', 'rad_soang_avatar.jpg', NULL, NULL, 'rad_soang.jpg', 'Some notes on <strong>Rad Soang</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. \n            lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.', 'M', '2019-02-22 07:15:48', NULL),
(7, 'taryn_muller', 'taryn.muller@conn.com', '$2y$10$4PiRpXqqZm9atQXHeDSj1eWit0e49d2/4KhMSwPa3NAnZi9kwRe1e', NULL, 'A', 1, NULL, 'Taryn', 'Muller', NULL, NULL, '2019-02-22 07:15:54', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:54', NULL),
(8, 'emma_schroeder', 'emma.schroeder@abbott.info', '$2y$10$TwM3h/7NBG1.BM87jkaXPuRLe5MxcDF0PjMiQEz4dDxStg2iG5KD2', NULL, 'A', 1, NULL, 'Emma', 'Schroeder', NULL, NULL, '2019-02-22 07:15:54', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:54', NULL),
(9, 'alexzander_botsford', 'alexzander.botsford@gmail.com', '$2y$10$Qbu24m1qrZWCkWDitiyRzeolRo1p7WVI9QGWX1hvLdBhwOyVPdb1m', NULL, 'A', 1, NULL, 'Alexzander', 'Botsford', NULL, NULL, '2019-02-22 07:15:54', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:54', NULL),
(10, 'tad_walsh', 'tad.walsh@yahoo.com', '$2y$10$Lo/P9bzTQTGmPrhJBnqo4uFGV2N5YS2Fvf0ozniFLT9yK4GH40dxq', NULL, 'A', 1, NULL, 'Tad', 'Walsh', NULL, NULL, '2019-02-22 07:15:54', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:54', NULL),
(11, 'jackeline_satterfield', 'jackeline.satterfield@hotmail.com', '$2y$10$2bbxTufsgWRPmpIN54mn0eMi7fqaaTZPYr5umyMfu.om/lGQ4Hdie', NULL, 'A', 1, NULL, 'Jackeline', 'Satterfield', NULL, NULL, '2019-02-22 07:15:54', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:54', NULL),
(12, 'harry_simonis', 'harry.simonis@haag.info', '$2y$10$srz8TfGud4EXLrnc74CMQ.kapMbBhbixzycmOmuk.nOgGf/aOu3zi', NULL, 'A', 1, NULL, 'Harry', 'Simonis', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(13, 'mackenzie_conroy', 'mackenzie.conroy@yahoo.com', '$2y$10$OZqDbZzzDHbob.sCFpR7uul9ZfpHhYKKNAa6ak.FSrZdbThc9jKMq', NULL, 'A', 1, NULL, 'Mackenzie', 'Conroy', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(14, 'marjolaine_jast', 'marjolaine.jast@yahoo.com', '$2y$10$ONNFhePOGW3793m0v/KajuNIjGvDAKjGxn/dUdkCATEeUYesqqjr2', NULL, 'A', 1, NULL, 'Marjolaine', 'Jast', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(15, 'valerie_renner', 'valerie.renner@yahoo.com', '$2y$10$UOKg/vKn/G3oFZgPSa0BpuA.I/sepCj8.rx0Bk1HYQw40yESaYCt.', NULL, 'A', 1, NULL, 'Valerie', 'Renner', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(16, 'gerardo_schamberger', 'gerardo.schamberger@prohaska.net', '$2y$10$ts1CiHy45SIZ6GxML0zVt.pxULLQauub/0.CJJct7iM.3SfWscvt.', NULL, 'A', 1, NULL, 'Gerardo', 'Schamberger', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(17, 'molly_heaney', 'molly.heaney@reinger.org', '$2y$10$i9zzHw/Ue5zHLlcImV87kuJBt8ZUXruUtKgDqHVWsyiYtwMnBkhM6', NULL, 'A', 1, NULL, 'Molly', 'Heaney', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(18, 'leda_boyle', 'leda.boyle@gmail.com', '$2y$10$Bx057gLqPF4LMRtFnSMbl.0.SRvq99Y8XyTC/dWutSk07H7z4z.k2', NULL, 'A', 1, NULL, 'Leda', 'Boyle', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(19, 'ford_cormier', 'ford.cormier@yahoo.com', '$2y$10$gntBRCSX3RaUwHvlVeN6UOZHv4r.Qn0Ajel6542N6e.77HtQ1XVUW', NULL, 'A', 1, NULL, 'Ford', 'Cormier', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(20, 'lorna_hudson', 'lorna.hudson@schuppe.org', '$2y$10$QuJzwX0zSafBKgfzkYy6pe3vYxcmL04M8dv.vDnOHw6alFh5.gXK6', NULL, 'A', 1, NULL, 'Lorna', 'Hudson', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(21, 'juanita_witting', 'juanita.witting@predovic.com', '$2y$10$M722uo7MebyohwqM/NnpBOMgKcfrOprm20n/KXMlKDImaAWHwSWW2', NULL, 'A', 1, NULL, 'Juanita', 'Witting', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(22, 'lonie_flatley', 'lonie.flatley@stroman.org', '$2y$10$PLjZEeSuIGwOFCgqyZsHNOtU4moHEyqWb222xvxLou/AAIa8hXK0e', NULL, 'A', 1, NULL, 'Lonie', 'Flatley', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(23, 'elinore_crist', 'elinore.crist@hotmail.com', '$2y$10$Ejpn28BdhwYvQR1lINa/jOIJZwNmqcNyyCJeEtYgTK74Mo.tsCE0C', NULL, 'A', 1, NULL, 'Elinore', 'Crist', NULL, NULL, '2019-02-22 07:15:55', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:55', NULL),
(24, 'shanie_dare', 'shanie.dare@yahoo.com', '$2y$10$NiU1G02Cg9UrD5wcSW0ODOnYbROZPgpd5y5F.DnYQ18UVJJn3BzkW', NULL, 'A', 1, NULL, 'Shanie', 'Dare', NULL, NULL, '2019-02-22 07:15:56', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:56', NULL),
(25, 'guiseppe_rosenbaum', 'guiseppe.rosenbaum@yahoo.com', '$2y$10$i0gNVYDTw18oWVAlR4lIReX14DXfSlnXWLEWloQiO.r2PrQb8q276', NULL, 'A', 1, NULL, 'Guiseppe', 'Rosenbaum', NULL, NULL, '2019-02-22 07:15:56', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:56', NULL),
(26, 'anita_cummerata', 'anita.cummerata@miller.com', '$2y$10$gbcoBkCOXIhEHrdGWR2XJ.Ua2r7OgSrU6qubmuBtlFCB09c/hACVa', NULL, 'A', 1, NULL, 'Anita', 'Cummerata', NULL, NULL, '2019-02-22 07:15:56', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:56', NULL),
(27, 'elna_buckridge', 'elna.buckridge@hotmail.com', '$2y$10$ZoNG4sWIvmXDgQD9/p63KO/5xL4ribbKpfKM0SZaJJTdKLakJEmbe', NULL, 'A', 1, NULL, 'Elna', 'Buckridge', NULL, NULL, '2019-02-22 07:15:56', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:56', NULL),
(28, 'evert_schmitt', 'evert.schmitt@connelly.com', '$2y$10$GhAFXBWvj4c8LcIhVEbPiuITNgiCCcRgMoNB36DVLTM3Sxble0wNi', NULL, 'A', 1, NULL, 'Evert', 'Schmitt', NULL, NULL, '2019-02-22 07:15:56', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:56', NULL),
(29, 'gladys_goyette', 'gladys.goyette@yahoo.com', '$2y$10$6Tm91PCvGJrorb7n/18r5ez1VKB5Hy/mdzFKRfAuhTehQAXOusfVK', NULL, 'A', 1, NULL, 'Gladys', 'Goyette', NULL, NULL, '2019-02-22 07:15:56', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:56', NULL),
(30, 'lexi_o\'reilly', 'lexi.o\'reilly@yahoo.com', '$2y$10$uI8FmUHfNU7SAldjMygQb.bKKw8AnqFJyFgiUlbs635F5E0FxEqZC', NULL, 'A', 1, NULL, 'Lexi', 'O\'Reilly', NULL, NULL, '2019-02-22 07:15:56', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:56', NULL),
(31, 'andreanne_hand', 'andreanne.hand@gmail.com', '$2y$10$SLidXO7ITuEzXaYARGvB2uZBj04KTkKWkvcwxf54Wq9BM2f8j0d3W', NULL, 'A', 1, NULL, 'Andreanne', 'Hand', NULL, NULL, '2019-02-22 07:15:56', NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:15:56', NULL),
(32, 'marisa murphy', 'marisa.murphy@votes_site.com', '$2y$10$UaQyGbU2oWYE6BexL80E1uSlGkF9DB0GgVls0AmqTDnor9sblfbPm', NULL, 'N', 0, NULL, 'Marisa', 'Murphy', '790-919-1458 x3435', 'votes_site_marisa murphy.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:10', NULL),
(33, 'jocelyn witting', 'jocelyn.witting@votes_site.com', '$2y$10$mbVcdSemqwOypbhA.nJkF.HQXJReVDXE8WPMQA4UN8yqJbc7C1p/2', NULL, 'A', 1, NULL, 'Jocelyn', 'Witting', '(428) 755-8261 x613', 'votes_site_jocelyn witting.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:10', NULL),
(34, 'irwin schimmel', 'irwin.schimmel@votes_site.com', '$2y$10$g3AIrhwzJY5H2TfBV5dH1ujC.o3O3l5aVcFzFfE1T3VOpyjtph/He', NULL, 'A', 1, NULL, 'Irwin', 'Schimmel', '1-990-682-1628', 'votes_site_irwin schimmel.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:10', NULL),
(35, 'elsa howell', 'elsa.howell@votes_site.com', '$2y$10$Z91.LNhB1qnv1SEeZlnl8.FEPR200UW8DivkiAp8mVzen68Ls.EKe', NULL, 'A', 1, NULL, 'Elsa', 'Howell', '636.553.9782 x76937', 'votes_site_elsa howell.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:11', NULL),
(36, 'blair kreiger', 'blair.kreiger@votes_site.com', '$2y$10$O.0b0ODkE671i9.y6OTM8.ERYMV6WdgJzuhrKcnWIgEuPgCzHxZPS', NULL, 'A', 1, NULL, 'Blair', 'Kreiger', '(671) 220-5759', 'votes_site_blair kreiger.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:11', NULL),
(37, 'clifford rutherford', 'clifford.rutherford@votes_site.com', '$2y$10$MsIuAb9t5w6iHSmC7Blf9.M1jtA6dV/bHP5yXcp3wdgMLCOByYmPi', NULL, 'A', 1, NULL, 'Clifford', 'Rutherford', '662-634-6160 x39981', 'votes_site_clifford rutherford.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:11', NULL),
(38, 'jermaine becker', 'jermaine.becker@votes_site.com', '$2y$10$tq4Bus5I.G6kTGOgJFk8RedreJVU3X8ol9amb5QXGT81JQCGr9O7S', NULL, 'N', 0, NULL, 'Jermaine', 'Becker', '406-895-7654 x1601', 'votes_site_jermaine becker.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:11', NULL),
(39, 'domenic gleason', 'domenic.gleason@votes_site.com', '$2y$10$25g734wU/myqqXar/UmQhewzGtFXjJDxAPu5uNhhENjf2PSVJ65Ci', NULL, 'A', 1, NULL, 'Domenic', 'Gleason', '1-263-483-7730', 'votes_site_domenic gleason.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:11', NULL),
(40, 'clementine davis', 'clementine.davis@votes_site.com', '$2y$10$UyVr9Ym0woIXDR7nLMHc.eEvGUYtwZmNN.uyBRTRkaACiHZ8iKfkO', NULL, 'N', 0, NULL, 'Clementine', 'Davis', '301-290-8167', 'votes_site_clementine davis.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:11', NULL),
(41, 'kaleigh zboncak', 'kaleigh.zboncak@votes_site.com', '$2y$10$nfl2bCQ0bQU4xqYgPbhQLeygHMMP54jZwHgPWnahfj3dTJ/QOtFYy', NULL, 'N', 0, NULL, 'Kaleigh', 'Zboncak', '454.603.6236 x591', 'votes_site_kaleigh zboncak.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-02-22 07:16:11', NULL),
(43, 'JackParrot', 'admin@mail.commm', '$2y$10$7MXf1csRRB2msVnbc.jGHuyK/egdaHdBWBeI6IQL3aod29X41SbXW', NULL, 'N', 0, '8e5102f4852e4314ac705028fd9aee739ebfc8afa54fea7fd2918b7372bfaa43', 'Jack', 'Parrot', '098-765-4321', 'jackparrot.vote_site.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-03-13 15:17:36', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `group_id` smallint(5) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`, `created_at`) VALUES
(1, 7, 3, '2019-02-22 07:15:54'),
(2, 8, 3, '2019-02-22 07:15:54'),
(3, 9, 3, '2019-02-22 07:15:54'),
(4, 10, 3, '2019-02-22 07:15:54'),
(5, 11, 3, '2019-02-22 07:15:54'),
(6, 12, 3, '2019-02-22 07:15:55'),
(7, 13, 3, '2019-02-22 07:15:55'),
(8, 14, 3, '2019-02-22 07:15:55'),
(9, 15, 3, '2019-02-22 07:15:55'),
(10, 16, 3, '2019-02-22 07:15:55'),
(11, 17, 3, '2019-02-22 07:15:55'),
(12, 18, 3, '2019-02-22 07:15:55'),
(13, 19, 3, '2019-02-22 07:15:55'),
(14, 20, 3, '2019-02-22 07:15:55'),
(15, 21, 3, '2019-02-22 07:15:55'),
(16, 22, 3, '2019-02-22 07:15:55'),
(17, 23, 3, '2019-02-22 07:15:56'),
(18, 24, 3, '2019-02-22 07:15:56'),
(19, 25, 3, '2019-02-22 07:15:56'),
(20, 26, 3, '2019-02-22 07:15:56'),
(21, 27, 3, '2019-02-22 07:15:56'),
(22, 28, 3, '2019-02-22 07:15:56'),
(23, 29, 3, '2019-02-22 07:15:56'),
(24, 30, 3, '2019-02-22 07:15:56'),
(25, 31, 3, '2019-02-22 07:15:56'),
(26, 1, 1, '2019-02-22 07:15:56'),
(27, 1, 2, '2019-02-22 07:15:56'),
(28, 2, 1, '2019-02-22 07:15:56'),
(29, 2, 2, '2019-02-22 07:15:56'),
(30, 3, 1, '2019-02-22 07:15:56'),
(31, 3, 2, '2019-02-22 07:15:56'),
(32, 4, 1, '2019-02-22 07:15:56'),
(33, 4, 2, '2019-02-22 07:15:56'),
(34, 5, 1, '2019-02-22 07:15:56'),
(35, 5, 2, '2019-02-22 07:15:56'),
(38, 32, 3, '2019-02-22 07:16:10'),
(39, 33, 3, '2019-02-22 07:16:10'),
(40, 34, 3, '2019-02-22 07:16:10'),
(41, 35, 3, '2019-02-22 07:16:11'),
(42, 36, 3, '2019-02-22 07:16:11'),
(43, 37, 3, '2019-02-22 07:16:11'),
(44, 38, 3, '2019-02-22 07:16:11'),
(45, 39, 3, '2019-02-22 07:16:11'),
(46, 40, 3, '2019-02-22 07:16:11'),
(47, 41, 3, '2019-02-22 07:16:11');

-- --------------------------------------------------------

--
-- Структура таблицы `users_site_subscriptions`
--

CREATE TABLE `users_site_subscriptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `site_subscription_id` int(10) UNSIGNED DEFAULT NULL,
  `mailchimp_subscription_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users_site_subscriptions`
--

INSERT INTO `users_site_subscriptions` (`id`, `user_id`, `site_subscription_id`, `mailchimp_subscription_id`, `created_at`) VALUES
(1, NULL, NULL, NULL, '2019-03-13 15:17:36'),
(2, NULL, NULL, NULL, '2019-03-13 15:17:36');

-- --------------------------------------------------------

--
-- Структура таблицы `votes`
--

CREATE TABLE `votes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(260) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `creator_id` int(10) UNSIGNED NOT NULL,
  `vote_category_id` int(10) UNSIGNED NOT NULL,
  `is_quiz` tinyint(1) NOT NULL DEFAULT '0',
  `is_homepage` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('N','A','I') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT ' N=>New,  A=>Active, I=>Inactive',
  `ordering` int(10) UNSIGNED NOT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `votes`
--

INSERT INTO `votes` (`id`, `name`, `slug`, `description`, `creator_id`, `vote_category_id`, `is_quiz`, `is_homepage`, `status`, `ordering`, `image`, `created_at`, `updated_at`) VALUES
(1, 'To be or not to be ?eeeeeeeeeeeee', 'to-be-or-not-to-be', 'Still trying to find an answer. is the opening phrase of a soliloquy spoken by <i><i>Prince <i>Hamlet</i></i></i> in the so-called \"nunnery scene\" of <i><i>William Shakespeare</i>\'s</i> play <i>Hamlet</i>. Act III, Scene I.Though it is called a soliloquy <i>Hamlet</i> is not alone when he makes this speech because Ophelia is on stage pretending to read while waiting for <i>Hamlet</i> to notice her, and Claudius and Polonius, who have placed Ophelia in <i><i>Hamlet</i>\'s</i> way in order to overhear their conversation and find out if <i>Hamlet</i> is really mad or only pretending, have concealed themselves. Even so, <i>Hamlet</i> seems to consider himself alone and there is no indication that the others on stage hear him before he addresses Ophelia. In the speech, <i>Hamlet</i> contemplates death and suicide, bemoaning the pain and unfairness of life but acknowledging that the alternative might be worse. The meaning of the speech is heavily debated but seems clearly concerned with <i><i>Hamlet</i>\'s</i> hesitation to avenge his father\'s murder (discovered in Act I) by his uncle Claudius.\r\n Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt...', 5, 1, 0, 1, 'A', 111, 'tobe.png', '2019-02-22 07:15:50', '2019-02-27 11:11:01'),
(2, 'Who Framed Roger Rabbit ?', 'who-framed-roger-rabbit', 'Who Framed Roger Rabbit is a 1988 American live-action/animated fantasy film directed by <i>Robert Zemeckis</i>, produced by Frank Marshall and Robert Watts, and written by Jeffrey Price and Peter S. Seaman. The film is based on Gary K. Wolf\'s 1981 novel Who Censored Roger Rabbit? The film stars Bob Hoskins, Christopher Lloyd, Charles Fleischer, Stubby Kaye, and Joanna Cassidy. Combining live-action and animation, the film is set in <b>Hollywood</b> during the late 1940s, where animated characters and people co-exist. The story follows Eddie Valiant, a private detective who must exonerate \"Toon\" (i.e. cartoon character) Roger Rabbit, who is accused of murdering a wealthy businessman.\n            Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt...\n            Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt...', 5, 2, 1, 1, 'A', 1, 'title.jpg', '2019-02-22 07:15:51', NULL),
(4, 'Which fictional city is the home of Batman ?', 'which-fictional-city-is-the-home-of-batman', 'You must to know something about Batman...\n            There are thousands of comic book characters who have influenced individuals and even larger audiences, but few have become legendary. Only a handful of comic characters have been able to transcend the nerd realm and enter the minds of mainstream audiences.\nOf course, one of this handful is Batman, one of the most iconic superheroes of all-time, rivaled only by his DC counterpart Superman, who he\'ll be battling soon in the upcoming Batman V Superman: Dawn of Justice. From the countless incarnations we’ve seen in comics, television, and movies, every Batman left a mark on entertainment.\nHowever in order to understand the character and really grasp his motivations, actions, and importance, we must look at some of the definitive moments in Batman\'s history. Having been around for 76 years, there is a lot to siphon through, but here we go!', 4, 2, 1, 1, 'A', 4, 'city.jpg', '2019-02-22 07:15:51', NULL),
(6, 'Do you like design of this site ?', 'do-you-like-design-of-this-site', 'Do you like design of this site ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.', 5, 6, 0, 1, 'A', 6, '', '2019-02-22 07:15:51', NULL),
(10, 'What is the name of the fairy in Peter Pan ?', 'what-is-the-name-of-the-fairy-in-peter-pan', 'What is the name of the fairy in Peter Pan lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.\nlorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. \nlorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.        ', 1, 1, 1, 1, 'A', 10, 'Peter_Pan,_by_Oliver_Herford,_1907.png', '2019-02-22 07:15:51', NULL),
(15, 'Who directed the movie Jaws?', 'who-directed-the-movie-jaws', 'Who directed the movie Jaws lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. \n            lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.\nlorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. ', 1, 2, 1, 1, 'A', 15, 'movie-jaws.jpg', '2019-02-22 07:15:52', NULL),
(16, 'In the film Babe, what type of animal was Babe?', 'in-the-film-babe-what-type-of-animal-was-babe', 'Babe is a 1995 Australian-American comedy-drama film directed by <i>Chris Noonan</i>, produced by <i>George Miller</i>, and written by both. It is an adaptation of Dick King-Smith\'s 1983 novel The Sheep-Pig, also known as Babe: The Gallant Pig in the US, which tells the story of a pig raised as livestock who wants to do the work of a sheepdog. The main animal characters are played by a combination of real and animatronic pigs and Border Collies.[3]\n\nAfter seven years of development, Babe was filmed in Robertson, New South Wales, Australia. The talking-animal visual effects were done by Rhythm & Hues Studios and Jim Henson\'s Creature Shop. The film was both a box office and critical success, and in 1998 Miller went on to direct a sequel, Babe: Pig in the City.\n\nBabe, an orphaned piglet, is chosen for a \"guess the weight\" contest at a county fair. The winning farmer, Arthur Hoggett, brings him home and allows him to stay with a Border Collie named Fly, her mate Rex and their puppies, in the barn.\n\nA duck named Ferdinand, who crows as roosters are said to every morning to wake people so he will be considered useful and be spared from being eaten, persuades Babe to help him destroy the alarm clock that threatens his mission. Despite succeeding in this, they wake Duchess, the Hoggetts\' cat, and in the confusion accidentally destroy the living room. Rex sternly instructs Babe to stay away from Ferdinand (now a fugitive) and the house. Sometime later, when Fly\'s puppies are put up for sale, Babe asks if he can call her \"Mom\".\n\nChristmas brings a visit from the Hoggetts\' relatives. Babe is almost chosen for Christmas dinner but a duck is picked instead after Hoggett remarks to his wife Esme that Babe may bring a prize for ham at the next county fair. On Christmas Day, Babe justifies his existence by alerting Hoggett to sheep rustlers stealing sheep from one of the fields. The next day, Hoggett sees Babe sort the hens, separating the brown from the white ones. Impressed, he takes him to the fields and allows him to try and herd the sheep. Encouraged by an elder ewe named Maa, the sheep cooperate, but Rex sees Babe\'s actions as an insult to sheepdogs and confronts Fly in a vicious fight for encouraging Babe. He injures her leg and accidentally bites Hoggett\'s hand when he tries to intervene. Rex is then chained to the dog house, muzzled and sedated, leaving the sheep herding job to Babe.', 1, 2, 1, 1, 'A', 16, 'Babe_ver1.jpg', '2019-02-22 07:15:52', NULL),
(19, 'The title role of the 1990 movie “Pretty Woman” was played by which actress?', 'the-title-role-of-the-1990-movie-pretty-woman-was-played-by-which-actress', 'Pretty Woman is a 1990 American romantic comedy film directed by Garry Marshall from a screenplay by J. F. Lawton. The film stars <i>Richard Gere</i> and Julia Roberts, and features Hector Elizondo, Ralph Bellamy (in his final performance), Laura San Giacomo, and Jason Alexander in supporting roles. The film\'s story centers on down-on-her-luck <b>Hollywood</b> prostitute Vivian Ward, who is hired by Edward Lewis, a wealthy businessman, to be his escort for several business and social functions, and their developing relationship over the course of her week-long stay with him.\n\nOriginally intended to be a dark cautionary tale about class and sex work in Los Angeles, the film was reconceived as a romantic comedy with a large budget. It was widely successful at the box office and was the third highest-grossing film of 1990. The film saw the highest number of ticket sales in the U.S. ever for a romantic comedy, with Box Office Mojo listing it as the #1 romantic comedy by the highest estimated domestic tickets sold at 42,176,400, slightly ahead of My Big Fat Greek Wedding (2002) at 41,419,500 tickets. The film received positive reviews, with Roberts\'s performance being praised, for which she received a Golden Globe Award and a nomination for the Academy Award for Best Actress. In addition, screenwriter J. F. Lawton was nominated for a Writers Guild Award and a BAFTA Award.', 1, 2, 1, 0, 'N', 19, 'Pretty_woman_movie.jpg', '2019-02-22 07:15:52', NULL),
(20, 'Who Framed Roger Rabbit ?gg', 'who-framed-roger-rabbit-gg', '<p>bbbbbbbbbbb</p>', 5, 6, 0, 0, 'I', 5566, 'thinking.jpg', '2019-02-26 14:44:38', NULL),
(21, 'gg----', 'gg', '<p>8888888888</p>\r\n<p>6666666644444</p>', 5, 5, 0, 1, 'I', 44, NULL, '2019-02-27 13:10:26', '2019-02-27 13:15:37');

-- --------------------------------------------------------

--
-- Структура таблицы `vote_categories`
--

CREATE TABLE `vote_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(260) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `in_subscriptions` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `vote_categories`
--

INSERT INTO `vote_categories` (`id`, `name`, `slug`, `active`, `in_subscriptions`, `created_at`, `updated_at`) VALUES
(1, 'Classic literature', 'classic-literature', 1, 1, '2019-02-22 07:15:49', NULL),
(2, 'Movie&Cartoons', 'movie-cartoons', 1, 1, '2019-02-22 07:15:49', NULL),
(5, 'PHP unit tests888', 'php-unit-tests', 1, 1, '2019-02-22 07:15:49', '2019-02-27 13:23:04'),
(6, 'Miscellaneous', 'miscellaneous', 0, 0, '2019-02-22 07:15:49', NULL),
(7, 'PHP unit tests33', 'php-unit-tests33', 0, 0, '2019-02-26 14:49:55', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `vote_items`
--

CREATE TABLE `vote_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `vote_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordering` int(11) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `vote_items`
--

INSERT INTO `vote_items` (`id`, `vote_id`, `name`, `ordering`, `is_correct`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'To be...', 1, 0, 'to_be.png', '2019-02-22 07:15:52', NULL),
(2, 1, 'Not to be...', 2, 0, NULL, '2019-02-22 07:15:52', NULL),
(3, 1, 'That is really a big question...', 3, 0, NULL, '2019-02-22 07:15:52', NULL),
(4, 2, 'Cloverleaf Industry Company', 1, 0, NULL, '2019-02-22 07:15:52', NULL),
(5, 2, 'Judge Doom', 2, 1, 'Judge_Doom.jpg', '2019-02-22 07:15:52', NULL),
(6, 2, 'Marvin Acme', 3, 0, 'Marvin_acme.jpg', '2019-02-22 07:15:52', NULL),
(7, 2, 'Eddie Valiant', 4, 0, 'eddie_valiant.jpg', '2019-02-22 07:15:52', NULL),
(12, 4, 'London', 1, 0, 'london.jpg', '2019-02-22 07:15:52', NULL),
(13, 4, 'Gotham City', 2, 1, 'Gotham_City.jpg', '2019-02-22 07:15:52', NULL),
(14, 4, 'Rome', 3, 0, 'rome-colosseum-story.jpg', '2019-02-22 07:15:52', NULL),
(19, 6, 'Very cool', 1, 0, '', '2019-02-22 07:15:53', NULL),
(20, 6, 'Looking good', 2, 0, '', '2019-02-22 07:15:53', NULL),
(21, 6, 'So-so', 3, 0, '', '2019-02-22 07:15:53', NULL),
(22, 6, 'Poor', 4, 0, '', '2019-02-22 07:15:53', NULL),
(23, 6, 'Very Disgusting', 5, 0, '', '2019-02-22 07:15:53', NULL),
(35, 10, 'Tinkerbell', 1, 1, 'Peter_Pan.png', '2019-02-22 07:15:53', NULL),
(36, 10, 'Oliver Twist', 2, 0, 'OliverTwist.jpg', '2019-02-22 07:15:53', NULL),
(37, 10, 'Harry Potter', 3, 0, 'HarryPotter.png', '2019-02-22 07:15:53', NULL),
(38, 10, 'Huckleberry Finn', 4, 0, 'huckleberry-finn.jpg', '2019-02-22 07:15:53', NULL),
(55, 15, 'George Lucas', 1, 0, 'George_Lucas.jpg', '2019-02-22 07:15:53', NULL),
(56, 15, 'Steven Spielberg', 2, 1, 'StevenSpielberg.jpg', '2019-02-22 07:15:53', NULL),
(57, 15, 'Stanley Kubrick', 3, 0, 'StanleyKubrick.jpg', '2019-02-22 07:15:53', NULL),
(58, 15, 'Mel Brooks', 4, 0, 'MelBrooks.jpg', '2019-02-22 07:15:53', NULL),
(59, 15, 'Martin Scorsese', 5, 0, 'MartinScorsese.jpg', '2019-02-22 07:15:53', NULL),
(61, 16, 'Pig', 2, 1, 'pig.jpg', '2019-02-22 07:15:53', NULL),
(62, 16, 'Giraffe', 3, 0, 'Giraffe.jpg', '2019-02-22 07:15:53', NULL),
(63, 16, 'Bear', 4, 0, 'mother-bear.jpg', '2019-02-22 07:15:53', NULL),
(64, 16, 'Cat', 5, 0, 'cat.jpeg', '2019-02-22 07:15:53', NULL),
(74, 19, 'Natalie Portman', 1, 0, 'Natalie_Portman.jpg', '2019-02-22 07:15:53', NULL),
(75, 19, 'Julia Roberts', 2, 1, 'JuliaRoberts.jpg', '2019-02-22 07:15:53', NULL),
(76, 19, 'Meryl Streep', 3, 0, 'MerylStreep.jpg', '2019-02-22 07:15:53', NULL),
(77, 19, 'Emma Watson', 4, 0, 'Emma_Watson.jpg', '2019-02-22 07:15:53', NULL),
(78, 20, '1', 11, 0, 'wizdom.jpg', '2019-02-26 14:49:03', NULL),
(79, 20, '111', 33, 0, 'trindetch.jpg', '2019-02-26 14:49:20', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `vote_item_users_result`
--

CREATE TABLE `vote_item_users_result` (
  `id` int(10) UNSIGNED NOT NULL,
  `vote_item_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `vote_item_users_result`
--

INSERT INTO `vote_item_users_result` (`id`, `vote_item_id`, `user_id`, `is_correct`, `created_at`) VALUES
(1, 1, 1, 0, '2019-01-27 05:01:48'),
(2, 7, 1, 0, '2019-02-17 03:02:03'),
(4, 20, 1, 0, '2019-02-17 06:02:16'),
(8, 38, 1, 0, '2019-02-21 00:02:31'),
(13, 58, 1, 0, '2019-02-20 04:02:11'),
(14, 62, 1, 0, '2019-02-06 05:02:05'),
(17, 74, 1, 0, '2019-02-12 04:02:49'),
(18, 2, 2, 0, '2019-02-21 01:02:04'),
(19, 7, 2, 0, '2019-01-25 02:01:13'),
(21, 13, 2, 1, '2019-01-30 01:01:00'),
(23, 22, 2, 0, '2019-02-07 10:02:21'),
(27, 38, 2, 0, '2019-01-25 01:01:36'),
(32, 56, 2, 1, '2019-01-24 04:01:02'),
(36, 75, 2, 1, '2019-02-16 09:02:07'),
(37, 3, 3, 0, '2019-02-11 08:02:42'),
(38, 6, 3, 0, '2019-02-10 03:02:14'),
(39, 13, 3, 1, '2019-02-20 01:02:06'),
(41, 21, 3, 0, '2019-02-20 10:02:38'),
(45, 38, 3, 0, '2019-01-31 03:01:37'),
(50, 58, 3, 0, '2019-02-20 09:02:08'),
(54, 75, 3, 1, '2019-02-12 00:02:31'),
(55, 3, 4, 0, '2019-02-21 04:02:51'),
(56, 6, 4, 0, '2019-01-28 23:01:40'),
(58, 13, 4, 1, '2019-02-11 03:02:44'),
(60, 19, 4, 0, '2019-01-23 03:01:02'),
(64, 36, 4, 0, '2019-02-02 05:02:27'),
(69, 56, 4, 1, '2019-02-08 04:02:53'),
(73, 75, 4, 1, '2019-02-01 10:02:28'),
(74, 2, 5, 0, '2019-02-12 07:02:04'),
(75, 7, 5, 0, '2019-01-30 01:01:55'),
(77, 13, 5, 1, '2019-01-25 10:01:32'),
(79, 22, 5, 0, '2019-02-21 09:02:48'),
(83, 35, 5, 1, '2019-02-10 10:02:40'),
(88, 55, 5, 0, '2019-01-29 07:01:53'),
(89, 62, 5, 0, '2019-02-14 04:02:59'),
(92, 77, 5, 0, '2019-02-13 23:02:45'),
(111, 1, 7, 0, '2019-02-06 05:02:10'),
(112, 4, 7, 0, '2019-02-22 10:02:46'),
(114, 13, 7, 1, '2019-02-20 07:02:47'),
(116, 22, 7, 0, '2019-01-30 00:01:44'),
(120, 37, 7, 0, '2019-02-18 01:02:41'),
(125, 59, 7, 0, '2019-02-11 00:02:40'),
(126, 63, 7, 0, '2019-02-13 08:02:38'),
(129, 75, 7, 1, '2019-01-24 08:01:52'),
(130, 1, 8, 0, '2019-02-18 02:02:36'),
(131, 4, 8, 0, '2019-02-02 00:02:00'),
(133, 13, 8, 1, '2019-02-15 00:02:10'),
(135, 20, 8, 0, '2019-01-24 08:01:16'),
(139, 38, 8, 0, '2019-01-27 06:01:02'),
(144, 58, 8, 0, '2019-02-07 07:02:00'),
(145, 61, 8, 1, '2019-02-05 01:02:34'),
(148, 76, 8, 0, '2019-02-07 05:02:27'),
(149, 1, 9, 0, '2019-02-21 03:02:09'),
(151, 13, 9, 1, '2019-02-09 04:02:28'),
(153, 22, 9, 0, '2019-02-13 06:02:00'),
(157, 36, 9, 0, '2019-01-24 09:01:15'),
(162, 58, 9, 0, '2019-02-13 09:02:25'),
(163, 61, 9, 1, '2019-02-11 05:02:22'),
(166, 76, 9, 0, '2019-02-16 10:02:01'),
(167, 3, 10, 0, '2019-01-30 09:01:15'),
(168, 6, 10, 0, '2019-01-24 06:01:24'),
(169, 13, 10, 1, '2019-02-17 08:02:35'),
(171, 20, 10, 0, '2019-02-05 09:02:28'),
(175, 36, 10, 0, '2019-02-07 01:02:34'),
(180, 55, 10, 0, '2019-02-03 08:02:29'),
(181, 62, 10, 0, '2019-02-05 04:02:45'),
(184, 76, 10, 0, '2019-01-30 01:01:32'),
(185, 2, 11, 0, '2019-02-11 05:02:09'),
(186, 6, 11, 0, '2019-01-28 03:01:57'),
(188, 19, 11, 0, '2019-01-26 06:01:03'),
(192, 35, 11, 1, '2019-02-06 10:02:25'),
(197, 55, 11, 0, '2019-02-02 08:02:46'),
(198, 64, 11, 0, '2019-02-16 08:02:57'),
(201, 77, 11, 0, '2019-02-10 03:02:46'),
(202, 1, 12, 0, '2019-02-17 10:02:35'),
(203, 6, 12, 0, '2019-01-25 09:01:41'),
(205, 22, 12, 0, '2019-02-17 10:02:05'),
(209, 35, 12, 1, '2019-02-21 06:02:59'),
(214, 56, 12, 1, '2019-02-04 03:02:43'),
(215, 61, 12, 1, '2019-02-10 03:02:37'),
(218, 74, 12, 0, '2019-02-06 07:02:32'),
(219, 3, 13, 0, '2019-02-09 02:02:27'),
(220, 7, 13, 0, '2019-02-19 05:02:25'),
(222, 13, 13, 1, '2019-01-27 23:01:36'),
(224, 22, 13, 0, '2019-02-17 04:02:11'),
(228, 35, 13, 1, '2019-01-24 07:01:43'),
(233, 55, 13, 0, '2019-02-10 03:02:45'),
(234, 61, 13, 1, '2019-02-01 05:02:35'),
(237, 74, 13, 0, '2019-02-07 03:02:34'),
(238, 3, 14, 0, '2019-02-10 03:02:11'),
(239, 4, 14, 0, '2019-01-27 05:01:29'),
(241, 23, 14, 0, '2019-02-05 08:02:44'),
(245, 37, 14, 0, '2019-02-16 03:02:58'),
(250, 56, 14, 1, '2019-02-13 23:02:38'),
(251, 62, 14, 0, '2019-02-13 07:02:28'),
(254, 74, 14, 0, '2019-02-21 05:02:33'),
(255, 1, 15, 0, '2019-02-10 02:02:03'),
(256, 13, 15, 1, '2019-01-30 04:01:17'),
(258, 19, 15, 0, '2019-02-09 10:02:03'),
(262, 38, 15, 0, '2019-02-04 04:02:08'),
(267, 56, 15, 1, '2019-02-06 00:02:03'),
(268, 61, 15, 1, '2019-02-11 03:02:25'),
(271, 74, 15, 0, '2019-02-14 01:02:24'),
(272, 1, 16, 0, '2019-01-30 23:01:41'),
(273, 4, 16, 0, '2019-01-23 04:01:36'),
(275, 13, 16, 1, '2019-01-25 08:01:25'),
(277, 20, 16, 0, '2019-02-07 09:02:00'),
(281, 36, 16, 0, '2019-02-14 01:02:30'),
(286, 57, 16, 0, '2019-02-11 08:02:12'),
(287, 62, 16, 0, '2019-02-02 04:02:15'),
(290, 75, 16, 1, '2019-02-15 10:02:22'),
(291, 2, 17, 0, '2019-01-30 02:01:39'),
(292, 6, 17, 0, '2019-02-22 02:02:32'),
(295, 19, 17, 0, '2019-02-19 04:02:51'),
(299, 36, 17, 0, '2019-01-25 02:01:43'),
(304, 57, 17, 0, '2019-01-27 00:01:17'),
(305, 64, 17, 0, '2019-02-18 05:02:16'),
(308, 75, 17, 1, '2019-01-23 07:01:21'),
(309, 3, 18, 0, '2019-02-04 04:02:01'),
(311, 20, 18, 0, '2019-02-05 09:02:49'),
(315, 36, 18, 0, '2019-02-18 06:02:52'),
(320, 57, 18, 0, '2019-02-16 01:02:11'),
(321, 62, 18, 0, '2019-01-23 04:01:35'),
(324, 77, 18, 0, '2019-02-14 01:02:07'),
(325, 2, 19, 0, '2019-02-13 01:02:01'),
(326, 7, 19, 0, '2019-01-31 10:01:25'),
(328, 22, 19, 0, '2019-02-20 00:02:12'),
(332, 36, 19, 0, '2019-02-20 02:02:52'),
(337, 57, 19, 0, '2019-02-01 00:02:32'),
(341, 74, 19, 0, '2019-01-22 09:01:11'),
(342, 3, 20, 0, '2019-02-10 06:02:40'),
(343, 7, 20, 0, '2019-02-05 10:02:45'),
(346, 22, 20, 0, '2019-02-05 02:02:13'),
(350, 36, 20, 0, '2019-01-23 03:01:47'),
(355, 56, 20, 1, '2019-02-06 05:02:21'),
(356, 62, 20, 0, '2019-02-20 00:02:30'),
(359, 75, 20, 1, '2019-02-04 10:02:27'),
(360, 1, 21, 0, '2019-02-04 03:02:26'),
(361, 7, 21, 0, '2019-02-13 01:02:45'),
(362, 13, 21, 1, '2019-02-07 04:02:05'),
(364, 20, 21, 0, '2019-02-14 00:02:24'),
(368, 38, 21, 0, '2019-02-18 05:02:12'),
(373, 59, 21, 0, '2019-02-08 23:02:09'),
(374, 64, 21, 0, '2019-02-18 10:02:02'),
(377, 77, 21, 0, '2019-02-20 00:02:07'),
(378, 1, 22, 0, '2019-01-24 00:01:48'),
(379, 7, 22, 0, '2019-02-03 04:02:26'),
(380, 13, 22, 1, '2019-01-27 08:01:17'),
(382, 22, 22, 0, '2019-02-15 01:02:29'),
(386, 36, 22, 0, '2019-02-13 08:02:13'),
(391, 55, 22, 0, '2019-01-28 07:01:17'),
(392, 61, 22, 1, '2019-02-04 07:02:10'),
(395, 76, 22, 0, '2019-02-16 07:02:28'),
(396, 2, 23, 0, '2019-02-13 02:02:01'),
(397, 7, 23, 0, '2019-02-12 05:02:25'),
(398, 13, 23, 1, '2019-02-17 23:02:36'),
(400, 20, 23, 0, '2019-01-28 00:01:22'),
(404, 36, 23, 0, '2019-02-15 01:02:33'),
(409, 56, 23, 1, '2019-02-08 01:02:32'),
(410, 64, 23, 0, '2019-01-30 03:01:32'),
(413, 76, 23, 0, '2019-01-28 02:01:22'),
(414, 2, 24, 0, '2019-01-24 03:01:46'),
(415, 6, 24, 0, '2019-02-14 06:02:34'),
(416, 13, 24, 1, '2019-02-03 02:02:20'),
(418, 22, 24, 0, '2019-01-24 03:01:15'),
(422, 36, 24, 0, '2019-01-26 07:01:00'),
(427, 59, 24, 0, '2019-02-16 01:02:29'),
(428, 61, 24, 1, '2019-01-28 02:01:45'),
(431, 77, 24, 0, '2019-01-25 00:01:13'),
(432, 2, 25, 0, '2019-02-10 00:02:23'),
(433, 7, 25, 0, '2019-02-20 06:02:25'),
(435, 19, 25, 0, '2019-01-31 02:01:53'),
(439, 38, 25, 0, '2019-01-29 09:01:06'),
(444, 59, 25, 0, '2019-01-29 03:01:31'),
(445, 62, 25, 0, '2019-02-07 23:02:28'),
(448, 76, 25, 0, '2019-01-30 04:01:00'),
(449, 3, 26, 0, '2019-01-24 02:01:42'),
(450, 7, 26, 0, '2019-02-22 04:02:17'),
(451, 13, 26, 1, '2019-02-08 08:02:52'),
(453, 22, 26, 0, '2019-02-21 07:02:43'),
(457, 36, 26, 0, '2019-02-17 23:02:37'),
(462, 58, 26, 0, '2019-02-13 00:02:21'),
(463, 62, 26, 0, '2019-02-04 02:02:38'),
(466, 75, 26, 1, '2019-01-27 07:01:39'),
(467, 1, 27, 0, '2019-02-15 08:02:55'),
(468, 13, 27, 1, '2019-02-01 06:02:06'),
(470, 23, 27, 0, '2019-01-25 09:01:33'),
(474, 37, 27, 0, '2019-01-31 09:01:12'),
(479, 56, 27, 1, '2019-02-17 06:02:10'),
(480, 61, 27, 1, '2019-02-05 06:02:22'),
(483, 74, 27, 0, '2019-01-22 08:01:16'),
(484, 3, 28, 0, '2019-01-29 06:01:27'),
(485, 4, 28, 0, '2019-02-16 03:02:34'),
(488, 21, 28, 0, '2019-02-18 08:02:36'),
(492, 37, 28, 0, '2019-02-15 06:02:47'),
(497, 55, 28, 0, '2019-02-10 04:02:49'),
(498, 64, 28, 0, '2019-02-05 23:02:41'),
(501, 77, 28, 0, '2019-02-10 23:02:59'),
(502, 3, 29, 0, '2019-02-14 03:02:19'),
(503, 4, 29, 0, '2019-01-27 09:01:19'),
(505, 22, 29, 0, '2019-02-06 10:02:00'),
(509, 35, 29, 1, '2019-02-05 08:02:55'),
(514, 55, 29, 0, '2019-01-27 00:01:36'),
(518, 75, 29, 1, '2019-02-03 03:02:17'),
(519, 3, 30, 0, '2019-01-23 23:01:51'),
(520, 6, 30, 0, '2019-02-16 08:02:33'),
(522, 20, 30, 0, '2019-01-29 05:01:39'),
(526, 38, 30, 0, '2019-01-24 04:01:12'),
(531, 59, 30, 0, '2019-02-19 07:02:34'),
(532, 62, 30, 0, '2019-02-02 00:02:35'),
(535, 74, 30, 0, '2019-02-17 08:02:53'),
(536, 1, 31, 0, '2019-02-18 08:02:51'),
(537, 13, 31, 1, '2019-02-01 06:02:59'),
(539, 19, 31, 0, '2019-01-29 08:01:46'),
(543, 38, 31, 0, '2019-01-30 02:01:18'),
(548, 58, 31, 0, '2019-01-24 23:01:28'),
(549, 64, 31, 0, '2019-01-29 01:01:17'),
(552, 74, 31, 0, '2019-01-23 07:01:51');

-- --------------------------------------------------------

--
-- Структура таблицы `websockets_statistics_entries`
--

CREATE TABLE `websockets_statistics_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `app_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int(11) NOT NULL,
  `websocket_message_count` int(11) NOT NULL,
  `api_message_count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `youtube_access_tokens`
--

CREATE TABLE `youtube_access_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `access_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_log_created_at_index` (`created_at`),
  ADD KEY `activity_log_causer_type_causer_id_subject_id_index` (`causer_type`,`causer_id`,`subject_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Индексы таблицы `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `banners_text_unique` (`text`),
  ADD UNIQUE KEY `banners_url_unique` (`url`),
  ADD KEY `banners_ordering_active_index` (`ordering`,`active`),
  ADD KEY `banners_created_at_index` (`created_at`);

--
-- Индексы таблицы `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chats_created_at_index` (`created_at`),
  ADD KEY `chats_creator_id_status_name_index` (`creator_id`,`status`,`name`);

--
-- Индексы таблицы `chats_last_visited`
--
ALTER TABLE `chats_last_visited`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chats_last_visited_user_id_chat_id_unique` (`user_id`,`chat_id`),
  ADD KEY `chats_last_visited_chat_id_foreign` (`chat_id`),
  ADD KEY `chats_last_visited_visited_at_index` (`visited_at`);

--
-- Индексы таблицы `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_chat_id_message_type_index` (`message_type`,`user_id`),
  ADD KEY `chat_messages_updated_at_by_user_id_foreign` (`updated_at_by_user_id`),
  ADD KEY `chat_messages_user_id_chat_id_index` (`user_id`,`chat_id`),
  ADD KEY `chat_messages_chat_id_is_top_index` (`chat_id`,`is_top`),
  ADD KEY `chat_messages_created_at_index` (`created_at`);

--
-- Индексы таблицы `chat_participants`
--
ALTER TABLE `chat_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_participants_user_id_chat_id_unique` (`user_id`,`chat_id`),
  ADD KEY `chat_participants_status_user_index` (`status`,`user_id`),
  ADD KEY `chat_participants_created_at_index` (`created_at`),
  ADD KEY `chat_participants_chat_id_status_user_id_index` (`chat_id`,`status`,`user_id`);

--
-- Индексы таблицы `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_us_acceptor_id_accepted_index` (`acceptor_id`,`accepted`),
  ADD KEY `contact_us_author_email_accepted_index` (`author_email`,`accepted`);

--
-- Индексы таблицы `cron_notifications`
--
ALTER TABLE `cron_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cron_notifications_cron_type_cron_object_id_index` (`cron_type`,`cron_object_id`);

--
-- Индексы таблицы `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `downloads_title_unique` (`title`),
  ADD UNIQUE KEY `downloads_file_unique` (`file`),
  ADD KEY `downloads_created_at_index` (`created_at`),
  ADD KEY `downloads_mime_file_index` (`mime`,`file`),
  ADD KEY `downloads_creator_id_title_index` (`creator_id`,`title`);

--
-- Индексы таблицы `external_news_importing`
--
ALTER TABLE `external_news_importing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_news_importing_title_unique` (`title`),
  ADD UNIQUE KEY `external_news_importing_url_unique` (`url`),
  ADD KEY `external_news_importing_status_index` (`status`),
  ADD KEY `external_news_importing_created_at_index` (`created_at`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groups_name_unique` (`name`),
  ADD UNIQUE KEY `groups_description_unique` (`description`),
  ADD KEY `groups_created_at_index` (`created_at`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `page_contents`
--
ALTER TABLE `page_contents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_contents_slug_unique` (`slug`),
  ADD KEY `page_contents_created_at_index` (`created_at`),
  ADD KEY `page_contents_is_homepage_published_index` (`is_homepage`,`published`),
  ADD KEY `page_contents_creator_id_published_title_index` (`creator_id`,`published`,`title`),
  ADD KEY `page_contents_page_type_published_title_index` (`page_type`,`published`,`title`),
  ADD KEY `page_contents_source_type_published_index` (`source_type`,`published`);

--
-- Индексы таблицы `page_content_images`
--
ALTER TABLE `page_content_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_contents_page_content_id_filename_unique` (`page_content_id`,`filename`),
  ADD KEY `page_contents_page_content_id_is_main` (`page_content_id`,`is_main`),
  ADD KEY `page_contents_page_content_id_is_video_filename` (`page_content_id`,`is_video`,`filename`),
  ADD KEY `page_content_message_documents_created_at_index` (`created_at`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `quiz_quality_results`
--
ALTER TABLE `quiz_quality_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quiz_quality_results_vote_id_id_user_id_index` (`vote_id`,`user_id`),
  ADD KEY `quiz_quality_results_user_id_foreign` (`user_id`),
  ADD KEY `quiz_quality_results_quiz_quality_id_vote_id_user_id_index` (`quiz_quality_id`,`vote_id`,`user_id`);

--
-- Индексы таблицы `search_results`
--
ALTER TABLE `search_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_results_user_id_foreign` (`user_id`),
  ADD KEY `search_results_text_user_id_index` (`text`,`user_id`),
  ADD KEY `search_results_text_found_results_index` (`text`,`found_results`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_name_unique` (`name`),
  ADD KEY `settings_created_at_index` (`created_at`);

--
-- Индексы таблицы `settings_text`
--
ALTER TABLE `settings_text`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_text_name_unique` (`name`),
  ADD KEY `settings_text_created_at_index` (`created_at`);

--
-- Индексы таблицы `site_subscriptions`
--
ALTER TABLE `site_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_subscriptions_name_unique` (`name`),
  ADD KEY `site_subscriptions_vote_category_id_foreign` (`vote_category_id`),
  ADD KEY `site_subscriptions_created_at_index` (`created_at`),
  ADD KEY `site_subscriptions_mailchimp_list_name_index` (`mailchimp_list_name`),
  ADD KEY `site_subscriptions_mailchimp_list_id_index` (`mailchimp_list_id`),
  ADD KEY `site_subscriptions_active_name_index` (`active`,`name`);

--
-- Индексы таблицы `taggables`
--
ALTER TABLE `taggables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taggables_tag_id_foreign` (`tag_id`);

--
-- Индексы таблицы `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tag_details`
--
ALTER TABLE `tag_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag_details_tag_id_unique` (`tag_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_status_index` (`status`),
  ADD KEY `users_created_at_index` (`created_at`),
  ADD KEY `users_provider_name_username_index` (`provider_name`,`username`);

--
-- Индексы таблицы `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_groups_user_id_group_id_unique` (`user_id`,`group_id`),
  ADD KEY `users_groups_group_id_foreign` (`group_id`),
  ADD KEY `users_groups_created_at_index` (`created_at`);

--
-- Индексы таблицы `users_site_subscriptions`
--
ALTER TABLE `users_site_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_site_subscriptions_user_id_site_subscription_id_unique` (`user_id`,`site_subscription_id`),
  ADD KEY `users_site_subscriptions_site_subscription_id_foreign` (`site_subscription_id`),
  ADD KEY `users_site_subscriptions_created_at_index` (`created_at`),
  ADD KEY `users_site_subscriptions_mailchimp_subscription_id_index` (`mailchimp_subscription_id`);

--
-- Индексы таблицы `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `votes_name_unique` (`name`),
  ADD UNIQUE KEY `votes_slug_unique` (`slug`),
  ADD KEY `votes_created_at_index` (`created_at`),
  ADD KEY `votes_is_quiz_status_index` (`is_quiz`,`status`),
  ADD KEY `votes_ordering_status_index` (`ordering`,`status`),
  ADD KEY `votes_is_homepage_status_index` (`is_homepage`,`status`),
  ADD KEY `votes_creator_id_status_name_index` (`creator_id`,`status`,`name`),
  ADD KEY `votes_vote_category_id_status_name_index` (`vote_category_id`,`status`,`name`);

--
-- Индексы таблицы `vote_categories`
--
ALTER TABLE `vote_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vote_categories_name_unique` (`name`),
  ADD UNIQUE KEY `vote_categories_slug_unique` (`slug`),
  ADD KEY `vote_categories_created_at_index` (`created_at`),
  ADD KEY `vote_categories_active_name_index` (`active`,`name`);

--
-- Индексы таблицы `vote_items`
--
ALTER TABLE `vote_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vote_items_vote_id_name_unique` (`vote_id`,`name`),
  ADD KEY `vote_items_vote_id_ordering_index` (`vote_id`,`ordering`),
  ADD KEY `vote_items_vote_id_is_correct_index` (`vote_id`,`is_correct`),
  ADD KEY `vote_items_created_at_index` (`created_at`);

--
-- Индексы таблицы `vote_item_users_result`
--
ALTER TABLE `vote_item_users_result`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vote_item_users_result_vote_item_id_user_id_index` (`vote_item_id`,`user_id`),
  ADD KEY `vote_item_users_result_user_id_foreign` (`user_id`),
  ADD KEY `vote_item_users_result_vote_item_id_is_correct_user_id_index` (`vote_item_id`,`is_correct`,`user_id`);

--
-- Индексы таблицы `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `youtube_access_tokens`
--
ALTER TABLE `youtube_access_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT для таблицы `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `chats_last_visited`
--
ALTER TABLE `chats_last_visited`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `chat_participants`
--
ALTER TABLE `chat_participants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `cron_notifications`
--
ALTER TABLE `cron_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `external_news_importing`
--
ALTER TABLE `external_news_importing`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT для таблицы `page_contents`
--
ALTER TABLE `page_contents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;
--
-- AUTO_INCREMENT для таблицы `page_content_images`
--
ALTER TABLE `page_content_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `quiz_quality_results`
--
ALTER TABLE `quiz_quality_results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=590;
--
-- AUTO_INCREMENT для таблицы `search_results`
--
ALTER TABLE `search_results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT для таблицы `settings_text`
--
ALTER TABLE `settings_text`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `site_subscriptions`
--
ALTER TABLE `site_subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `taggables`
--
ALTER TABLE `taggables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT для таблицы `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT для таблицы `tag_details`
--
ALTER TABLE `tag_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT для таблицы `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT для таблицы `users_site_subscriptions`
--
ALTER TABLE `users_site_subscriptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT для таблицы `vote_categories`
--
ALTER TABLE `vote_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `vote_items`
--
ALTER TABLE `vote_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT для таблицы `vote_item_users_result`
--
ALTER TABLE `vote_item_users_result`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=553;
--
-- AUTO_INCREMENT для таблицы `websockets_statistics_entries`
--
ALTER TABLE `websockets_statistics_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `youtube_access_tokens`
--
ALTER TABLE `youtube_access_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `chats_last_visited`
--
ALTER TABLE `chats_last_visited`
  ADD CONSTRAINT `chats_last_visited_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `chats_last_visited_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `chat_messages_updated_at_by_user_id_foreign` FOREIGN KEY (`updated_at_by_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `chat_participants`
--
ALTER TABLE `chat_participants`
  ADD CONSTRAINT `chat_participants_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `chat_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `contact_us`
--
ALTER TABLE `contact_us`
  ADD CONSTRAINT `contact_us_acceptor_id_foreign` FOREIGN KEY (`acceptor_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `downloads`
--
ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `page_contents`
--
ALTER TABLE `page_contents`
  ADD CONSTRAINT `page_contents_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `page_content_images`
--
ALTER TABLE `page_content_images`
  ADD CONSTRAINT `page_content_images_page_content_id_foreign` FOREIGN KEY (`page_content_id`) REFERENCES `page_contents` (`id`);

--
-- Ограничения внешнего ключа таблицы `quiz_quality_results`
--
ALTER TABLE `quiz_quality_results`
  ADD CONSTRAINT `quiz_quality_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quiz_quality_results_vote_id_foreign` FOREIGN KEY (`vote_id`) REFERENCES `votes` (`id`);

--
-- Ограничения внешнего ключа таблицы `search_results`
--
ALTER TABLE `search_results`
  ADD CONSTRAINT `search_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `site_subscriptions`
--
ALTER TABLE `site_subscriptions`
  ADD CONSTRAINT `site_subscriptions_vote_category_id_foreign` FOREIGN KEY (`vote_category_id`) REFERENCES `vote_categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `taggables`
--
ALTER TABLE `taggables`
  ADD CONSTRAINT `taggables_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tag_details`
--
ALTER TABLE `tag_details`
  ADD CONSTRAINT `tag_details_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `users_groups_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `users_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `users_site_subscriptions`
--
ALTER TABLE `users_site_subscriptions`
  ADD CONSTRAINT `users_site_subscriptions_site_subscription_id_foreign` FOREIGN KEY (`site_subscription_id`) REFERENCES `site_subscriptions` (`id`),
  ADD CONSTRAINT `users_site_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `votes_vote_category_id_foreign` FOREIGN KEY (`vote_category_id`) REFERENCES `vote_categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `vote_items`
--
ALTER TABLE `vote_items`
  ADD CONSTRAINT `vote_items_vote_id_foreign` FOREIGN KEY (`vote_id`) REFERENCES `votes` (`id`);

--
-- Ограничения внешнего ключа таблицы `vote_item_users_result`
--
ALTER TABLE `vote_item_users_result`
  ADD CONSTRAINT `vote_item_users_result_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `vote_item_users_result_vote_item_id_foreign` FOREIGN KEY (`vote_item_id`) REFERENCES `vote_items` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
