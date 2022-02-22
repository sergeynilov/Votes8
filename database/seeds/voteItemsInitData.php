<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\VoteItem;

class voteItemsInitData extends Seeder
{
    private $vote_items_tb;
    public function __construct()
    {
        $this->vote_items_tb= with(new VoteItem)->getTable();
    }

    public function run()
    {

        \DB::table($this->vote_items_tb)->insert([
            'id'            => 1,
            'vote_id'       => 1,
            'name'          => 'To be...',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'to_be.png',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 2,
            'vote_id'       => 1,
            'name'          => 'Not to be...',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => null,
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 3,
            'vote_id'       => 1,
            'name'          => 'That is really a big question...',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => null,
        ]);


        // 2=Who Framed Roger Rabbit ?
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 4,
            'vote_id'       => 2,
            'name'          => 'Cloverleaf Industry Company',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => null,
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 5,
            'vote_id'       => 2,
            'name'          => 'Judge Doom',
            'ordering'      => 2,
            'is_correct'    => true,
            'image'         => 'Judge_Doom.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 6,
            'vote_id'       => 2,
            'name'          => 'Marvin Acme',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'Marvin_acme.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 7,
            'vote_id'       => 2,
            'name'          => 'Eddie Valiant',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'eddie_valiant.jpg',
        ]);


        // 3=How many weeks in a year
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 8,
            'vote_id'       => 3,
            'name'          => '42',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => null,
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 9,
            'vote_id'       => 3,
            'name'          => '50',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => null,
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 10,
            'vote_id'       => 3,
            'name'          => '52',
            'ordering'      => 3,
            'is_correct'    => true,
            'image'         => null,
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 11,
            'vote_id'       => 3,
            'name'          => '100',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => null,
        ]);


        // 4 = Which fictional city is the home of Batman ?
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 12,
            'vote_id'       => 4,
            'name'          => 'London',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'london.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 13,
            'vote_id'       => 4,
            'name'          => 'Gotham City',
            'ordering'      => 2,
            'is_correct'    => true,
            'image'         => 'Gotham_City.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 14,
            'vote_id'       => 4,
            'name'          => 'Rome',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'rome-colosseum-story.jpg',
        ]);


        // 5 = Who was known as the Maid of Orleans ?
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 15,
            'vote_id'       => 5,
            'name'          => 'Joan of Arc',
            'ordering'      => 1,
            'is_correct'    => true,
            'image'         => 'joan-of-arc.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 16,
            'vote_id'       => 5,
            'name'          => 'Margaret Thatcher',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => 'Margaret-Thatcher.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 17,
            'vote_id'       => 5,
            'name'          => 'Madeleine Albright',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'MadeleineAlbright.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 18,
            'vote_id'       => 5,
            'name'          => 'Condoleezza Rice',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'CondoleezzaRice.jpg',
        ]);


        // 6 => 'Do you like design of this site ?'
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 19,
            'vote_id'       => 6,
            'name'          => 'Very cool',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => '',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 20,
            'vote_id'       => 6,
            'name'          => 'Looking good',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => '',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 21,
            'vote_id'       => 6,
            'name'          => 'So-so',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => '',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 22,
            'vote_id'       => 6,
            'name'          => 'Poor',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => '',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 23,
            'vote_id'       => 6,
            'name'          => 'Very Disgusting',
            'ordering'      => 5,
            'is_correct'    => false,
            'image'         => '',
        ]);


        // 7 = Which Roman emperor supposedly fiddled while Rome burned ?
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 24,
            'vote_id'       => 7,
            'name'          => 'Augustus',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'emperor_augustus.jpg',
        ]);

        \DB::table($this->vote_items_tb)->insert([
            'id'            => 25,
            'vote_id'       => 7,
            'name'          => 'Nero',
            'ordering'      => 2,
            'is_correct'    => true,
            'image'         => 'nero.jpg',
        ]);

        \DB::table($this->vote_items_tb)->insert([
            'id'            => 26,
            'vote_id'       => 7,
            'name'          => 'Vespasian',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'vespasian.jpeg',
        ]);

        \DB::table($this->vote_items_tb)->insert([
            'id'            => 27,
            'vote_id'       => 7,
            'name'          => 'Tiberius',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'tiberius.jpg',
        ]);

        // 8=Which crime-fighting cartoon dog has the initals “S.D.” on his collar ?
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 28,
            'vote_id'       => 8,
            'name'          => 'Underdog',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'underdog.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 29,
            'vote_id'       => 8,
            'name'          => 'Scooby Doo',
            'ordering'      => 2,
            'is_correct'    => true,
            'image'         => 'ScoobyDoo.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 30,
            'vote_id'       => 8,
            'name'          => 'Snoopy',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'Snoopy.jpg',
        ]);


        //       9 = 'name'             => 'Traditionally, how many Wonders of the World are there ?',
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 31,
            'vote_id'       => 9,
            'name'          => '12',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => '',
        ]);

        \DB::table($this->vote_items_tb)->insert([
            'id'            => 32,
            'vote_id'       => 9,
            'name'          => '5',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => '',
        ]);

        \DB::table($this->vote_items_tb)->insert([
            'id'            => 33,
            'vote_id'       => 9,
            'name'          => '7',
            'ordering'      => 3,
            'is_correct'    => true,
            'image'         => '',
        ]);

        \DB::table($this->vote_items_tb)->insert([
            'id'            => 34,
            'vote_id'       => 9,
            'name'          => '33',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => '',
        ]);


        // 10 = 'What is the name of the fairy in Peter Pan ?',
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 35,
            'vote_id'       => 10,
            'name'          => 'Tinkerbell',
            'ordering'      => 1,
            'is_correct'    => true,
            'image'         => 'Peter_Pan.png',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 36,
            'vote_id'       => 10,
            'name'          => 'Oliver Twist',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => 'OliverTwist.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 37,
            'vote_id'       => 10,
            'name'          => 'Harry Potter',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'HarryPotter.png',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 38,
            'vote_id'       => 10,
            'name'          => 'Huckleberry Finn',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'huckleberry-finn.jpg',
        ]);




        /*11- Which planet is the closest to Earth?   A. Venus. */
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 39,
            'vote_id'       => 11,
            'name'          => 'Mars',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'Mars.jpeg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 40,
            'vote_id'       => 11,
            'name'          => 'Venus',
            'ordering'      => 2,
            'is_correct'    => true,
            'image'         => 'Venus.gif',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 41,
            'vote_id'       => 11,
            'name'          => 'Sun',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'sun.jpeg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 42,
            'vote_id'       => 11,
            'name'          => 'Saturn',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'saturn.jpg',
        ]);

        /* 12- According to the old proverb, to which European capital city do all roads lead?  A. Rome. */
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 43,
            'vote_id'       => 12,
            'name'          => 'Milan',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'milan.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 44,
            'vote_id'       => 12,
            'name'          => 'Bagdad',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => 'bagdad.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 45,
            'vote_id'       => 12,
            'name'          => 'Rome',
            'ordering'      => 3,
            'is_correct'    => true,
            'image'         => 'rome.jpg',
        ]);

        /* 13- On which mountain did Moses receive the Ten Commandments?  A. Mount Sinai. */
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 46,
            'vote_id'       => 13,
            'name'          => 'Mount Sinai',
            'ordering'      => 1,
            'is_correct'    => true,
            'image'         => 'Mount Sinai.jpeg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 47,
            'vote_id'       => 13,
            'name'          => 'Kilimanjaro',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => 'kilimanjaro.jpeg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 48,
            'vote_id'       => 13,
            'name'          => 'Everest',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'Mont_Everest.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 49,
            'vote_id'       => 13,
            'name'          => 'Elbrus',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'Elbrus.jpeg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 50,
            'vote_id'       => 13,
            'name'          => 'Mount Olympus',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'MountOlympus.jpeg',
        ]);


        /* 14- Which is the tallest mammal? A. The giraffe. */
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 51,
            'vote_id'       => 14,
            'name'          => 'Elephant',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'Elephant.jpeg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 52,
            'vote_id'       => 14,
            'name'          => 'Saltwater Crocodile',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => 'Saltwater Crocodile.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 53,
            'vote_id'       => 14,
            'name'          => 'Ostrich',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'Ostrich.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 54,
            'vote_id'       => 14,
            'name'          => 'giraffe',
            'ordering'      => 4,
            'is_correct'    => true,
            'image'         => 'giraffe.jpeg',
        ]);

        /* 15- Who directed the movie Jaws? A. Steven Spielberg. */
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 55,
            'vote_id'       => 15,
            'name'          => 'George Lucas',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'George_Lucas.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 56,
            'vote_id'       => 15,
            'name'          => 'Steven Spielberg',
            'ordering'      => 2,
            'is_correct'    => true,
            'image'         => 'StevenSpielberg.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 57,
            'vote_id'       => 15,
            'name'          => 'Stanley Kubrick',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'StanleyKubrick.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 58,
            'vote_id'       => 15,
            'name'          => 'Mel Brooks',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'MelBrooks.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 59,
            'vote_id'       => 15,
            'name'          => 'Martin Scorsese',
            'ordering'      => 5,
            'is_correct'    => false,
            'image'         => 'MartinScorsese.jpg',
        ]);

        /* 16-  In the film Babe, what type of animal was Babe?  A. A pig. */
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 60,
            'vote_id'       => 16,
            'name'          => 'donkey',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'donkey.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 61,
            'vote_id'       => 16,
            'name'          => 'Pig',
            'ordering'      => 2,
            'is_correct'    => true,
            'image'         => 'pig.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 62,
            'vote_id'       => 16,
            'name'          => 'Giraffe',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'Giraffe.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 63,
            'vote_id'       => 16,
            'name'          => 'Bear',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'mother-bear.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 64,
            'vote_id'       => 16,
            'name'          => 'Cat',
            'ordering'      => 5,
            'is_correct'    => false,
            'image'         => 'cat.jpeg',
        ]);

        /*    17      //        Mount Everest is found in which mountain range?   A. The Himalayas.  */
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 65,
            'vote_id'       => 17,
            'name'          => 'Kilimanjaro',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'Kilimanjaro.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 66,
            'vote_id'       => 17,
            'name'          => 'Great Plains',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => 'Great_Plains.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 67,
            'vote_id'       => 17,
            'name'          => 'The Himalayas',
            'ordering'      => 3,
            'is_correct'    => true,
            'image'         => 'himalayas-mountain.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 68,
            'vote_id'       => 17,
            'name'          => 'Appalachian Mountains',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'Appalachians.jpg',
        ]);

        /*       18 . In Greek mythology, who turned all that he touched into gold?  A. Midas. */
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 69,
            'vote_id'       => 18,
            'name'          => 'Hercules',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'Hercules.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 70,
            'vote_id'       => 18,
            'name'          => 'Odysseus',
            'ordering'      => 2,
            'is_correct'    => false,
            'image'         => 'Odysseus.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 71,
            'vote_id'       => 18,
            'name'          => 'Achilles',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'Achilles.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 72,
            'vote_id'       => 18,
            'name'          => 'Orpheus',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'Orpheus.jpg',
        ]);

        \DB::table($this->vote_items_tb)->insert([
            'id'            => 73,
            'vote_id'       => 18,
            'name'          => 'Midas',
            'ordering'      => 5,
            'is_correct'    => true,
            'image'         => 'Midas.jpg',
        ]);

        /* . 19 The title role of the 1990 movie “Pretty Woman” was played by which actress? A. Julia Roberts.  */
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 74,
            'vote_id'       => 19,
            'name'          => 'Natalie Portman',
            'ordering'      => 1,
            'is_correct'    => false,
            'image'         => 'Natalie_Portman.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 75,
            'vote_id'       => 19,
            'name'          => 'Julia Roberts',
            'ordering'      => 2,
            'is_correct'    => true,
            'image'         => 'JuliaRoberts.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 76,
            'vote_id'       => 19,
            'name'          => 'Meryl Streep',
            'ordering'      => 3,
            'is_correct'    => false,
            'image'         => 'MerylStreep.jpg',
        ]);
        \DB::table($this->vote_items_tb)->insert([
            'id'            => 77,
            'vote_id'       => 19,
            'name'          => 'Emma Watson',
            'ordering'      => 4,
            'is_correct'    => false,
            'image'         => 'Emma_Watson.jpg',
        ]);

    }


}
