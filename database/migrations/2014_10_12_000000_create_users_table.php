<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class CreateUsersTable extends Migration
{
    private $users_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
    }
    public function up()
    {
        Schema::create($this->users_tb, function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',100)->unique();
            $table->string('email', 100)->unique();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->enum('status', ['N', 'A', 'I'])->default("N")->comment( ' N => New(Waiting activation), A=>Active, I=>Inactive' );
            $table->boolean('verified')->default(false);
            $table->string('verification_token')->nullable();

            $table->string('first_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->string('phone',50)->nullable();
            $table->string('website',50)->nullable();

            $table->dateTime('activated_at')->nullable();
            $table->string('avatar',100)->nullable();
            $table->string('full_photo',100)->nullable();
            $table->mediumText('notes')->nullable();
            $table->enum('sex', ['M', 'F'])->nullable()->comment( ' M => Male, F=>Female' );

            $table->string('address_line1',255)->nullable();
            $table->string('address_city',255)->nullable();
            $table->string('address_state',5)->nullable();
            $table->string('address_postal_code',5)->nullable();
            $table->string('address_country_code',2)->nullable();

            $table->string('shipping_address_line1',255)->nullable();
            $table->string('shipping_address_city',255)->nullable();
            $table->string('shipping_address_state',5)->nullable();
            $table->string('shipping_address_postal_code',5)->nullable();
            $table->string('shipping_address_country_code',2)->nullable();

            $table->index(['status'], 'users_status_index');
            $table->index(['shipping_address_country_code', 'shipping_address_postal_code'], 'users_shipping_country_code_postal_code_index');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->index(['created_at'], 'users_created_at_index');
        });

        \DB::table($this->users_tb)->insert([
            'id' => 1,
            'username'   => 'Shawn Hadray',
//            'email'      => 'ShawnHadray@makevote.site.com',
            'email'      => 'nilovsergey@yahoo.com',
            'status'     => 'N',
            'verified'   => 1,
            'password'   => bcrypt('111111'),
            'first_name' => 'Shawn',
            'last_name'  => 'Hadray',
            'phone'      => '987-7543-916',
            'website'    => 'shawn-hadray-make-vote.site.com',
            'avatar'     => 'shawn_hadray.jpg',
            'full_photo' => 'shawn_hadray_full.jpg',
            'activated_at' => now(),
            'notes'      => 'Some notes on <strong>Shawn Hadray</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'sex'        =>'M',
            'address_line1'               =>'First Street, apt 12b',
            'address_city'                =>'Saratoga',
            'address_state'               =>'CA',
            'address_postal_code'         =>'95070',
            'address_country_code'        =>'US',
            'shipping_address_line1'      =>'First Street, ap 12b',
            'shipping_address_city'       =>'Saratoga',
            'shipping_address_state'      =>'CA',
            'shipping_address_postal_code'  =>'95070',
            'shipping_address_country_code' =>'US',
        ]);

        \DB::table($this->users_tb)->insert([
            'id' => 2,
            'username'   => 'Lisa Longred',
//            'email'      => 'LisaLongred@makevote.site.com',
            'email'      => 'nilovsergey@i.ua',
            'status'     => 'A',
            'verified'   => 1,
            'password'   => bcrypt('111111'),
            'first_name' => 'Lisa',
            'last_name'  => 'Longred',
            'phone'      => '987-7543-916',
            'website'    => 'lisalongred-make-vote.site.com',
            'avatar'     => 'LisaLongred_avatar.jpg',
            'full_photo' => 'LisaLongred.jpg',
            'activated_at' => now(),
            'notes'      => 'Some notes on <strong>Lisa Longred</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. 
            lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'sex'        =>'F',
            'address_line1'               =>'Third Big Street, app 212',
            'address_city'                =>'Saratoga',
            'address_state'               =>'CA',
            'address_postal_code'         =>'95070',
            'address_country_code'        =>'US',
            'shipping_address_line1'      =>'Third Big Street, app 212',
            'shipping_address_city'       =>'Saratoga',
            'shipping_address_state'      =>'CA',
            'shipping_address_postal_code'  =>'95070',
            'shipping_address_country_code' =>'US',
        ]);

        \DB::table($this->users_tb)->insert([
            'id' => 3,
            'username'   => 'Tony Black',
            'email'      => 'nilovsergey@meta.ua',
//            'email'      => 'TonyBlack@makevote.site.com',
            'status'     => 'A',
            'verified'   => 1,
            'password'   => bcrypt('111111'),
            'first_name' => 'Tony',
            'last_name'  => 'Black',
            'phone'      => '247-159-0976',
            'website'    => 'tony-black-make-vote.site.com',
            'avatar'     => 'tony_black.jpg',
            'full_photo' => 'tony_black_full_photo.jpg',
            'activated_at' => now(),
            'notes'      => 'Some notes on <strong>Tony Black</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. 
            lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'sex'        =>'M',
            'address_line1'               =>'Third Big Street, app 212',
            'address_city'                =>'Saratoga',
            'address_state'               =>'CA',
            'address_postal_code'         =>'95070',
            'address_country_code'        =>'US',
            'shipping_address_line1'      =>'Third Big Street, app 212',
            'shipping_address_city'       =>'Saratoga',
            'shipping_address_state'      =>'CA',
            'shipping_address_postal_code'  =>'95070',
            'shipping_address_country_code' =>'US',
        ]);
        \DB::table($this->users_tb)->insert([
            'id' => 4,
            'username' => 'Martha Lang',
//            'email' => 'MarthaLang@makevote.site.com',
            'email' => 'nilovsergey@ukr.net',
            'status'=> 'I',
            'verified'   => 1,
            'password'   => bcrypt('111111'),
            'first_name' => 'Martha',
            'last_name'  => 'Lang',
            'phone'      => '247-541-7172',
            'website'    => 'martha-lang-make-vote.site.com',
            'avatar'     => 'Martha_lang_avatar.jpg',
            'full_photo' => 'Martha_lang.jpg',
            'activated_at' => now(),
            'notes'      => 'Some notes on <strong>Martha Lang</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. 
            lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'sex'        =>'F',
            'address_line1'               =>'First Street, apt 12b',
            'address_city'                =>'Saratoga',
            'address_state'               =>'CA',
            'address_postal_code'         =>'95070',
            'address_country_code'        =>'US',
            'shipping_address_line1'      =>'First Street, ap 12b',
            'shipping_address_city'       =>'Saratoga',
            'shipping_address_state'      =>'CA',
            'shipping_address_postal_code'  =>'95070',
            'shipping_address_country_code' =>'US',
        ]);

        \DB::table($this->users_tb)->insert([
            'id' => 5,
            'username'   => 'Admin',
            'email'      => 'admin@mail.com',
            'status'     => 'A',
            'verified'   => 1,
            'password'   => bcrypt('111111'),
            'first_name' => 'Rad',
            'last_name'  => 'Soang',
            'phone'      => '247-541-7172',
            'website'    => 'rad-soang-make_vote.site.com',
            'avatar'     => 'rad_soang_avatar.jpg',
            'full_photo' => 'rad_soang.jpg',
            'activated_at' => now(),
            'notes'      => 'Some notes on <strong>Rad Soang</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. 
            lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'sex'        =>'M',
            'address_line1'               =>'Third Big Street, app 212',
            'address_city'                =>'Saratoga',
            'address_state'               =>'CA',
            'address_postal_code'         =>'95070',
            'address_country_code'        =>'US',
            'shipping_address_line1'      =>'Third Big Street, app 212',
            'shipping_address_city'       =>'Saratoga',
            'shipping_address_state'      =>'CA',
            'shipping_address_postal_code'  =>'95070',
            'shipping_address_country_code' =>'US',
        ]);

        \DB::table($this->users_tb)->insert([
            'id' => 6,
            'username'   => 'votes_demo',
            'email'      => 'votes_demo@votes.com',
            'status'     => 'A',
            'verified'   => 1,
            'password'   => bcrypt('654321'),
            'first_name' => 'Blacky',
            'last_name'  => 'Boak',
            'phone'      => '284-921-7970',
            'website'    => 'votes-demo-make-vote.site.com',
            'avatar'     => 'blacky_boak_avatar.jpeg',
            'full_photo' => 'blacky_boak.jpeg',
            'activated_at' => now(),
            'notes'      => 'Some notes on <strong>Blacky Boak</strong>, who lorem <i>ipsum dolor sit</i> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum. 
            lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',
            'sex'        =>'M',
            'address_line1'               =>'First Street, apt 12b',
            'address_city'                =>'Saratoga',
            'address_state'               =>'CA',
            'address_postal_code'         =>'95070',
            'address_country_code'        =>'US',
            'shipping_address_line1'      =>'First Street, ap 12b',
            'shipping_address_city'       =>'Saratoga',
            'shipping_address_state'      =>'CA',
            'shipping_address_postal_code'  =>'95070',
            'shipping_address_country_code' =>'US',
        ]);

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->users_tb);
    }
}
