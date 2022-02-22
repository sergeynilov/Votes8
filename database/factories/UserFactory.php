<?php

use Faker\Generator as Faker;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use App\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/* CREATE TABLE `vt2_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_state` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_postal_code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_country_code` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_state` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_postal_code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_country_code` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_status_index` (`status`),
  KEY `users_shipping_country_code_postal_code_index` (`shipping_address_country_code`,`shipping_address_postal_code`),
  KEY `users_created_at_index` (`created_at`),
  KEY `users_provider_name_username_index` (`provider_name`,`username`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 */
$factory->define(App\User::class, function (Faker $faker) {

    $username= 'Username ' . $faker->unique()->word;
    
    return [
        'username' => $username,
        'email' => $faker->unique()->safeEmail,
        'status' => 'A',
        'verified' => true,
        'first_name' => $faker->name,
        'last_name' => $faker->name,
//        'phone' => $faker->phoneNumber,
//        'website' => $faker->url,
        'activated_at' => now(),


//        'meta_description' => $faker->text,
//        'meta_keywords' => $faker->words(4),
    ];
});