<?php

namespace Tests\App\Http\Repositories;

use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# docker-compose exec app php artisan test --filter=UserServiceTest
class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * docker-compose exec app php artisan test --filter=UserServiceTest::testExtendsClassService
     *
     * Checks if the service class is behaving as expected
     *
     * @return void
     */
    public function testExtendsClassService()
    {
        // New instance Service
        $service = new UserService(new UserRepository());

        // Assert that the 'checkPermissions' exists in the UserService.
        // This test ensures that the desired function is implemented and available for use.
        $this->assertTrue(method_exists($service, 'checkPermissions'));
    }
}
