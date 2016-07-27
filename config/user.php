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
        'user'  => 'role:sys.admin',
        'group' => 'role:sys.admin',
        'role'  => 'role:sys.admin',
    ],
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
        'backend.sidebar.user.role'       => [
            'priority' => 3,
            'url'      => 'route:backend.role.index',
            'label'    => 'trans:user::role.roles',
            'icon'     => 'fa-male',
            'active'   => 'backend/role*',
        ],
    ],

    /**
     * Định nghĩa các chức vụ
     * - Group: nhóm chức vụ, tên nhóm phải duy nhất
     * - Role: chức vụ, tên chức vụ duy nhất trong nhóm
     * - Trong một nhóm, Role có level cao hơn sẽ kế thừa các quyền của role thấp hơn
     *
     * ==> Định danh một Role: 'group.role'
     */
    'roles'           => [
        // hệ thống
        'sys' => [
            'sadmin' => 200,
            'admin'  => 100,
        ],
    ],
    /**
     * Định nghĩa các nhóm chức vụ
     * Sử dụng khi check $user->is();
     */
    'role_groups'     => [
        'administrator' => ['sys.sadmin', 'sys.admin'],
    ],
];