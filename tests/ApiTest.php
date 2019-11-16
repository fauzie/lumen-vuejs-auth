<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHome()
    {
        $this->get('/');
        $this->assertResponseStatus(200);
    }

    /**
     * Testing API home
     *
     * @return void
     */
    public function testApi()
    {
        $response = $this->json('GET', '/api');
        $response->assertResponseStatus(200);
    }

    /**
     * Testing API login
     *
     * @return void
     */
    public function testApiLogin()
    {
        $response = $this->json('GET', '/api/login');
        $response->assertResponseStatus(405);
        $response = $this->json('POST', '/api/login');
        $response->assertResponseStatus(422);
        $response = $this->json('POST', '/api/login', [
            'email' => 'nonexistence@email.com',
            'password' => 'randompw'
        ]);
        $response->assertResponseStatus(422);
    }

    /**
     * Testing API register
     *
     * @return void
     */
    public function testApiRegister()
    {
        $response = $this->json('GET', '/api/register');
        $response->assertResponseStatus(405);
        $response = $this->json('PUT', '/api/register', [
            'email' => 'fake@email.com',
            'phone' => '081287654332',
            'firstname' => 'Tester',
            'dob' => '1990-12-30',
            'gender' => 'female',
            'password' => 'passwordtester'
        ]);
        $response->assertResponseStatus(422);
    }

    /**
     * Testing API logout
     *
     * @return void
     */
    public function testApiLogout()
    {
        $response = $this->json('POST', '/api/logout');
        $response->assertResponseStatus(405);
        $response = $this->json('GET', '/api/logout');
        $response->assertResponseStatus(401);
    }
}
