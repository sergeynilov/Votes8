<?php

return [

    // These CSS rules will be applied after the regular template CSS

    /*
        'css' => [
            '.button-content .button { background: red }',
        ],
    */

    'beautymail.css' => 'css/beautymail.css',

    'colors' => [

//        'highlight' => '#004ca3',
//        'button'    => '#004cad',

        'highlight' => '#004ca3',
        'button'    => '#87f5ff',

    ],

    'view' => [
        'senderName'  => 'senderName',
//        'reminder'    => 'reminder',
//        'unsubscribe' => 'unsubscribe',
//        'address'     => 'address',

        'logo'        => [
            'path'   => config('app.url').config('app.medium_slogan_img_url'),
            'width'  => 320,
            'height' => 160,
        ],

        'twitter'  => 'Select & Vote',
        'facebook' => 'Select & Vote',
        'flickr'   => 'Select & Vote',
    ],

];
