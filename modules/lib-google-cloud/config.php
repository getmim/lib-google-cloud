<?php

return [
    '__name' => 'lib-google-cloud',
    '__version' => '0.1.0',
    '__git' => 'git@github.com:getmim/lib-google-cloud.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'https://iqbalfn.com/'
    ],
    '__files' => [
        'modules/lib-google-cloud' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'lib-curl' => NULL 
            ],
            [
                'lib-cache' => NULL 
            ],
            [
                'lib-jwt' => NULL 
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'LibGoogleCloud\\Library' => [
                'type' => 'file',
                'base' => 'modules/lib-google-cloud/library'
            ]
        ],
        'files' => []
    ]
];
