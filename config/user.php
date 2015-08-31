<?php
return [
    /**
     * Tự động add các route
     */
    'add_route'   => true,
    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares' => [
        'account'  => null,
        'auth'     => [
            ['guest', ['except' => 'getLogout']]
        ],
        'password' => 'guest',
        'user'     => 'admin',
    ],
];