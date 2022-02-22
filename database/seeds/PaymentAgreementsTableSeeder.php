<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentAgreementsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('payment_agreements')->delete();

/*        \DB::table('payment_agreements')->insert(array (
            0 =>
            array (
                'id' => 1,
                'user_id' => 6,
                'payment_type' => 'PS',
                'state' => 'Pending',
                'payment_agreement_id' => 'I-AF0P6DF9Y3BE',
                'description' => 'Base Agreement on Select & Vote lorem ipsum dolor sit amet, consectetur adipiscing elit',
                'start_date' => '2019-08-17T07:00:00Z',
                'created_at' => '2019-08-17 14:48:37',
            ),
            1 =>
            array (
                'id' => 2,
                'user_id' => 6,
                'payment_type' => 'PS',
                'state' => 'Pending',
                'payment_agreement_id' => 'I-PCFP6BKK8LG5',
                'description' => 'Base Agreement on Select & Vote lorem ipsum dolor sit amet, consectetur adipiscing elit',
                'start_date' => '2019-08-17T07:00:00Z',
                'created_at' => '2019-08-17 14:55:03',
            ),
            2 =>
            array (
                'id' => 3,
                'user_id' => 6,
                'payment_type' => 'PS',
                'state' => 'Pending',
                'payment_agreement_id' => 'I-V9YX0H64ELL8',
                'description' => 'Base Agreement on Select & Vote lorem ipsum dolor sit amet, consectetur adipiscing elit',
                'start_date' => '2019-08-17T07:00:00Z',
                'created_at' => '2019-08-17 14:55:45',
            ),
        ));
        */

    }
}
