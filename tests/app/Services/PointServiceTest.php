<?php

namespace Tests\App\Http\Repositories;

use App\Repositories\PointRepository;
use App\Services\PointService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# docker-compose exec app php artisan test --filter=PointServiceTest
class PointServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * docker-compose exec app php artisan test --filter=PointServiceTest::testExtendsClassService
     *
     * Checks if the service class is behaving as expected
     *
     * @return void
     */
    public function testExtendsClassService()
    {
        // New instance Service
        $service = new PointService(new PointRepository());

        // Assert that the 'checkPermissions' exists in the PointService.
        // This test ensures that the desired function is implemented and available for use.
        $this->assertTrue(method_exists($service, 'checkPermissions'));
    }
}
