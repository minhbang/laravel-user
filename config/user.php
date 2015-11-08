<?php
return [
    'group_types'     => [
        'system', // group hệ thống: administrator, test...
        'normal', // các group thông thường ~ đơn vị
    ],
    'group_max_depth' => 5,
    /**
     * Tự động add các route
     */
    'add_route'       => true,
    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares'     => [
        'account'  => null,
        'auth'     => [
            ['guest', ['except' => 'getLogout']],
        ],
        'password' => 'guest',
        'user'     => 'admin',
        'group'    => 'admin',
    ],
];