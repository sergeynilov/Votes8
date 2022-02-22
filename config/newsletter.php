<?php

return [

    /*
     * The API key of a MailChimp account. You can find yours at
     * https://us10.admin.mailchimp.com/account/api-key-popup/.
     */
    'apiKey' => env('MAILCHIMP_APIKEY'),

    /*
     * The listName to use when no listName has been specified in a method.
     */
    'defaultListName' => 'News of site',

    /*
     * Here you can define properties of the lists.
     */
    'lists' => [

        /*
         * This key is used to identify this list. It can be used
         * as the listName parameter provided in the various methods.
         *
         * You can set it to any string you want and you can add
         * as many lists as you want.
         */
        'News of site' => [
            'id' => 'f33474b1c9',
        ],
        'Classic literature' => [
            'id' => 'a6cd2a60e6',
        ],
        'Movie&Cartoons' => [
            'id' => '087acbf04b',
        ],
        'Subscribers' => [
            'id' => 'a6cd2a60e6',
        ],
        'Earth&World' => [
            'id' => '42dde41bd4',
        ],
        'History' => [
            'id' => 'ac63b59026',
        ],
    ],

    /*
     * If you're having trouble with https connections, set this to false.
     */
    'ssl' => true,

];
/* You can add more lists in your config/newsletter.php file.

There is an array called lists to which you can your different newsletters, for example:

     * Here you can define properties of the lists.
'lists' => [
    'list_name1' => [
        'id' => env('MAILCHIMP_LIST_ID1'),
    ],
    'list_name2' => [
        'id' => env('MAILCHIMP_LIST_ID2'),
    ],
],
 */