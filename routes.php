<?php

return [
    '/' => 	['GET', 'Test@me'],

    # API

    '/api' => [
        '/users' =>         ['GET', 'Date@current'],
        '/user' => [
            '/{id:\d+}' =>  ['GET', 'Test@me'],
        ]
    ]
];
