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
];