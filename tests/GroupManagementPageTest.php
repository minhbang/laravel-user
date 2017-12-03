<?php namespace Minhbang\User\Tests;

use Minhbang\User\Tests\Stubs\IntergrationTestCase;

/**
 * Class IntergrationTest
 * @package Minhbang\User\Tests
 * @author Minh Bang
 */
class GroupManagementPageTest extends IntergrationTestCase
{
    /**
     * User bình thường truy cập trang quản lý user
     */
    public function testGuestAccessGroupManagementPage()
    {
        $response = $this->get('/backend/user');
        // Yêu cầu đăng nhập khi truy cập
        $response->assertRedirect('/auth/login');
    }

    /**
     * User bình thường truy cập trang quản lý user
     */
    public function testUserAccessGroupManagementPage()
    {
        $response = $this->actingAs($this->users['user'])->get('/backend/user');
        $response->assertStatus(403);
    }

    /**
     * Admin truy cập trang quản lý user
     */
    public function testAdminAccessGroupManagementPage()
    {
        $response = $this->actingAs($this->users['admin'])->get('/backend/user');
        $response->assertStatus(200);
    }

    /**
     * Super Admin truy cập trang quản lý user
     */
    public function testSuperAdminAccessGroupManagementPage()
    {
        // Truy cập bằng quyền Super Admin
        $response = $this->actingAs($this->users['super_admin'])->get('/backend/user');
        $response->assertStatus(200);
    }
}