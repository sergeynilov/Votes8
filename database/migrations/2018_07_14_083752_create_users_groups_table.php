<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Traits\funcsTrait;
use App\User;
use App\Group;

class CreateUsersGroupsTable extends Migration
{
    use funcsTrait;
    private $users_tb;
    private $groups_tb;
    private $users_groups_tb;
    public function __construct()
    {
        $this->users_tb= with(new User)->getTable();
        $this->groups_tb= with(new Group)->getTable();
        $this->users_groups_tb= with(new Group)->getTable();
    }
    public function up()
    {
        Schema::create($this->groups_tb, function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 20)->unique();
            $table->string('description', 100)->unique();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at'], 'groups_created_at_index');
        });

        \DB::table($this->groups_tb)->insert([
            'id'          => USER_ACCESS_ADMIN,  # 1
            'name'        => 'Admin',
            'description' => 'Administrator has access to all parts of backend.',
        ]);
        \DB::table($this->groups_tb)->insert([
            'id'          => USER_ACCESS_MANAGER, # 2
            'name'        => 'Manager',
            'description' => 'Manager has access to all votes, page contents, contact us of backend...',
        ]);

        \DB::table($this->groups_tb)->insert([
            'id'          => USER_ACCESS_USER, # 3
            'name'        => 'User',
            'description' => 'User has access to all pages of frontend and his profile...',
        ]);


        Schema::create('users_groups', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on($this->users_tb)->onDelete('CASCADE');

            $table->smallInteger('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on($this->groups_tb)->onDelete('CASCADE');

            $table->timestamp('created_at')->useCurrent();
            $table->index(['created_at'], 'users_groups_created_at_index');

            $table->unique(['user_id', 'group_id'], 'users_groups_user_id_group_id_unique');
        });


        $faker = Faker\Factory::create();

        for($i = 0; $i < 25; $i++) {
//            $faker_name= $faker->name;
            //    public function pregSplit(string $splitter, string $string_items, bool $skip_empty = true, $to_lower= false ) : array

            
            $title= '';
            $sex= rand(1,2);
            if ( $sex == 1 ) {
                $first_name = $faker->firstNameMale;

//                $title_rand= rand(1,4);
//                $title= ($title_rand == 1) ? $faker->titleMale . ' ' : '';
            } else {
                $first_name = $faker->firstNameFemale;

//                $title_rand= rand(1,4);
//                $title= ($title_rand == 1) ? $faker->titleFemale . ' ' : '';
            }

            $last_name= $faker->lastName;
            $faker_name= strtolower($first_name . '_' . $last_name);
            $faker_email= $faker->email;
            $arr= $this->pregSplit("/@/", $faker_email );
            if (count($arr)==2) {
                $faker_email= strtolower($first_name . '.' . $last_name).'@'.$arr[1];
            }

            if ( empty($title.$faker_name) ) continue;
            $newUser= App\User::create([
                'id' => 6+$i,
                'username'     => $title.$faker_name,
                'first_name'   => $first_name,
                'last_name'    => $last_name,
                'email'        => $faker_email,
                'status'       => 'A',
                'verified'     => 1,
                'password'     => bcrypt('111111'),
                'activated_at' => now(),
            ]);

            \DB::table('users_groups')->insert([
                'user_id'=> $newUser->id,
                'group_id'=> USER_ACCESS_USER
            ]);

        }

        Artisan::call('db:seed', array('--class' => 'usersGroupsInitData'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->groups_tb, function ($table) {
            $table->dropIndex('groups_created_at_index');
        });
        Schema::table('users_groups', function ($table) {
            $table->dropIndex('users_groups_created_at_index');
        });
        Schema::dropIfExists('users_groups');
        Schema::dropIfExists($this->groups_tb);

    }
}
