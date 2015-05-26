<?php

use App\User;

class DashboardControllerTest extends TestCase
{
    public function testNotLoggedIn()
    {
        $response = $this->call('GET', '/');

        $this->assertResponseStatus(302);
    }

    public function testDashboard()
    {
        $this->seed();

        $user = User::where('email', 'stephen@rebelinblue.com')->firstOrFail();

        $this->be($user);

        $response = $this->call('GET', '/');
    }
}
