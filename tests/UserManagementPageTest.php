<?php namespace Minhbang\User\Tests;

use Minhbang\User\Tests\Stubs\IntergrationTestCase;

/**
 * Class UserManagementPageTest
 * @package Minhbang\User\Tests
 * @author Minh Bang
 */
class UserManagementPageTest extends IntergrationTestCase
{
    /**
     * User bình thường truy cập trang quản lý user
     */
    public function testGuestAccessUserManagementPage()
    {
        $response = $this->get('/backend/user');
        // Yêu cầu đăng nhập khi truy cập
        $response->assertRedirect('/auth/login');
    }

    /**
     * User bình thường truy cập trang quản lý user
     */
    public function testUserAccessUserManagementPage()
    {
        $response = $this->actingAs($this->users['user'])->get('/backend/user');
        $response->assertStatus(403);
    }

    /**
     * Admin truy cập trang quản lý user
     */
    public function testAdminAccessUserManagementPage()
    {
        $response = $this->actingAs($this->users['admin'])->get('/backend/user');
        $response->assertStatus(200);
    }

    /**
     * Super Admin truy cập trang quản lý user
     */
    public function testSuperAdminAccessUserManagementPage()
    {
        // Truy cập bằng quyền Super Admin
        $response = $this->actingAs($this->users['super_admin'])->get('/backend/user');
        $response->assertStatus(200);
    }
}