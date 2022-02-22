<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('payment_items')->delete();

        \DB::table('payment_items')->insert(array (
            0 =>
            array (
                'id' => 1,
                'payment_id' => 1,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-07-20 13:39:34',
            ),
            1 =>
            array (
                'id' => 2,
                'payment_id' => 2,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-07-20 13:45:33',
            ),
            2 =>
            array (
                'id' => 3,
                'payment_id' => 2,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-07-20 13:45:33',
            ),
            3 =>
            array (
                'id' => 4,
                'payment_id' => 3,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-07-21 10:06:47',
            ),
            4 =>
            array (
                'id' => 5,
                'payment_id' => 3,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-07-21 10:06:47',
            ),
            5 =>
            array (
                'id' => 6,
                'payment_id' => 4,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-21 10:32:07',
            ),
            6 =>
            array (
                'id' => 7,
                'payment_id' => 4,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-07-21 10:32:07',
            ),
            7 =>
            array (
                'id' => 8,
                'payment_id' => 4,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-07-21 10:32:07',
            ),
            8 =>
            array (
                'id' => 9,
                'payment_id' => 5,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-21 11:06:16',
            ),
            9 =>
            array (
                'id' => 10,
                'payment_id' => 6,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-07-21 13:32:38',
            ),
            10 =>
            array (
                'id' => 11,
                'payment_id' => 7,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-21 13:48:38',
            ),
            11 =>
            array (
                'id' => 12,
                'payment_id' => 8,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-07-21 13:49:49',
            ),
            12 =>
            array (
                'id' => 13,
                'payment_id' => 8,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-07-21 13:49:49',
            ),
            13 =>
            array (
                'id' => 14,
                'payment_id' => 9,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-21 15:10:13',
            ),
            14 =>
            array (
                'id' => 15,
                'payment_id' => 9,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-07-21 15:10:13',
            ),
            15 =>
            array (
                'id' => 16,
                'payment_id' => 10,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-21 15:12:52',
            ),
            16 =>
            array (
                'id' => 17,
                'payment_id' => 11,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-07-21 15:38:49',
            ),
            17 =>
            array (
                'id' => 18,
                'payment_id' => 12,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-07-22 09:33:57',
            ),
            18 =>
            array (
                'id' => 19,
                'payment_id' => 13,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-07-22 09:38:36',
            ),
            19 =>
            array (
                'id' => 20,
                'payment_id' => 13,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-07-22 09:38:36',
            ),
            20 =>
            array (
                'id' => 21,
                'payment_id' => 14,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-07-22 14:02:07',
            ),
            21 =>
            array (
                'id' => 22,
                'payment_id' => 14,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-07-22 14:02:07',
            ),
            22 =>
            array (
                'id' => 23,
                'payment_id' => 15,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-22 14:05:47',
            ),
            23 =>
            array (
                'id' => 24,
                'payment_id' => 16,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-22 15:08:05',
            ),
            24 =>
            array (
                'id' => 25,
                'payment_id' => 16,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-07-22 15:08:05',
            ),
            25 =>
            array (
                'id' => 26,
                'payment_id' => 17,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-22 15:23:42',
            ),
            26 =>
            array (
                'id' => 27,
                'payment_id' => 17,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-22 15:23:42',
            ),
            27 =>
            array (
                'id' => 28,
                'payment_id' => 18,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-22 15:25:10',
            ),
            28 =>
            array (
                'id' => 29,
                'payment_id' => 19,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-22 15:28:32',
            ),
            29 =>
            array (
                'id' => 30,
                'payment_id' => 19,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-07-22 15:28:32',
            ),
            30 =>
            array (
                'id' => 31,
                'payment_id' => 20,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-23 13:58:15',
            ),
            31 =>
            array (
                'id' => 32,
                'payment_id' => 20,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-23 13:58:15',
            ),
            32 =>
            array (
                'id' => 33,
                'payment_id' => 21,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-23 15:16:13',
            ),
            33 =>
            array (
                'id' => 34,
                'payment_id' => 21,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-23 15:16:13',
            ),
            34 =>
            array (
                'id' => 35,
                'payment_id' => 22,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-24 13:05:13',
            ),
            35 =>
            array (
                'id' => 36,
                'payment_id' => 22,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-24 13:05:13',
            ),
            36 =>
            array (
                'id' => 37,
                'payment_id' => 23,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-24 13:30:31',
            ),
            37 =>
            array (
                'id' => 38,
                'payment_id' => 24,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-24 14:55:53',
            ),
            38 =>
            array (
                'id' => 39,
                'payment_id' => 24,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-07-24 14:55:53',
            ),
            39 =>
            array (
                'id' => 40,
                'payment_id' => 24,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-24 14:55:53',
            ),
            40 =>
            array (
                'id' => 41,
                'payment_id' => 25,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-07-24 15:03:41',
            ),
            41 =>
            array (
                'id' => 42,
                'payment_id' => 25,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-24 15:03:41',
            ),
            42 =>
            array (
                'id' => 43,
                'payment_id' => 26,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-07-24 15:18:34',
            ),
            43 =>
            array (
                'id' => 44,
                'payment_id' => 26,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 3,
                'price' => '0.80',
                'created_at' => '2019-07-24 15:18:34',
            ),
            44 =>
            array (
                'id' => 45,
                'payment_id' => 27,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-08-09 14:49:35',
            ),
            45 =>
            array (
                'id' => 46,
                'payment_id' => 27,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-08-09 14:49:35',
            ),
            46 =>
            array (
                'id' => 47,
                'payment_id' => 28,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-08-09 14:53:02',
            ),
            47 =>
            array (
                'id' => 48,
                'payment_id' => 28,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-08-09 14:53:02',
            ),
            48 =>
            array (
                'id' => 49,
                'payment_id' => 29,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-08-09 14:53:53',
            ),
            49 =>
            array (
                'id' => 50,
                'payment_id' => 30,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-08-09 14:55:00',
            ),
            50 =>
            array (
                'id' => 51,
                'payment_id' => 31,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-08-09 14:57:36',
            ),
            51 =>
            array (
                'id' => 52,
                'payment_id' => 32,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-08-09 15:18:11',
            ),
            52 =>
            array (
                'id' => 53,
                'payment_id' => 32,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-08-09 15:18:11',
            ),
            53 =>
            array (
                'id' => 54,
                'payment_id' => 33,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-08-09 15:25:46',
            ),
            54 =>
            array (
                'id' => 55,
                'payment_id' => 33,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-08-09 15:25:46',
            ),
            55 =>
            array (
                'id' => 56,
                'payment_id' => 34,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-08-09 15:26:03',
            ),
            56 =>
            array (
                'id' => 57,
                'payment_id' => 34,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-08-09 15:26:03',
            ),
            57 =>
            array (
                'id' => 58,
                'payment_id' => 35,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-08-09 15:26:37',
            ),
            58 =>
            array (
                'id' => 59,
                'payment_id' => 36,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-08-09 15:27:31',
            ),
            59 =>
            array (
                'id' => 60,
                'payment_id' => 36,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-08-09 15:27:31',
            ),
            60 =>
            array (
                'id' => 61,
                'payment_id' => 37,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-10-17 12:56:58',
            ),
            61 =>
            array (
                'id' => 62,
                'payment_id' => 37,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-10-17 12:56:58',
            ),
            62 =>
            array (
                'id' => 63,
                'payment_id' => 38,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-10-17 12:59:48',
            ),
            63 =>
            array (
                'id' => 64,
                'payment_id' => 38,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-10-17 12:59:48',
            ),
            64 =>
            array (
                'id' => 65,
                'payment_id' => 38,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 4,
                'price' => '1.50',
                'created_at' => '2019-10-17 12:59:48',
            ),
            65 =>
            array (
                'id' => 66,
                'payment_id' => 39,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 1,
                'price' => '1.20',
                'created_at' => '2019-10-17 13:00:52',
            ),
            66 =>
            array (
                'id' => 67,
                'payment_id' => 39,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 2,
                'price' => '1.00',
                'created_at' => '2019-10-17 13:00:52',
            ),
            67 =>
            array (
                'id' => 68,
                'payment_id' => 39,
                'item_type' => 'D',
                'quantity' => 1,
                'item_id' => 5,
                'price' => '1.10',
                'created_at' => '2019-10-17 13:00:52',
            ),
        ));


    }
}
