<?php
return [
    'group_types'     => [
        'system', // group hệ thống: administrator, test...
        'normal', // các group thông thường ~ đơn vị
    ],
    'group_max_depth' => 5,
    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares'     => [
        'user'  => ['web', 'role:sys.admin'],
        'group' => ['web', 'role:sys.admin'],
    ],
    // Login Ussername, vd: email, username
    'username'        => 'username',
    // Định nghĩa menus cho user
    'menus'           => [
        'backend.sidebar.user.manager'    => [
            'priority' => 1,
            'url'      => 'route:backend.user.index',
            'label'    => 'trans:user::user.user',
            'icon'     => 'fa-users',
            'active'   => ['backend/user', 'backend/user/*'],
        ],
        'backend.sidebar.user.user_group' => [
            'priority' => 2,
            'url'      => 'route:backend.user_group.index',
            'label'    => 'trans:user::group.group',
            'icon'     => 'fa-sitemap',
            'active'   => 'backend/user_group*',
        ],
    ],
];